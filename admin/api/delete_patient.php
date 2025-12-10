<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

// Security: Admin only
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: /Oro_Vision/auth/login.php");
    exit;
}

// Ensure ID is provided
if (!isset($_GET['id'])) {
    header("Location: /Oro_Vision/admin/index.php?page=patients/patient_list&error=missing_id");
    exit;
}

$patientID = $_GET['id'];

// DELETE PATIENT
// Delete related user account first
$delUser = $conn->prepare("DELETE FROM user WHERE PatientID = ?");
$delUser->bind_param("s", $patientID);
$delUser->execute();



$stmt = $conn->prepare("DELETE FROM patient WHERE PatientID = ?");
$stmt->bind_param("s", $patientID);

if ($stmt->execute()) {
    header("Location: /Oro_Vision/admin/index.php?page=patients/patient_list&deleted=1");
    exit;
} else {
    header("Location: /Oro_Vision/admin/index.php?page=patients/patient_list&error=delete_failed");
    exit;
}
