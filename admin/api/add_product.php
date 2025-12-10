<?php
require_once '../../config.php';

// Allow POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=inventory");
    exit();
}

// Proper casing helper
function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        trim($string)
    );
}

// Collect and sanitize form input
$brand       = proper_case($_POST['Brand'] ?? '');
$description = proper_case($_POST['Description'] ?? '');

$errors = [];

// Validation
if (empty($brand))        $errors[] = "Brand is required.";
if (empty($description))  $errors[] = "Description is required.";

if (!empty($errors)) {
    // You may redirect back with error messages if you use alerts
    header("Location: /Oro_Vision/admin/index.php?page=add_product&error=" . urlencode(implode(", ", $errors)));
    exit();
}

// Insert product (Quantity defaults to 0 via your database schema)
$stmt = $conn->prepare("
    INSERT INTO product (Brand, Description)
    VALUES (?, ?)
");

$stmt->bind_param("ss", $brand, $description);

if ($stmt->execute()) {
    header("Location: /Oro_Vision/admin/index.php?page=inventory&added=1");
    exit();
} else {
    echo "Database Error: " . $conn->error;
}
