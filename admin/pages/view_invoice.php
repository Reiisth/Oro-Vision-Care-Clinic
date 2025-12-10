<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

if (!isset($_GET['id'])) {
    echo "<p>No invoice selected.</p>";
    exit;
}

$invoiceID = $_GET['id'];

// Fetch invoice header
$stmt = $conn->prepare("
    SELECT i.InvoiceID, i.InvoiceDate, i.DueDate, i.Status,
           p.PatientID, p.FirstName, p.LastName
    FROM invoice i
    INNER JOIN patient p ON i.PatientID = p.PatientID
    WHERE i.InvoiceID = ?
");
$stmt->bind_param("i", $invoiceID);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

if (!$invoice) {
    echo "<p>Invoice not found.</p>";
    exit;
}

// Fetch invoice items
$itemStmt = $conn->prepare("
    SELECT Description, Quantity, UnitPrice
    FROM invoice_items
    WHERE InvoiceID = ?
");
$itemStmt->bind_param("i", $invoiceID);
$itemStmt->execute();
$items = $itemStmt->get_result();

$grandTotal = 0;
?>

<main class="main-content-wrapper">
    <div class="invoice-wrapper">

        <div class="invoice-header">
            <h2>Invoice #<?= $invoice['InvoiceID'] ?></h2>

            <div class="invoice-meta">
                <span><strong>Date:</strong> <?= $invoice['InvoiceDate'] ?></span>
                <span><strong>Due:</strong> <?= $invoice['DueDate'] ?></span>
                <span class="status-badge <?= strtolower($invoice['Status']) ?>">
                    <?= $invoice['Status'] ?>
                </span>
            </div>
        </div>

        <div class="patient-info">
            <p><strong>Patient:</strong> <?= $invoice['FirstName'] . " " . $invoice['LastName'] ?></p>
            <p><strong>Patient ID:</strong> <?= $invoice['PatientID'] ?></p>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php 
                        $lineTotal = $item['Quantity'] * $item['UnitPrice'];  
                        $grandTotal += $lineTotal; // ✅ FIXED — accumulate total properly
                    ?>
                    <tr>
                        <td><?= $item['Description'] ?></td>
                        <td><?= $item['Quantity'] ?></td>
                        <td>₱<?= number_format($item['UnitPrice'], 2) ?></td>
                        <td>₱<?= number_format($lineTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- TOTAL ONLY (Subtotal removed) -->
        <div class="total-wrapper">
            <div class="total-box">
                <p class="total">
                    <span>Total</span>
                    <span>₱<?= number_format($grandTotal, 2) ?></span>
                </p>
            </div>
        </div>


        <div class="action-buttons">
            <a href="index.php?page=invoices" class="btn-back">
                <span class="material-symbols-rounded">arrow_back</span>
                Back
            </a>

            <button onclick="window.print()" class="btn-print">
                <span class="material-symbols-rounded">print</span>
                Print Invoice
            </button>
        </div>


    </div>
</main>

