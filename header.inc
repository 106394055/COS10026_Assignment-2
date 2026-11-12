<!DOCTYPE html>
<html lang="en" <?php echo isset($htmlClass) ? ' class="' . $htmlClass . '"' : ''; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'VinFast Careers'; ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Explore careers and opportunities at VinFast.'; ?>">
    <?php if (isset($pageAuthor)): ?>
        <meta name="author" content="<?php echo $pageAuthor; ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="images/Home/Vinfast_logo.png?v=2" type="image/png">
    <?php if (isset($extraHead)) echo $extraHead; ?>
</head>

<body<?php echo isset($bodyClass) ? ' class="' . $bodyClass . '"' : ''; ?>>
    <?php if (isset($wrapperClass)): ?>
        <div class="<?php echo $wrapperClass; ?>">
        <?php endif; ?>

        <!-- Skip link for Accessibility -->
        <a class="skip-link" href="#main">Skip to main content</a>

        <!-- Header -->
        <header class="site-header">
            <div class="container site-header__inner">
                <a class="site-brand" href="index.php">
                    <img src="images/Home/Vinfast_logo.png" alt="VinFast Logo" width="83" height="66">
                    <span class="site-brand__wordmark">VINFAST</span>
                </a>