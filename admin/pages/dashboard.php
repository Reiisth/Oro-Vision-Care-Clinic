<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

/* ============================================
   USER NAME (Optional)
=============================================== */
$patientID = $_SESSION['PatientID'] ?? null;
$lastName = "Patient";

if ($patientID) {
    $stmt = $conn->prepare("SELECT LastName FROM patient WHERE PatientID = ?");
    $stmt->bind_param("s", $patientID);
    $stmt->execute();
    $stmt->bind_result($lastName);
    $stmt->fetch();
    $stmt->close();
}

/* ============================================
   1. TOTAL INVOICES
=============================================== */
$totalInvoices = $conn->query("SELECT COUNT(*) AS c FROM invoice")->fetch_assoc()['c'];

/* ============================================
   2. TOTAL REVENUE
=============================================== */
$totalRevenue = $conn->query("
    SELECT SUM(ii.Quantity * ii.UnitPrice) AS total 
    FROM invoice_items ii
")->fetch_assoc()['total'] ?? 0;

/* ============================================
   3. TOTAL UNPAID AMOUNT
=============================================== */
$unpaidTotal = $conn->query("
    SELECT SUM(ii.Quantity * ii.UnitPrice) AS total
    FROM invoice i 
    INNER JOIN invoice_items ii ON i.InvoiceID = ii.InvoiceID
    WHERE i.Status = 'Unpaid'
")->fetch_assoc()['total'] ?? 0;

/* ============================================
   4. TOTAL PATIENTS
=============================================== */
$totalPatients = $conn->query("
    SELECT COUNT(*) AS c FROM patient
")->fetch_assoc()['c'];

/* ============================================
   5. CHART DATA
=============================================== */

/* ------ Monthly Revenue ------ */
$res = $conn->query("
    SELECT DATE_FORMAT(i.InvoiceDate, '%b') AS month,
           SUM(ii.Quantity*ii.UnitPrice) AS total
    FROM invoice i
    INNER JOIN invoice_items ii ON i.InvoiceID = ii.InvoiceID
    GROUP BY MONTH(i.InvoiceDate)
    ORDER BY MONTH(i.InvoiceDate)
");
$months = [];
$monthlyRevenue = [];
while ($row = $res->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyRevenue[] = (float)$row['total'];
}

/* ------ Invoice Status Count ------ */
$paidCount     = $conn->query("SELECT COUNT(*) AS c FROM invoice WHERE Status='Paid'")->fetch_assoc()['c'];
$unpaidCount   = $conn->query("SELECT COUNT(*) AS c FROM invoice WHERE Status='Unpaid'")->fetch_assoc()['c'];
$pendingCount  = $conn->query("SELECT COUNT(*) AS c FROM invoice WHERE Status='Pending'")->fetch_assoc()['c'];

/* ------ Top Paying Patients ------ */
$res = $conn->query("
    SELECT CONCAT(p.FirstName,' ',p.LastName) AS name,
           SUM(ii.Quantity*ii.UnitPrice) AS total
    FROM patient p
    INNER JOIN invoice i ON p.PatientID = i.PatientID
    INNER JOIN invoice_items ii ON i.InvoiceID = ii.InvoiceID
    GROUP BY p.PatientID
    ORDER BY total DESC
    LIMIT 5
");
$topPatientsNames = [];
$topPatientsTotals = [];
while ($row = $res->fetch_assoc()) {
    $topPatientsNames[] = $row['name'];
    $topPatientsTotals[] = (float)$row['total'];
}

/* ------ Invoice Count Per Month ------ */
$res = $conn->query("
    SELECT DATE_FORMAT(InvoiceDate, '%b') AS month,
           COUNT(*) AS c
    FROM invoice
    GROUP BY MONTH(InvoiceDate)
    ORDER BY MONTH(InvoiceDate)
");
$invoiceCounts = [];
while ($row = $res->fetch_assoc()) {
    $invoiceCounts[] = $row['c'];
}
?>

<header>
  <button class="btn-print-report btn-shine" onclick="window.print()">
    <span class="material-symbols-rounded">print</span>
    Print Report
  </button>

  <h2 class="page-title">Dashboard</h2> 
                    <a href="../auth/logout.php" class="btn-logout" aria-label="Logout">
                    <span style="font-weight:700; color:var(--text-darker)">Logout</span>
                    <svg class="logout-icon" viewBox="0 0 24 24" fill="none"><path d="M15 7l5 5-5 5M20 12H9M11 19H6a2 2 0 01-2-2V7a2 2 0 012-2h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></header>
</a>
<main class="main-w-header main-content-wrapper">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h2 class="page-title">Welcome back, <span class="gold-text"> Dr. <?= htmlspecialchars($lastName) ?>!</span></h2>
    <p class="page-subtitle">Overview of clinic revenue, activity, and analytics.</p>

    <!-- SCORECARDS -->
    <div class="dashboard-cards">
        <div class="card kpi-card">
            <h4>Total Invoices</h4>
            <p class="kpi-number"><?= $totalInvoices ?></p>
        </div>

        <div class="card kpi-card">
            <h4>Total Revenue</h4>
            <p class="kpi-number">₱<?= number_format($totalRevenue,2) ?></p>
        </div>

        <div class="card kpi-card">
            <h4>Unpaid Amount</h4>
            <p class="kpi-number unpaid">₱<?= number_format($unpaidTotal,2) ?></p>
        </div>

        <div class="card kpi-card">
            <h4>Total Patients</h4>
            <p class="kpi-number"><?= $totalPatients ?></p>
        </div>
    </div>

    <!-- CHARTS ROW 1 -->
    <div class="dashboard-charts">
        <div class="chart-card three-column">
            <h4>Monthly Revenue</h4>
            <canvas id="monthlyRevenueChart">
            </canvas>
        </div>

        <div class="chart-card">
            <h4>Invoice Status Breakdown</h4>
            <canvas id="statusPieChart"></canvas>
        </div>

    <!-- CHARTS ROW 2 -->
      
        <div class="chart-card">
            <h4>Top Paying Patients</h4>
            <canvas id="topPatientsChart"></canvas>
        </div>

        <div class="chart-card">
            <h4>Invoices per Month</h4>
            <canvas id="invoiceCountChart"></canvas>
        </div>
    </div>


    
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize Charts AFTER DOM loads -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: "Monthly Revenue",
                data: <?= json_encode($monthlyRevenue) ?>,
                borderColor: "#D4AF37",
                backgroundColor: "rgba(212,175,55,0.2)",
                tension: 0.4,
                fill: true
            }]
        },
        options: {
          maintainAspectRatio: false}
    });

    new Chart(document.getElementById('statusPieChart'), {
        type: 'pie',
        data: {
            labels: ["Paid", "Unpaid", "Pending"],
            datasets: [{
                data: [<?= $paidCount ?>, <?= $unpaidCount ?>, <?= $pendingCount ?>],
                backgroundColor: ["#27ae60", "#e74c3c", "#f1c40f"]
            }]
        },
        options: {
          maintainAspectRatio: false}
    });

    new Chart(document.getElementById('topPatientsChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($topPatientsNames) ?>,
            datasets: [{
                label: "Total Spent",
                data: <?= json_encode($topPatientsTotals) ?>,
                backgroundColor: "#D4AF37"
            }]
        },
        options: {
          maintainAspectRatio: false}
        
    });

    new Chart(document.getElementById('invoiceCountChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: "Invoices",
                data: <?= json_encode($invoiceCounts) ?>,
                backgroundColor: "#0097A7"}]},
        options: {
          maintainAspectRatio: false}
    });

});
</script>

</main>
