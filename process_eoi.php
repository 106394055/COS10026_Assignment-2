<?php
// Block direct URL access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}

require_once("db_connect.php");

function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Retrieve and sanitise POST inputs
$jobRef        = isset($_POST["jobRef"]) ? sanitise_input($_POST["jobRef"]) : "";
$firstName     = isset($_POST["firstName"]) ? sanitise_input($_POST["firstName"]) : "";
$lastName      = isset($_POST["lastName"]) ? sanitise_input($_POST["lastName"]) : "";
$dob           = isset($_POST["dob"]) ? sanitise_input($_POST["dob"]) : "";
$gender        = isset($_POST["gender"]) ? sanitise_input($_POST["gender"]) : "";
$email         = isset($_POST["email"]) ? sanitise_input($_POST["email"]) : "";
$phone         = isset($_POST["phone"]) ? sanitise_input($_POST["phone"]) : "";
$state         = isset($_POST["state"]) ? sanitise_input($_POST["state"]) : "";
$postcode      = isset($_POST["postcode"]) ? sanitise_input($_POST["postcode"]) : "";
$streetAddress = isset($_POST["streetAddress"]) ? sanitise_input($_POST["streetAddress"]) : "";
$suburb        = isset($_POST["suburb"]) ? sanitise_input($_POST["suburb"]) : "";
$primarySkill  = isset($_POST["primarySkill"]) ? sanitise_input($_POST["primarySkill"]) : "";
$otherSkills   = isset($_POST["otherSkills"]) ? sanitise_input($_POST["otherSkills"]) : "";

$skillsRaw = isset($_POST["skills"]) && is_array($_POST["skills"]) ? $_POST["skills"] : [];
$skillsClean = [];
foreach ($skillsRaw as $skill) {
    $skillsClean[] = sanitise_input($skill);
}

// Server-side validation
$errors = [];

$validJobRefs = ["VF001", "VF002", "VF003", "VF004"];
if (empty($jobRef)) {
    $errors[] = "Job Reference Number is required.";
} elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $jobRef) || !in_array($jobRef, $validJobRefs)) {
    $errors[] = "Invalid Job Reference Number selected (must be 5 alphanumeric characters, e.g., VF001-VF004).";
}

if (empty($firstName)) {
    $errors[] = "First Name is required.";
} elseif (!preg_match("/^[A-Za-z\s]{1,20}$/", $firstName)) {
    $errors[] = "First Name must contain only letters and spaces, maximum 20 characters.";
}

if (empty($lastName)) {
    $errors[] = "Last Name is required.";
} elseif (!preg_match("/^[A-Za-z\s'-]{1,20}$/", $lastName)) {
    $errors[] = "Last Name must contain only letters, spaces, hyphens or apostrophes, maximum 20 characters.";
}

if (empty($dob)) {
    $errors[] = "Date of Birth is required.";
} elseif (!preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/", $dob)) {
    $errors[] = "Date of Birth must follow the dd/mm/yyyy format.";
} else {
    $dobParts = explode("/", $dob);
    $day   = (int)$dobParts[0];
    $month = (int)$dobParts[1];
    $year  = (int)$dobParts[2];
    if (!checkdate($month, $day, $year)) {
        $errors[] = "Date of Birth is not a valid calendar date.";
    } else {
        $birthDate = DateTime::createFromFormat("d/m/Y", $dob);
        $today     = new DateTime();
        $age       = $today->diff($birthDate)->y;
        if ($birthDate > $today || $age < 15 || $age > 80) {
            $errors[] = "Applicant age must be between 15 and 80 years old.";
        }
    }
}

$validGenders = ["female", "male", "non-binary", "prefer-not-to-say"];
if (empty($gender) || !in_array($gender, $validGenders)) {
    $errors[] = "Please select a valid Gender option.";
}

if (empty($email)) {
    $errors[] = "Email Address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid Email Address.";
}

$phoneDigits = str_replace(" ", "", $phone);
if (empty($phoneDigits)) {
    $errors[] = "Phone Number is required.";
} elseif (!preg_match("/^[0-9]{8,12}$/", $phoneDigits)) {
    $errors[] = "Phone Number must contain between 8 and 12 digits.";
}

$statePostcodeMap = [
    "VIC" => ["3", "8"],
    "NSW" => ["1", "2"],
    "QLD" => ["4", "9"],
    "NT"  => ["0"],
    "WA"  => ["6"],
    "SA"  => ["5"],
    "TAS" => ["7"],
    "ACT" => ["0", "2"]
];

if (empty($state) || !array_key_exists($state, $statePostcodeMap)) {
    $errors[] = "Please select a valid Australian State/Territory.";
}

if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (isset($statePostcodeMap[$state])) {
    $firstDigit = substr($postcode, 0, 1);
    if (!in_array($firstDigit, $statePostcodeMap[$state])) {
        $errors[] = "The postcode ($postcode) does not match the selected state ($state).";
    }
}

if (empty($streetAddress)) {
    $errors[] = "Street Address is required.";
} elseif (strlen($streetAddress) > 40) {
    $errors[] = "Street Address must not exceed 40 characters.";
}

if (empty($suburb)) {
    $errors[] = "Suburb/Town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb/Town must not exceed 40 characters.";
}

$validSkills = [
    "HTML/CSS",
    "UI/UX Design",
    "Data Analysis",
    "Project Management",
    "Digital Marketing",
    "Customer Service",
    "E-commerce"
];
if (empty($primarySkill) || !in_array($primarySkill, $validSkills)) {
    $errors[] = "Please select a valid Primary Skill.";
}

if (in_array("Other", $skillsClean) && empty($otherSkills)) {
    $errors[] = "You selected 'Other' under Additional Skills. Please specify your other skills.";
}
if (strlen($otherSkills) > 200) {
    $errors[] = "Other Skills description must not exceed 200 characters.";
}

// Validate uploaded files if provided
$uploadedFileNames = [];
if (isset($_FILES["documentUpload"]) && !empty($_FILES["documentUpload"]["name"][0])) {
    $allowedExtensions = ["pdf", "doc", "docx", "jpg", "png"];
    $maxFileSize = 5 * 1024 * 1024;
    $totalFiles = count($_FILES["documentUpload"]["name"]);

    for ($i = 0; $i < $totalFiles; $i++) {
        $fileError = $_FILES["documentUpload"]["error"][$i];
        if ($fileError === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading file: " . htmlspecialchars($_FILES["documentUpload"]["name"][$i]);
            continue;
        }

        $origName = basename($_FILES["documentUpload"]["name"][$i]);
        $fileSize = $_FILES["documentUpload"]["size"][$i];
        $fileExt  = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            $errors[] = "Invalid file type for '$origName'. Only PDF, DOC, DOCX, JPG, and PNG are allowed.";
        } elseif ($fileSize > $maxFileSize) {
            $errors[] = "File '$origName' exceeds the maximum size limit of 5 MB.";
        } else {
            $uploadedFileNames[] = [
                "tmp_name"  => $_FILES["documentUpload"]["tmp_name"][$i],
                "safe_name" => time() . "_" . preg_replace("/[^A-Za-z0-9._-]/", "_", $origName)
            ];
        }
    }
}

// Create table if not exists & insert record
$eoiNumber = null;
$dbError = "";

if (empty($errors)) {
    $savedFilesList = [];
    if (!empty($uploadedFileNames)) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        foreach ($uploadedFileNames as $fileItem) {
            if (move_uploaded_file($fileItem["tmp_name"], $uploadDir . $fileItem["safe_name"])) {
                $savedFilesList[] = $fileItem["safe_name"];
            }
        }
    }

    $additionalSkillsStr = !empty($skillsClean) ? implode(", ", $skillsClean) : "None";
    $otherSkillsStr      = !empty($otherSkills) ? $otherSkills : "None";
    $documentsStr        = !empty($savedFilesList) ? implode(", ", $savedFilesList) : "None";
    $defaultStatus       = "New";

    $createTableSql = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        job_ref VARCHAR(10) NOT NULL,
        first_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(20) NOT NULL,
        dob VARCHAR(15) NOT NULL,
        gender VARCHAR(25) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        state VARCHAR(10) NOT NULL,
        postcode VARCHAR(4) NOT NULL,
        street_address VARCHAR(40) NOT NULL,
        suburb VARCHAR(40) NOT NULL,
        primary_skill VARCHAR(50) NOT NULL,
        additional_skills VARCHAR(255),
        other_skills TEXT,
        documents VARCHAR(255),
        status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (!mysqli_query($conn, $createTableSql)) {
        $dbError = "Failed to initialize database table: " . mysqli_error($conn);
    } else {
        $insertSql = "INSERT INTO eoi (
            job_ref, first_name, last_name, dob, gender, email, phone,
            state, postcode, street_address, suburb, primary_skill,
            additional_skills, other_skills, documents, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $insertSql);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssssssssssss",
                $jobRef,
                $firstName,
                $lastName,
                $dob,
                $gender,
                $email,
                $phoneDigits,
                $state,
                $postcode,
                $streetAddress,
                $suburb,
                $primarySkill,
                $additionalSkillsStr,
                $otherSkillsStr,
                $documentsStr,
                $defaultStatus
            );

            if (mysqli_stmt_execute($stmt)) {
                $eoiNumber = mysqli_insert_id($conn);
            } else {
                $dbError = "Database insertion error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $dbError = "Database statement preparation failed: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);

// Page layout variables (same structure as apply.php)
$pageTitle = (empty($errors) && empty($dbError)) ? "Application Submitted - VinFast" : "Application Error - VinFast";
$pageDescription = "Expression of Interest submission status at VinFast.";
$pageAuthor = "Do Vu Khanh Quynh";
$wrapperClass = "apply-page";
$currentPage = "apply";

include 'header.inc';
include 'nav.inc';
?>

<!-- Main Content Area -->
<main id="main">
    <section class="hero-banner">
        <div class="container hero-content">
            <h1 class="hero-title">Application <span class="highlight-blue">Status</span></h1>
            <p>Expression of Interest (EOI) Processing Result</p>
        </div>
    </section>

    <div class="container main-container" style="max-width: 820px; margin: 2.5rem auto;">
        <?php if (!empty($errors)): ?>
            <div class="card" style="border-left: 5px solid #dc2626; padding: 2rem;">
                <h2 style="color: #dc2626; margin-bottom: 1rem;">Submission Failed — Please Fix the Following Errors</h2>
                <p style="margin-bottom: 1rem;">Your application could not be processed because some fields did not meet our validation requirements:</p>
                <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem; line-height: 1.7;">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="apply.php" class="btn btn--primary">← Back to Application Form</a>
            </div>

        <?php elseif (!empty($dbError)): ?>
            <div class="card" style="border-left: 5px solid #dc2626; padding: 2rem;">
                <h2 style="color: #dc2626; margin-bottom: 1rem;">System Error</h2>
                <p style="margin-bottom: 1.5rem;"><?php echo htmlspecialchars($dbError); ?></p>
                <a href="apply.php" class="btn btn--secondary">← Return to Form</a>
            </div>

        <?php else: ?>
            <div class="card" style="border-left: 5px solid #16a34a; padding: 2rem;">
                <h2 style="color: #16a34a; margin-bottom: 0.75rem;">Application Submitted Successfully!</h2>
                <p style="font-size: 1.1rem; margin-bottom: 1.25rem;">
                    Thank you, <strong><?php echo $firstName . " " . $lastName; ?></strong>. Your Expression of Interest has been recorded.
                </p>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <p style="margin: 0; font-size: 1.15rem;">
                        Your Application Reference (EOI Number): <strong style="color: #0b5fff; font-size: 1.35rem;">#<?php echo $eoiNumber; ?></strong>
                    </p>
                    <p style="margin: 0.35rem 0 0; font-size: 0.9rem; color: #4b5563;">
                        Please save this EOI number for future inquiries. Current status: <strong>New</strong>.
                    </p>
                </div>

                <h3 style="margin-bottom: 0.75rem;">Submitted Details Summary</h3>
                <ul style="margin-left: 1.25rem; margin-bottom: 1.75rem; line-height: 1.8;">
                    <li><strong>Job Reference:</strong> <?php echo $jobRef; ?></li>
                    <li><strong>Full Name:</strong> <?php echo $firstName . " " . $lastName; ?></li>
                    <li><strong>Date of Birth:</strong> <?php echo $dob; ?></li>
                    <li><strong>Gender:</strong> <?php echo ucfirst($gender); ?></li>
                    <li><strong>Email:</strong> <?php echo $email; ?></li>
                    <li><strong>Phone:</strong> <?php echo $phoneDigits; ?></li>
                    <li><strong>Address:</strong> <?php echo $streetAddress . ", " . $suburb . ", " . $state . " " . $postcode; ?></li>
                    <li><strong>Primary Skill:</strong> <?php echo $primarySkill; ?></li>
                    <li><strong>Additional Skills:</strong> <?php echo $additionalSkillsStr; ?></li>
                    <li><strong>Other Skills:</strong> <?php echo $otherSkillsStr; ?></li>
                    <li><strong>Uploaded Documents:</strong> <?php echo $documentsStr; ?></li>
                </ul>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="jobs.php" class="btn btn--secondary">Browse More Jobs</a>
                    <a href="index.php" class="btn btn--primary">Return to Home</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.inc'; ?>