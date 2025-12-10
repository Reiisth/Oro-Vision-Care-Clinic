<?php
require '../config.php';

if (!isset($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$invoiceID = $_GET['id'];

// Fetch invoice header + patient
$header = $conn->prepare("
    SELECT 
        i.InvoiceID,
        i.InvoiceDate,
        i.DueDate,
        i.Status,
        CONCAT(p.FirstName, ' ', p.LastName) AS PatientName
    FROM invoice i
    INNER JOIN patient p ON i.PatientID = p.PatientID
    WHERE i.InvoiceID = ?
");
$header->bind_param("i", $invoiceID);
$header->execute();
$invoice = $header->get_result()->fetch_assoc();

if (!$invoice) {
    echo json_encode([]);
    exit;
}

// Fetch invoice items
$items = $conn->prepare("
    SELECT Description, Quantity, UnitPrice
    FROM invoice_items
    WHERE InvoiceID = ?
");
$items->bind_param("i", $invoiceID);
$items->execute();
$itemResult = $items->get_result();

$invoice['Items'] = [];

while ($row = $itemResult->fetch_assoc()) {
    $invoice['Items'][] = $row;
}

echo json_encode($invoice);
?>
