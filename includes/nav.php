<nav class="site-nav" aria-label="Main Navigation">
    <ul>
        <li><a href="index.php" <?php echo (isset($currentPage) && $currentPage == 'home') ? 'aria-current="page"' : ''; ?>>Home</a></li>
        <li><a href="jobs.php" <?php echo (isset($currentPage) && $currentPage == 'jobs') ? 'aria-current="page"' : ''; ?>>Jobs</a></li>
        <li><a href="apply.php" <?php echo (isset($currentPage) && $currentPage == 'apply') ? 'aria-current="page"' : ''; ?>>Apply</a></li>
        <li><a href="about.php" <?php echo (isset($currentPage) && $currentPage == 'about') ? 'aria-current="page"' : ''; ?>>About</a></li>
    </ul>
</nav>
</div>
</header>