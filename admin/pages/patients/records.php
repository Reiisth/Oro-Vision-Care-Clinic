<?php
// Fetch case records with patient names
$sql = "
    SELECT 
        c.CaseID,
        c.Date,
        c.PatientID,
        CONCAT(p.FirstName, ' ', p.LastName) AS PatientName,
        c.ChiefComplaint,
        c.AssociatedComplaint,
        c.OcularHistory
    FROM case_record c
    INNER JOIN patient p ON c.PatientID = p.PatientID
    ORDER BY c.Date DESC
";

$result = $conn->query($sql);
?>
<main class="main-content-wrapper">
    <div class="thick-header">
        <div class="main-content-header-container-thick">
            <div class="page-title-wrapper">
                <h2 class="page-title">Case Records</h2>
                <p class="page-subtitle">Manage and review patient case records.</p>
            </div>
            <div class="page-actions">
            <?php if (isset($_GET['deleted']) || isset($_GET['added'])): ?>
                <div id="goldAlert" class="gold-alert">
                    <span class="material-symbols-rounded">check_circle</span>
                    <?= isset($_GET['deleted']) 
                            ? "Case record deleted successfully." 
                            : "New case record added successfully."; ?>
                </div>

                <script>
                const alertBox = document.getElementById("goldAlert");

                if (alertBox) {
                    setTimeout(() => {
                        alertBox.classList.add("hide");
                    }, 2500);

                    setTimeout(() => {
                        alertBox.remove();
                    }, 3500);
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
                <a href="index.php?page=patients/add_case" class="btn btn-sm btn-primary btn-pill btn-shine" id="btnAddPatient">
                    <span class="material-symbols-rounded">add</span>
                    New Case
                </a>
        </div>
    </div>
    
 <!-- Table Wrapper -->
<div class="main-with-thick-header">
    <div class="table-wrapper">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Case ID</th>
                    <th>Date</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Chief Complaint</th>
                    <th>Associated Complaint</th>
                    <th>Ocular History</th>
                    <th>Test Record</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="caseTableBody">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CaseID'] ?></td>
                    <td><?= $row['Date'] ?></td>
                    <td><span class="gold-text txt-bold txt-medium"><?= $row['PatientID'] ?></span></td>
                    <td><span class="name-main"><?= $row['PatientName'] ?></span></td>
                    <td><?= $row['ChiefComplaint'] ?></td>
                    <td><?= $row['AssociatedComplaint'] ?></td>
                    <td><?= $row['OcularHistory'] ?></td>
                    <td>
                        <button 
                            class="btn btn-sm btn-ghost btn-pill shadow-hov"
                            onclick="openTestRecordModal(
                            '<?= $row['CaseID'] ?>', 
                            '<?= $row['PatientName'] ?>',
                            '<?= $row['Date'] ?>'
                        )">
                            <span class="material-symbols-rounded icon-sm gold-text">table_eye
                            </span>
                        </button>
                    </td>
                    <td class="action-icons">
                        <button 
                            class="icon-btn delete-icon" 
                            onclick="deleteCaseRecord('<?= $row['CaseID'] ?>')" 
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

<div id="testRecordModal" class="modal-backdrop" style="display:none;">
    <div class="modal-card test-modal">

        <div class="modal-header">
            <h2 class="txt-grey txt-extra-large">Test Record</h2>
            <span class="material-symbols-rounded close-btn" onclick="closeTestRecordModal()">close</span>
        </div>

        <div class="modal-body" id="testRecordContent">
            <!-- Filled dynamically -->
        </div>

    </div>
</div>

</main>

<script>
// REAL-TIME FILTERING (same logic as patient_list.php)
const caseSearchInput = document.getElementById("search");
const caseRows = document.querySelectorAll("#caseTableBody tr");

caseSearchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase().trim();
    let visibleCount = 0;

    caseRows.forEach(row => {
        const cells = row.querySelectorAll("td");

        const caseID = cells[0].innerText.toLowerCase();
        const patientID = cells[2].innerText.toLowerCase();
        const patientName = cells[3].innerText.toLowerCase();

        const matches =
            caseID.includes(query) ||
            patientID.includes(query) ||
            patientName.includes(query);

        if (matches) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    // Handle No Results
    let noDataRow = document.getElementById("noCaseDataRow");

    if (visibleCount === 0) {
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noCaseDataRow";
            tr.innerHTML = `
                <td colspan="7" style="text-align:center; padding:20px; color:var(--muted);">
                    No matching records found.
                </td>`;
            document.getElementById("caseTableBody").appendChild(tr);
        }
    } else {
        if (noDataRow) noDataRow.remove();
    }
});


function openTestRecordModal(CaseID, PatientName, CaseDate) 
 {
    const modal = document.getElementById("testRecordModal");
    const content = document.getElementById("testRecordContent");

    modal.style.display = "flex";

    fetch(`/Oro_Vision/admin/api/get_test_record.php?caseID=${CaseID}`)
        .then(res => res.json())
        .then(data => {
            if (!data || Object.keys(data).length === 0) {
                content.innerHTML = `
                    <p class="txt-muted" style="padding:10px;">No test record found for this case.</p>
                `;
                return;
            }

           content.innerHTML = `
                    <div class="modal-info-block">
                        <h3 class="modal-patient-name">${PatientName}</h3>
                        <p class="modal-case-date">
                            <span class="material-symbols-rounded date-icon">calendar_month</span>
                            ${CaseDate}
                        </p>
                    </div>

                    <div class="history-grid">


                    <div>
                        <label>Cover Test</label>
                        <p>${data.CoverTest || "None"}</p>
                    </div>

                    <div>
                        <label>Motility Test</label>
                        <p>${data.MotilityTest || "None"}</p>
                    </div>

                    <div>
                        <label>PERRLA</label>
                        <p>${data.Perrla || "None"}</p>
                    </div>

                    <div>
                        <label>Corneal Reflection</label>
                        <p>${data.CornealReflection || "None"}</p>
                    </div>

                    <div>
                        <label>Diplopia Test</label>
                        <p>${data.DiplopiaTest || "None"}</p>
                    </div>

                    <div>
                        <label>Notes</label>
                        <p>${data.Notes || "None"}</p>
                    </div>

                </div>
            `;
        })
        .catch(err => {
            content.innerHTML = `<p style="color:red;">Error loading test results.</p>`;
        });
}

function closeTestRecordModal() {
    document.getElementById("testRecordModal").style.display = "none";
}

function deleteCaseRecord(caseID) {
    if (!confirm("Are you sure you want to delete this case record? This cannot be undone.")) {
        return;
    }

    // redirect to delete API
    window.location.href = `/Oro_Vision/admin/api/delete_case_record.php?id=${caseID}`;
}


</script>

