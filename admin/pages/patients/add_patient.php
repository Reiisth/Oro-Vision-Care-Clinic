<?php
    require '../config.php';

    function proper_case($string) {
        return preg_replace_callback(
            "/\\b[\\w'-]+\\b/",
            function($word) {
                return ucfirst(strtolower($word[0]));
            },
            $string
        );
    }


    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $firstname = proper_case(trim($_POST['firstname']));
        $lastname  = proper_case(trim($_POST['lastname']));

        $email = trim($_POST['email']);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $dob = $_POST['dob'];
        $sex = $_POST['sex'];
        $civil_status = $_POST['civil_status'];

        $occupation = proper_case(trim($_POST['occupation']));

        $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);

        $province = trim($_POST['province']);
        $city = trim($_POST['city']);
        $barangay = trim($_POST['barangay']);

        $password = $_POST['password'];

        // Validation
        if (empty($firstname)) $errors[] = "First name is required.";
        if (empty($lastname)) $errors[] = "Last name is required.";
        if (empty($dob)) $errors[] = "Date of Birth is required.";
        if (empty($sex)) $errors[] = "Sex is required.";
        if (empty($civil_status)) $errors[] = "Civil Status is required.";
        if (empty($phone)) $errors[] = "Phone number is required.";
        if (empty($occupation)) $errors[] = "Occupation is required.";
        if (empty($password)) $errors[] = "Password is required.";

        // Only validate email if provided
        if (!empty($email)) {
            $stmt = $conn->prepare("SELECT Email FROM patient WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = "This email is already registered.";
            }
        }

        if (empty($errors)) {

            // Insert patient info
            $insertPatient = $conn->prepare("
                INSERT INTO patient (FirstName, LastName, Email, birthday, Sex, CivilStatus, Contact, Occupation, Province, City, Barangay)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $email_or_null = !empty($email) ? $email : NULL;

            $insertPatient->bind_param(
                "sssssssssss",
                $firstname,
                $lastname,
                $email_or_null,
                $dob,
                $sex,
                $civil_status,
                $phone,
                $occupation,
                $province,
                $city,
                $barangay
            );

            if ($insertPatient->execute()) {

                // Get new patient ID
                $newPatientID = $insertPatient->insert_id;

                // Insert into user table
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertUser = $conn->prepare("
                    INSERT INTO user (PatientID, Password, Role)
                    VALUES (?, ?, 'Patient')
                ");

                $insertUser->bind_param("is", $newPatientID, $hashedPassword);

                if ($insertUser->execute()) {
                    $fullName = urlencode($firstname . " " . $lastname);
                    $rawPassword = urlencode($password);


                    $_SESSION['signup_success'] = true;
                    $_SESSION['patient_name']   = $firstname . " " . $lastname;
                    $_SESSION['firstname']      = $firstname;
                    $_SESSION['patient_id']     = $newPatientID;
                    $_SESSION['patient_pass']   = $password; // Plain password used on signup

                    header("Location: signup.php");
                    exit();


                } else {
                    $errors[] = "Failed to create user login.";
                }

            } else {
                $errors[] = "Failed to save patient information.";
            }

        }
    }

?>
<div class="admin-page-container">
    <header>
        <div class="page-header-wrapper">
            <div class="page-title-wrapper">
                <h1 class="page-title">Add New Patient</h1>
                <p class="page-subtitle">Fill out the form below to register a new patient</p>
            </div>

            <a href="index.php?page=patients/patient_list" class="back-btn">
                    <span class="material-symbols-rounded back-icon">arrow_back</span>
                    Back to List
            </a>
        </div>
    </header>

    <main class="main-w-header">
        <form action="/Oro_Vision/admin/api/add_patient.php" class="signup-form" method="POST">



                <h4 class="section-title">Personal Information</h4>
                <div class="form-field">
                    <label>First Name</label>
                    <input type="text" name="FirstName" placeholder="Enter your patient's first name" required>
                </div>
                <div class="form-field">
                    <label>Last Name</label>
                    <input type="text" name="LastName" placeholder="Enter your patient's last name" required>
                </div>
                <div class="form-field">
                    <label>Date of Birth</label>
                    <input type="date" name="Birthday" required>
                </div>
                <div class="form-field">
                    <label>Sex</label>
                    <select name="Sex" required>
                        <option value="" disabled selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Civil Status</label>
                    <select name="CivilStatus" required>
                        <option value="" disabled selected>Select Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Occupation</label>
                    <input type="text" name="Occupation" placeholder="Enter your patient's occupation" required>
                </div>

                <h4 class="section-title">Address</h4>

                <div class="form-field">
                    <label for="Province">Province</label>
                    <select id="province" name="Province" required>
                        <option value="">Select Province</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="City">City / Municipality</label>
                    <select id="city" name="City" required>
                        <option value="">Select City / Municipality</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="Barangay">Barangay</label>
                    <select id="barangay" name="Barangay" required>
                        <option value="">Select Barangay</option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label>Phone Number</label>
                    <input type="text" name="Contact" placeholder="09XX XXX XXXX" required>
                </div>
                
                <h4 class="section-title">Account Security</h4>
                <div class="form-field">
                    <label>Email</label>
                    <input type="email" name="Email" placeholder="Enter your patient's email">
                </div>
                
                <div class="form-field">
                    <label>Password</label>
                    <div class="password-wrapper full">
                        <input type="password" id="password" name="Password" placeholder="Create a Password" required>
                        <span id="togglePassword" class="material-symbols-rounded toggle-eye">
                            visibility
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-primary-pill">Register New Patient</button>

            </form>
        </main>
        <script>
            document.getElementById("togglePassword").addEventListener("click", function () {
                const password = document.getElementById("password");

                if (password.type === "password") {
                    password.type = "text";
                    this.textContent = "visibility_off";
                } else {
                    password.type = "password";
                    this.textContent = "visibility";
                }
            });
        </script>

        <script src="../js/address.js"></script>
        <?php if (isset($_SESSION['newpatient_success']) && $_SESSION['newpatient_success'] === true): ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            Swal.fire({
                title: `Welcome to Oro Vision Care Clinic <?= $_SESSION['newpatient_firstname'] ?>!`,
                html: `
                    <div style="font-size: 16px; text-align: left;">
                        <strong>Patient Name:</strong> <?= $_SESSION['newpatient_fullname'] ?><br>
                        <strong>Patient ID:</strong> <?= $_SESSION['newpatient_id'] ?><br>
                        <strong>Password:</strong> <?= $_SESSION['newpatient_pass'] ?><br><br>
                        Please keep these credentials safe.
                    </div>
                `,
                icon: "success",
                confirmButtonText: "Go to Patient List",
                confirmButtonColor: "#d4af37",
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = "/Oro_Vision/admin/index.php?page=patients/patient_list&added=1";
            });

        });
        </script>

        <?php
        unset($_SESSION['newpatient_success']);
        unset($_SESSION['newpatient_firstname']);
        unset($_SESSION['newpatient_fullname']);
        unset($_SESSION['newpatient_id']);
        unset($_SESSION['newpatient_pass']);
        endif;
        ?>


</div>