<?php
/**
 * File: manage.php
 * Author: Nguyen Nhat Thanh (106394055)
 * Description: HR Manager Expression of Interest (EOI) Management Portal.
 *              Protected by session authentication (login.php).
 *              Supports listing all EOIs, filtering by job reference or applicant name(s),
 *              sorting by chosen field/direction, updating EOI status, and deleting EOIs
 *              by job reference — strictly without JavaScript.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Enforce authentication before doing anything else
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php?auth_required=1');
    exit();
}

// Enforce 30-minute idle session timeout
$idleTimeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - (int) $_SESSION['last_activity']) > $idleTimeout) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Handle POST Logout cleanly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['post_action'] ?? '') === 'logout') {
    $token = isset($_POST['csrf_token']) && is_string($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (hash_equals($_SESSION['csrf_token'], $token)) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: login.php?logged_out=1');
        exit();
    }
}

require_once __DIR__ . '/db_connect.php';

// Ensure `eoi` table exists (matches process_eoi.sql schema)
$ensureEoiSql = "CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    dob VARCHAR(15) NOT NULL,
    gender VARCHAR(25) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    state VARCHAR(10) NOT NULL,
    postcode VARCHAR(4) NOT NULL,
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    primary_skill VARCHAR(50) NOT NULL,
    additional_skills VARCHAR(255),
    other_skills TEXT,
    documents VARCHAR(255),
    status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$dbError = (!isset($conn) || !($conn instanceof mysqli));
if (!$dbError) {
    if (!mysqli_query($conn, $ensureEoiSql)) {
        error_log('manage.php: failed ensuring eoi table: ' . mysqli_error($conn));
        $dbError = true;
    }
}

$feedbackType = ''; // 'success' | 'error' | 'info'
$feedbackMsg  = '';

$validStatuses = ['New', 'Current', 'Final'];

// -------------------------------------------------------------------------
// 1. Handle State-Changing POST Requests (Update Status / Delete by Job Ref)
// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$dbError) {
    $postAction     = isset($_POST['post_action']) && is_string($_POST['post_action']) ? $_POST['post_action'] : '';
    $submittedToken = isset($_POST['csrf_token']) && is_string($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $feedbackType = 'error';
        $feedbackMsg  = 'Invalid security token. Please reload the page and try again.';
    } elseif ($postAction === 'update_status') {
        $eoiNumberRaw = $_POST['eoi_number'] ?? '';
        $newStatus    = isset($_POST['new_status']) && is_string($_POST['new_status']) ? trim($_POST['new_status']) : '';
        $eoiNumber    = filter_var($eoiNumberRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($eoiNumber === false) {
            $feedbackType = 'error';
            $feedbackMsg  = 'Please enter a valid positive EOI Number.';
        } elseif (!in_array($newStatus, $validStatuses, true)) {
            $feedbackType = 'error';
            $feedbackMsg  = 'Invalid status selected. Allowed statuses are New, Current, or Final.';
        } else {
            // Check if EOI exists first so we give accurate feedback even if status was unchanged
            $checkStmt = mysqli_prepare($conn, "SELECT status FROM eoi WHERE EOInumber = ? LIMIT 1");
            if ($checkStmt) {
                mysqli_stmt_bind_param($checkStmt, 'i', $eoiNumber);
                mysqli_stmt_execute($checkStmt);
                $checkRes = mysqli_stmt_get_result($checkStmt);
                $existing = $checkRes ? mysqli_fetch_assoc($checkRes) : null;
                if ($checkRes) {
                    mysqli_free_result($checkRes);
                }
                mysqli_stmt_close($checkStmt);

                if (!$existing) {
                    $feedbackType = 'error';
                    $feedbackMsg  = "No EOI record was found with EOI Number #{$eoiNumber}.";
                } elseif ($existing['status'] === $newStatus) {
                    $feedbackType = 'info';
                    $feedbackMsg  = "EOI #{$eoiNumber} already has the status '{$newStatus}'.";
                } else {
                    $updStmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
                    if ($updStmt) {
                        mysqli_stmt_bind_param($updStmt, 'si', $newStatus, $eoiNumber);
                        if (mysqli_stmt_execute($updStmt)) {
                            $feedbackType = 'success';
                            $feedbackMsg  = "Status for EOI #{$eoiNumber} has been updated from '{$existing['status']}' to '{$newStatus}'.";
                        } else {
                            $feedbackType = 'error';
                            $feedbackMsg  = 'Unable to update EOI status due to a database error.';
                        }
                        mysqli_stmt_close($updStmt);
                    } else {
                        $feedbackType = 'error';
                        $feedbackMsg  = 'Unable to prepare status update statement.';
                    }
                }
            } else {
                $feedbackType = 'error';
                $feedbackMsg  = 'Unable to verify EOI record.';
            }
        }
    } elseif ($postAction === 'delete_by_job') {
        $deleteJobRef = isset($_POST['delete_job_ref']) && is_string($_POST['delete_job_ref'])
            ? strtoupper(trim($_POST['delete_job_ref']))
            : '';
        $confirmed = isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === '1';

        if ($deleteJobRef === '' || !preg_match('/^[A-Z0-9-]{1,10}$/', $deleteJobRef)) {
            $feedbackType = 'error';
            $feedbackMsg  = 'Please select or enter a valid Job Reference Number (alphanumeric, up to 10 characters).';
        } elseif (!$confirmed) {
            $feedbackType = 'error';
            $feedbackMsg  = 'Deletion aborted: Please tick the confirmation checkbox before deleting EOIs.';
        } else {
            $delStmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
            if ($delStmt) {
                mysqli_stmt_bind_param($delStmt, 's', $deleteJobRef);
                if (mysqli_stmt_execute($delStmt)) {
                    $deletedCount = mysqli_stmt_affected_rows($delStmt);
                    if ($deletedCount > 0) {
                        $plural       = ($deletedCount === 1) ? 'record' : 'records';
                        $feedbackType = 'success';
                        $feedbackMsg  = "Successfully deleted {$deletedCount} EOI {$plural} for Job Reference '{$deleteJobRef}'.";
                    } else {
                        $feedbackType = 'info';
                        $feedbackMsg  = "No EOI records were found for Job Reference '{$deleteJobRef}'. Nothing was deleted.";
                    }
                } else {
                    $feedbackType = 'error';
                    $feedbackMsg  = 'Unable to delete EOI records due to a database error.';
                }
                mysqli_stmt_close($delStmt);
            } else {
                $feedbackType = 'error';
                $feedbackMsg  = 'Unable to prepare deletion statement.';
            }
        }
    }
}

// -------------------------------------------------------------------------
// 2. Read & Whitelist Query Filter + Sort Parameters (GET or preserved POST)
// -------------------------------------------------------------------------
$sourceParams = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET;

$queryMode = isset($sourceParams['action']) && is_string($sourceParams['action'])
    ? $sourceParams['action']
    : 'all';
if (!in_array($queryMode, ['all', 'by_job', 'by_name'], true)) {
    $queryMode = 'all';
}

$filterJobRef = isset($sourceParams['job_ref']) && is_string($sourceParams['job_ref'])
    ? strtoupper(trim($sourceParams['job_ref']))
    : '';
$filterFirstName = isset($sourceParams['first_name']) && is_string($sourceParams['first_name'])
    ? trim($sourceParams['first_name'])
    : '';
$filterLastName = isset($sourceParams['last_name']) && is_string($sourceParams['last_name'])
    ? trim($sourceParams['last_name'])
    : '';

// Strict whitelist for ORDER BY column & direction (prevents SQL injection)
$allowedSortFields = [
    'EOInumber'     => 'EOI Number',
    'job_ref'       => 'Job Reference',
    'first_name'    => 'First Name',
    'last_name'     => 'Last Name',
    'status'        => 'EOI Status',
    'state'         => 'State',
    'primary_skill' => 'Primary Skill',
];

$sortBy = isset($sourceParams['sort_by']) && is_string($sourceParams['sort_by'])
    ? $sourceParams['sort_by']
    : 'EOInumber';
if (!array_key_exists($sortBy, $allowedSortFields)) {
    $sortBy = 'EOInumber';
}

$sortDir = isset($sourceParams['sort_dir']) && is_string($sourceParams['sort_dir'])
    ? strtoupper(trim($sourceParams['sort_dir']))
    : 'ASC';
if (!in_array($sortDir, ['ASC', 'DESC'], true)) {
    $sortDir = 'ASC';
}

// -------------------------------------------------------------------------
// 3. Fetch Summary Metrics, Available Job References & Filtered EOI Rows
// -------------------------------------------------------------------------
$stats = [
    'total'   => 0,
    'New'     => 0,
    'Current' => 0,
    'Final'   => 0,
];

$jobRefOptions = [
    'VF001' => 'VF001 - E-Commerce UX/UI Front-End Developer',
    'VF002' => 'VF002 - Digital Merchandising & Catalogue Specialist',
    'VF003' => 'VF003 - E-Commerce Data Analyst',
    'VF004' => 'VF004 - Customer Experience CRM Manager',
];

$eoiRecords   = [];
$viewSummary  = 'Showing all Expressions of Interest';

if (!$dbError) {
    // Load dynamic job titles from `jobs` if table exists
    $jobsRes = mysqli_query($conn, "SELECT job_ref, job_title FROM jobs ORDER BY job_ref ASC");
    if ($jobsRes) {
        while ($jRow = mysqli_fetch_assoc($jobsRes)) {
            $ref = strtoupper(trim((string) $jRow['job_ref']));
            if ($ref !== '') {
                $jobRefOptions[$ref] = $ref . ' - ' . trim((string) $jRow['job_title']);
            }
        }
        mysqli_free_result($jobsRes);
    }

    // Also include any distinct job_ref present in `eoi`
    $eoiRefsRes = mysqli_query($conn, "SELECT DISTINCT job_ref FROM eoi ORDER BY job_ref ASC");
    if ($eoiRefsRes) {
        while ($rRow = mysqli_fetch_assoc($eoiRefsRes)) {
            $ref = strtoupper(trim((string) $rRow['job_ref']));
            if ($ref !== '' && !isset($jobRefOptions[$ref])) {
                $jobRefOptions[$ref] = $ref;
            }
        }
        mysqli_free_result($eoiRefsRes);
    }

    // Aggregate status metrics across the entire `eoi` table
    $statRes = mysqli_query($conn, "SELECT status, COUNT(*) AS cnt FROM eoi GROUP BY status");
    if ($statRes) {
        while ($sRow = mysqli_fetch_assoc($statRes)) {
            $st  = $sRow['status'];
            $cnt = (int) $sRow['cnt'];
            $stats['total'] += $cnt;
            if (isset($stats[$st])) {
                $stats[$st] = $cnt;
            }
        }
        mysqli_free_result($statRes);
    }

    // Build filtered & sorted SELECT query using prepared statements
    $baseSelect = "SELECT EOInumber, job_ref, first_name, last_name, dob, gender,
                          email, phone, state, postcode, street_address, suburb,
                          primary_skill, additional_skills, other_skills, documents, status
                   FROM eoi";
    $orderClause = " ORDER BY {$sortBy} {$sortDir}, EOInumber ASC";

    $stmt       = null;
    $skipQuery  = false;

    if ($queryMode === 'by_job') {
        if ($filterJobRef === '') {
            if ($feedbackMsg === '') {
                $feedbackType = 'error';
                $feedbackMsg  = 'Please select a Job Reference Number to filter EOIs.';
            }
            $viewSummary = 'Filter by Job Reference (no reference selected)';
            $skipQuery   = true;
        } else {
            $viewSummary = "Showing EOIs for Job Reference \"{$filterJobRef}\"";
            $stmt = mysqli_prepare($conn, $baseSelect . " WHERE job_ref = ?" . $orderClause);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $filterJobRef);
            }
        }
    } elseif ($queryMode === 'by_name') {
        if ($filterFirstName === '' && $filterLastName === '') {
            if ($feedbackMsg === '') {
                $feedbackType = 'error';
                $feedbackMsg  = 'Please enter a First Name, a Last Name, or both to search applicants.';
            }
            $viewSummary = 'Filter by Applicant Name (no name entered)';
            $skipQuery   = true;
        } elseif ($filterFirstName !== '' && $filterLastName !== '') {
            $viewSummary = "Showing EOIs matching First Name \"{$filterFirstName}\" and Last Name \"{$filterLastName}\"";
            $fnPattern   = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filterFirstName) . '%';
            $lnPattern   = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filterLastName) . '%';
            $stmt = mysqli_prepare(
                $conn,
                $baseSelect . " WHERE first_name LIKE ? ESCAPE '!' AND last_name LIKE ? ESCAPE '!'" . $orderClause
            );
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ss', $fnPattern, $lnPattern);
            }
        } elseif ($filterFirstName !== '') {
            $viewSummary = "Showing EOIs matching First Name \"{$filterFirstName}\"";
            $fnPattern   = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filterFirstName) . '%';
            $stmt = mysqli_prepare(
                $conn,
                $baseSelect . " WHERE first_name LIKE ? ESCAPE '!'" . $orderClause
            );
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $fnPattern);
            }
        } else {
            $viewSummary = "Showing EOIs matching Last Name \"{$filterLastName}\"";
            $lnPattern   = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filterLastName) . '%';
            $stmt = mysqli_prepare(
                $conn,
                $baseSelect . " WHERE last_name LIKE ? ESCAPE '!'" . $orderClause
            );
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $lnPattern);
            }
        }
    } else {
        $viewSummary = 'Showing all Expressions of Interest';
        $stmt = mysqli_prepare($conn, $baseSelect . $orderClause);
    }

    if (!$skipQuery) {
        if ($stmt && mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $eoiRecords[] = $row;
                }
                mysqli_free_result($res);
            } else {
                $dbError = true;
            }
            mysqli_stmt_close($stmt);
        } else {
            if ($stmt) {
                mysqli_stmt_close($stmt);
            }
            $dbError = true;
        }
    }

    mysqli_close($conn);
}

$adminFullName = (string) ($_SESSION['admin_full_name'] ?? 'HR Manager');
$adminUsername = (string) ($_SESSION['admin_username'] ?? 'admin');
$adminRole     = (string) ($_SESSION['admin_role'] ?? 'HR Manager');

$pageTitle       = 'HR Manager Portal | VinFast Digital Retail Careers';
$pageDescription = 'Manage Expressions of Interest (EOIs) for VinFast Digital Retail Careers.';
$pageAuthor      = 'Nguyen Nhat Thanh';
$bodyClass       = 'page-manage';
$currentPage     = 'login';

$extraHead = <<<'MANAGE_STYLES'
<style>
.page-manage .manage-main {
    padding: clamp(20px, 3.5vw, 40px) 0 76px;
}

/* Hero Banner matching login.php aesthetic */
.page-manage .manage-hero {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: clamp(26px, 4vw, 42px);
    margin-bottom: 24px;
    border-radius: 22px;
    background: linear-gradient(150deg, #0b5fff 0%, #0a3eab 56%, #081f52 100%);
    color: #ffffff;
    box-shadow: 0 20px 54px rgba(13, 40, 83, 0.14);
    overflow: hidden;
    isolation: isolate;
}
.page-manage .manage-hero::before {
    content: '';
    position: absolute;
    width: 320px;
    height: 320px;
    right: -90px;
    bottom: -150px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 50%;
    box-shadow: 0 0 0 60px rgba(255, 255, 255, 0.045),
                0 0 0 125px rgba(255, 255, 255, 0.03);
    pointer-events: none;
    z-index: -1;
}
.page-manage .manage-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 13px;
    margin-bottom: 14px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    font-size: 0.75rem;
    font-weight: 750;
    letter-spacing: 0.09em;
    text-transform: uppercase;
}
.page-manage .manage-eyebrow::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #8fd9ff;
}
.page-manage .manage-hero h1 {
    margin: 0 0 8px;
    color: #ffffff;
    font-size: clamp(1.75rem, 3.2vw, 2.5rem);
    font-weight: 800;
    letter-spacing: -0.035em;
    line-height: 1.15;
}
.page-manage .manage-hero h1 span {
    color: #b8d6ff;
}
.page-manage .manage-hero p {
    margin: 0;
    max-width: 60ch;
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.96rem;
}
.page-manage .manage-session-box {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    padding: 16px 20px;
    border: 1px solid rgba(255, 255, 255, 0.24);
    border-radius: 16px;
    background: rgba(8, 31, 82, 0.42);
}
.page-manage .manage-session-user {
    text-align: right;
}
.page-manage .manage-session-user strong {
    display: block;
    font-size: 0.98rem;
    color: #ffffff;
}
.page-manage .manage-session-user span {
    font-size: 0.82rem;
    color: #b8d6ff;
}
.page-manage .btn-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 8px 18px;
    border: 1.5px solid rgba(255, 255, 255, 0.45);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.14);
    color: #ffffff;
    font: inherit;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.15s ease, border-color 0.15s ease;
}
.page-manage .btn-logout:hover {
    background: #ffffff;
    border-color: #ffffff;
    color: var(--color-navy, #0a1f4d);
}

/* KPI Strip */
.page-manage .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.page-manage .kpi-card {
    padding: 18px 20px;
    border: 1px solid var(--color-border, #dbe6f5);
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(13, 40, 83, 0.05);
}
.page-manage .kpi-card__label {
    display: block;
    margin-bottom: 6px;
    color: var(--color-muted, #4a5a72);
    font-size: 0.78rem;
    font-weight: 750;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
.page-manage .kpi-card__value {
    font-size: 1.85rem;
    font-weight: 800;
    color: var(--color-navy, #0a1f4d);
    line-height: 1.1;
}
.page-manage .kpi-card--new .kpi-card__value { color: #0b5fff; }
.page-manage .kpi-card--current .kpi-card__value { color: #b45309; }
.page-manage .kpi-card--final .kpi-card__value { color: #15803d; }

/* Feedback Alerts */
.page-manage .manage-alert {
    padding: 14px 18px;
    margin-bottom: 22px;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1.5;
}
.page-manage .manage-alert--success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 5px solid #16a34a;
    color: #166534;
}
.page-manage .manage-alert--error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-left: 5px solid #dc2626;
    color: #991b1b;
}
.page-manage .manage-alert--info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-left: 5px solid #0b5fff;
    color: #1e40af;
}

/* Query & Management Control Panels */
.page-manage .controls-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 26px;
}
.page-manage .control-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 22px 24px;
    border: 1px solid var(--color-border, #dbe6f5);
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(13, 40, 83, 0.06);
}
.page-manage .control-card--danger {
    border-color: #fecaca;
    background: #fffdfd;
}
.page-manage .control-card__tag {
    display: inline-block;
    width: fit-content;
    margin-bottom: 8px;
    padding: 3px 10px;
    border-radius: 999px;
    background: var(--color-chip, #e3edff);
    color: var(--color-primary-dark, #0847c4);
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
.page-manage .control-card--danger .control-card__tag {
    background: #fee2e2;
    color: #991b1b;
}
.page-manage .control-card h2 {
    margin: 0 0 6px;
    color: var(--color-navy, #0a1f4d);
    font-size: 1.18rem;
    font-weight: 750;
}
.page-manage .control-card p.control-card__desc {
    margin: 0 0 16px;
    color: var(--color-muted, #4a5a72);
    font-size: 0.88rem;
}
.page-manage .form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
.page-manage .field-group {
    margin-bottom: 12px;
}
.page-manage .field-group label {
    display: block;
    margin-bottom: 6px;
    color: #213452;
    font-size: 0.84rem;
    font-weight: 700;
}
.page-manage .field-group input[type="text"],
.page-manage .field-group input[type="number"],
.page-manage .field-group select {
    display: block;
    width: 100%;
    min-height: 44px;
    padding: 9px 12px;
    border: 1.5px solid #cddbf0;
    border-radius: 10px;
    background: #fbfdff;
    color: #142641;
    font: inherit;
    font-size: 0.92rem;
}
.page-manage .field-group input:focus-visible,
.page-manage .field-group select:focus-visible {
    outline: none;
    border-color: var(--color-primary, #0b5fff);
    box-shadow: 0 0 0 4px rgba(11, 95, 255, 0.15);
}
.page-manage .confirm-check {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin: 6px 0 14px;
    padding: 10px 12px;
    border: 1px solid #fecaca;
    border-radius: 10px;
    background: #fef2f2;
    color: #7f1d1d;
    font-size: 0.84rem;
    font-weight: 600;
    cursor: pointer;
}
.page-manage .confirm-check input[type="checkbox"] {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
.page-manage .btn-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 6px;
}
.page-manage .btn-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    padding: 10px 20px;
    border: 2px solid #dc2626;
    border-radius: 10px;
    background: #dc2626;
    color: #ffffff;
    font: inherit;
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.15s ease, border-color 0.15s ease;
}
.page-manage .btn-danger:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}

/* Results Section & Sort Bar */
.page-manage .results-card {
    border: 1px solid var(--color-border, #dbe6f5);
    border-radius: 20px;
    background: #ffffff;
    box-shadow: 0 14px 40px rgba(13, 40, 83, 0.08);
    overflow: hidden;
}
.page-manage .results-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--color-border, #dbe6f5);
    background: var(--color-block, #f5f9ff);
}
.page-manage .results-header__title h2 {
    margin: 0 0 4px;
    color: var(--color-navy, #0a1f4d);
    font-size: 1.25rem;
}
.page-manage .results-header__title p {
    margin: 0;
    color: var(--color-muted, #4a5a72);
    font-size: 0.88rem;
}
.page-manage .sort-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 10px;
}
.page-manage .sort-bar__field label {
    display: block;
    margin-bottom: 4px;
    font-size: 0.76rem;
    font-weight: 750;
    color: #213452;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.page-manage .sort-bar__field select {
    min-height: 40px;
    padding: 7px 11px;
    border: 1.5px solid #cddbf0;
    border-radius: 9px;
    background: #ffffff;
    color: #142641;
    font: inherit;
    font-size: 0.88rem;
}
.page-manage .sort-bar__btn {
    min-height: 40px;
    padding: 8px 16px;
    font-size: 0.88rem;
}

/* Responsive Data Table */
.page-manage .table-wrap {
    width: 100%;
    overflow-x: auto;
}
.page-manage .eoi-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
    text-align: left;
}
.page-manage .eoi-table thead th {
    padding: 13px 14px;
    background: var(--color-navy, #0a1f4d);
    color: #ffffff;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    white-space: nowrap;
}
.page-manage .eoi-table tbody tr {
    border-bottom: 1px solid var(--color-border, #dbe6f5);
}
.page-manage .eoi-table tbody tr:nth-child(even) {
    background: #fafcff;
}
.page-manage .eoi-table tbody tr:hover {
    background: #f0f6ff;
}
.page-manage .eoi-table td {
    padding: 13px 14px;
    vertical-align: top;
    color: #1e293b;
}
.page-manage .eoi-id-badge {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 8px;
    background: #eef4fc;
    color: var(--color-navy, #0a1f4d);
    font-weight: 800;
    white-space: nowrap;
}
.page-manage .job-ref-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    background: var(--color-chip, #e3edff);
    color: var(--color-primary-dark, #0847c4);
    font-weight: 750;
    white-space: nowrap;
}
.page-manage .status-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 11px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 750;
    white-space: nowrap;
}
.page-manage .status-pill--new {
    background: #e3edff;
    color: #0847c4;
    border: 1px solid #bfd6ff;
}
.page-manage .status-pill--current {
    background: #fff3e6;
    color: #b45309;
    border: 1px solid #fcd39c;
}
.page-manage .status-pill--final {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.page-manage .inline-status-form {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 170px;
}
.page-manage .inline-status-form select {
    min-height: 36px;
    padding: 5px 8px;
    border: 1.5px solid #cddbf0;
    border-radius: 8px;
    background: #ffffff;
    font: inherit;
    font-size: 0.83rem;
}
.page-manage .inline-status-form button {
    min-height: 36px;
    padding: 5px 11px;
    border: 0;
    border-radius: 8px;
    background: var(--color-primary, #0b5fff);
    color: #ffffff;
    font: inherit;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
}
.page-manage .inline-status-form button:hover {
    background: var(--color-primary-dark, #0847c4);
}
.page-manage .cell-sub {
    display: block;
    margin-top: 3px;
    color: var(--color-muted, #4a5a72);
    font-size: 0.81rem;
}
.page-manage .empty-state {
    padding: 44px 24px;
    text-align: center;
    color: var(--color-muted, #4a5a72);
}
.page-manage .empty-state h3 {
    margin-bottom: 6px;
    color: var(--color-navy, #0a1f4d);
}

@media (max-width: 960px) {
    .page-manage .kpi-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .page-manage .controls-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .page-manage .kpi-grid,
    .page-manage .form-grid-2 {
        grid-template-columns: 1fr;
    }
    .page-manage .manage-session-box {
        width: 100%;
        align-items: flex-start;
    }
    .page-manage .manage-session-user {
        text-align: left;
    }
}
@media (prefers-reduced-motion: reduce) {
    .page-manage .btn-logout,
    .page-manage .btn-danger {
        transition: none;
    }
}
</style>
MANAGE_STYLES;

require __DIR__ . '/header.inc';
require __DIR__ . '/nav.inc';
?>

<main id="main" class="manage-main">
    <div class="container">

        <!-- HR Portal Hero Banner -->
        <section class="manage-hero" aria-labelledby="manage-hero-title">
            <div>
                <span class="manage-eyebrow">VinFast Digital Retail · HR Portal</span>
                <h1 id="manage-hero-title">Recruitment <span>EOI Dashboard</span></h1>
                <p>Review, filter, sort, update application statuses, and manage Expressions of Interest across all open VinFast Digital Retail positions.</p>
            </div>
            <div class="manage-session-box">
                <div class="manage-session-user">
                    <strong><?= e($adminFullName) ?></strong>
                    <span>@<?= e($adminUsername) ?> · <?= e($adminRole) ?></span>
                </div>
                <form method="post" action="manage.php">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="post_action" value="logout">
                    <button type="submit" class="btn-logout">Sign out</button>
                </form>
            </div>
        </section>

        <!-- Status Summary KPIs -->
        <section class="kpi-grid" aria-label="EOI status summary">
            <div class="kpi-card">
                <span class="kpi-card__label">Total EOIs</span>
                <span class="kpi-card__value"><?= (int) $stats['total'] ?></span>
            </div>
            <div class="kpi-card kpi-card--new">
                <span class="kpi-card__label">New</span>
                <span class="kpi-card__value"><?= (int) $stats['New'] ?></span>
            </div>
            <div class="kpi-card kpi-card--current">
                <span class="kpi-card__label">Current</span>
                <span class="kpi-card__value"><?= (int) $stats['Current'] ?></span>
            </div>
            <div class="kpi-card kpi-card--final">
                <span class="kpi-card__label">Final</span>
                <span class="kpi-card__value"><?= (int) $stats['Final'] ?></span>
            </div>
        </section>

        <!-- Operation Feedback Banner -->
        <?php if ($dbError): ?>
            <p class="manage-alert manage-alert--error" role="alert">Database connection or query error. Please verify MySQL is running and <code>vinfast_db</code> is configured.</p>
        <?php elseif ($feedbackMsg !== ''): ?>
            <p class="manage-alert manage-alert--<?= e($feedbackType) ?>" role="status"><?= e($feedbackMsg) ?></p>
        <?php endif; ?>

        <!-- HR Manager Query & Action Controls (Zero JavaScript) -->
        <section class="controls-grid" aria-label="HR Manager Query and Action Tools">

            <!-- 1. List All EOIs & List by Job Reference -->
            <article class="control-card">
                <div>
                    <span class="control-card__tag">Query 1 &amp; 2 · Browse &amp; Filter</span>
                    <h2>List EOIs (All or by Job Reference)</h2>
                    <p class="control-card__desc">Display all submitted EOIs or filter applications for a specific VinFast job reference code.</p>

                    <form method="get" action="manage.php">
                        <input type="hidden" name="action" value="by_job">
                        <div class="field-group">
                            <label for="filter-job-ref">Job Reference Number</label>
                            <select id="filter-job-ref" name="job_ref">
                                <option value="">-- Select a Job Reference --</option>
                                <?php foreach ($jobRefOptions as $code => $label): ?>
                                    <option value="<?= e($code) ?>"<?= ($filterJobRef === $code) ? ' selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-grid-2">
                            <div class="field-group">
                                <label for="job-sort-by">Sort Results By</label>
                                <select id="job-sort-by" name="sort_by">
                                    <?php foreach ($allowedSortFields as $fieldKey => $fieldLabel): ?>
                                        <option value="<?= e($fieldKey) ?>"<?= ($sortBy === $fieldKey) ? ' selected' : '' ?>><?= e($fieldLabel) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field-group">
                                <label for="job-sort-dir">Order</label>
                                <select id="job-sort-dir" name="sort_dir">
                                    <option value="ASC"<?= ($sortDir === 'ASC') ? ' selected' : '' ?>>Ascending (A–Z / 1–9)</option>
                                    <option value="DESC"<?= ($sortDir === 'DESC') ? ' selected' : '' ?>>Descending (Z–A / 9–1)</option>
                                </select>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn--primary">List by Job Reference</button>
                            <a href="manage.php?action=all&amp;sort_by=<?= urlencode($sortBy) ?>&amp;sort_dir=<?= urlencode($sortDir) ?>" class="btn btn--secondary">List All EOIs</a>
                        </div>
                    </form>
                </div>
            </article>

            <!-- 2. List by Applicant First Name, Last Name, or Both -->
            <article class="control-card">
                <div>
                    <span class="control-card__tag">Query 3 · Applicant Search</span>
                    <h2>Search by Applicant Name</h2>
                    <p class="control-card__desc">Search EOIs by applicant first name, last name, or both fields together.</p>

                    <form method="get" action="manage.php">
                        <input type="hidden" name="action" value="by_name">
                        <div class="form-grid-2">
                            <div class="field-group">
                                <label for="search-first-name">First Name</label>
                                <input type="text" id="search-first-name" name="first_name" maxlength="20"
                                       placeholder="e.g. Minh" value="<?= e($filterFirstName) ?>">
                            </div>
                            <div class="field-group">
                                <label for="search-last-name">Last Name</label>
                                <input type="text" id="search-last-name" name="last_name" maxlength="20"
                                       placeholder="e.g. Nguyen" value="<?= e($filterLastName) ?>">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="field-group">
                                <label for="name-sort-by">Sort Results By</label>
                                <select id="name-sort-by" name="sort_by">
                                    <?php foreach ($allowedSortFields as $fieldKey => $fieldLabel): ?>
                                        <option value="<?= e($fieldKey) ?>"<?= ($sortBy === $fieldKey) ? ' selected' : '' ?>><?= e($fieldLabel) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field-group">
                                <label for="name-sort-dir">Order</label>
                                <select id="name-sort-dir" name="sort_dir">
                                    <option value="ASC"<?= ($sortDir === 'ASC') ? ' selected' : '' ?>>Ascending (A–Z / 1–9)</option>
                                    <option value="DESC"<?= ($sortDir === 'DESC') ? ' selected' : '' ?>>Descending (Z–A / 9–1)</option>
                                </select>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn--primary">Search Applicant</button>
                            <a href="manage.php?action=all" class="btn btn--secondary">Reset Search</a>
                        </div>
                    </form>
                </div>
            </article>

            <!-- 3. Change an EOI Status -->
            <article class="control-card">
                <div>
                    <span class="control-card__tag">Action 4 · Status Workflow</span>
                    <h2>Change an EOI Status</h2>
                    <p class="control-card__desc">Update an application's recruitment stage (<strong>New</strong>, <strong>Current</strong>, or <strong>Final</strong>) by EOI Number or directly in the table below.</p>

                    <form method="post" action="manage.php">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="post_action" value="update_status">
                        <input type="hidden" name="action" value="<?= e($queryMode) ?>">
                        <input type="hidden" name="job_ref" value="<?= e($filterJobRef) ?>">
                        <input type="hidden" name="first_name" value="<?= e($filterFirstName) ?>">
                        <input type="hidden" name="last_name" value="<?= e($filterLastName) ?>">
                        <input type="hidden" name="sort_by" value="<?= e($sortBy) ?>">
                        <input type="hidden" name="sort_dir" value="<?= e($sortDir) ?>">

                        <div class="form-grid-2">
                            <div class="field-group">
                                <label for="status-eoi-number">EOI Number</label>
                                <input type="number" id="status-eoi-number" name="eoi_number" min="1" step="1"
                                       placeholder="e.g. 1" required>
                            </div>
                            <div class="field-group">
                                <label for="status-new-value">New Status</label>
                                <select id="status-new-value" name="new_status" required>
                                    <option value="New">New</option>
                                    <option value="Current">Current</option>
                                    <option value="Final">Final</option>
                                </select>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn--primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </article>

            <!-- 4. Delete All EOIs by a Given Job Reference -->
            <article class="control-card control-card--danger">
                <div>
                    <span class="control-card__tag">Action 5 · Permanent Removal</span>
                    <h2>Delete EOIs by Job Reference</h2>
                    <p class="control-card__desc">Permanently remove all Expression of Interest records matching the selected job reference code.</p>

                    <form method="post" action="manage.php">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="post_action" value="delete_by_job">
                        <input type="hidden" name="action" value="<?= e($queryMode) ?>">
                        <input type="hidden" name="job_ref" value="<?= e($filterJobRef) ?>">
                        <input type="hidden" name="first_name" value="<?= e($filterFirstName) ?>">
                        <input type="hidden" name="last_name" value="<?= e($filterLastName) ?>">
                        <input type="hidden" name="sort_by" value="<?= e($sortBy) ?>">
                        <input type="hidden" name="sort_dir" value="<?= e($sortDir) ?>">

                        <div class="field-group">
                            <label for="delete-job-ref">Target Job Reference</label>
                            <select id="delete-job-ref" name="delete_job_ref" required>
                                <option value="">-- Select Job Reference to Delete --</option>
                                <?php foreach ($jobRefOptions as $code => $label): ?>
                                    <option value="<?= e($code) ?>"><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="confirm-check" for="confirm-delete-box">
                            <input type="checkbox" id="confirm-delete-box" name="confirm_delete" value="1" required>
                            <span>I confirm that I want to permanently delete all EOIs for this job reference.</span>
                        </label>

                        <div class="btn-row">
                            <button type="submit" class="btn-danger">Delete Matching EOIs</button>
                        </div>
                    </form>
                </div>
            </article>

        </section>

        <!-- EOI Results Table & Sort Field Selector -->
        <section class="results-card" aria-labelledby="results-heading">
            <div class="results-header">
                <div class="results-header__title">
                    <h2 id="results-heading"><?= e($viewSummary) ?></h2>
                    <p>
                        <?= count($eoiRecords) ?> <?= count($eoiRecords) === 1 ? 'record' : 'records' ?> found ·
                        Sorted by <strong><?= e($allowedSortFields[$sortBy]) ?></strong> (<?= e($sortDir) ?>)
                    </p>
                </div>

                <!-- Quick Sort Form preserving active filter -->
                <form class="sort-bar" method="get" action="manage.php">
                    <input type="hidden" name="action" value="<?= e($queryMode) ?>">
                    <?php if ($queryMode === 'by_job'): ?>
                        <input type="hidden" name="job_ref" value="<?= e($filterJobRef) ?>">
                    <?php elseif ($queryMode === 'by_name'): ?>
                        <input type="hidden" name="first_name" value="<?= e($filterFirstName) ?>">
                        <input type="hidden" name="last_name" value="<?= e($filterLastName) ?>">
                    <?php endif; ?>

                    <div class="sort-bar__field">
                        <label for="quick-sort-by">Sort Field</label>
                        <select id="quick-sort-by" name="sort_by">
                            <?php foreach ($allowedSortFields as $fieldKey => $fieldLabel): ?>
                                <option value="<?= e($fieldKey) ?>"<?= ($sortBy === $fieldKey) ? ' selected' : '' ?>><?= e($fieldLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="sort-bar__field">
                        <label for="quick-sort-dir">Direction</label>
                        <select id="quick-sort-dir" name="sort_dir">
                            <option value="ASC"<?= ($sortDir === 'ASC') ? ' selected' : '' ?>>Ascending</option>
                            <option value="DESC"<?= ($sortDir === 'DESC') ? ' selected' : '' ?>>Descending</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn--primary sort-bar__btn">Apply Sort</button>
                </form>
            </div>

            <?php if (empty($eoiRecords)): ?>
                <div class="empty-state">
                    <h3>No Expressions of Interest Found</h3>
                    <p>No EOI records matched your current filter criteria. Click <strong>List All EOIs</strong> above to view every application.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="eoi-table">
                        <thead>
                            <tr>
                                <th scope="col">EOI #</th>
                                <th scope="col">Job Ref</th>
                                <th scope="col">Applicant</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Address</th>
                                <th scope="col">Skills &amp; Documents</th>
                                <th scope="col">Current Status</th>
                                <th scope="col">Change Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eoiRecords as $row):
                                $eoiId      = (int) $row['EOInumber'];
                                $statusText = (string) $row['status'];
                                $statusSlug = strtolower($statusText);
                            ?>
                            <tr>
                                <td><span class="eoi-id-badge">#<?= $eoiId ?></span></td>
                                <td><span class="job-ref-pill"><?= e((string) $row['job_ref']) ?></span></td>
                                <td>
                                    <strong><?= e((string) $row['first_name'] . ' ' . (string) $row['last_name']) ?></strong>
                                    <span class="cell-sub">DOB: <?= e((string) $row['dob']) ?></span>
                                    <span class="cell-sub">Gender: <?= e(ucfirst((string) $row['gender'])) ?></span>
                                </td>
                                <td>
                                    <a href="mailto:<?= e((string) $row['email']) ?>"><?= e((string) $row['email']) ?></a>
                                    <span class="cell-sub">Tel: <?= e((string) $row['phone']) ?></span>
                                </td>
                                <td>
                                    <?= e((string) $row['street_address']) ?>
                                    <span class="cell-sub"><?= e((string) $row['suburb'] . ', ' . (string) $row['state'] . ' ' . (string) $row['postcode']) ?></span>
                                </td>
                                <td>
                                    <strong>Primary:</strong> <?= e((string) $row['primary_skill']) ?>
                                    <span class="cell-sub"><strong>Additional:</strong> <?= e((string) ($row['additional_skills'] ?? 'None')) ?></span>
                                    <?php if (!empty($row['other_skills']) && $row['other_skills'] !== 'None'): ?>
                                        <span class="cell-sub"><strong>Other:</strong> <?= e((string) $row['other_skills']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($row['documents']) && $row['documents'] !== 'None'):
                                        $docItems = array_filter(array_map('trim', explode(',', (string) $row['documents'])));
                                    ?>
                                        <span class="cell-sub"><strong>Docs:</strong>
                                            <?php foreach ($docItems as $idx => $docName):
                                                $safeDocBase = basename($docName);
                                                $docDiskPath = __DIR__ . '/uploads/' . $safeDocBase;
                                                if ($idx > 0) { echo ', '; }
                                                if ($safeDocBase !== '' && is_file($docDiskPath)): ?>
                                                    <a href="uploads/<?= e(rawurlencode($safeDocBase)) ?>" target="_blank" rel="noopener"><?= e($safeDocBase) ?></a>
                                                <?php else: ?>
                                                    <?= e($docName) ?>
                                                <?php endif;
                                            endforeach; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill status-pill--<?= e($statusSlug) ?>"><?= e($statusText) ?></span>
                                </td>
                                <td>
                                    <form class="inline-status-form" method="post" action="manage.php">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                        <input type="hidden" name="post_action" value="update_status">
                                        <input type="hidden" name="eoi_number" value="<?= $eoiId ?>">
                                        <input type="hidden" name="action" value="<?= e($queryMode) ?>">
                                        <input type="hidden" name="job_ref" value="<?= e($filterJobRef) ?>">
                                        <input type="hidden" name="first_name" value="<?= e($filterFirstName) ?>">
                                        <input type="hidden" name="last_name" value="<?= e($filterLastName) ?>">
                                        <input type="hidden" name="sort_by" value="<?= e($sortBy) ?>">
                                        <input type="hidden" name="sort_dir" value="<?= e($sortDir) ?>">

                                        <label class="sr-only" for="row-status-<?= $eoiId ?>">Status for EOI #<?= $eoiId ?></label>
                                        <select id="row-status-<?= $eoiId ?>" name="new_status">
                                            <?php foreach ($validStatuses as $stOption): ?>
                                                <option value="<?= e($stOption) ?>"<?= ($statusText === $stOption) ? ' selected' : '' ?>><?= e($stOption) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit">Save</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php require __DIR__ . '/footer.inc'; ?>