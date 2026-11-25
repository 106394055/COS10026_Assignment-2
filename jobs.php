<?php
/**
 * COS10026 Part 2 — SCRUM-83: Jobs rendered from MySQL.
 * Data source: vinfast_db.jobs (the team's exported jobs.sql).
 * The markup and CSS class names intentionally match the Part 1 Jobs page.
 */
$pageTitle = 'VinFast Jobs page';
$pageDescription = 'Explore job opportunities at VinFast Digital Retail.';
$pageAuthor = 'Duong Nguyen Khoi';
$bodyClass = 'page-jobs';
$currentPage = 'jobs';

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

require_once __DIR__ . '/db_connect.php'; // Provides $conn, as established by the team.
if (isset($conn) && $conn instanceof mysqli) {
    // Fixed SQL; prepare now so later search/filtering can safely bind parameters.
    $sql = 'SELECT job_ref, category, job_title, team, reports_to, work_mode,
                   type_and_hours, salary, about_role, key_responsibilities,
                   essential_requirements, preferable_criteria, performance_goals,
                   benefits, equal_opportunity, closing_date
            FROM jobs ORDER BY job_ref';
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt && mysqli_stmt_execute($stmt)) {
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
    if ($stmt) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    $dbError = true;
}
if ($dbError) {
    error_log('Jobs page: unable to load job listings from MySQL.');
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
          <p class="hero__text"><?= count($jobs) ?> listed roles in digital retail. Explore each position below.</p>
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
        <p>Explore <?= count($jobs) ?> digital retail positions. Select a role to read its complete job description and application deadline.</p>
      </section>

      <nav id="role-links" class="panel" aria-label="Open a role">
        <p class="panel__label">Open a role: select a reference to read its full details</p>
        <?php if ($jobs): ?>
        <ul class="pill-list">
          <?php foreach ($jobs as $role): ?>
            <li><a class="pill" href="#<?= h(job_fragment($role['job_ref'])) ?>"><?= h($role['job_ref']) ?>: <?= h($role['category']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
          <p role="status"><?= $dbError ? 'Job listings are temporarily unavailable. Please try again later.' : 'No jobs are currently listed.' ?></p>
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
