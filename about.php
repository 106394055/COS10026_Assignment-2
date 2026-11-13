<?php
/*
 * about.php - Team HCPS About page (Project Part 2)
 * Team contributions are loaded from the `about` table in MySQL.
 * Shared layout comes from header.inc / nav.inc / footer.inc.
 */
$pageTitle       = "VinFast About page";
$pageDescription = "Meet Team HCPS, the four Swinburne students who designed and built the VinFast Digital Retail careers website.";
$currentPage     = "about";
$htmlClass       = "no-js";

require_once "db_connect.php";   // gives us $conn (see settings.php for the credentials)

// ---- Query: all members, in display order --------------------------------
$members  = array();
$db_error = (!isset($conn) || $conn === false);   // also safe while db_connect.php is still empty

if (!$db_error) {
    $query = "SELECT member_id, student_id, full_name, initials, photo_path, photo_alt,
                     role_title, contribution_part1, contribution_part2, quote_vi, quote_en
              FROM about
              ORDER BY display_order ASC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
        mysqli_free_result($result);
    } else {
        $db_error = true;
    }
    mysqli_close($conn);
}

include "header.inc";
include "nav.inc";
?>

    <main id="main">
        <!-- hero section -->
        <section class="about-hero">
            <div class="container about-hero__inner">
                <div class="about-hero__content">
                    <p class="about-hero__subtitle">TEAM HCPS</p>
                    <h1 class="about-hero__title">Four members - <br> One careers website</h1>
                    <p class="about-hero__desc">We design and build the careers website for VinFast's technical and
                        user-experience teams supporting its online retail platform.</p>
                    <div class="about-hero__badges">
                        <span class="chip">G05 - E-Commerce & Digital Retail</span>
                        <span class="chip">COS10026 - Fall 2026</span>
                    </div>
                </div>
                <div class="about-hero__meta">
                    <div class="about-hero__team">
                        <p>THE TEAM</p>
                        <div class="team-avatars">
<?php foreach ($members as $m) { ?>
                            <span class="avatar"><?= htmlspecialchars($m["initials"]) ?></span>
<?php } ?>
                        </div>
                    </div>
                    <div class="about-hero__toolkit">
                        <p>OUR TOOLKIT</p>
                        <div class="toolkit-badges">
                            <span class="chip">Jira</span>
                            <span class="chip">GitHub</span>
                            <span class="chip">Canva</span>
                            <span class="chip">HTML5</span>
                            <span class="chip">CSS3</span>
                            <span class="chip">PHP</span>
                            <span class="chip">MySQL</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <!-- section 1: group info -->
            <section class="about-section" id="group-info">
                <h2 class="about-section__title">1. Group Information</h2>
                <div class="info-grid">
                    <div class="info-column">
                        <ul>
                            <li><strong>Group name:</strong> HCPS</li>
                            <li><strong>Class:</strong> Saturday 7:00 AM - 10:00 AM</li>
                            <li><strong>Members:</strong>
                                <ul>
<?php foreach ($members as $m) { ?>
                                    <li><?= htmlspecialchars($m["student_id"]) ?> - <?= htmlspecialchars($m["full_name"]) ?></li>
<?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="info-column">
                        <dl class="course-details">
                            <dt>Course:</dt>
                            <dd>COS10026 Computing Technology Inquiry Project</dd>
                            <dt>Semester:</dt>
                            <dd>Fall 2026</dd>
                            <dt>Facilitator:</dt>
                            <dd>Assoc. Prof. Ho Bao</dd>
                            <dt>Project:</dt>
                            <dd>Applied Web Project Part 2</dd>
                        </dl>
                    </div>
                </div>
            </section>

            <!-- section 2: team contributions -->
            <section class="about-section" id="team-contributions">
                <h2 class="about-section__title">2. Team Contributions & Quotes</h2>
                <p class="about-section__subtitle">What each of us built in Project Part 1 and Part 2, in our own words.</p>

                <?php if ($db_error || count($members) === 0) { ?>
                <p class="about-section__subtitle">Team contributions are temporarily unavailable. Please try again later.</p>
<?php } else { ?>
                <div class="team-grid">
<?php foreach ($members as $m) { ?>
                    <article class="team-card">
                        <div class="team-card__header">
<?php if (!empty($m["photo_path"])) { ?>
                            <img src="<?= htmlspecialchars($m["photo_path"]) ?>"
                                alt="<?= htmlspecialchars($m["photo_alt"]) ?>" class="team-card__img">
<?php } else { ?>
                            <div class="team-card__img-placeholder"><?= htmlspecialchars($m["initials"]) ?></div>
<?php } ?>
                            <h3 class="team-card__name"><?= htmlspecialchars($m["full_name"]) ?></h3>
                        </div>
                        <div class="team-card__body">
                            <p class="team-card__role"><?= htmlspecialchars($m["role_title"]) ?></p>
                            <p class="team-card__label">CONTRIBUTION - PROJECT PART 1:</p>
                            <p class="team-card__text"><?= htmlspecialchars($m["contribution_part1"]) ?></p>
                            <p class="team-card__label">CONTRIBUTION - PROJECT PART 2:</p>
                            <p class="team-card__text"><?= htmlspecialchars($m["contribution_part2"]) ?></p>
                            <div class="team-card__quote-box">
                                <p class="team-card__label">QUOTE (VIETNAMESE):</p>
                                <blockquote class="team-card__quote">"<?= htmlspecialchars($m["quote_vi"]) ?>"</blockquote>
                                <p class="team-card__label">ENGLISH TRANSLATION:</p>
                                <blockquote class="team-card__quote">"<?= htmlspecialchars($m["quote_en"]) ?>"</blockquote>
                            </div>
                        </div>
                    </article>
<?php } ?>
                </div>
<?php } ?>
            </section>

            <!-- section 3: how we work -->
            <section class="about-section" id="workflow">
                <h2 class="about-section__title">3. How We Work</h2>
                <p class="about-section__subtitle">From plan to published site.</p>

                <div class="workflow-steps">
                    <div class="step">
                        <div class="step__header">
                            <span class="step__num">1</span>
                            <h3 class="step__title">Plan</h3>
                        </div>
                        <div class="step__content">
                            <p>A Jira board with epics, user stories and tasks, run in two sprints.</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step__header">
                            <span class="step__num">2</span>
                            <h3 class="step__title">Design</h3>
                        </div>
                        <div class="step__content">
                            <p>Page mockups and layouts in Canva, plus mobile layouts.</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step__header">
                            <span class="step__num">3</span>
                            <h3 class="step__title">Build</h3>
                        </div>
                        <div class="step__content">
                            <p>Hand-coded HTML5 and CSS3 in Part 1, then PHP and MySQL in Part 2, with every member building at least one page.</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step__header">
                            <span class="step__num">4</span>
                            <h3 class="step__title">Test & Publish</h3>
                        </div>
                        <div class="step__content">
                            <p>Validation, security, responsive and accessibility checks before submission.</p>
                        </div>
                    </div>
                </div>

                <h3 class="tools-title">Tools We Use</h3>
                <p class="tools-subtitle">Everything our team uses to plan, design, build and publish.</p>
                <div class="tools-grid">
                    <div class="tool-card">
                        <h4>1. Jira</h4>
                        <p>Plan sprints, track tasks and assign owners.</p>
                    </div>
                    <div class="tool-card">
                        <h4>2. GitHub</h4>
                        <p>Version control and team collaboration.</p>
                    </div>
                    <div class="tool-card">
                        <h4>3. Canva</h4>
                        <p>Page mockups and layouts.</p>
                    </div>
                    <div class="tool-card">
                        <h4>4. HTML5 & CSS3</h4>
                        <p>Semantic, accessible page structure. Responsive, consistent styling.</p>
                    </div>
                    <div class="tool-card">
                        <h4>5. PHP & MySQL</h4>
                        <p>Server-side logic, validation and database-driven content.</p>
                    </div>
                </div>
            </section>

            <!-- section 4: group photo -->
            <section class="about-section" id="group-photo">
                <h2 class="about-section__title">4. Group Photo</h2>
                <p class="about-section__subtitle">The team behind the site.</p>
                <figure class="group-photo-wrapper">
                    <!-- note: remember to update group-photo.jpg with our real photo -->
                    <img src="images/About/group-photo.jpg" alt="Group photo of Team HCPS members"
                        class="group-photo-img">
                    <figcaption>Figure 1: Team HCPS - Swinburne HCMC Campus</figcaption>
                </figure>
            </section>

            <!-- section 5: fun facts -->
            <section class="about-section" id="fun-facts">
                <h2 class="about-section__title">5. Fun Facts</h2>
                <div class="table-responsive">
                    <table class="fun-facts-table">
                        <caption>Table 1: Fun facts about Team HCPS</caption>
                        <thead>
                            <tr>
                                <th scope="col">Member</th>
                                <th scope="col">Fun fact</th>
                                <th scope="col">Favourite thing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Đỗ Vũ Khánh Quỳnh (KQ)</th>
                                <td>Enjoys writing online fanfiction web novels in spare time</td>
                                <td>Reading novels & Listening to music</td>
                            </tr>
                            <tr>
                                <th scope="row">Dương Nguyên Khôi (NK)</th>
                                <td>Keen to be put in a coma just to sleep for a few days straight</td>
                                <td>Watching comedy</td>
                            </tr>
                            <tr>
                                <th scope="row">Nguyễn Nhật Thanh (NT)</th>
                                <td>Like to explore the space and new things</td>
                                <td>Watching films & Listening to music</td>
                            </tr>
                            <tr>
                                <th scope="row">Trần Quang Đại (QĐ)</th>
                                <td>President of the Swinburne Basketball Club</td>
                                <td>Playing basketball, listening to R&B/Hip-hop, & playing Black Myth: Wukong</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- section 6: acknowledgement -->
            <section class="about-section" id="acknowledgement">
                <div class="ack-layout">
                    <!-- left column: text -->
                    <div class="ack-text">
                        <h2 class="about-section__title">Acknowledgement of Country</h2>
                        <div class="ack-content">
                            <p>We respectfully acknowledge the Wurundjeri People of the Kulin Nation, who are the
                                Traditional
                                Owners of the land on which Swinburne’s Australian campuses are located in Melbourne’s
                                east and
                                outer-east,
                                and pay our respect to their Elders past, present and emerging.</p>
                            <p>We are honoured to recognise our connection to Wurundjeri Country, history, culture and
                                spirituality through these locations, and strive to ensure that we operate in a manner
                                that respects
                                and honours the
                                Elders and Ancestors of these lands.</p>
                            <p>We also respectfully acknowledge Swinburne’s Aboriginal and Torres Strait Islander staff,
                                students, alumni, partners and visitors. We also acknowledge and respect the Traditional
                                Owners of
                                lands across
                                Australia, their Elders, Ancestors, cultures and heritage, and recognise the continuing
                                sovereignties of
                                all Aboriginal and Torres Strait Islander Nations.</p>
                        </div>
                    </div>

                    <!-- right column: logo -->
                    <div class="ack-visual">
                        <!-- note: add swinburne logo png to the images folder -->
                        <img src="images/About/swinburne-coverbook.jpg"
                            alt="Swinburne University of Technology Cover Book" class="ack-logo-img">
                    </div>
                </div>
            </section>
        </div>
    </main>

<?php include "footer.inc"; ?>
