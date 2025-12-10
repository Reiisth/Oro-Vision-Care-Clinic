<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function ($word) {
            return ucfirst(strtolower($word[0]));
        },
        trim($string)
    );
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=patients/add");
    exit;
}

/* -----------------------
   CLEAN & NORMALIZE DATA
-------------------------*/

$FirstName    = proper_case($_POST['FirstName']);
$LastName     = proper_case($_POST['LastName']);
$Birthday     = $_POST['Birthday'];
$Sex          = $_POST['Sex'];
$CivilStatus  = proper_case($_POST['CivilStatus']);
$Barangay     = $_POST['Barangay'];
$City         = $_POST['City'];
$Province     = $_POST['Province'];
$Email        = !empty($_POST['Email']) ? strtolower(trim($_POST['Email'])) : NULL;
$Contact      = preg_replace('/[^0-9]/', '', $_POST['Contact']); // Keep digits only

// Medical fields default to empty string
$Allergies      = proper_case($_POST['Allergies']      ?? '');
$Surgery        = proper_case($_POST['Surgery']        ?? '');
$Injury         = proper_case($_POST['Injury']         ?? '');
$ChronicIllness = proper_case($_POST['ChronicIllness'] ?? '');
$Medications    = proper_case($_POST['Medications']    ?? '');
$Eyedrops       = proper_case($_POST['Eyedrops']       ?? '');

$passwordPlain  = $_POST['Password'];
$hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

/* -----------------------
   INSERT PATIENT RECORD
-------------------------*/

$stmt = $conn->prepare("
INSERT INTO patient (
    FirstName, LastName, Birthday, Sex, CivilStatus,
    Barangay, City, Province, Email, Contact,
    Allergies, Surgery, Injury, ChronicIllness, Medications, Eyedrops
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssssssssssss",
    $FirstName, $LastName, $Birthday, $Sex, $CivilStatus,
    $Barangay, $City, $Province, $Email, $Contact,
    $Allergies, $Surgery, $Injury, $ChronicIllness, $Medications, $Eyedrops
);

if (!$stmt->execute()) {
    die("Patient insert failed: " . $stmt->error);
}

$newPatientID = $stmt->insert_id;

/* -----------------------
   INSERT LOGIN ACCOUNT
-------------------------*/

$stmtUser = $conn->prepare("
    INSERT INTO user (PatientID, Password, Role)
    VALUES (?, ?, 'Patient')
");

$stmtUser->bind_param("is", $newPatientID, $hashedPassword);

if (!$stmtUser->execute()) {
    die("User insert failed: " . $stmtUser->error);
}

/* -----------------------
   SUCCESS – REDIRECT
-------------------------*/
session_start();
$_SESSION['newpatient_success'] = true;
$_SESSION['newpatient_fullname'] = $FirstName . " " . $LastName;
$_SESSION['newpatient_firstname'] = $FirstName;
$_SESSION['newpatient_id'] = $newPatientID;
$_SESSION['newpatient_pass'] = $passwordPlain;

header("Location: /Oro_Vision/admin/index.php?page=patients/add_patient");
exit;

