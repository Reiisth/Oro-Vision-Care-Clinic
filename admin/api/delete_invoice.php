<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

if (!isset($_GET['id'])) {
    header("Location: /Oro_Vision/admin/index.php?page=invoices&error=no_id");
    exit;
}

$invoiceID = intval($_GET['id']);

/* 
-------------------------------------
 DELETE CHILD RECORDS FIRST
 (invoice_items)
-------------------------------------
*/
$itemStmt = $conn->prepare("DELETE FROM invoice_items WHERE InvoiceID = ?");
$itemStmt->bind_param("i", $invoiceID);
$itemStmt->execute();

/* 
-------------------------------------
 DELETE INVOICE HEADER
-------------------------------------
*/
$stmt = $conn->prepare("DELETE FROM invoice WHERE InvoiceID = ?");
$stmt->bind_param("i", $invoiceID);
$stmt->execute();

/* 
-------------------------------------
 REDIRECT BACK WITH SUCCESS MESSAGE
-------------------------------------
*/
header("Location: /Oro_Vision/admin/index.php?page=invoices&deleted=1");
exit;
