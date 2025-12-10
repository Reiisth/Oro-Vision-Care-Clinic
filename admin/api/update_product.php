<?php
require_once '../../config.php';

// Safety check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=inventory");
    exit();
}

// Proper casing function
function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        $string
    );
}

// Collect POST data
$productID   = intval($_POST['ProductID']);
$brand       = proper_case(trim($_POST['Brand']));
$description = trim($_POST['Description']);

// Validate
if (empty($brand) || empty($description)) {
    header("Location: /Oro_Vision/admin/index.php?page=edit_product&id=$productID&error=empty");
    exit();
}

// Update query
$stmt = $conn->prepare("
    UPDATE product
    SET Brand = ?, Description = ?
    WHERE ProductID = ?
");

$stmt->bind_param("ssi", $brand, $description, $productID);

if ($stmt->execute()) {
    header("Location: /Oro_Vision/admin/index.php?page=inventory&updated=1");
    exit();
} else {
    echo "Error updating product: " . $conn->error;
}
