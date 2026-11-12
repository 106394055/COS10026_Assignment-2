<?php
$pageTitle = "VinFast Apply page";
$pageDescription = "Apply for a position at VinFast, a global leader in automotive innovation. Fill out the application form to join our team and drive your career forward.";
$pageAuthor = "Do Vu Khanh Quynh";
$wrapperClass = "apply-page";
$currentPage = "apply";
$extraHead = '<style>
        /* Embedded CSS example for upload section */
        .form-section--upload {
            border-top: 3px solid #0b5fff;
        }
    </style>';

include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Main Content Area -->
<main id="main">
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container hero-content">
            <h1 class="hero-title">Join VinFast<br>Drive Your <span class="highlight-blue">Career</span> Forward
            </h1>
            <p>Be part of a global movement towards a greener and smarter future.<br>Submit your application and
                let's shape what's next, together.</p>
        </div>
    </section>

    <!-- Application Section Container -->
    <div class="container main-container">

        <!-- Stepper Progress Bar -->
        <div class="stepper-bar">
            <div class="step step--active">
                <span class="step__num">1</span>
                <span class="step__label">Personal Details</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step__num">2</span>
                <span class="step__label">Contact Details</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step__num">3</span>
                <span class="step__label">Skills & Experience</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step__num">4</span>
                <span class="step__label">Upload & Submit</span>
            </div>
        </div>

        <!-- Content Grid: Form + Sidebar -->
        <div class="content-grid">

            <!-- Left Column: Form -->
            <div class="form-column">
                <form id="applyForm" method="post" enctype="multipart/form-data"
                    action="process_eoi.php" novalidate>

                    <!-- Section 1: Personal Details -->
                    <fieldset class="form-section">
                        <legend class="section-header">
                            <span class="section-header__num">1</span>
                            <span class="section-header__title">Position and Personal Details</span>
                            <span class="section-header__desc" style="max-width: 32rem;">
                                Let us know who you are and the position you're
                                applying for.
                            </span>
                        </legend>

                        <!-- Row 1: Job Ref, First Name, Last Name (each taking up 1/3) -->
                        <div class="form-row">
                            <div class="form-group col-13">
                                <label for="jobRef">Job Reference Number <span class="required">*</span></label>
                                <select id="jobRef" name="jobRef" required>
                                    <option value="" disabled selected>Select a job position</option>
                                    <option value="VF001">VF001 - Software & Web Development</option>
                                    <option value="VF002">VF002 - Product & Retail Merchandising</option>
                                    <option value="VF003">VF003 - Data & Analytics</option>
                                    <option value="VF004">VF004 - Customer Service & Operations</option>
                                </select>
                                <span class="field-hint">Select reference number (VF001-VF004)</span>
                            </div>

                            <div class="form-group col-13">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="firstName" placeholder="e.g. Nguyen"
                                    pattern="[A-Za-z\s]{1,20}" maxlength="20" required>
                                <span class="field-hint">Maximum 20 letters (A-Z)</span>
                            </div>

                            <div class="form-group col-13">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastName" name="lastName" placeholder="e.g. An"
                                    pattern="[A-Za-z\s'-]{1,20}" maxlength="20" required>
                                <span class="field-hint">Maximum 20 letters (A-Z)</span>
                            </div>
                        </div>

                        <!-- Row 2: Date of Birth (1/3) and Gender (2/3) -->
                        <div class="form-row">
                            <div class="form-group col-13">
                                <label for="dob">Date of Birth <span class="required">*</span></label>
                                <input type="text" id="dob" name="dob" placeholder="dd/mm/yyyy"
                                    pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/(19|20)\d{2}" required>
                                <span class="field-hint">Format: dd/mm/yyyy</span>
                            </div>

                            <div class="form-group col-23">
                                <fieldset class="gender-fieldset">
                                    <legend class="gender-legend">Gender <span class="required">*</span>
                                    </legend>
                                    <div class="radio-group">
                                        <label class="radio-item" for="gender-female">
                                            <input type="radio" id="gender-female" name="gender" value="female"
                                                required> Female
                                        </label>
                                        <label class="radio-item" for="gender-male">
                                            <input type="radio" id="gender-male" name="gender" value="male">
                                            Male
                                        </label>
                                        <label class="radio-item" for="gender-nonbinary">
                                            <input type="radio" id="gender-nonbinary" name="gender"
                                                value="non-binary"> Non-binary
                                        </label>
                                        <label class="radio-item" for="gender-notstated">
                                            <input type="radio" id="gender-notstated" name="gender"
                                                value="prefer-not-to-say"> Prefer not to
                                            say
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        </