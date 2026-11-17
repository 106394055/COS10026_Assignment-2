<nav class="site-nav" aria-label="Main Navigation">
    <ul>
        <li><a href="index.php" <?php echo (isset($currentPage) && $currentPage == 'home') ? 'aria-current="page"' : ''; ?>>Home</a></li>
        <li><a href="jobs.php" <?php echo (isset($currentPage) && $currentPage == 'jobs') ? 'aria-current="page"' : ''; ?>>Jobs</a></li>
        <li><a href="apply.php" <?php echo (isset($currentPage) && $currentPage == 'apply') ? 'aria-current="page"' : ''; ?>>Apply</a></li>
        <li><a href="about.php" <?php echo (isset($currentPage) && $currentPage == 'about') ? 'aria-current="page"' : ''; ?>>About</a></li>
        <li class="site-nav__admin-item"><a class="site-nav__admin" href="login.php" <?php echo (isset($currentPage) && $currentPage == 'login') ? 'aria-current="page"' : ''; ?>>Administrator</a></li>
    </ul>
</nav>
</div>
</header>