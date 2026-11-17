<?php
$pageTitle = 'VinFast Jobs page';
$pageDescription = 'Explore open positions and build the future of digital retail with VinFast Digital Retail.';
$pageAuthor = 'Duong Nguyen Khoi';
$bodyClass = 'page-jobs';
$currentPage = 'jobs';

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
          <p class="hero__text">Four roles across web development, merchandising, data and customer experience. Ho Chi Minh City / Remote.</p>
          <p class="hero__deadline">Applications close&nbsp;<time datetime="2026-10-09">Friday, 09 October 2026</time></p>
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
        <p>These four roles keep VinFast's online retail platform running: web interfaces, product and catalogue data, sales analytics and customer communication. All roles are hybrid and based in Ho Chi Minh City. Quote the reference number (VF001 to VF004) on the application form.</p>
      </section>

      <nav id="role-links" class="panel" aria-label="Open a role">
        <p class="panel__label">Open a role: select a reference to read its full details</p>
        <ul class="pill-list">
          <li><a class="pill" href="#vf001">VF001: Software &amp; Web Development</a></li>
          <li><a class="pill" href="#vf002">VF002: Product &amp; Retail Merchandising</a></li>
          <li><a class="pill" href="#vf003">VF003: Data &amp; Analytics</a></li>
          <li><a class="pill" href="#vf004">VF004: Customer Service &amp; Operations</a></li>
        </ul>
      </nav>

      <section class="panel how-to" aria-labelledby="how-to-apply-heading">
        <h2 id="how-to-apply-heading">How to apply</h2>
        <div class="how-to__body">
          <ol>
            <li><span>Choose a role and note its reference number.</span></li>
            <li><span>Complete the form on the Apply page.</span></li>
            <li><span>Submit before <time datetime="2026-10-09">Friday, 09 October 2026</time>.</span></li>
          </ol>
          <a class="btn btn--primary how-to__cta" href="apply.php">Go to the application form &rarr;</a>
        </div>
      </section>

    <!-- ===== 5. ROLE PANELS (VF001 to VF004) ===== -->

    <!-- Role Panel VF001 -->
    <section class="role-modal" id="vf001" aria-labelledby="vf001-title">
      <a class="role-modal__backdrop" href="#role-links" tabindex="-1" aria-hidden="true"></a>
      <div class="role-modal__sheet">
        <nav class="role-tabs" aria-label="Switch role">
          <ul>
            <li><a href="#vf001" aria-current="true">VF001</a></li>
            <li><a href="#vf002">VF002</a></li>
            <li><a href="#vf003">VF003</a></li>
            <li><a href="#vf004">VF004</a></li>
          </ul>
          <a class="role-modal__close" href="#role-links">Close</a>
        </nav>

        <header class="role-modal__head">
          <h2>Software &amp; Web Development</h2>
          <p class="dept-text">Builds the web interfaces customers use to configure and order vehicles.</p>
          <div class="role-modal__title-row">
            <h3 id="vf001-title">E-Commerce UX/UI Front-End Developer</h3>
            <span class="ref-chip">VF001</span>
            <a class="btn btn--primary" href="apply.php" aria-label="Apply for VF001: E-Commerce UX/UI Front-End Developer">Apply for VF001</a>
          </div>
        </header>

        <dl class="role-meta">
          <div>
            <dt>Team</dt>
            <dd>Digital Retail UI Team</dd>
          </div>
          <div>
            <dt>Reports to</dt>
            <dd>Lead Digital Retail Architect</dd>
          </div>
          <div>
            <dt>Work mode</dt>
            <dd>Hybrid (Ho Chi Minh City / Remote)</dd>
          </div>
          <div>
            <dt>Type and hours</dt>
            <dd>Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)</dd>
          </div>
          <div>
            <dt>Salary</dt>
            <dd>30,000,000 - 45,000,000 VND per month (gross, negotiable based on experience)</dd>
          </div>
        </dl>

        <div class="role-grid">
          <section class="block block--wide" aria-labelledby="vf001-about">
            <h4 id="vf001-about">About the role</h4>
            <p class="block__context">What this role does and why it exists.</p>
            <p>Design and develop responsive web interfaces for the digital retail platform. Combine visual prototyping with front-end engineering to optimise the vehicle purchasing journey.</p>
          </section>

          <section class="block block--wide" aria-labelledby="vf001-resp">
            <h4 id="vf001-resp">Key responsibilities</h4>
            <p class="block__context">What you will do, listed in priority order.</p>
            <ol>
              <li>Create wireframes and interactive prototypes in Figma for vehicle configurations.</li>
              <li>Build responsive web interfaces for vehicle configuration and online ordering.</li>
              <li>Maintain web accessibility standards (WCAG) across all customer-facing retail pages.</li>
              <li>Implement loading states and performance budgets to optimise page speed.</li>
              <li>Integrate interfaces with order, payment and inventory APIs, minimising third-party dependencies.</li>
            </ol>
          </section>

          <section class="block" aria-labelledby="vf001-ess">
            <h4 id="vf001-ess">Essential requirements</h4>
            <p class="block__context">What you must have to be considered.</p>
            <ul>
              <li>Bachelor's degree in Computer Science, Software Engineering, Design, Human-Computer Interaction, or a related field.</li>
              <li>Minimum 3 years of commercial experience in front-end development or UX/UI design.</li>
              <li>Expert-level proficiency in Figma.</li>
              <li>Strong proficiency in semantic HTML5, CSS3, and core JavaScript.</li>
              <li>Practical knowledge of web accessibility standards and ARIA practices.</li>
            </ul>
          </section>

          <section class="block" aria-labelledby="vf001-pref">
            <h4 id="vf001-pref">Preferable criteria</h4>
            <p class="block__context">A bonus, but not required.</p>
            <ul>
              <li>Professional working proficiency in English (IELTS 6.0 equivalent or higher).</li>
              <li>Experience with modern front-end frameworks (such as React or Vue.js).</li>
              <li>Familiarity with secure data handling practices for online checkout systems.</li>
              <li>Previous experience in e-commerce or automotive digital retail platforms.</li>
            </ul>
          </section>
        </div>

        <details class="see-more">
          <summary>See more</summary>
          <div class="role-grid">
            <section class="block block--wide" aria-labelledby="vf001-goals">
              <h4 id="vf001-goals">Performance goals</h4>
              <p class="block__context">What success looks like in this role.</p>
              <ul class="goal-list">
                <li class="goal">
                  <p class="goal__label">60-day goal</p>
                  <p>Deliver reusable checkout and configurator components with zero critical errors in an accessibility audit.</p>
                </li>
                <li class="goal">
                  <p class="goal__label">90-day goal</p>
                  <p>Raise vehicle-configurator completion rate by 15%.</p>
                </li>
              </ul>
            </section>

            <section class="block" aria-labelledby="vf001-benefits">
              <h4 id="vf001-benefits">Benefits</h4>
              <p class="block__context">What comes with the job.</p>
              <p>Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.</p>
            </section>

            <section class="block" aria-labelledby="vf001-eeo">
              <h4 id="vf001-eeo">Equal opportunity</h4>
              <p class="block__context">Our commitment to every applicant.</p>
              <p>VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.</p>
            </section>
          </div>
        </details>

        <footer class="role-modal__footer">
          <div>
            <strong>Applications close <time datetime="2026-10-09">Friday, 09 October 2026</time></strong>
            <p>Use reference VF001 on the application form.</p>
          </div>
          <a class="btn" href="apply.php" aria-label="Apply for VF001: E-Commerce UX/UI Front-End Developer">Apply for VF001</a>
        </footer>
      </div>
    </section>

    <!-- Role Panel VF002 -->
    <section class="role-modal" id="vf002" aria-labelledby="vf002-title">
      <a class="role-modal__backdrop" href="#role-links" tabindex="-1" aria-hidden="true"></a>
      <div class="role-modal__sheet">
        <nav class="role-tabs" aria-label="Switch role">
          <ul>
            <li><a href="#vf001">VF001</a></li>
            <li><a href="#vf002" aria-current="true">VF002</a></li>
            <li><a href="#vf003">VF003</a></li>
            <li><a href="#vf004">VF004</a></li>
          </ul>
          <a class="role-modal__close" href="#role-links">Close</a>
        </nav>

        <header class="role-modal__head">
          <h2>Product &amp; Retail Merchandising</h2>
          <p class="dept-text">Keeps product information accurate and storefront layouts effective.</p>
          <div class="role-modal__title-row">
            <h3 id="vf002-title">Digital Merchandising &amp; Catalogue Specialist</h3>
            <span class="ref-chip">VF002</span>
            <a class="btn btn--primary" href="apply.php" aria-label="Apply for VF002: Digital Merchandising &amp; Catalogue Specialist">Apply for VF002</a>
          </div>
        </header>

        <dl class="role-meta">
          <div>
            <dt>Team</dt>
            <dd>E-Commerce Operations Team</dd>
          </div>
          <div>
            <dt>Reports to</dt>
            <dd>Head of Digital Merchandising</dd>
          </div>
          <div>
            <dt>Work mode</dt>
            <dd>Hybrid (Ho Chi Minh City / Remote)</dd>
          </div>
          <div>
            <dt>Type and hours</dt>
            <dd>Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)</dd>
          </div>
          <div>
            <dt>Salary</dt>
            <dd>15,000,000 - 25,000,000 VND per month (gross)</dd>
          </div>
        </dl>

        <div class="role-grid">
          <section class="block block--wide" aria-labelledby="vf002-about">
            <h4 id="vf002-about">About the role</h4>
            <p class="block__context">What this role does and why it exists.</p>
            <p>Manage digital product displays and catalogue data across VinFast's online retail platform. Ensure vehicle listings and accessories for models like the VF 2 are accurate and positioned to maximise online sales.</p>
          </section>

          <section class="block block--wide" aria-labelledby="vf002-resp">
            <h4 id="vf002-resp">Key responsibilities</h4>
            <p class="block__context">What you will do, listed in priority order.</p>
            <ol>
              <li>Maintain accurate pricing, specifications, and imagery for digital vehicle and accessory listings.</li>
              <li>Structure catalogue data in the CMS and keep product tags and compatibility attributes correct.</li>
              <li>Conduct weekly audits of product detail pages to correct data discrepancies.</li>
              <li>Coordinate storefront layouts and promotional campaigns for accessories and upcoming launches.</li>
              <li>Run A/B tests on product detail pages to identify effective merchandising layouts.</li>
            </ol>
          </section>

          <section class="block" aria-labelledby="vf002-ess">
            <h4 id="vf002-ess">Essential requirements</h4>
            <p class="block__context">What you must have to be considered.</p>
            <ul>
              <li>Bachelor's degree in Business, Marketing, E-Commerce, or a related field.</li>
              <li>Minimum 2 years of experience in e-commerce catalogue management or digital merchandising.</li>
              <li>Proficiency in Content Management Systems and Product Information Management tools.</li>
              <li>Ability to interpret sales and engagement data.</li>
              <li>Working knowledge of A/B testing methodologies.</li>
            </ul>
          </section>

          <section class="block" aria-labelledby="vf002-pref">
            <h4 id="vf002-pref">Preferable criteria</h4>
            <p class="block__context">A bonus, but not required.</p>
            <ul>
              <li>Professional working proficiency in English (IELTS 6.0 equivalent or higher).</li>
              <li>Experience managing complex automotive product variants.</li>
              <li>Understanding of SEO best practices for product pages.</li>
            </ul>
          </section>
        </div>

        <details class="see-more">
          <summary>See more</summary>
          <div class="role-grid">
            <section class="block block--wide" aria-labelledby="vf002-goals">
              <h4 id="vf002-goals">Performance goals</h4>
              <p class="block__context">What success looks like in this role.</p>
              <ul class="goal-list">
                <li class="goal">
                  <p class="goal__label">30-day goal</p>
                  <p>Achieve at least 98% data accuracy in a catalogue audit of the VF MPV 7.</p>
                </li>
                <li class="goal">
                  <p class="goal__label">90-day goal</p>
                  <p>Raise accessory conversion rate by 12% through layout adjustments.</p>
                </li>
              </ul>
            </section>

            <section class="block" aria-labelledby="vf002-benefits">
              <h4 id="vf002-benefits">Benefits</h4>
              <p class="block__context">What comes with the job.</p>
              <p>Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.</p>
            </section>

            <section class="block" aria-labelledby="vf002-eeo">
              <h4 id="vf002-eeo">Equal opportunity</h4>
              <p class="block__context">Our commitment to every applicant.</p>
              <p>VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.</p>
            </section>
          </div>
        </details>

        <footer class="role-modal__footer">
          <div>
            <strong>Applications close <time datetime="2026-10-09">Friday, 09 October 2026</time></strong>
            <p>Use reference VF002 on the application form.</p>
          </div>
          <a class="btn" href="apply.php" aria-label="Apply for VF002: Digital Merchandising &amp; Catalogue Specialist">Apply for VF002</a>
        </footer>
      </div>
    </section>

    <!-- Role Panel VF003 -->
    <section class="role-modal" id="vf003" aria-labelledby="vf003-title">
      <a class="role-modal__backdrop" href="#role-links" tabindex="-1" aria-hidden="true"></a>
      <div class="role-modal__sheet">
        <nav class="role-tabs" aria-label="Switch role">
          <ul>
            <li><a href="#vf001">VF001</a></li>
            <li><a href="#vf002">VF002</a></li>
            <li><a href="#vf003" aria-current="true">VF003</a></li>
            <li><a href="#vf004">VF004</a></li>
          </ul>
          <a class="role-modal__close" href="#role-links">Close</a>
        </nav>

        <header class="role-modal__head">
          <h2>Data &amp; Analytics</h2>
          <p class="dept-text">Turns online sales and behaviour data into recommendations for the product teams.</p>
          <div class="role-modal__title-row">
            <h3 id="vf003-title">E-Commerce Data Analyst</h3>
            <span class="ref-chip">VF003</span>
            <a class="btn btn--primary" href="apply.php" aria-label="Apply for VF003: E-Commerce Data Analyst">Apply for VF003</a>
          </div>
        </header>

        <dl class="role-meta">
          <div>
            <dt>Team</dt>
            <dd>Digital Retail Analytics Team</dd>
          </div>
          <div>
            <dt>Reports to</dt>
            <dd>Head of Data Analytics</dd>
          </div>
          <div>
            <dt>Work mode</dt>
            <dd>Hybrid (Ho Chi Minh City / Remote)</dd>
          </div>
          <div>
            <dt>Type and hours</dt>
            <dd>Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)</dd>
          </div>
          <div>
            <dt>Salary</dt>
            <dd>20,000,000 - 35,000,000 VND per month (gross)</dd>
          </div>
        </dl>

        <div class="role-grid">
          <section class="block block--wide" aria-labelledby="vf003-about">
            <h4 id="vf003-about">About the role</h4>
            <p class="block__context">What this role does and why it exists.</p>
            <p>Analyse digital retail data to optimise the customer journey for VinFast's online retail platform. Provide actionable insights to improve sales performance for recent launches.</p>
          </section>

          <section class="block block--wide" aria-labelledby="vf003-resp">
            <h4 id="vf003-resp">Key responsibilities</h4>
            <p class="block__context">What you will do, listed in priority order.</p>
            <ol>
              <li>Track and report on e-commerce metrics including conversion rates and customer drop-offs.</li>
              <li>Analyse user behaviour during digital pre-ordering workflows for upcoming launches.</li>
              <li>Build dashboards in Power BI or Tableau to visualise daily online retail performance.</li>
              <li>Identify traffic bottlenecks to help front-end teams optimise page load speed.</li>
              <li>Provide data-driven recommendations to improve the digital checkout experience.</li>
            </ol>
          </section>

          <section class="block" aria-labelledby="vf003-ess">
            <h4 id="vf003-ess">Essential requirements</h4>
            <p class="block__context">What you must have to be considered.</p>
            <ul>
              <li>Bachelor's degree in Data Science, Statistics, Computer Science, or a related field.</li>
              <li>Minimum 2 years of experience in e-commerce data analysis.</li>
              <li>Proficiency in SQL and data visualisation tools.</li>
              <li>Strong analytical skills to interpret complex web traffic data.</li>
            </ul>
          </section>

          <section class="block" aria-labelledby="vf003-pref">
            <h4 id="vf003-pref">Preferable criteria</h4>
            <p class="block__context">A bonus, but not required.</p>
            <ul>
              <li>Professional working proficiency in English (IELTS 6.0 equivalent or higher).</li>
              <li>Experience with Python or R for advanced data modelling.</li>
              <li>Background in automotive retail analytics.</li>
            </ul>
          </section>
        </div>

        <details class="see-more">
          <summary>See more</summary>
          <div class="role-grid">
            <section class="block block--wide" aria-labelledby="vf003-goals">
              <h4 id="vf003-goals">Performance goals</h4>
              <p class="block__context">What success looks like in this role.</p>
              <ul class="goal-list">
                <li class="goal">
                  <p class="goal__label">30-day goal</p>
                  <p>Build one tracking dashboard for the VF MPV 7 digital sales funnel.</p>
                </li>
                <li class="goal">
                  <p class="goal__label">90-day goal</p>
                  <p>Deliver evidence-backed recommendations for two major checkout drop-off points, adopted by the product teams within 90 days.</p>
                </li>
              </ul>
            </section>

            <section class="block" aria-labelledby="vf003-benefits">
              <h4 id="vf003-benefits">Benefits</h4>
              <p class="block__context">What comes with the job.</p>
              <p>Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.</p>
            </section>

            <section class="block" aria-labelledby="vf003-eeo">
              <h4 id="vf003-eeo">Equal opportunity</h4>
              <p class="block__context">Our commitment to every applicant.</p>
              <p>VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.</p>
            </section>
          </div>
        </details>

        <footer class="role-modal__footer">
          <div>
            <strong>Applications close <time datetime="2026-10-09">Friday, 09 October 2026</time></strong>
            <p>Use reference VF003 on the application form.</p>
          </div>
          <a class="btn" href="apply.php" aria-label="Apply for VF003: E-Commerce Data Analyst">Apply for VF003</a>
        </footer>
      </div>
    </section>

    <!-- Role Panel VF004 -->
    <section class="role-modal" id="vf004" aria-labelledby="vf004-title">
      <a class="role-modal__backdrop" href="#role-links" tabindex="-1" aria-hidden="true"></a>
      <div class="role-modal__sheet">
        <nav class="role-tabs" aria-label="Switch role">
          <ul>
            <li><a href="#vf001">VF001</a></li>
            <li><a href="#vf002">VF002</a></li>
            <li><a href="#vf003">VF003</a></li>
            <li><a href="#vf004" aria-current="true">VF004</a></li>
          </ul>
          <a class="role-modal__close" href="#role-links">Close</a>
        </nav>

        <header class="role-modal__head">
          <h2>Customer Service &amp; Operations</h2>
          <p class="dept-text">Manages how VinFast communicates with customers across the online purchase journey.</p>
          <div class="role-modal__title-row">
            <h3 id="vf004-title">Customer Experience CRM Manager</h3>
            <span class="ref-chip">VF004</span>
            <a class="btn btn--primary" href="apply.php" aria-label="Apply for VF004: Customer Experience CRM Manager">Apply for VF004</a>
          </div>
        </header>

        <dl class="role-meta">
          <div>
            <dt>Team</dt>
            <dd>Customer Experience Team</dd>
          </div>
          <div>
            <dt>Reports to</dt>
            <dd>Director of E-Commerce</dd>
          </div>
          <div>
            <dt>Work mode</dt>
            <dd>Hybrid (Ho Chi Minh City / Remote)</dd>
          </div>
          <div>
            <dt>Type and hours</dt>
            <dd>Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)</dd>
          </div>
          <div>
            <dt>Salary</dt>
            <dd>40,000,000 - 65,000,000 VND per month (gross)</dd>
          </div>
        </dl>

        <div class="role-grid">
          <section class="block block--wide" aria-labelledby="vf004-about">
            <h4 id="vf004-about">About the role</h4>
            <p class="block__context">What this role does and why it exists.</p>
            <p>Lead customer relationship management initiatives to enhance the digital purchasing journey for VinFast's online customers. Design and execute automated communication strategies to support the online retail platform.</p>
          </section>

          <section class="block block--wide" aria-labelledby="vf004-resp">
            <h4 id="vf004-resp">Key responsibilities</h4>
            <p class="block__context">What you will do, listed in priority order.</p>
            <ol>
              <li>Manage the CRM platform to track customer interactions across the digital retail ecosystem.</li>
              <li>Develop automated email and SMS workflows to nurture leads for upcoming launches.</li>
              <li>Segment customer data to deliver personalised post-purchase communications.</li>
              <li>Monitor customer satisfaction metrics to identify friction points in the e-commerce journey.</li>
              <li>Collaborate with the Data Analytics team to measure the ROI of retention campaigns.</li>
            </ol>
          </section>

          <section class="block" aria-labelledby="vf004-ess">
            <h4 id="vf004-ess">Essential requirements</h4>
            <p class="block__context">What you must have to be considered.</p>
            <ul>
              <li>Bachelor's degree in Marketing, Business, Communications, or a related field.</li>
              <li>Minimum 4 years of experience managing enterprise CRM systems (such as Salesforce or HubSpot).</li>
              <li>Proven track record in designing customer journey maps and automated marketing workflows.</li>
              <li>Strong analytical skills to interpret engagement data and customer feedback.</li>
            </ul>
          </section>

          <section class="block" aria-labelledby="vf004-pref">
            <h4 id="vf004-pref">Preferable criteria</h4>
            <p class="block__context">A bonus, but not required.</p>
            <ul>
              <li>Professional working proficiency in English (IELTS 6.0 equivalent or higher).</li>
              <li>Experience in the automotive sector or high-value e-commerce retail.</li>
              <li>Familiarity with data privacy regulations regarding customer communications.</li>
            </ul>
          </section>
        </div>

        <details class="see-more">
          <summary>See more</summary>
          <div class="role-grid">
            <section class="block block--wide" aria-labelledby="vf004-goals">
              <h4 id="vf004-goals">Performance goals</h4>
              <p class="block__context">What success looks like in this role.</p>
              <ul class="goal-list">
                <li class="goal">
                  <p class="goal__label">60-day goal</p>
                  <p>Deploy an automated post-purchase email sequence for the VF MPV 7 with an open rate of at least 40%.</p>
                </li>
                <li class="goal">
                  <p class="goal__label">90-day goal</p>
                  <p>Raise repeat-purchase rate by 8% through targeted re-engagement campaigns.</p>
                </li>
              </ul>
            </section>

            <section class="block" aria-labelledby="vf004-benefits">
              <h4 id="vf004-benefits">Benefits</h4>
              <p class="block__context">What comes with the job.</p>
              <p>Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.</p>
            </section>

            <section class="block" aria-labelledby="vf004-eeo">
              <h4 id="vf004-eeo">Equal opportunity</h4>
              <p class="block__context">Our commitment to every applicant.</p>
              <p>VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.</p>
            </section>
          </div>
        </details>

        <footer class="role-modal__footer">
          <div>
            <strong>Applications close <time datetime="2026-10-09">Friday, 09 October 2026</time></strong>
            <p>Use reference VF004 on the application form.</p>
          </div>
          <a class="btn" href="apply.php" aria-label="Apply for VF004: Customer Experience CRM Manager">Apply for VF004</a>
        </footer>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/footer.inc'; ?>
