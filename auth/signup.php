<?php
    session_start();
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

                    session_start();

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


    $page_title = "New Patient Signup";
    $custom_css = "css/signup.css";
    include '../header.php';
?>

        <h2>Oro Vision Care Clinic</h2>
        <h3>Create a New Patient Account</h3>
        <p>Please fill in the details below to sign up.</p>
        <form action="" method="POST" class="signup-form">


            <h4 class="section-title">Personal Information</h4>
            <div class="signup-field">
                <label>First Name</label>
                <input type="text" name="firstname" placeholder="Enter your first name" required>
            </div>
            <div class="signup-field">
                <label>Last Name</label>
                <input type="text" name="lastname" placeholder="Enter your last name" required>
            </div>
            <div class="signup-field">
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>
            <div class="signup-field">
                <label>Sex</label>
                <select name="sex" required>
                    <option value="" disabled selected>Select Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="signup-field">
                <label>Civil Status</label>
                <select name="civil_status" required>
                    <option value="" disabled selected>Select Civil Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Separated">Separated</option>
                </select>
            </div>
            <div class="signup-field">
                <label>Occupation</label>
                <input type="text" name="occupation" placeholder="Enter your occupation" required>
            </div>

            <h4 class="section-title">Address</h4>

            <div class="signup-field">
                <label for="province">Province</label>
                <select id="province" name="province" required>
                    <option value="">Select Province</option>
                </select>
            </div>
            <div class="signup-field">
                <label for="city">City / Municipality</label>
                <select id="city" name="city" required>
                    <option value="">Select City / Municipality</option>
                </select>
            </div>
            <div class="signup-field">
                <label for="barangay">Barangay</label>
                <select id="barangay" name="barangay" required>
                    <option value="">Select Barangay</option>
                </select>
            </div>
            
            <div class="signup-field">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="09XX XXX XXXX" required>
            </div>
            
            <h4 class="section-title">Account Security</h4>
            <div class="signup-field">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email">
            </div>
            
            <div class="signup-field">
                <label>Password</label>
                <div class="password-wrapper full">
                    <input type="password" id="password" name="password" placeholder="Create a Password" required>
                    <span id="togglePassword" class="material-symbols-rounded toggle-eye">
                        visibility
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-primary-pill">Sign Up</button>

        </form>

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
        <?php
        if (isset($_SESSION['signup_success']) && $_SESSION['signup_success'] === true):
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            Swal.fire({
                title: `Welcome to Oro Vision Care Clinic <?= $_SESSION['firstname'] ?>!`,
                html: `
                    <div style="font-size: 16px; text-align: left;">
                        <strong>Patient Name:</strong> <?= $_SESSION['patient_name'] ?><br>
                        <strong>Patient ID:</strong> <?= $_SESSION['patient_id'] ?><br>
                        <strong>Password:</strong> <?= $_SESSION['patient_pass'] ?><br><br>
                        Please keep these credentials safe.
                    </div>
                `,
                icon: "success",
                confirmButtonText: "Proceed to Login",
                confirmButtonColor: "#d4af37",
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = "login.php";
            });

        });
        </script>

        <?php
        unset($_SESSION['signup_success']);
        unset($_SESSION['patient_name']);
        unset($_SESSION['patient_id']);
        unset($_SESSION['patient_pass']);
        unset($_SESSION['firstname']);
        endif;
        ?>
    </body>
</html>