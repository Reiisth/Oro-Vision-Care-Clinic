<?php
session_start();
require_once '../config.php';

// Security: ensure patient is logged in
if(!isset($_SESSION['PatientID']) || $_SESSION['Role'] !== 'Patient') {
    header("Location: ../auth/login.php");
    exit();
}

$patientID = $_SESSION['PatientID'];

// Fetch patient basic info
$stmt = $conn->prepare("SELECT FirstName, LastName, Birthday, Province, City, Barangay FROM patient WHERE PatientID = ?");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$stmt->store_result();

$patientName = '';
$patientAge = '';
$patientAddress = '';

if ($stmt->num_rows > 0) {
    $stmt->bind_result($firstName, $lastName, $birthday, $province, $city, $barangay);
    $stmt->fetch();
    $patientName = htmlspecialchars(trim($firstName . ' ' . $lastName));

    if (!empty($birthday)) {
        try {
            $birthDate = new DateTime($birthday);
            $today = new DateTime('today');
            $patientAge = $birthDate->diff($today)->y;
        } catch (Exception $e) {
            $patientAge = '';
        }
    }

    $patientAddress = htmlspecialchars(trim("$barangay, $city, $province"));
}
$stmt->close();

// Fetch invoices for this patient (no TotalAmount field anymore)
$invoices = [];


$stmt = $conn->prepare("
    SELECT InvoiceID, PatientID, InvoiceDate, DueDate, Status
    FROM invoice
    WHERE PatientID = ?
    ORDER BY InvoiceDate DESC
");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$res = $stmt->get_result();

$invoiceIds = [];
while ($row = $res->fetch_assoc()) {
    $invoices[$row['InvoiceID']] = $row;
    $invoiceIds[] = $row['InvoiceID'];
}
$stmt->close();


// Fetch invoice items (Subtotal removed)
$itemsByInvoice = [];

if (!empty($invoiceIds)) {
    $placeholders = implode(",", array_fill(0, count($invoiceIds), "?"));
    $types = str_repeat("i", count($invoiceIds));

    $sql = "
        SELECT ItemID, InvoiceID, Description, Quantity, UnitPrice
        FROM invoice_items
        WHERE InvoiceID IN ($placeholders)
        ORDER BY ItemID ASC
    ";
    $stmt = $conn->prepare($sql);

    // dynamic bind
    $bindParams = [];
    $bindParams[] = & $types;

    foreach ($invoiceIds as $k => $v) {
        $bindParams[] = & $invoiceIds[$k];
    }

    call_user_func_array([$stmt, "bind_param"], $bindParams);

    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $inv = $row["InvoiceID"];
        if (!isset($itemsByInvoice[$inv])) {
            $itemsByInvoice[$inv] = [];
        }
        // cast numeric fields to proper types
        $row['Quantity'] = (float)$row['Quantity'];
        $row['UnitPrice'] = (float)$row['UnitPrice'];
        $row['Subtotal'] = $row['Quantity'] * $row['UnitPrice'];

        $itemsByInvoice[$inv][] = $row;
    }

    $stmt->close();
}


// Build final data array for frontend (compute totals manually)
$invoices_for_js = [];

foreach ($invoices as $id => $inv) {

    // Compute total (Quantity * UnitPrice)
    $total = 0;
    if (isset($itemsByInvoice[$id])) {
        foreach ($itemsByInvoice[$id] as $item) {
            $total += $item["Quantity"] * $item["UnitPrice"];
        }
    }

    $invoices_for_js[] = [
    "InvoiceID"   => $id,
    "InvoiceDate" => $inv["InvoiceDate"],
    "DueDate"     => $inv["DueDate"],
    "Status"      => $inv["Status"],
    // send raw numeric total (not formatted) so JS can safely compute/format
    "TotalAmount" => (float)$total,
    "Items"       => isset($itemsByInvoice[$id]) ? $itemsByInvoice[$id] : []
];
}
$clinicName = "Oro Vision Care Clinic";
$clinicAddress = "Nagcarlan, Laguna";

?>

<?php
$page_title = "Invoices";
$custom_css = "css/invoice.css";
include 'patient_header.php';?>

<section class="card-spread">
  <div class="card">
    <h1>Invoices</h1>
    <p style="margin:6px 0 14px 0;color:var(--muted)">Patient: <strong><?php echo $patientName; ?></strong> — Age: <strong><?php echo $patientAge; ?></strong></p>

    <ul id="invoiceList">
    <?php if(empty($invoices_for_js)): ?>
      <li class="empty">No invoices found.</li>
    <?php else: ?>
      <?php foreach($invoices_for_js as $inv): ?>
        <li class="invoice-item" data-id="<?php echo htmlspecialchars($inv['InvoiceID']); ?>"
            data-date="<?php echo htmlspecialchars($inv['InvoiceDate']); ?>"
            data-duedate="<?php echo htmlspecialchars($inv['DueDate']); ?>"
            data-total="<?php echo htmlspecialchars($inv['TotalAmount']); ?>"
            data-status="<?php echo htmlspecialchars($inv['Status']); ?>">
          <div class="meta">
            <div style="font-weight:600">Invoice #<?php echo htmlspecialchars($inv['InvoiceID']); ?></div>
            <div class="status"><?php echo htmlspecialchars($inv['InvoiceDate']); ?> · Due: <?php echo htmlspecialchars($inv['DueDate']); ?></div>
          </div>
          <div style="text-align:right">
            <div class="amount">₱<?php echo htmlspecialchars(number_format((float)$inv['TotalAmount'],2)); ?></div>
            <div class="status"><?php echo htmlspecialchars($inv['Status']); ?></div>
          </div>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
    </ul>
  </div>

  <div class="card">
    <h2>Invoice Preview</h2>
    <div id="previewArea" style="min-height:240px;display:flex;align-items:center;justify-content:center;color:var(--muted)">Select an invoice to view details</div>
  </div>
</section>

<!-- modal -->
<div class="modal-backdrop" id="modalOverlay">
  <div class="modal" role="dialog" aria-modal="true">

    <div class="print-header">
      <h2><?php echo htmlspecialchars($clinicName); ?></h2>
      <p><?php echo htmlspecialchars($clinicAddress); ?></p>
    </div>

    <div class="patient-info" id="patientInfo">
      <p><strong>Patient:</strong> <?php echo $patientName; ?><br>
      <strong>Age:</strong> <?php echo $patientAge; ?><br>
      <strong>Address:</strong> <?php echo $patientAddress; ?></p>
    </div>

    <h3 id="invoiceTitle">Invoice</h3>
    <p id="invoiceMeta" style="margin-top:6px;color:var(--muted)"></p>

    <table>
      <thead>
        <tr><th>Description</th><th style="width:80px">Qty</th><th style="width:120px">Unit Price</th><th style="width:120px">Subtotal</th></tr>
      </thead>
      <tbody id="modalTableBody"></tbody>
    </table>

    <div class="totals"><div><strong>Total: </strong> <span id="totalAmount">₱0.00</span><br><small id="statusText" style="color:var(--muted)"></small></div></div>

    <div class="modal-buttons">
      <button class="btn btn-sm btn-primary btn-rounded shadow-hov" onclick="window.print()">Print</button>
      <button class="btn btn-sm btn-ghost btn-rounded shadow-hov" onclick="closeModal()">Close</button>
    </div>

  </div>
</div>

<script>
    // Prepare data in JS from PHP
    const invoices = <?php echo json_encode($invoices_for_js, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;

    const invoiceList = document.getElementById('invoiceList');
    const previewArea = document.getElementById('previewArea');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTableBody = document.getElementById('modalTableBody');

    function formatCurrency(v){
    // Ensure numeric value; accept strings or numbers. If invalid, fallback to 0
    const n = Number(String(v).replace(/,/g, ''));
    const safe = Number.isFinite(n) ? n : 0;
    return '₱' + safe.toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2});
    }

    function renderPreview(inv){
    // simple preview shown in the right card
    if(!inv){ previewArea.innerHTML = '<div style="text-align:center;color:var(--muted)">Select an invoice to view details</div>'; return; }
    let html = `
        <div style="width:100%">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <div style="color:var(--gold-1)"><strong>Invoice #${inv.InvoiceID}</strong><div style="color:var(--muted);font-size:13px">Date: ${inv.InvoiceDate} · Due: ${inv.DueDate}</div></div>
            <div style="text-align:right"><div style="font-weight:700">${formatCurrency(inv.TotalAmount)}</div><div style="color:var(--muted);font-size:13px">${inv.Status}</div></div>
        </div>
        <div style="margin-top:8px;color:var(--muted);font-size:14px">${inv.Items.length} item(s)</div>
        <div style="margin-top:12px; display:flex; justify-content:flex-end;"><button class="btn btn-primary btn-pill btn-shine" onclick="openModal('${inv.InvoiceID}')">View / Print</button></div>
        </div>
    `;
    previewArea.innerHTML = html;
    }

    function openModal(invoiceId){
    const inv = invoices.find(i => String(i.InvoiceID) === String(invoiceId));
    if(!inv) return;
    // fill modal
    document.getElementById('invoiceTitle').textContent = 'Invoice #' + inv.InvoiceID;
    document.getElementById('invoiceMeta').textContent = 'Date: ' + inv.InvoiceDate + ' · Due: ' + inv.DueDate;

    // populate items
    modalTableBody.innerHTML = '';
    if(inv.Items && inv.Items.length){
        inv.Items.forEach(it => {
        const tr = document.createElement('tr');
        const desc = document.createElement('td'); desc.textContent = it.Description;
        const qty = document.createElement('td'); qty.textContent = it.Quantity;
        const up = document.createElement('td'); up.textContent = formatCurrency(it.UnitPrice);
        const sub = document.createElement('td'); sub.textContent = formatCurrency(it.Subtotal);
        tr.appendChild(desc); tr.appendChild(qty); tr.appendChild(up); tr.appendChild(sub);
        modalTableBody.appendChild(tr);
        });
    } else {
        const tr = document.createElement('tr');
        const td = document.createElement('td'); td.colSpan = 4; td.style.textAlign='center'; td.textContent='No items found.';
        tr.appendChild(td); modalTableBody.appendChild(tr);
    }

    document.getElementById('totalAmount').textContent = formatCurrency(inv.TotalAmount);
    document.getElementById('statusText').textContent = 'Status: ' + inv.Status;

    modalOverlay.style.display = 'flex';
    }

    function closeModal(){ modalOverlay.style.display = 'none'; }

    // wire up list clicks to preview
    invoiceList.querySelectorAll('.invoice-item').forEach(li => {
    li.addEventListener('click', () => {
        const id = li.dataset.id;
        const inv = invoices.find(i => String(i.InvoiceID) === String(id));
        renderPreview(inv);
    });
    });

    // auto-select first invoice if exists
    if(invoices.length>0){ renderPreview(invoices[0]); }
</script>

</body>
</html>
<?php $conn->close(); ?>