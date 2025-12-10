<?php
require_once "../../config.php";

// Validate required fields
if (
    empty($_POST['PatientID']) ||
    empty($_POST['Date']) ||
    empty($_POST['ChiefComplaint']) ||
    empty($_POST['AssociatedComplaint']) ||
    empty($_POST['OcularHistory'])
) {
    die("Missing required case fields.");
}

$patientID = $_POST['PatientID'];
$date = $_POST['Date'];
$chief = $_POST['ChiefComplaint'];
$assoc = $_POST['AssociatedComplaint'];
$history = $_POST['OcularHistory'];

// TEST RECORD
$cover = $_POST['CoverTest'];
$motility = $_POST['MotilityTest'];
$perrla = $_POST['Perrla'];
$corneal = $_POST['CornealReflection'];
$diplopia = $_POST['DiplopiaTest'];
$notes = $_POST['Notes'];


// 1️⃣ Insert into case_record
$stmt = $conn->prepare("
    INSERT INTO case_record 
        (PatientID, Date, ChiefComplaint, AssociatedComplaint, OcularHistory)
    VALUES 
        (?, ?, ?, ?, ?)
");
$stmt->bind_param("issss", $patientID, $date, $chief, $assoc, $history);
$stmt->execute();

$caseID = $stmt->insert_id; // Get the newly created CaseID


// 2️⃣ Insert into test_record (linked with CaseID)
$stmt2 = $conn->prepare("
    INSERT INTO test_record 
        (CaseID, CoverTest, MotilityTest, Perrla, CornealReflection, DiplopiaTest, Notes)
    VALUES
        (?, ?, ?, ?, ?, ?, ?)
");

$stmt2->bind_param(
    "issssss",
    $caseID,
    $cover,
    $motility,
    $perrla,
    $corneal,
    $diplopia,
    $notes
);

$stmt2->execute();


// 3️⃣ Redirect back to Case Records list with success flag
header("Location: /Oro_Vision/admin/index.php?page=patients/records&added=1");
exit;
