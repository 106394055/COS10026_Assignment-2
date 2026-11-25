<?php
/**
 * COS10026 Part 2 — SCRUM-83/84: Database-driven Jobs display and search.
 * Data source: vinfast_db.jobs (the team's exported jobs.sql).
 * The markup and CSS class names intentionally match the Part 1 Jobs page.
 */
$pageTitle = 'VinFast Jobs page';
$pageDescription = 'Explore job opportunities at VinFast Digital Retail.';
$pageAuthor = 'Duong Nguyen Khoi';
$bodyClass = 'page-jobs';
$currentPage = 'jobs';


// Scoped search styling: no modifications to the team's shared stylesheet.
$extraHead = <<<'JOBS_SEARCH_STYLES'
<style>
.page-jobs .jobs-search { display: flex; flex-wrap: wrap; align-items: end; gap: 12px; margin: 12px 0 16px; }
.page-jobs .jobs-search__field { flex: 1 1 220px; min-width: 0; }
.page-jobs .jobs-search__label { display: block; font-weight: 600; color: #0a1f4d; margin-bottom: 6px; }
.page-jobs .jobs-search__input { width: 100%; min-height: 44px; padding: 10px 12px; font: inherit; color: #0a1f4d; border-radius: 10px; border: 1px solid #96acce; background: #fff; }
.page-jobs .jobs-search__input:focus-visible { outline: 3px solid #135cf5; outline-offset: 2px; }
.page-jobs .jobs-search__submit { min-height: 44px; padding: 10px 20px; border: 0; border-radius: 10px; background: #0b5fff; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
.page-jobs .jobs-search__submit:hover { background: #084acb; }
.page-jobs .jobs-search__submit:focus-visible { outline: 3px solid #09275a; outline-offset: 3px; }
.page-jobs .jobs-search__clear { min-height: 44px; display: inline-flex; align-items: center; color: #0b48ae; font-weight: 600; text-decoration: underline; }
.page-jobs .jobs-search__status { margin: 8px 0 12px; color: #223452; font-weight: 500; }
.page-jobs .jobs-search__error { color: #971e27; font-weight: 700; }
@media (max-width: 600px) {
  .page-jobs .jobs-search { align-items: stretch; flex-direction: column; }
  .page-jobs .jobs-search__field, .page-jobs .jobs-search__submit { width: 100%; flex: auto; }
}
</style>
JOBS_SEARCH_STYLES;

/** Escape database text before putting it into HTML or HTML attributes. */
function h($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Split the existing newline-separated SQL descriptions into list items. */
function job_lines($value): array {
    $lines = preg_split('/\r\n|\n|\r/', trim((string) ($value ?? '')));
    return array_values(array_filter(array_map('trim', $lines ?: []), static fn($line) => $line !== ''));
}

/** Use a safe fragment identifier for CSS :target role panels. */
function job_fragment($reference): string {
    return preg_replace('/[^a-z0-9_-]/', '', strtolower((string) $reference));
}

/** Present an ISO SQL DATE in the same format as the original Jobs page. */
function job_closing_date($value): string {
    $raw = (string) ($value ?? '');
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $raw);
    return $date && $date->format('Y-m-d') === $raw
        ? $date->format('l, d F Y')
        : $raw;
}

/** The short department introduction comes from the database role description. */
function job_intro($value): string {
    $text = trim((string) ($value ?? ''));
    return preg_match('/^(.+?[.!?])(?:\s|$)/us', $text, $matches) ? $matches[1] : $text;
}

$jobs = [];
$dbError = false;

// Search is optional: opening jobs.php without ?q= lists all valid jobs.
$hasSearch = array_key_exists('q', $_GET);
$searchTerm = ($hasSearch && is_string($_GET['q'])) ? trim($_GET['q']) : '';
$searchError = '';
if ($hasSearch && !is_string($_GET['q'])) {
    $searchError = 'Invalid search request.';
} elseif (strlen($searchTerm) > 400) {
    $searchError = 'Please use a shorter search term.';
}

require_once __DIR__ . '/db_connect.php'; // Shared connection: settings.php -> $conn.
if (isset($conn) && $conn instanceof mysqli && $searchError === '') {
    $sql = "SELECT job_ref, category, job_title, team, reports_to, work_mode,
                   type_and_hours, salary, about_role, key_responsibilities,
                   essential_requirements, preferable_criteria, performance_goals,
                   benefits, equal_opportunity, closing_date
            FROM jobs
            WHERE TRIM(job_ref) <> '' AND TRIM(job_title) <> ''";

    if ($searchTerm !== '') {
        // Match job titles only. Escape LIKE wildcards so % and _ are literal.
        $sql .= " AND job_title LIKE ? ESCAPE '!'";
    }
    $sql .= ' ORDER BY job_ref';

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $bound = true;
        if ($searchTerm !== '') {
            $literalSearch = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $searchTerm);
            $pattern = '%' . $literalSearch . '%';
            $bound = mysqli_stmt_bind_param($stmt, 's', $pattern);
        }
        if ($bound && mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result !== false) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $jobs[] = $row;
                }
                mysqli_free_result($result);
            } else {
                $dbError = true;
            }
        } else {
            $dbError = true;
        }
        mysqli_stmt_close($stmt);
    } else {
        $dbError = true;
    }
    mysqli_close($conn);
} elseif ($searchError === '') {
    $dbError = true;
}
if ($dbError) {
    error_log('Jobs page: unable to load or search job listings from MySQL.');
}

require __DIR__ . '/header.inc';
require __DIR__ . '/nav.inc';
?>
<main id="main">
    <section class="hero" aria-labelledby="hero-title">
      <div class="container hero__grid">
        <div class="hero__content">
          <p class="hero__eyebrow">CAREERS AT VINFAST</p>
          <h1 id="hero-title" class="hero__title">Open Positions</h1>
          <p class="hero__subtitle">Build the future of digital retail with us.</p>
          <p class="hero__text"><?= count($jobs) ?> <?= count($jobs) === 1 ? 'role' : 'roles' ?> <?= $searchTerm !== '' ? 'match your search.' : 'listed in digital retail.' ?><?= $jobs ? ' Explore each position below.' : '' ?></p>
          <p class="hero__deadline">Application deadlines are listed for each role.</p>
        </div>
        <div class="hero__media">
          <div class="hero-slider" role="region" aria-label="VinFast gallery slideshow">
                <div class="hero-slider">
                  <div class="hero-slider_slide" id="slide-1">
                    <img src="images/Jobs/pic1.jpg" alt="Slide 1">
                    <a href="#slide-9" aria-label="Previous slide"></a>
                    <a href="#slide-2" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-2">
                    <img src="images/Jobs/pic2.jpg" alt="Slide 2">
                    <a href="#slide-1" aria-label="Previous slide"></a>
                    <a href="#slide-3" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-3"> 
                    <img src="images/Jobs/pic3.jpg" alt="Slide 3">
                    <a href="#slide-2" aria-label="Previous slide"></a>
                    <a href="#slide-4" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-4"> 
                    <img src="images/Jobs/pic4.jpg" alt="Slide 4">
                    <a href="#slide-3" aria-label="Previous slide"></a>
                    <a href="#slide-5" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-5"> 
                    <img src="images/Jobs/pic5.jpg" alt="Slide 5">
                    <a href="#slide-4" aria-label="Previous slide"></a>
                    <a href="#slide-6" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-6"> 
                    <img src="images/Jobs/pic6.jpg" alt="Slide 6">
                    <a href="#slide-5" aria-label="Previous slide"></a>
                    <a href="#slide-7" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-7"> 
                    <img src="images/Jobs/pic7.jpg" alt="Slide 7">
                    <a href="#slide-6" aria-label="Previous slide"></a>
                    <a href="#slide-8" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-8"> 
                    <img src="images/Jobs/pic8.jpg" alt="Slide 8">
                    <a href="#slide-7" aria-label="Previous slide"></a>
                    <a href="#slide-9" aria-label="Next slide"></a>
                  </div>

                  <div class="hero-slider_slide" id="slide-9"> 
                    <img src="images/Jobs/pic9.jpg" alt="Slide 9">
                    <a href="#slide-8" aria-label="Previous slide"></a>
                    <a href="#slide-1" aria-label="Next slide"></a>
                  </div>

                  <!-- CSS-only slide indicators -->
                  <nav class="hero-slider__dots" aria-label="Gallery slide navigation">
                      <a class="hero-slider__dot" href="#slide-1" aria-label="Go to slide 1"></a>
                      <a class="hero-slider__dot" href="#slide-2" aria-label="Go to slide 2"></a>
                      <a class="hero-slider__dot" href="#slide-3" aria-label="Go to slide 3"></a>
                      <a class="hero-slider__dot" href="#slide-4" aria-label="Go to slide 4"></a>
                      <a class="hero-slider__dot" href="#slide-5" aria-label="Go to slide 5"></a>
                      <a class="hero-slider__dot" href="#slide-6" aria-label="Go to slide 6"></a>
                      <a class="hero-slider__dot" href="#slide-7" aria-label="Go to slide 7"></a>
                      <a class="hero-slider__dot" href="#slide-8" aria-label="Go to slide 8"></a>
                      <a class="hero-slider__dot" href="#slide-9" aria-label="Go to slide 9"></a>
                  </nav>
                                </div> <!-- Inner hero-slider -->
              </div> <!-- Outer hero-slider -->
            </div> <!-- hero__media -->
          </div> <!-- hero__grid -->
        </section>

    <!-- 2. Breadcrumb -->

<div class="container overview">
      <aside class="scam-aside" aria-labelledby="scam-heading">
        <span class="scam-aside__icon" aria-hidden="true" style="user-select: none;">!</span>
        <h2 id="scam-heading">Spot recruitment scams</h2>
        <p>Fake job offers target job seekers. Check these points before you apply.</p>
        <ul>
          <li>A real application never asks for a fee.</li>
          <li>Never share bank details or one-time codes in an application.</li>
          <li>Check that the reference number matches the job listed on this page.</li>
          <li>Report suspicious messages using the email in the footer.</li>
        </ul>
      </aside>

      <section class="panel" aria-labelledby="about-roles-heading">
        <h2 id="about-roles-heading">About these roles</h2>
        <p>Explore digital retail positions. Select a listed role to read its complete job description and application deadline.</p>
      </section>

      <nav id="role-links" class="panel" aria-label="Open a role">
        <p class="panel__label">Find a role by job title</p>
        <form class="jobs-search" action="jobs.php#role-links" method="get" role="search">
          <div class="jobs-search__field">
            <label class="jobs-search__label" for="job-search-term">Search job titles</label>
            <input class="jobs-search__input" type="search" id="job-search-term" name="q"
                   value="<?= h($searchTerm) ?>" maxlength="100"
                   placeholder="e.g. Data Analyst, CRM Manager" autocomplete="off">
          </div>
          <button class="jobs-search__submit" type="submit">Search</button>
          <?php if ($searchTerm !== '' || $searchError !== ''): ?>
            <a class="jobs-search__clear" href="jobs.php#role-links">Clear search</a>
          <?php endif; ?>
        </form>
        <?php if ($searchError !== ''): ?>
          <p class="jobs-search__error" role="alert"><?= h($searchError) ?></p>
        <?php elseif (!$dbError && $jobs): ?>
          <p class="jobs-search__status" role="status">
            <?php if ($searchTerm !== ''): ?>
              <?= count($jobs) ?> <?= count($jobs) === 1 ? 'job matches' : 'jobs match' ?> "<?= h($searchTerm) ?>".
            <?php else: ?>
              Showing <?= count($jobs) ?> <?= count($jobs) === 1 ? 'job' : 'jobs' ?>.
            <?php endif; ?>
          </p>
        <?php endif; ?>
        <?php if ($jobs): ?>
        <ul class="pill-list">
          <?php foreach ($jobs as $role): ?>
            <li><a class="pill" href="#<?= h(job_fragment($role['job_ref'])) ?>"><?= h($role['job_ref']) ?>: <?= h($role['category']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php elseif ($searchError === ''): ?>
          <p role="status" class="jobs-search__status"><?= $dbError
              ? 'Job listings are temporarily unavailable. Please try again later.'
              : ($searchTerm !== ''
                  ? 'This occupation is not yet available on our site. Please check back for updates or explore other opportunities.'
                  : 'No jobs are currently listed.') ?></p>
        <?php endif; ?>
      </nav>

      <section class="panel how-to" aria-labelledby="how-to-apply-heading">
        <h2 id="how-to-apply-heading">How to apply</h2>
        <div class="how-to__body">
          <ol>
            <li><span>Choose a role and note its reference number.</span></li>
            <li><span>Complete the form on the Apply page.</span></li>
            <li><span>Check the closing date listed for your chosen role.</span></li>
          </ol>
          <a class="btn btn--primary how-to__cta" href="apply.php">Go to the application form &rarr;</a>
        </div>
      </section>


    <?php foreach ($jobs as $job):
        $ref = (string) $job['job_ref'];
        $fragment = job_fragment($ref);
        $roleTitleId = $fragment . '-title';
        $deadline = job_closing_date($job['closing_date']);
    ?>
    <section class="role-modal" id="<?= h($fragment) ?>" aria-labelledby="<?= h($roleTitleId) ?>">
      <a class="role-modal__backdrop" href="#role-links" tabindex="-1" aria-hidden="true"></a>
      <div class="role-modal__sheet">
        <nav class="role-tabs" aria-label="Switch role">
          <ul>
            <?php foreach ($jobs as $tab): ?>
              <li><a href="#<?= h(job_fragment($tab['job_ref'])) ?>"<?= $tab['job_ref'] === $ref ? ' aria-current="true"' : '' ?>><?= h($tab['job_ref']) ?></a></li>
            <?php endforeach; ?>
          </ul>
          <a class="role-modal__close" href="#role-links">Close</a>
        </nav>

        <header class="role-modal__head">
          <h2><?= h($job['category']) ?></h2>
          <p class="dept-text"><?= h(job_intro($job['about_role'])) ?></p>
          <div class="role-modal__title-row">
            <h3 id="<?= h($roleTitleId) ?>"><?= h($job['job_title']) ?></h3>
            <span class="ref-chip"><?= h($ref) ?></span>
            <a class="btn btn--primary" href="apply.php" aria-label="Apply for <?= h($ref) ?>: <?= h($job['job_title']) ?>">Apply for <?= h($ref) ?></a>
          </div>
        </header>

        <dl class="role-meta">
          <div><dt>Team</dt><dd><?= h($job['team']) ?></dd></div>
          <div><dt>Reports to</dt><dd><?= h($job['reports_to']) ?></dd></div>
          <div><dt>Work mode</dt><dd><?= h($job['work_mode']) ?></dd></div>
          <div><dt>Type and hours</dt><dd><?= h($job['type_and_hours']) ?></dd></div>
          <div><dt>Salary</dt><dd><?= h($job['salary']) ?></dd></div>
        </dl>

        <div class="role-grid">
          <section class="block block--wide" aria-labelledby="<?= h($fragment) ?>-about">
            <h4 id="<?= h($fragment) ?>-about">About the role</h4>
            <p class="block__context">What this role does and why it exists.</p>
            <p><?= h($job['about_role']) ?></p>
          </section>

          <section class="block block--wide" aria-labelledby="<?= h($fragment) ?>-resp">
            <h4 id="<?= h($fragment) ?>-resp">Key responsibilities</h4>
            <p class="block__context">What you will do, listed in priority order.</p>
            <ol>
              <?php foreach (job_lines($job['key_responsibilities']) as $item): ?>
                <li><?= h($item) ?></li>
              <?php endforeach; ?>
            </ol>
          </section>

          <section class="block" aria-labelledby="<?= h($fragment) ?>-ess">
            <h4 id="<?= h($fragment) ?>-ess">Essential requirements</h4>
            <p class="block__context">What you must have to be considered.</p>
            <ul>
              <?php foreach (job_lines($job['essential_requirements']) as $item): ?>
                <li><?= h($item) ?></li>
              <?php endforeach; ?>
            </ul>
          </section>

          <section class="block" aria-labelledby="<?= h($fragment) ?>-pref">
            <h4 id="<?= h($fragment) ?>-pref">Preferable criteria</h4>
            <p class="block__context">A bonus, but not required.</p>
            <ul>
              <?php foreach (job_lines($job['preferable_criteria']) as $item): ?>
                <li><?= h($item) ?></li>
              <?php endforeach; ?>
            </ul>
          </section>
        </div>

        <details class="see-more">
          <summary>See more</summary>
          <div class="role-grid">
            <section class="block block--wide" aria-labelledby="<?= h($fragment) ?>-goals">
              <h4 id="<?= h($fragment) ?>-goals">Performance goals</h4>
              <p class="block__context">What success looks like in this role.</p>
              <ul class="goal-list">
                <?php foreach (job_lines($job['performance_goals']) as $line):
                    $goalParts = explode(':', $line, 2);
                    $goalLabel = count($goalParts) === 2 ? trim($goalParts[0]) : 'Goal';
                    $goalText = count($goalParts) === 2 ? trim($goalParts[1]) : $line;
                ?>
                <li class="goal">
                  <p class="goal__label"><?= h($goalLabel) ?></p>
                  <p><?= h($goalText) ?></p>
                </li>
                <?php endforeach; ?>
              </ul>
            </section>

            <section class="block" aria-labelledby="<?= h($fragment) ?>-benefits">
              <h4 id="<?= h($fragment) ?>-benefits">Benefits</h4>
              <p class="block__context">What comes with the job.</p>
              <p><?= h($job['benefits']) ?></p>
            </section>

            <section class="block" aria-labelledby="<?= h($fragment) ?>-eeo">
              <h4 id="<?= h($fragment) ?>-eeo">Equal opportunity</h4>
              <p class="block__context">Our commitment to every applicant.</p>
              <p><?= h($job['equal_opportunity']) ?></p>
            </section>
          </div>
        </details>

        <footer class="role-modal__footer">
          <div>
            <strong>Applications close <time datetime="<?= h($job['closing_date']) ?>"><?= h($deadline) ?></time></strong>
            <p>Use reference <?= h($ref) ?> on the application form.</p>
          </div>
          <a class="btn" href="apply.php" aria-label="Apply for <?= h($ref) ?>: <?= h($job['job_title']) ?>">Apply for <?= h($ref) ?></a>
        </footer>
      </div>
    </section>
    <?php endforeach; ?>

  </div><!-- .container.overview -->
  </main>

<?php require __DIR__ . '/footer.inc'; ?>
