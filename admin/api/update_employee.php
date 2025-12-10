<?php
require '../../config.php';

// Utility: Proper Case Formatting
function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        $string
    );
}

// Must be POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=employee&error=invalid_request");
    exit;
}

// -----------------------------
// SANITIZE & FORMAT INPUTS
// -----------------------------
$EmployeeID = intval($_POST['EmployeeID'] ?? 0);

$FirstName  = proper_case(trim($_POST['FirstName'] ?? ''));
$LastName   = proper_case(trim($_POST['LastName'] ?? ''));

$Province   = trim($_POST['Province'] ?? '');
$City       = trim($_POST['City'] ?? '');
$Barangay   = trim($_POST['Barangay'] ?? '');

$Role       = trim($_POST['Role'] ?? '');
$DateHired  = trim($_POST['DateHired'] ?? '');

// Sanitize phone number
$Contact = preg_replace('/[^0-9]/', '', $_POST['Contact'] ?? '');


// -----------------------------
// VALIDATION
// -----------------------------
$errors = [];

if ($EmployeeID === 0) $errors[] = "Invalid employee ID.";
if ($FirstName === '') $errors[] = "First name is required.";
if ($LastName === '')  $errors[] = "Last name is required.";

if ($Province === '')  $errors[] = "Province is required.";
if ($City === '')      $errors[] = "City is required.";
if ($Barangay === '')  $errors[] = "Barangay is required.";

if ($Contact === '')   $errors[] = "Phone number is required.";
if ($Role === '')      $errors[] = "Role is required.";
if ($DateHired === '') $errors[] = "Date hired is required.";

if (!empty($errors)) {
    // OPTIONAL: print_r($errors);
    header("Location: /Oro_Vision/admin/index.php?page=edit_employee&id=$EmployeeID&error=1");
    exit;
}


// -----------------------------
// UPDATE QUERY
// -----------------------------
$stmt = $conn->prepare("
    UPDATE employee
    SET FirstName = ?, 
        LastName = ?,
        Contact = ?,
        Province = ?,
        City = ?,
        Barangay = ?,
        Role = ?,
        DateHired = ?
    WHERE EmployeeID = ?
");

$stmt->bind_param(
    "ssssssssi",
    $FirstName,
    $LastName,
    $Contact,
    $Province,
    $City,
    $Barangay,
    $Role,
    $DateHired,
    $EmployeeID
);


// -----------------------------
// EXECUTE & REDIRECT
// -----------------------------
if ($stmt->execute()) {
    header("Location: /Oro_Vision/admin/index.php?page=employee&updated=1");
    exit;
} else {
    header("Location: /Oro_Vision/admin/index.php?page=edit_employee&id=$EmployeeID&error=update_failed");
    exit;
}
