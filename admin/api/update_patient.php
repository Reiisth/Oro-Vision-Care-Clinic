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
    header("Location: /Oro_Vision/admin/index.php?page=patients/patient_list");
    exit();
}

/* -----------------------
   CLEAN + NORMALIZE INPUT
-------------------------*/

$FirstName      = proper_case($_POST['FirstName']);
$LastName       = proper_case($_POST['LastName']);
$Birthday       = $_POST['Birthday'];
$Sex            = proper_case($_POST['Sex']);
$CivilStatus    = proper_case($_POST['CivilStatus']);

$Barangay       = $_POST['Barangay'];
$City           = $_POST['City'];
$Province       = $_POST['Province'];

$Email          = !empty($_POST['Email']) ? strtolower(trim($_POST['Email'])) : NULL;
$Contact        = preg_replace('/[^0-9]/', '', $_POST['Contact']);

$Allergies      = proper_case($_POST['Allergies']      ?? '');
$Surgery        = proper_case($_POST['Surgery']        ?? '');
$Injury         = proper_case($_POST['Injury']         ?? '');
$ChronicIllness = proper_case($_POST['ChronicIllness'] ?? '');
$Medications    = proper_case($_POST['Medications']    ?? '');
$Eyedrops       = proper_case($_POST['Eyedrops']       ?? '');

$PatientID      = $_POST['PatientID'];

/* -----------------------
          UPDATE QUERY
-------------------------*/

$stmt = $conn->prepare("
UPDATE patient SET
    FirstName=?, LastName=?, Birthday=?, Sex=?, CivilStatus=?,
    Barangay=?, City=?, Province=?, Email=?, Contact=?,
    Allergies=?, Surgery=?, Injury=?, ChronicIllness=?, Medications=?, Eyedrops=?
WHERE PatientID=?
");

$stmt->bind_param(
    "ssssssssssssssssi",
    $FirstName, $LastName, $Birthday, $Sex, $CivilStatus,
    $Barangay, $City, $Province, $Email, $Contact,
    $Allergies, $Surgery, $Injury, $ChronicIllness, $Medications, $Eyedrops,
    $PatientID
);

$success = $stmt->execute();

if ($success) {
    header("Location: /Oro_Vision/admin/index.php?page=patients/patient_list&updated=1");
    exit();
}

echo "Update failed.";
?>
