<?php
require_once '../../config.php';

if (!isset($_GET['id'])) {
    die("Missing CaseID");
}

$caseID = $_GET['id'];

// Delete case record
$stmt = $conn->prepare("DELETE FROM case_record WHERE CaseID = ?");
$stmt->bind_param("i", $caseID);
$stmt->execute();

// Redirect back with success flag
header("Location: /Oro_Vision/admin/index.php?page=patients/records&deleted=1");
exit;


