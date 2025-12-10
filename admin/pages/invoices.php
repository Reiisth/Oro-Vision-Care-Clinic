<?php
// Fetch invoices with patient names and computed total amount
$sql = "
    SELECT 
        i.InvoiceID,
        i.InvoiceDate,
        i.DueDate,
        i.Status,
        i.PatientID,
        CONCAT(p.FirstName, ' ', p.LastName) AS PatientName,
        COALESCE(SUM(ii.Quantity * ii.UnitPrice), 0) AS TotalAmount
    FROM invoice i
    INNER JOIN patient p ON i.PatientID = p.PatientID
    LEFT JOIN invoice_items ii ON i.InvoiceID = ii.InvoiceID
    GROUP BY i.InvoiceID
    ORDER BY i.InvoiceDate DESC
";

$result = $conn->query($sql);
?>
<main class="main-content-wrapper">
    <div class="thick-header">
        <div class="main-content-header-container-thick">
            <div class="page-title-wrapper">
                <h2 class="page-title">Invoices</h2>
                <p class="page-subtitle">Manage and review invoices.</p>
            </div>

            <!-- ALERTS -->
            <div class="page-actions">
                <?php if (isset($_GET['deleted']) || isset($_GET['added']) || isset($_GET['updated'])): ?>
                    <div id="goldAlert" class="gold-alert">
                        <span class="material-symbols-rounded">check_circle</span>
                        <?= isset($_GET['deleted']) 
                                ? "Invoice deleted successfully."
                                : (isset($_GET['updated'])
                                    ? "Invoice updated successfully."
                                    : "New invoice created successfully."); ?>
                    </div>

                    <script>
                        const alertBox = document.getElementById("goldAlert");
                        if (alertBox) {
                            setTimeout(() => alertBox.classList.add("hide"), 2500);
                            setTimeout(() => alertBox.remove(), 3500);
                        }
                    </script>
                <?php endif; ?>
            </div>
        </div>

        <!-- SEARCH + ADD INVOICE -->
        <div class="search-and-add-wrapper">
            <div class="search-container">
                <input 
                    type="text" 
                    id="search" 
                    class="search-input" 
                    placeholder="Search Invoice ID or Patient Name..."
                >
                <span class="material-symbols-rounded search-icon">search</span>
            </div>

            <a href="index.php?page=add_invoice" class="btn btn-sm btn-primary btn-pill btn-shine">
                <span class="material-symbols-rounded">add</span>
                New Invoice
            </a>
        </div>
    </div>


    <!-- TABLE -->
    <div class="main-with-thick-header">
        <div class="table-wrapper">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Total Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="centered">Action</th>
                    </tr>
                </thead>

                <tbody id="invoiceTableBody">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['InvoiceID'] ?></td>
                        <td><?= $row['InvoiceDate'] ?></td>

                        <td><span class="gold-text txt-bold txt-medium"><?= $row['PatientID'] ?></span></td>
                        <td><span class="name-main"><?= $row['PatientName'] ?></span></td>

                        <td>₱<?= number_format($row['TotalAmount'], 2) ?></td>
                        <td><?= $row['DueDate'] ?></td>

                        <!-- STATUS (color-coded optional) -->
                        <td>
                            <span class="status-badge <?= strtolower($row['Status']) ?>">
                                <?= $row['Status'] ?>
                            </span>
                        </td>

                        <!-- ACTIONS -->
                        <td class="action-icons">
                            <a href="index.php?page=view_invoice&id=<?= $row['InvoiceID'] ?>" 
                               class="icon-btn hidden-anchor view-btn" title="View Invoice">
                                <span class="material-symbols-rounded">visibility</span>
                            </a>
                            <?php if (strtolower($row['Status']) !== 'paid'): ?>
                                <button 
                                    class="icon-btn settle-btn" 
                                    onclick="settleInvoice('<?= $row['InvoiceID'] ?>')" 
                                    title="Mark as Paid">
                                    <span class="material-symbols-rounded">payments</span>
                                </button>
                            <?php endif; ?>
                            <button class="icon-btn delete-btn" 
                                    onclick="deleteInvoice('<?= $row['InvoiceID'] ?>')" 
                                    title="Delete">
                                <span class="material-symbols-rounded icon-sm">delete</span>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    </div>

</main>

<script>
// REAL-TIME SEARCH
const searchInput = document.getElementById("search");
const rows = document.querySelectorAll("#invoiceTableBody tr");

searchInput.addEventListener("input", () => {
    const q = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    rows.forEach(row => {
        const invoiceID = row.children[0].innerText.toLowerCase();
        const patientID = row.children[2].innerText.toLowerCase();
        const patientName = row.children[3].innerText.toLowerCase();

        const match =
            invoiceID.includes(q) ||
            patientID.includes(q) ||
            patientName.includes(q);

        row.style.display = match ? "" : "none";
    });
    const noDataRow = document.getElementById("noDataRow");

    if (visibleCount === 0) {
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noDataRow";
            tr.innerHTML = `<td colspan="8" style="text-align:center; padding:20px; color:var(--muted);">
                No matching records found.
            </td>`;
            document.querySelector(".patient-table tbody").appendChild(tr);
        }
    } else {
        if (noDataRow) noDataRow.remove();
    }
});

// DELETE FUNCTION
function deleteInvoice(id) {
    if (!confirm("Are you sure you want to delete this invoice? This cannot be undone.")) return;
    window.location.href = `/Oro_Vision/admin/api/delete_invoice.php?id=${id}`;
}


</script>

<script>
function settleInvoice(id) {
    if (!confirm("Settle this invoice? This will mark it as PAID.")) return;

    window.location.href = `/Oro_Vision/admin/api/settle_invoice.php?id=${id}`;
}
</script>


<style>
/* Optional: Nice status color badges */
.status-badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.paid { background:#d4f8d4; color:#1a7f1a; }
.status-badge.unpaid { background:#ffe0e0; color:#b30000; }
.status-badge.pending { background:#fff3cd; color:#996c00; }
</style>
