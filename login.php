<?php
$pageTitle = 'Sign In | VinFast Digital Retail Careers';
$pageDescription = 'Preview of the secure manager sign-in page for VinFast Digital Retail Careers.';
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
.page-login .login-hint {
    margin: 7px 0 0;
    color: #64748b;
    font-size: .79rem;
    line-height: 1.5;
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
}
.page-login .login-submit:disabled {
    opacity: .58;
    cursor: not-allowed;
}
.page-login .login-notice {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-top: 18px;
    padding: 14px 15px;
    border: 1px solid #cfe0fb;
    border-radius: 10px;
    background: #f1f6ff;
    color: #27456e;
    font-size: .84rem;
    line-height: 1.6;
}
.page-login .login-notice svg {
    flex-shrink: 0;
    margin-top: 2px;
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
    .page-login .login-field input { transition: none; }
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
                    <p>Welcome to the careers portal. A dedicated sign-in space for the recruitment management team.</p>
                </div>
                <div class="login-intro__footer">
                    <span class="login-lock" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="11" width="16" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                        </svg>
                    </span>
                    <span>Manager access will be protected by server-side authentication.</span>
                </div>
            </section>

            <section class="login-form-panel" aria-labelledby="login-title">
                <p class="login-topline">Manager access</p>
                <h2 id="login-title">Welcome back</h2>
                <p class="login-subtitle">Sign in to manage recruitment applications and review candidate information.</p>

                <!-- Preview UI only. No submission form is used, preventing credentials from being transmitted. -->
                <div class="login-fields" role="group" aria-label="Login form preview">
                    <div class="login-field">
                        <label for="login-username">Username</label>
                        <input id="login-username" name="username-preview" type="text" autocomplete="off" placeholder="Enter your username" aria-describedby="login-preview-notice">
                    </div>
                    <div class="login-field">
                        <label for="login-password">Password</label>
                        <input id="login-password" name="password-preview" type="password" autocomplete="off" placeholder="Enter your password" aria-describedby="login-preview-notice">
                        <p class="login-hint">Authentication and password verification will be enabled in the next development step.</p>
                    </div>
                </div>

                <button class="login-submit" type="button" disabled aria-describedby="login-preview-notice">Sign in</button>
                <div class="login-notice" role="status" id="login-preview-notice">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><path d="M12 11v6M12 7h.01"/>
                    </svg>
                    <span><strong>Preview mode:</strong> Sign-in is not enabled. This page does not send or store usernames or passwords. Please do not enter real credentials yet.</span>
                </div>

                <p class="login-bottom">Looking for opportunities? <a href="jobs.php">Explore open jobs</a></p>
            </section>
        </div>
    </div>
</main>

<?php require __DIR__ . '/footer.inc'; ?>
