<?php
require '../../config.php';

// Utility: Convert to Proper Case
function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        $string
    );
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=employee");
    exit;
}

// --- Sanitize Inputs ---
$FirstName = proper_case(trim($_POST['FirstName'] ?? ''));
$LastName  = proper_case(trim($_POST['LastName'] ?? ''));

$Province  = trim($_POST['Province'] ?? '');
$City      = trim($_POST['City'] ?? '');
$Barangay  = trim($_POST['Barangay'] ?? '');

$Role      = trim($_POST['Role'] ?? '');
$DateHired = trim($_POST['DateHired'] ?? '');

// Sanitize phone number (keep digits only)
$Contact = preg_replace('/[^0-9]/', '', $_POST['Contact'] ?? '');

// --- Validate Required Fields ---
$errors = [];

if ($FirstName === '') $errors[] = "First name is required.";
if ($LastName === '')  $errors[] = "Last name is required.";
if ($Province === '')  $errors[] = "Province is required.";
if ($City === '')      $errors[] = "City is required.";
if ($Barangay === '')  $errors[] = "Barangay is required.";
if ($Contact === '')   $errors[] = "Contact number is required.";
if ($Role === '')      $errors[] = "Role is required.";
if ($DateHired === '') $errors[] = "Date hired is required.";

if (!empty($errors)) {
    // OPTIONAL: Display errors (for debugging)
    // print_r($errors);
    header("Location: /Oro_Vision/admin/index.php?page=add_employee&error=1");
    exit;
}

// --- Insert into Database ---
$stmt = $conn->prepare("
    INSERT INTO employee 
    (FirstName, LastName, Contact, Province, City, Barangay, Role, DateHired)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssss",
    $FirstName,
    $LastName,
    $Contact,
    $Province,
    $City,
    $Barangay,
    $Role,
    $DateHired
);

if ($stmt->execute()) {
    // Success → redirect back to employee list
    header("Location: /Oro_Vision/admin/index.php?page=employee&added=1");
    exit;
} else {
    // Fail → log or redirect with error flag
    header("Location: /Oro_Vision/admin/index.php?page=employee&error=1");
    exit;
}
