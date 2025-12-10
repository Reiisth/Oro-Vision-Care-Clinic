<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

if (!isset($_GET['id'])) {
    header("Location: /Oro_Vision/admin/index.php?page=invoices&error=no_id");
    exit;
}

$invoiceID = intval($_GET['id']);

// Mark invoice as PAID
$stmt = $conn->prepare("UPDATE invoice SET Status = 'Paid' WHERE InvoiceID = ?");
$stmt->bind_param("i", $invoiceID);
$stmt->execute();

header("Location: /Oro_Vision/admin/index.php?page=invoices&updated=1");
exit;
