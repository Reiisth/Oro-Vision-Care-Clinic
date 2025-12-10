<?php
require_once '../../config.php';

// Make sure required fields are present
if (
    empty($_POST['PatientID']) ||
    empty($_POST['Date']) ||
    empty($_POST['Sph_OD']) ||
    empty($_POST['Cyl_OD']) ||
    empty($_POST['Axis_OD']) ||
    empty($_POST['Sph_OS']) ||
    empty($_POST['Cyl_OS']) ||
    empty($_POST['Axis_OS'])
) {
    die("Missing required fields.");
}

$patientID = $_POST['PatientID'];
$date = $_POST['Date'];

$sph_od = $_POST['Sph_OD'];
$cyl_od = $_POST['Cyl_OD'];
$axis_od = $_POST['Axis_OD'];

$sph_os = $_POST['Sph_OS'];
$cyl_os = $_POST['Cyl_OS'];
$axis_os = $_POST['Axis_OS'];

$addPower = $_POST['AddPower'];        // Required for OD
$addPower_os = $_POST['AddPower_OS']; // Optional for OS

$pd = $_POST['pd'];
$prism = $_POST['Prism'] ?? null;
$lens = $_POST['LensType'];
$material = $_POST['Material'];
$notes = $_POST['Notes'] ?? null;


// If OS AddPower is empty, default to OD AddPower
if (empty($addPower_os)) {
    $addPower_os = $addPower;
}


// Prepare SQL insert for prescription table
$stmt = $conn->prepare("
    INSERT INTO prescription 
    (PatientID, Date, 
     Sph_OD, Cyl_OD, Axis_OD, 
     Sph_OS, Cyl_OS, Axis_OS, 
     AddPower, Prism, LensType, Material, pd, Notes)
    VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssssssssssss",
    $patientID,
    $date,
    $sph_od, $cyl_od, $axis_od,
    $sph_os, $cyl_os, $axis_os,
    $addPower,
    $prism,
    $lens,
    $material,
    $pd,
    $notes
);


// Execute insert
if ($stmt->execute()) {
    header("Location: /Oro_Vision/admin/index.php?page=prescriptions&added=1");
    exit;
} else {
    echo "Error inserting prescription: " . $conn->error;
}
