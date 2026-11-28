<?php
/**
 * VinFast Digital Retail — shared session and permission functions.
 * COS10026 Assignment 2, proposed five-role account system.
 *
 * IMPORTANT: This file does not change login.php or manage.php by itself.
 * Those pages must be integrated before public registration goes live.
 */
declare(strict_types=1);

/** Start the session before any HTML has been sent. */
function vf_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/', // Matches the current login.php/manage.php session cookie.
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

vf_start_session();

/** Fixed, server-side roles. Never take a role from public registration input. */
function vf_can(string $role, string $permission): bool
{
    $permissions = [
        'super_admin' => [
            'manage_jobs', 'manage_accounts', 'grant_staff_roles',
            'view_login_history', 'manage_eoi', 'manage_about',
            'access_staff_dashboard', 'edit_own_profile',
        ],
        'jobs_admin' => [
            'manage_jobs', 'manage_accounts', 'view_login_history',
            'access_staff_dashboard', 'edit_own_profile',
        ],
        'application_admin' => [
            'manage_eoi', 'access_staff_dashboard', 'edit_own_profile',
        ],
        'about_admin' => [
            'manage_about', 'access_staff_dashboard', 'edit_own_profile',
        ],
        'candidate' => [
            'view_own_applications', 'edit_own_profile',
        ],
    ];

    return in_array($permission, $permissions[$role] ?? [], true);
}

/** Generate a CSRF token for forms that modify account or application data. */
function vf_csrf_token(): string
{
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Validate POST CSRF token. This does NOT replace authentication or permission checks. */
function vf_verify_csrf(?string $submittedToken): bool
{
    return $submittedToken !== null
        && $submittedToken !== ''
        && hash_equals(vf_csrf_token(), $submittedToken);
}

/** Call only AFTER password_verify() and account_status checks succeed in login.php. */
function vf_sign_in(array $user): void
{
    $id = filter_var($user['user_id'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);
    $role = (string) ($user['role'] ?? '');

    if ($id === false || !vf_can($role, 'edit_own_profile')
        || ($user['account_status'] ?? null) !== 'active') {
        throw new InvalidArgumentException('Cannot create a session for this account.');
    }

    session_regenerate_id(true);
    $_SESSION = [];
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = (string) $user['username'];
    $_SESSION['role'] = $role;
    $_SESSION['candidate_id'] = ($user['candidate_id'] ?? null) === null
        ? null : (int) $user['candidate_id'];
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/** Completely end the session, including the browser cookie. */
function vf_sign_out(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

/** Private service error; do not expose database details to a visitor. */
function vf_auth_service_unavailable(): never
{
    error_log('VinFast auth: user verification query failed.');
    http_response_code(503);
    exit('Authentication service is temporarily unavailable.');
}

/**
 * Look up the logged-in user again on EVERY protected request.
 * This ensures disabled accounts or changed roles take effect immediately.
 * Temporary compatibility: recognizes the current login.php admin_user_id key.
 * No session role (legacy or otherwise) is trusted without a DB lookup.
 *
 * @return array<string, mixed>|null
 */
function vf_current_user(): ?array
{
    $id = filter_var($_SESSION['user_id'] ?? $_SESSION['admin_user_id'] ?? null,
        FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false || $id === null) {
        return null;
    }

    if (isset($_SESSION['last_activity'])
        && time() - (int) $_SESSION['last_activity'] > 1800) {
        vf_sign_out();
        return null;
    }

    // Include only once and reuse the team's shared mysqli connection.
    require_once __DIR__ . '/db_connect.php';
    global $conn;
    if (!isset($conn) || !($conn instanceof mysqli)) {
        vf_auth_service_unavailable();
    }

    $sql = 'SELECT user_id, username, full_name, email, role, account_status,
                   candidate_id
            FROM users WHERE user_id = ? LIMIT 1';
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        vf_auth_service_unavailable();
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $executed = mysqli_stmt_execute($stmt);
    $result = $executed ? mysqli_stmt_get_result($stmt) : false;
    if ($result === false) {
        mysqli_stmt_close($stmt);
        vf_auth_service_unavailable();
    }
    $user = mysqli_fetch_assoc($result) ?: null;
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    if (!$user || $user['account_status'] !== 'active') {
        vf_sign_out();
        return null;
    }

    // The authoritative role comes from the database, never a browser field.
    $_SESSION['role'] = (string) $user['role'];
    $_SESSION['user_id'] = (int) $user['user_id'];
    $_SESSION['candidate_id'] = ($user['candidate_id'] ?? null) === null
        ? null : (int) $user['candidate_id'];
    $_SESSION['last_activity'] = time();
    return $user;
}

/** Protect pages AND their POST handlers. Include this BEFORE any HTML output. */
function vf_require_permission(string $permission): array
{
    $user = vf_current_user();
    if ($user === null) {
        header('Location: login.php?auth_required=1');
        exit;
    }
    if (!vf_can((string) $user['role'], $permission)) {
        http_response_code(403);
        exit('Access denied: insufficient permissions.');
    }
    return $user;
}

/** Ownership is checked as well as the candidate role (prevents IDOR). */
function vf_require_candidate_owner(int $candidateId): array
{
    $user = vf_require_permission('view_own_applications');
    if ($candidateId < 1 || $user['candidate_id'] === null
        || (int) $user['candidate_id'] !== $candidateId) {
        http_response_code(403);
        exit('Access denied.');
    }
    return $user;
}
