<?php
  $pageTitle = "VinFast Home page";
  $pageDesc = "Explore careers and opportunities at VinFast.";
  $pageAuthor = "Nguyen Nhat Thanh";
  $bodyClass = "page-home";
  $currentPage = "home";
  
  include 'header.inc';
  include 'nav.inc';
?>

  <main id="main-content">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">Join VinFast</h1>
        <h1 class="hero-subtitle">Drive Your Career Forward</h1>
      </div>
      <img src="images/Home/VinFast-background.png" alt="VinFast Background" class="hero-background">
    </section>

    <!-- Info / Stats Section -->
    <section class="stats-section">
      <h2 class="stats-title">Drive the Future with Us</h2>

      <div class="stats-grid">
        <!-- Card 1 -->
        <div class="stat-card">
          <img src="images/Home/audience.png" alt="Audience Icon" class="stat-icon">
          <h3 class="stat-number">#57</h3>
          <p class="stat-description">
            <a href="https://time.com/7377459/asia-pacific-best-companies-2026/">TIME's Asia-Pacific’s best companies of 2026
            </a>
          </p>
        </div>

        <!-- Card 2 -->
        <div class="stat-card">
          <img src="images/Home/world.png" alt="World Icon" class="stat-icon">
          <h3 class="stat-number">#3 ASEAN</h3>
          <p class="stat-description">
            <a href="https://www.pwc.com/my/en/assets/publications/2026/pwc-my-asean-6-automotive-6th-market-snapshot.pdf">Present in 16 countries</a>
          </p>
        </div>

        <!-- Card 3 -->
        <div class="stat-card">
          <img src="images/Home/sales.png" alt="Electric Car Icon" class="stat-icon">
          <h3 class="stat-number">Dozens</h3>
          <p class="stat-description">
            <a href="https://vinfastauto.us/investor-relations/news/vinfast-honored-for-investor-relations-excellence-at-the-asian-excellence">International awards and titles in 2025-2026</a>
          </p>
        </div>
      </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
      <div class="search-box">
        <select class="search-input" aria-label="Select a job reference code">
          <option value="">Select Job Code...</option>
          <option value="#VF001">VF001</option>
          <option value="#VF002">VF002</option>
          <option value="#VF003">VF003</option>
          <option value="#VF004">VF004</option>
        </select>

        <button class="search-button">Search</button>
      </div>
    </section>

    <!-- Jobs Section -->
    <section class="jobs-section">
      <!-- JOB 1 TABLE -->
      <table class="job-table" id="VF001">
        <caption>(VF001) Job Details for E-Commerce UX/UI Front-End Developer</caption>

        <tbody>
          <tr class="job-header-row">
            <td colspan="3">
              <div class="job-header-content">
                <div class="job-title-group">
                  <span class="badge">VF001</span>
                  <span class="job-title">E-Commerce UX/UI Front-End Developer</span>
                </div>
                <a href="job.php" class="btn-apply">EXPLORE NOW</a>
              </div>
            </td>
          </tr>
          <tr class="job-details-row">
            <td class="col-location">
              <div class="info-box">
                <img class="job-icon" src="images/Home/location.png" alt="Location Icon">
                <div class="info">Hybrid</div>
                <div class="info-subtext">Ho Chi Minh City / Remote</div>
              </div>
            </td>
            <td class="col-salary">
              <div class="info-box">
                <img class="job-icon" src="images/Home/money-bag.png" alt="Money Bag Icon">
                <div class="info">30 000 000 - 40 000 000 VND per month</div>
              </div>
            </td>
            <td class="col-tasks">
              <div class="info-box">
                <img class="job-icon" src="images/Home/briefcase.png" alt="Time Icon">
                <div class="info"> Full-Time</div>
                <div class="info-subtext"> (10:00 AM - 4:00 PM ICT)</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- JOB 2 TABLE -->
      <table class="job-table" id="VF002">
        <caption>(VF002) Job Details for Digital Merchandising &amp; Catalogue Specialist </caption>

        <tbody>
          <tr class="job-header-row">
            <td colspan="3">
              <div class="job-header-content">
                <div class="job-title-group">
                  <span class="badge">VF002</span>
                  <span class="job-title">Digital Merchandising &amp; Catalogue Specialist</span>
                </div>
                <a href="job.php" class="btn-apply">EXPLORE NOW</a>
              </div>
            </td>
          </tr>
          <tr class="job-details-row">
            <td class="col-location">
              <div class="info-box">
                <img class="job-icon" src="images/Home/location.png" alt="Location Icon">
                <div class="info">Hybrid</div>
                <div class="info-subtext">Ho Chi Minh City / Remote</div>
              </div>
            </td>
            <td class="col-salary">
              <div class="info-box">
                <img class="job-icon" src="images/Home/money-bag.png" alt="Money Bag Icon">
                <div class="info">15 000 000 - 25 000 000 VND per month</div>
              </div>
            </td>
            <td class="col-tasks">
              <div class="info-box">
                <img class="job-icon" src="images/Home/briefcase.png" alt="Time Icon">
                <div class="info"> Full-Time</div>
                <div class="info-subtext"> (10:00 AM - 4:00 PM ICT)</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- JOB 3 TABLE -->
      <table class="job-table" id="VF003">
        <caption>(VF003) Job Details for E-Commerce Data Analyst</caption>

        <tbody>
          <tr class="job-header-row">
            <td colspan="3">
              <div class="job-header-content">
                <div class="job-title-group">
                  <span class="badge">VF003</span>
                  <span class="job-title">E-Commerce Data Analyst</span>
                </div>
                <a href="job.php" class="btn-apply">EXPLORE NOW</a>
              </div>
            </td>
          </tr>
          <tr class="job-details-row">
            <td class="col-location">
              <div class="info-box">
                <img class="job-icon" src="images/Home/location.png" alt="Location Icon">
                <div class="info">Hybrid</div>
                <div class="info-subtext">Ho Chi Minh City / Remote</div>
              </div>
            </td>
            <td class="col-salary">
              <div class="info-box">
                <img class="job-icon" src="images/Home/money-bag.png" alt="Money Bag Icon">
                <div class="info">20 000 000 - 35 000 000 VND per month</div>
              </div>
            </td>
            <td class="col-tasks">
              <div class="info-box">
                <img class="job-icon" src="images/Home/briefcase.png" alt="Time Icon">
                <div class="info"> Full-Time</div>
                <div class="info-subtext"> (10:00 AM - 4:00 PM ICT)</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- JOB 4 TABLE -->
      <table class="job-table" id="VF004">
        <caption>(VF004) Job Details for Customer Experience CRM Manager</caption>

        <tbody>
          <tr class="job-header-row">
            <td colspan="3">
              <div class="job-header-content">
                <div class="job-title-group">
                  <span class="badge">VF004</span>
                  <span class="job-title">Customer Experience CRM Manager</span>
                </div>
                <a href="job.php" class="btn-apply">EXPLORE NOW</a>
              </div>
            </td>
          </tr>
          <tr class="job-details-row">
            <td class="col-location">
              <div class="info-box">
                <img class="job-icon" src="images/Home/location.png" alt="Location Icon">
                <div class="info">Hybrid</div>
                <div class="info-subtext">Ho Chi Minh City / Remote</div>
              </div>
            </td>
            <td class="col-salary">
              <div class="info-box">
                <img class="job-icon" src="images/Home/money-bag.png" alt="Money Bag Icon">
                <div class="info">40 000 000 - 65 000 000 VND per month</div>
              </div>
            </td>
            <td class="col-tasks">
              <div class="info-box">
                <img class="job-icon" src="images/Home/briefcase.png" alt="Time Icon">
                <div class="info"> Full-Time</div>
                <div class="info-subtext"> (10:00 AM - 4:00 PM ICT)</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- Career Banner Section  -->
    <section class="banner">
      <!-- Background Image Element -->
      <div class="banner-bg">
        <img src="images/Home/banner.png" alt="VinFast Career Banner Background" class="banner-bg-center">
      </div>

      <!-- Text Overlay Content -->
      <div class="banner-content">
        <h1 class="banner-heading">Your Next Chapter <br> Starts Here</h1>
        <p class="banner-subtext">
          Join VinFast and turn your talent into real impact.
        </p>
        <a href="apply.php" class="banner-btn">
          <span>APPLY NOW</span>
        </a>
      </div>
    </section>

    <!-- Bot  -->
    <div class="site-bot">
      <details class="site-bot__Widget">
        <summary class="site-bot__trigger">
          <img src="images/Home/Vinfast_chatbot.png" alt="Chatbot Icon" width="48" height="48">
          <span class="site-bot__badge">Ask VF Bot</span>
        </summary>
        <div class="site-bot__panel">
          <p class="site-bot__title">VinFast Career Assistant</p>
          <p class="site-bot__text">Need help applying? Quote a 5-character reference (VF001–VF004) on the Apply page before <time datetime="2026-10-09">09 October 2026</time>.</p>
          <ul class="site-bot__links">
            <li><a href="job.php">Browse open roles</a></li>
            <li><a href="apply.php">Go to application form</a></li>
            <li><a href="mailto:info@vinfast-digital-retail.example">Email recruitment team</a></li>
          </ul>
        </div>
      </details>
    </div>
  </main>

<?php
  include 'footer.inc';
?>