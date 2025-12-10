<?php
session_start();
require '../config.php';

$patientIDError = "";
$passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patientID = trim($_POST['patientID']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT PatientID, Password, Role FROM user WHERE PatientID = ?");
    $stmt->bind_param("s", $patientID);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user) {

        if (password_verify($password, $user['Password'])) {

            $_SESSION['PatientID'] = $user['PatientID'];
            $_SESSION['Role'] = $user['Role'];

            if ($user['Role'] === 'Patient') {
                header("Location: ../patient/index.php");
                exit();
            }

            if ($user['Role'] === 'Admin') {
                header("Location: ../admin/index.php");
                exit();
            }

        } else {
            $passwordError = "Incorrect password.";
        }

    } else {
        $patientIDError = "Patient ID not found.";
    }

    $stmt->close();
}

$conn->close();


$page_title = "Login - Oro Vision Care";
$custom_css = "css/login.css";
include '../header.php';
?>

        <section id="login-section">
        <div class="login-container">

            <h2>Oro Vision Care Clinic</h2>
            <h3>Portal Access</h3>
            <p>Login to Your Dashboard</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <?php if(!empty($patientIDError)) { ?>
                <p class="error-message"><?php echo $patientIDError; ?></p>
            <?php } ?>
            <div class="field-group">
            <label for="patientID">Patient ID</label>
            <input type="text" id="patientID" name="patientID" placeholder="Enter your Patient ID" required class="login-input">
            </div>

            <?php if(!empty($passwordError)) { ?>
                <p class="error-message"><?php echo $passwordError; ?></p>
            <?php } ?>
            <div class="field-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your Password" required class="login-input">
            </div>

            <button type="submit" class="btn-primary-pill">Login</button>

            </form>

        </div>
        </section>

    </body>
</html>