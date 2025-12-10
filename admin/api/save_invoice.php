<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Oro_Vision/admin/index.php?page=add_invoice&error=invalid_request");
    exit;
}

$patientID = $_POST['PatientID'] ?? null;
$invoiceDate = $_POST['InvoiceDate'] ?? null;
$dueDate = $_POST['DueDate'] ?? null;
$status = $_POST['Status'] ?? 'Unpaid';


// Arrays from dynamic rows
$descriptions = $_POST['Description'] ?? [];
$quantities = $_POST['Quantity'] ?? [];
$unitPrices = $_POST['UnitPrice'] ?? [];

// ----------------------------
// VALIDATION
// ----------------------------
if (!$patientID || !$invoiceDate || !$dueDate) {
    header("Location: /Oro_Vision/admin/index.php?page=add_invoice&error=missing_header");
    exit;
}

// Ensure at least one item exists
if (count($descriptions) === 0) {
    header("Location: /Oro_Vision/admin/index.php?page=add_invoice&error=no_items");
    exit;
}

// Filter out completely empty rows
$validItems = [];
for ($i = 0; $i < count($descriptions); $i++) {
    if (
        trim($descriptions[$i]) !== "" &&
        floatval($quantities[$i]) > 0 &&
        floatval($unitPrices[$i]) >= 0
    ) {
        $validItems[] = $i;
    }
}

if (count($validItems) === 0) {
    header("Location: /Oro_Vision/admin/index.php?page=add_invoice&error=empty_items");
    exit;
}

// ----------------------------
// INSERT INVOICE HEADER
// ----------------------------
$stmt = $conn->prepare("
    INSERT INTO invoice (PatientID, InvoiceDate, DueDate, Status)
    VALUES (?, ?, ?, ?)

");
$stmt->bind_param("isss", $patientID, $invoiceDate, $dueDate, $status);
$stmt->execute();

$invoiceID = $conn->insert_id;  // <-- critical

// ----------------------------
// INSERT ITEMS
// ----------------------------
$itemStmt = $conn->prepare("
    INSERT INTO invoice_items (InvoiceID, Description, Quantity, UnitPrice)
    VALUES (?, ?, ?, ?)
");

foreach ($validItems as $i) {
    $desc = $descriptions[$i];
    $qty = floatval($quantities[$i]);
    $price = floatval($unitPrices[$i]);

    $itemStmt->bind_param("isid", $invoiceID, $desc, $qty, $price);
    $itemStmt->execute();
}

// ----------------------------
// FINISH
// ----------------------------
header("Location: /Oro_Vision/admin/index.php?page=invoices&added=1");
exit;
