<?php
// Fetch prescription records with patient names
$sql = "
    SELECT 
        ps.PrescriptionID,
        ps.Date,
        ps.PatientID,
        CONCAT(p.FirstName, ' ', p.LastName) AS PatientName,
        ps.Sph_OD,
        ps.Sph_OS,
        ps.Cyl_OD,
        ps.Cyl_OS,
        ps.Axis_OD,
        ps.Axis_OS,
        ps.AddPower,
        ps.Prism,
        ps.LensType,
        ps.Material,
        ps.pd,
        ps.Notes
    FROM prescription ps
    INNER JOIN patient p ON ps.PatientID = p.PatientID
    ORDER BY ps.Date DESC
";

$result = $conn->query($sql);
?>
<main class="main-content-wrapper">
    <div class="thick-header">
        <div class="main-content-header-container-thick">
            <div class="page-title-wrapper">
                <h2 class="page-title">Prescriptions</h2>
                <p class="page-subtitle">Manage and review patient prescriptions.</p>
            </div>
            <div class="page-actions">
            <?php if (isset($_GET['deleted']) || isset($_GET['added'])): ?>
                <div id="goldAlert" class="gold-alert">
                    <span class="material-symbols-rounded">check_circle</span>
                    <?= isset($_GET['deleted']) 
                            ? "Prescription deleted successfully." 
                            : "New prescription added successfully."; ?>
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

        <div class="search-and-add-wrapper">
            <div class="search-container">
                <input 
                    type="text" 
                    id="search" 
                    class="search-input" 
                    placeholder="Search PatientID or Name..."
                >
                <span class="material-symbols-rounded search-icon">search</span>
            </div>
            <a href="index.php?page=add_prescription" class="btn btn-sm btn-primary btn-pill btn-shine">
                <span class="material-symbols-rounded">add</span>
                New Prescription
            </a>
        </div>
    </div>

<div class="main-with-thick-header">
    <div class="table-wrapper">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Date</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>View Prescription</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="prescriptionTableBody">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['PrescriptionID'] ?></td>
                    <td><?= $row['Date'] ?></td>
                    <td><span class="gold-text txt-bold txt-medium"><?= $row['PatientID'] ?></span></td>
                    <td><span class="name-main"><?= $row['PatientName'] ?></span></td>

                    <!-- View Prescription Button -->
                    <td>
                        <button class="btn btn-sm btn-ghost btn-pill shadow-hov view-prescription"
                            data-id="<?= $row['PrescriptionID'] ?>"
                            data-name="<?= htmlspecialchars($row['PatientName']) ?>"
                            data-date="<?= $row['Date'] ?>"
                            data-sph-od="<?= $row['Sph_OD'] ?>"
                            data-cyl-od="<?= $row['Cyl_OD'] ?>"
                            data-axis-od="<?= $row['Axis_OD'] ?>"
                            data-sph-os="<?= $row['Sph_OS'] ?>"
                            data-cyl-os="<?= $row['Cyl_OS'] ?>"
                            data-axis-os="<?= $row['Axis_OS'] ?>"
                            data-add="<?= $row['AddPower'] ?>"
                            data-pd="<?= $row['pd'] ?>"
                            data-prism="<?= htmlspecialchars($row['Prism']) ?>"
                            data-lens="<?= htmlspecialchars($row['LensType']) ?>"
                            data-material="<?= htmlspecialchars($row['Material']) ?>"
                            data-notes='<?= json_encode($row["Notes"]) ?>'
                        >
                            <span class="material-symbols-rounded icon-sm gold-text">eyeglasses_2</span>
                        </button>
                    </td>

                    <!-- Notes Column -->
                    <td><?= htmlspecialchars($row['Notes']) ?></td>

                    <!-- Delete Button -->
                    <td class="action-icons">
                        <button 
                            class="icon-btn delete-icon" 
                            onclick="deletePrescription('<?= $row['PrescriptionID'] ?>')" 
                            title="Delete"
                        >
                            <span class="material-symbols-rounded icon-sm">delete</span>
                        </button>
                    </td>
                </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal-backdrop" id="prescriptionModal" style="display:none;">
    <div class="modal prescription-modal">

        <div class="print-header">
            <h2>Oro Vision Care Clinic</h2>
            <p>Nagcarlan, Laguna</p>
        </div>

        <div class="patient-info">
            <p id="prescriptionPatientName"></p>
            <p id="prescriptionDate"></p>
        </div>

        <h3>Prescription Details</h3>

        <table>
            <thead>
                <tr>
                    <th>Eye</th>
                    <th>SPH</th>
                    <th>CYL</th>
                    <th>AX</th>
                    <th>ADD</th>
                </tr>
            </thead>
            <tbody id="prescriptionModalTableBody"></tbody>
        </table>

        <div class="additional-info">
            <p id="pdDisplay"></p>
            <p id="prismDisplay"></p>
            <p id="lensDisplay"></p>
            <p id="materialDisplay"></p>
            <p id="notesDisplay" style="white-space:pre-wrap;"></p>
        </div>

        <div class="modal-buttons">
            <button onclick="window.print()" class="btn btn-sm btn-rounded btn-primary shadow-hov">Print</button>
            <button class="btn btn-sm btn-rounded btn-ghost shadow-hov" onclick="closePrescriptionModal()">Close</button>
        </div>

    </div>
</div>

</main>

<script>
// REAL-TIME FILTERING
const searchInput = document.getElementById("search");
const rows = document.querySelectorAll("#prescriptionTableBody tr");

searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        const prescriptionID = cells[0].innerText.toLowerCase();
        const patientID = cells[2].innerText.toLowerCase();
        const patientName = cells[3].innerText.toLowerCase();

        const match =
            prescriptionID.includes(query) ||
            patientID.includes(query) ||
            patientName.includes(query);

        row.style.display = match ? "" : "none";
        if (match) visibleCount++;
    });
    const noDataRow = document.getElementById("noDataRow");

    if (visibleCount === 0) {
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noDataRow";
            tr.innerHTML = `<td colspan="7" style="text-align:center; padding:20px; color:var(--muted);">
                No matching records found.
            </td>`;
            document.querySelector(".patient-table tbody").appendChild(tr);
        }
    } else {
        if (noDataRow) noDataRow.remove();
    }
});

// View Prescription Modal Handler
document.querySelectorAll(".view-prescription").forEach(btn => {
    btn.addEventListener("click", () => {

        openPrescriptionModal(
            btn.dataset.id,
            btn.dataset.name,
            btn.dataset.date,
            btn.dataset.sphOd,
            btn.dataset.cylOd,
            btn.dataset.axisOd,
            btn.dataset.sphOs,
            btn.dataset.cylOs,
            btn.dataset.axisOs,
            btn.dataset.add,
            btn.dataset.pd,
            btn.dataset.prism,
            btn.dataset.lens,
            btn.dataset.material,
            JSON.parse(btn.dataset.notes)
        );
    });
});

function openPrescriptionModal(
    id, patientName, date,
    sph_od, cyl_od, axis_od,
    sph_os, cyl_os, axis_os,
    addPower, pd, prism, lens, material, notes
) {
    document.getElementById("prescriptionModal").style.display = "flex";

    document.getElementById("prescriptionPatientName").innerHTML =
        `<strong>Patient:</strong> ${patientName}`;
    document.getElementById("prescriptionDate").innerHTML =
        `<strong>Date:</strong> ${date}`;

    document.getElementById("prescriptionModalTableBody").innerHTML = `
        <tr>
            <td>OD</td>
            <td>${sph_od}</td>
            <td>${cyl_od}</td>
            <td>${axis_od}</td>
            <td>${addPower}</td>
        </tr>
        <tr>
            <td>OS</td>
            <td>${sph_os}</td>
            <td>${cyl_os}</td>
            <td>${axis_os}</td>
            <td>${addPower}</td>
        </tr>
    `;

    document.getElementById("pdDisplay").innerHTML = `<strong>PD:</strong> ${pd}`;
    document.getElementById("prismDisplay").innerHTML = `<strong>Prism:</strong> ${prism || "None"}`;
    document.getElementById("lensDisplay").innerHTML = `<strong>Lens Type:</strong> ${lens}`;
    document.getElementById("materialDisplay").innerHTML = `<strong>Material:</strong> ${material}`;
    document.getElementById("notesDisplay").innerHTML = `<strong>Notes:</strong><br>${notes}`;
}

function closePrescriptionModal() {
    document.getElementById("prescriptionModal").style.display = "none";
}

function deletePrescription(id) {
    if (!confirm("Are you sure you want to delete this prescription? This cannot be undone.")) return;
    window.location.href = `/Oro_Vision/admin/api/delete_prescription.php?id=${id}`;
}
</script>
