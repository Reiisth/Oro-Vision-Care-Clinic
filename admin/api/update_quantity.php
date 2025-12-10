<?php
require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ProductID = intval($_POST['ProductID']);
    $Quantity  = intval($_POST['Quantity']);

    $stmt = $conn->prepare("UPDATE product SET Quantity = ? WHERE ProductID = ?");
    $stmt->bind_param("ii", $Quantity, $ProductID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
