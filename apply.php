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

include 'header.inc';
include 'nav.inc';
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
                    </fieldset>

                    <!-- Section 2: Contact Details -->
                    <fieldset class="form-section">
                        <legend class="section-header">
                            <span class="section-header__num">2</span>
                            <span class="section-header__title">Contact Details</span>
                            <span class="section-header__desc">Provide your current contact information.</span>
                        </legend>

                        <!-- Row 1: Email Address (full width) -->
                        <div class="form-row">
                            <div class="form-group col-full">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" placeholder="e.g. example@email.com"
                                    required>
                            </div>
                        </div>

                        <!-- Row 2: Phone Number -->
                        <div class="form-row">
                            <div class="form-group col-full">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" pattern="[0-9]{8,12}" maxlength="12"
                                    inputmode="numeric" placeholder="e.g. 0412345678" required>
                                <span class="field-hint">8-12 digits</span>
                            </div>
                        </div>

                        <!-- Row 3: State and Postcode on the same line (each field occupies 1/2 of the width) -->
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="state">State <span class="required">*</span></label>
                                <select id="state" name="state" required>
                                    <option value="" disabled selected>Select a state</option>
                                    <option value="VIC">VIC - Victoria</option>
                                    <option value="NSW">NSW - New South Wales</option>
                                    <option value="QLD">QLD - Queensland</option>
                                    <option value="NT">NT - Northern Territory</option>
                                    <option value="WA">WA - Western Australia</option>
                                    <option value="SA">SA - South Australia</option>
                                    <option value="TAS">TAS - Tasmania</option>
                                    <option value="ACT">ACT - Australian Capital Territory</option>
                                </select>
                                <span class="field-hint">VIC, NSW, QLD, NT, WA, SA, TAS, ACT</span>
                            </div>

                            <div class="form-group col-12">
                                <label for="postcode">Postcode <span class="required">*</span></label>
                                <input type="text" id="postcode" name="postcode" placeholder="e.g. 3000"
                                    pattern="\d{4}" maxlength="4" required>
                                <span class="field-hint">Exactly 4 digits</span>
                            </div>
                        </div>

                        <!-- Row 4: Street Address 1 row alone -->
                        <div class="form-row">
                            <div class="form-group col-full">
                                <label for="streetAddress">Street Address <span
                                        class="required">*</span></label>
                                <input type="text" id="streetAddress" name="streetAddress"
                                    placeholder="e.g. 123 Nguyen Van Cu Street" maxlength="40" required>
                                <span class="field-hint">Maximum 40 characters</span>
                            </div>
                        </div>

                        <!-- Row 5: Suburb/Town 1 row alone -->
                        <div class="form-row">
                            <div class="form-group col-full">
                                <label for="suburb">Suburb/Town <span class="required">*</span></label>
                                <input type="text" id="suburb" name="suburb" placeholder="e.g. District 1"
                                    maxlength="40" required>
                                <span class="field-hint">Maximum 40 characters</span>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 3: Skills and Experience -->
                    <fieldset class="form-section">
                        <legend class="section-header">
                            <span class="section-header__num">3</span>
                            <span class="section-header__title">Skills and Experience</span>
                            <span class="section-header__desc">Select the skills that best match your
                                experience.</span>
                        </legend>

                        <div class="form-group col-full">
                            <label for="primarySkill">
                                Primary Skill <span class="required">*</span>
                            </label>
                            <select id="primarySkill" name="primarySkill" required>
                                <option value="" disabled selected>
                                    Select your strongest skill
                                </option>
                                <option value="HTML/CSS">HTML/CSS</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="Data Analysis">Data Analysis</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="Customer Service">Customer Service</option>
                                <option value="E-commerce">E-commerce</option>
                            </select>
                        </div>

                        <div class="form-group col-full">
                            <fieldset class="skills-fieldset">
                                <legend class="skills-legend">
                                    Additional Skills (Select all that apply)
                                </legend>
                                <div class="checkbox-grid">
                                    <label class="checkbox-item" for="skill-html">
                                        <input type="checkbox" id="skill-html" name="skills[]" value="HTML/CSS">
                                        HTML / CSS
                                    </label>
                                    <label class="checkbox-item" for="skill-uiux">
                                        <input type="checkbox" id="skill-uiux" name="skills[]"
                                            value="UI/UX Design">
                                        UI/UX Design
                                    </label>
                                    <label class="checkbox-item" for="skill-data">
                                        <input type="checkbox" id="skill-data" name="skills[]"
                                            value="Data Analysis"> Data
                                        Analysis
                                    </label>
                                    <label class="checkbox-item" for="skill-pm">
                                        <input type="checkbox" id="skill-pm" name="skills[]"
                                            value="Project Management">
                                        Project Management
                                    </label>
                                    <label class="checkbox-item" for="skill-marketing">
                                        <input type="checkbox" id="skill-marketing" name="skills[]"
                                            value="Digital Marketing">
                                        Digital Marketing
                                    </label>
                                    <label class="checkbox-item" for="skill-cs">
                                        <input type="checkbox" id="skill-cs" name="skills[]"
                                            value="Customer Service">
                                        Customer Service
                                    </label>
                                    <label class="checkbox-item" for="skill-ecommerce">
                                        <input type="checkbox" id="skill-ecommerce" name="skills[]"
                                            value="E-commerce">
                                        E-commerce
                                    </label>
                                    <label class="checkbox-item" for="skill-other">
                                        <input type="checkbox" id="skill-other" name="skills[]" value="Other">
                                        Other
                                        (please specify)
                                    </label>
                                </div>
                            </fieldset>
                        </div>

                        <div class="form-group col-full">
                            <label for="otherSkills">Other Skills (Optional)</label>
                            <textarea id="otherSkills" name="otherSkills" rows="3"
                                placeholder="e.g. Languages, software tools, certifications, etc."
                                maxlength="200"></textarea>
                            <span class="field-hint">Maximum 200 characters</span>
                        </div>
                    </fieldset>

                    <!-- Section 4: Document Upload -->
                    <fieldset class="form-section form-section--upload">
                        <legend class="section-header">
                            <span class="section-header__num">4</span>
                            <span class="section-header__title">
                                Upload Supporting Documents
                            </span>
                            <span class="section-header__desc">You can upload any relevant documents such as
                                your
                                CV, cover letter, portfolio or certificates.</span>
                        </legend>

                        <div class="form-group col-full">
                            <label for="documentUpload">
                                Select Document <span class="required">*</span>
                            </label>
                            <input type="file" id="documentUpload" name="documentUpload[]" accept=".pdf,.doc,.docx,.jpg,.png" multiple required>
                            <span class="field-hint">
                                Accepted file types: PDF, DOC, DOCX, JPG and PNG.
                            </span>
                        </div>
                    </fieldset>

                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="reset" class="btn btn--secondary">
                            <span class="btn__icon">🔄</span> Reset Form
                        </button>
                        <button type="submit" class="btn btn--primary">
                            <span class="btn__icon">✈️</span> Submit Application
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Column: Sidebar -->
            <aside class="sidebar-column">
                <div class="card card--tips">
                    <div class="card__header">
                        <span class="card__icon text-yellow">💡</span>
                        <h2>Application Tips</h2>
                    </div>
                    <ul>
                        <li>Make sure all required fields are completed.</li>
                        <li>Use a valid email address and phone number.</li>
                        <li>You can upload multiple files (e.g. CV, portfolio, cover letter, certificates).</li>
                        <li>Accepted file types: PDF, DOC, DOCX, JPG, PNG.</li>
                        <li>Maximum file size: 5 MB per file.</li>
                    </ul>
                </div>

                <div class="card card--security">
                    <div class="card__header">
                        <span class="card__icon text-green">🛡️</span>
                        <h2>Your Information is Secure</h2>
                    </div>
                    <p>
                        This website is an academic student project.
                        Application submissions are processed through the
                        Swinburne form testing service and are not actual
                        VinFast recruitment applications.
                        Please do not submit sensitive personal information.
                    </p>
                </div>

                <div class="card card--help">
                    <div class="card__header">
                        <span class="card__icon text-blue">🎧</span>
                        <h2>Need Help?</h2>
                    </div>
                    <p>If you have any questions about the application process, please contact our recruitment
                        team.
                    </p>
                    <a href="mailto:careers@vinfast.com" class="email-link">✉️ careers@vinfast.com</a>
                </div>

                <div class="card card--timeline">
                    <div class="card__header">
                        <span class="card__icon text-blue">📅</span>
                        <h2>Application Timeline</h2>
                    </div>

                    <ul class="timeline-list">
                        <li class="timeline-item">
                            <div class="timeline-badge bg-blue">📢</div>
                            <div class="timeline-content">
                                <span class="date">September 19, 2026</span>
                                <h3>Application opens</h3>
                                <p>Start submitting your application.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-badge bg-purple">📄</div>
                            <div class="timeline-content">
                                <span class="date">October 09, 2026</span>
                                <h3>Application deadline</h3>
                                <p>You can no longer apply after this day.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-badge bg-orange">⏳</div>
                            <div class="timeline-content">
                                <span class="date">October 20, 2026</span>
                                <h3>Application time</h3>
                                <p>Applications are currently under review by our HR team.</p>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item--highlight">
                            <div class="timeline-badge bg-teal">📝</div>
                            <div class="timeline-content">
                                <span class="date">November 01, 2026</span>
                                <h3>Shortlist announcement</h3>
                                <p>You will be notified whether you are selected for an interview.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-badge bg-purple">👥</div>
                            <div class="timeline-content">
                                <span class="date">November 29, 2026</span>
                                <h3>Interview</h3>
                                <p>Selected candidates will be invited to an interview.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-badge bg-yellow">🏆</div>
                            <div class="timeline-content">
                                <span class="date">December 05, 2026</span>
                                <h3>Final results announcement</h3>
                                <p>You will receive the final recruitment result.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>

        </div>
    </div>
</main>

<?php include 'footer.inc'; ?>