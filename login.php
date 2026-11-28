<?php
/**
 * VinFast Digital Retail — multi-role login handler (SCRUM-87).
 * Integrated with auth.php and the existing styled login.php template.
 * Public registration is NOT enabled by this change.
 */
require_once __DIR__ . '/auth.php';

function login_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function vf_login_destination(string $role): string
{
    return match ($role) {
        'super_admin', 'application_admin' => 'manage.php',
        'about_admin' => 'about.php',
        'jobs_admin', 'candidate' => 'jobs.php',
        default => 'jobs.php',
    };
}

// Refresh the logged-in user's role and status from MySQL before redirecting.
$existingUser = vf_current_user();
if ($existingUser !== null) {
    if (!vf_can((string) $existingUser['role'], 'manage_eoi')) {
        // Remove any legacy broad admin flag inherited from the team's old login.
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_user_id'],
              $_SESSION['admin_username'], $_SESSION['admin_full_name'], $_SESSION['admin_role']);
    }
    header('Location: ' . vf_login_destination((string) $existingUser['role']));
    exit;
}

// Never let stale legacy session flags persist on the sign-in screen.
unset($_SESSION['admin_logged_in'], $_SESSION['admin_user_id'],
      $_SESSION['admin_username'], $_SESSION['admin_full_name'], $_SESSION['admin_role']);

$loginError = '';
$loginNotice = '';
$enteredUser = '';

if (($_GET['logged_out'] ?? '') === '1') {
    $loginNotice = 'You have been signed out securely.';
} elseif (($_GET['timeout'] ?? '') === '1') {
    $loginError = 'Your session expired due to inactivity. Please sign in again.';
} elseif (($_GET['auth_required'] ?? '') === '1') {
    $loginError = 'Please sign in to access that page.';
}

$maxAttempts = 5;
$lockoutDuration = 60;
$lockoutUntil = (int) ($_SESSION['login_lockout_until'] ?? 0);

// auth.php starts a secure session and provides the CSRF token shared with the form.
vf_csrf_token();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $token = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : null;
    $enteredUser = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
    $enteredPass = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    if ($lockoutUntil > time()) {
        $loginError = 'Too many failed sign-in attempts. Please try again later.';
    } elseif (!vf_verify_csrf($token)) {
        $loginError = 'Invalid security token. Please refresh the page and try again.';
    } elseif ($enteredUser === '' || $enteredPass === '' || strlen($enteredUser) > 50 || strlen($enteredPass) > 255) {
        $loginError = 'Invalid username or password.';
    } else {
        require_once __DIR__ . '/db_connect.php';
        // Role and account status are determined by the database, never by POST data.
        $stmt = mysqli_prepare($conn,
            'SELECT user_id, username, full_name, password_hash, role, account_status,
                    candidate_id
             FROM users WHERE username = ? LIMIT 1');
        if (!$stmt) {
            error_log('Login: failed preparing user lookup.');
            $loginError = 'Authentication service is temporarily unavailable.';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $enteredUser);
            $ok = mysqli_stmt_execute($stmt);
            $result = $ok ? mysqli_stmt_get_result($stmt) : false;
            $user = $result ? mysqli_fetch_assoc($result) : null;
            if ($result) {
                mysqli_free_result($result);
            }
            mysqli_stmt_close($stmt);

            if (!$ok) {
                error_log('Login: failed executing user lookup.');
                $loginError = 'Authentication service is temporarily unavailable.';
            } elseif (!$user || !password_verify($enteredPass, (string) $user['password_hash'])
                || $user['account_status'] !== 'active') {
                // Identical message for unknown, disabled, and wrong-password accounts.
                $loginError = 'Invalid username or password.';
                $attempts = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['login_attempts'] = $attempts;
                if ($attempts >= $maxAttempts) {
                    $_SESSION['login_lockout_until'] = time() + $lockoutDuration;
                    $_SESSION['login_attempts'] = 0;
                    $loginError = 'Too many failed sign-in attempts. Please try again later.';
                }
            } else {
                if (!vf_can((string) $user['role'], 'edit_own_profile')) {
                    // Unknown role: fail closed instead of granting permissions.
                    $loginError = 'Your account cannot sign in at the moment.';
                } else {
                    if (password_needs_rehash((string) $user['password_hash'], PASSWORD_DEFAULT)) {
                        $newHash = password_hash($enteredPass, PASSWORD_DEFAULT);
                        $rehashStmt = mysqli_prepare($conn,
                            'UPDATE users SET password_hash = ? WHERE user_id = ?');
                        if ($rehashStmt) {
                            $id = (int) $user['user_id'];
                            mysqli_stmt_bind_param($rehashStmt, 'si', $newHash, $id);
                            mysqli_stmt_execute($rehashStmt);
                            mysqli_stmt_close($rehashStmt);
                        }
                    }

                    vf_sign_in($user);

                    // TEMPORARY compatibility with Thanh's current manage.php.
                    // Only roles allowed to manage EOIs get the legacy admin flag.
                    // manage.php must be updated to vf_require_permission('manage_eoi')
                    // before public registration is enabled.
                    if (vf_can((string) $user['role'], 'manage_eoi')) {
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_user_id'] = (int) $user['user_id'];
                        $_SESSION['admin_username'] = (string) $user['username'];
                        $_SESSION['admin_full_name'] = (string) $user['full_name'];
                        $_SESSION['admin_role'] = (string) $user['role'];
                    }

                    header('Location: ' . vf_login_destination((string) $user['role']));
                    exit;
                }
            }
        }
    }
}

$pageTitle = 'Sign In | VinFast Digital Retail Careers';
$pageDescription = 'Administrator sign-in for VinFast Digital Retail Careers.';
$pageAuthor = 'Duong Nguyen Khoi';
$bodyClass = 'page-login';
$currentPage = 'login';

// Page-specific CSS is scoped to .page-login so existing pages are unaffected.
// Using $extraHead means this standalone file works with the team's current header.inc.
$extraHead = <<<'LOGIN_STYLES'
<style>
.page-login .login-main {
    padding: clamp(32px, 6vw, 74px) 0 88px;
    min-height: min(850px, 85vh);
}
.page-login .login-shell {
    display: grid;
    grid-template-columns: minmax(0, 1.04fr) minmax(340px, .96fr);
    max-width: 1050px;
    margin: 0 auto;
    overflow: hidden;
    border: 1px solid var(--color-border, #dbe6f5);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 24px 70px rgba(13, 40, 83, .12);
}
.page-login .login-intro {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 60px;
    min-height: 585px;
    padding: clamp(32px, 5vw, 62px);
    background: linear-gradient(150deg, #0b5fff 0%, #0a3eab 56%, #081f52 100%);
    color: #fff;
    isolation: isolate;
}
.page-login .login-intro::before {
    content: '';
    position: absolute;
    width: 340px;
    height: 340px;
    right: -110px;
    bottom: -165px;
    border: 1px solid rgba(255, 255, 255, .19);
    border-radius: 50%;
    box-shadow: 0 0 0 65px rgba(255, 255, 255, .045),
                0 0 0 135px rgba(255, 255, 255, .035);
    pointer-events: none;
    z-index: -1;
}
.page-login .login-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    width: fit-content;
    padding: 9px 13px;
    border: 1px solid rgba(255, 255, 255, .3);
    border-radius: 999px;
    background: rgba(255, 255, 255, .1);
    color: #fff;
    font-size: .75rem;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .1em;
}
.page-login .login-eyebrow::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #8fd9ff;
}
.page-login .login-intro h1 {
    margin: 24px 0 17px;
    color: #fff;
    font-size: clamp(2.3rem, 4.2vw, 3.6rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -.045em;
}
.page-login .login-intro h1 span {
    display: block;
    color: #b8d6ff;
}
.page-login .login-intro p {
    max-width: 390px;
    margin: 0;
    color: rgba(255, 255, 255, .86);
    font-size: 1rem;
    line-height: 1.8;
}
.page-login .login-intro__footer {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 18px;
    border-top: 1px solid rgba(255, 255, 255, .22);
    color: rgba(255, 255, 255, .86);
    font-size: .86rem;
}
.page-login .login-lock {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .13);
}
.page-login .login-form-panel {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: clamp(28px, 4.6vw, 58px);
}
.page-login .login-topline {
    margin: 0 0 9px;
    color: var(--color-primary, #0b5fff);
    font-size: .77rem;
    font-weight: 800;
    letter-spacing: .13em;
    text-transform: uppercase;
}
.page-login .login-form-panel h2 {
    margin: 0 0 10px;
    color: var(--color-navy, #0a1f4d);
    font-size: clamp(1.85rem, 3vw, 2.15rem);
    letter-spacing: -.035em;
    line-height: 1.2;
}
.page-login .login-form-panel .login-subtitle {
    margin: 0 0 32px;
    color: var(--color-muted, #4a5a72);
    font-size: .95rem;
    line-height: 1.7;
}
.page-login .login-fields {
    display: grid;
    gap: 20px;
}
.page-login .login-field label {
    display: block;
    margin-bottom: 8px;
    color: #213452;
    font-size: .89rem;
    font-weight: 750;
}
.page-login .login-field input {
    display: block;
    width: 100%;
    min-height: 52px;
    padding: 13px 15px;
    border: 1.5px solid #cddbf0;
    border-radius: 10px;
    background: #fbfdff;
    color: #142641;
    font: inherit;
    font-size: .96rem;
    transition: border-color .18s ease, box-shadow .18s ease;
}
.page-login .login-field input::placeholder { color: #8090a6; }
.page-login .login-field input:focus-visible {
    outline: none;
    border-color: var(--color-primary, #0b5fff);
    box-shadow: 0 0 0 4px rgba(11, 95, 255, .15);
}
.page-login .login-submit {
    display: block;
    width: 100%;
    min-height: 52px;
    margin-top: 27px;
    padding: 12px 22px;
    border: 0;
    border-radius: 10px;
    background: var(--color-primary, #0b5fff);
    color: #fff;
    font: inherit;
    font-size: 1rem;
    font-weight: 750;
    text-align: center;
    cursor: pointer;
    transition: background-color .18s ease;
}
.page-login .login-submit:hover:not(:disabled) {
    background: var(--color-primary-dark, #0847c4);
}
.page-login .login-submit:disabled {
    opacity: .82;
    cursor: not-allowed;
}
.page-login .login-alert {
    margin: 0 0 20px;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: .9rem;
    font-weight: 600;
    line-height: 1.5;
}
.page-login .login-alert--error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.page-login .login-alert--notice {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.page-login .login-bottom {
    margin: 23px 0 0;
    color: #5d6d83;
    font-size: .87rem;
    text-align: center;
}
.page-login .login-bottom a {
    color: #0954e5;
    font-weight: 700;
    text-underline-offset: 3px;
}
.page-login .login-bottom a:focus-visible {
    outline: 3px solid #0b5fff;
    outline-offset: 3px;
}
@media (max-width: 820px) {
    .page-login .login-shell { grid-template-columns: 1fr; max-width: 570px; }
    .page-login .login-intro { min-height: 270px; gap: 32px; padding: 35px; }
    .page-login .login-intro h1 { font-size: 2.6rem; }
    .page-login .login-intro__footer { display: none; }
    .page-login .login-form-panel { padding: 36px 35px 42px; }
}
@media (max-width: 480px) {
    .page-login .login-main { padding: 22px 0 54px; }
    .page-login .login-shell { border-radius: 15px; }
    .page-login .login-intro { min-height: 218px; padding: 28px 24px; }
    .page-login .login-intro h1 { font-size: 2.15rem; }
    .page-login .login-intro p { font-size: .9rem; }
    .page-login .login-form-panel { padding: 31px 23px 35px; }
    .page-login .login-form-panel h2 { font-size: 1.78rem; }
}
@media (prefers-reduced-motion: reduce) {
    .page-login .login-field input,
    .page-login .login-submit { transition: none; }
}
</style>
LOGIN_STYLES;

require __DIR__ . '/header.inc';
require __DIR__ . '/nav.inc';
?>

<main id="main" class="login-main">
    <div class="container">
        <div class="login-shell">
            <section class="login-intro" aria-labelledby="login-hero-title">
                <div>
                    <span class="login-eyebrow">VinFast Digital Retail</span>
                    <h1 id="login-hero-title">Your career.<span>Your next move.</span></h1>
                    <p>Welcome to the careers portal. Sign in to access your account.</p>
                </div>
                <div class="login-intro__footer">
                    <span class="login-lock" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="11" width="16" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                        </svg>
                    </span>
                    <span>Secure access · Staff and applicants</span>
                </div>
            </section>

            <section class="login-form-panel" aria-labelledby="login-title">
                <p class="login-topline">Account access</p>
                <h2 id="login-title">Welcome back</h2>
                <p class="login-subtitle">Sign in to access your VinFast Careers account.</p>

                <?php if ($loginError !== ''): ?>
                    <p class="login-alert login-alert--error" role="alert"><?= login_h($loginError) ?></p>
                <?php elseif ($loginNotice !== ''): ?>
                    <p class="login-alert login-alert--notice" role="status"><?= login_h($loginNotice) ?></p>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= login_h($_SESSION['csrf_token']) ?>">
                    <div class="login-fields" role="group" aria-label="Account sign in">
                        <div class="login-field">
                            <label for="login-username">Username</label>
                            <input id="login-username" name="username" type="text" maxlength="50" autocomplete="username" placeholder="Enter your username" value="<?= login_h($enteredUser) ?>" required>
                        </div>
                        <div class="login-field">
                            <label for="login-password">Password</label>
                            <input id="login-password" name="password" type="password" maxlength="255" autocomplete="current-password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <button class="login-submit" type="submit">Sign in</button>
                </form>
                <p class="login-bottom">Looking for opportunities? <a href="jobs.php">Explore open jobs</a></p>
            </section>
        </div>
    </div>
</main>

<?php require __DIR__ . '/footer.inc'; ?>