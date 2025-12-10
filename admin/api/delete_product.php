<?php
require_once '../../config.php';

if (!isset($_GET['id'])) {
    // No product ID provided
    header("Location: /Oro_Vision/admin/index.php?page=inventory");
    exit();
}

$productID = $_GET['id'];

// Delete product
$stmt = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
$stmt->bind_param("i", $productID);

if ($stmt->execute()) {
    // Redirect with deleted flag to show success popup
    header("Location: /Oro_Vision/admin/index.php?page=inventory&deleted=1");
    exit();
} else {
    echo "Error deleting product: " . $conn->error;
}
