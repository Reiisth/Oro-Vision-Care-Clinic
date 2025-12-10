<?php
require '../../config.php';

// Ensure an ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /Oro_Vision/admin/index.php?page=employee&error=missing_id");
    exit;
}

$employeeID = intval($_GET['id']); // sanitize ID

// Delete employee using prepared statement
$stmt = $conn->prepare("DELETE FROM employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);

if ($stmt->execute()) {
    // Success → redirect back to employee page
    header("Location: /Oro_Vision/admin/index.php?page=employee&deleted=1");
    exit;
} else {
    // Failure → send error flag
    header("Location: /Oro_Vision/admin/index.php?page=employee&error=delete_failed");
    exit;
}
