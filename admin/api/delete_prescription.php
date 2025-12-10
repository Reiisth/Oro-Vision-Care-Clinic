<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No prescription ID provided.");
}

$prescriptionID = $_GET['id'];

// Prepare delete query
$stmt = $conn->prepare("DELETE FROM prescription WHERE PrescriptionID = ?");
$stmt->bind_param("i", $prescriptionID);

if ($stmt->execute()) {
    // Success → redirect with popup
    header("Location: /Oro_Vision/admin/index.php?page=prescriptions&deleted=1");
    exit;
} else {
    die("Error deleting prescription: " . $stmt->error);
}
