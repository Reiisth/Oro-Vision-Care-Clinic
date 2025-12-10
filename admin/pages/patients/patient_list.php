<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Patient Management</h1>
        <p class="page-subtitle">Manage patient records and view their medical history.</p>
    </div>
    <div class="page-actions">
        <?php 
        if (isset($_GET['deleted']) || isset($_GET['added']) || isset($_GET['updated'])): 

            // Choose message
            $message = isset($_GET['deleted']) ? "Patient record deleted successfully."
                    : (isset($_GET['added']) ? "New patient added successfully."
                    : "Patient record updated successfully.");
        ?>
            <div id="goldAlert" class="gold-alert">
                <span class="material-symbols-rounded">check_circle</span>
                <?= $message ?>
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

<div class="page-content">

    <!-- Search Bar -->
    <div class="bar-and-btn"> 
        <div class="patient-search">
            <input type="text" id="searchInput" placeholder="Search by Patient ID or Name...">
            <span class="material-symbols-rounded search-icon">search</span>
            
        </div>
        <a href="index.php?page=patients/add_patient" class="btn btn-sm btn-primary btn-pill btn-shine" id="btnAddPatient">
        <span class="material-symbols-rounded">add</span>
        Add Patient
        </a>
    </div>

    <!-- Table Wrapper -->
    <div class="table-wrapper">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Last Visit</th>
                    <th>Med. History</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Fetch patient data (you can replace with prepared statements)
                $query = "SELECT 
                        p.PatientID,
                        p.FirstName,
                        p.LastName,
                        p.Birthday,
                        p.Sex,
                        p.CivilStatus,
                        p.Email,
                        p.Contact,
                        p.Barangay,
                        p.City,
                        p.Province,

                        -- NEW: Get most recent case record date
                        (
                            SELECT MAX(Date)
                            FROM case_record c
                            WHERE c.PatientID = p.PatientID
                        ) AS LastVisit

                    FROM patient p
                    ORDER BY p.LastName ASC
";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        // Calculate Age
                        $age = date_diff(date_create($row['Birthday']), date_create('today'))->y;

                        $fullName = $row['FirstName'] . " " . $row['LastName'];
                        $address = "{$row['Barangay']}, {$row['City']}, {$row['Province']}";
                        $contact = "{$row['Email']}<br>{$row['Contact']}";
                ?>

                <tr>
                    <td><span class="gold-text txt-bold txt-medium"><?= $row['PatientID'] ?></span></td>
                    <td>
                        <div class="name-block">
                            <span class="name-main"><?= $fullName ?></span>
                            <span class="name-sub">
                                <?= $row['Sex'] ?> 
                                <?php if (!empty($row['CivilStatus'])): ?>
                                    • <?= $row['CivilStatus'] ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </td>

                    <td><?= $age ?></td>
                    <td><?= $address ?></td>
                    <td>
                        <div class="contact-block">
                            <div class="contact-line">
                                <span class="material-symbols-rounded contact-icon">mail</span>
                                <span><?= $row['Email'] ?></span>
                            </div>

                            <div class="contact-line">
                                <span class="material-symbols-rounded contact-icon">call</span>
                                <span><?= $row['Contact'] ?></span>
                            </div>
                        </div>
                    </td>

                    <td>
                        <?php if ($row['LastVisit']): ?>
                            <?= $row['LastVisit'] ?>
                        <?php else: ?>
                            <span class="txt-muted">No visits yet</span>
                        <?php endif; ?>
                    </td>


                    <!-- VIEW BTN -->
                    <td>
                        <button class="btn btn-sm btn-ghost btn-pill btn-sm shadow-hov" onclick="openPatientModal('<?= $row['PatientID'] ?>')">
                            <span class="material-symbols-rounded view-icon">clinical_notes</span>
                        </button>
                    </td>

                    <!-- ACTION BTNS -->
                    <td class="action-icons">
                        <a href="index.php?page=patients/edit&id=<?= $row['PatientID'] ?>" class="icon-btn edit-icon" title="Edit">
                            <span class="material-symbols-rounded">edit</span>
                        </a>

                        <button onclick="deletePatient('<?= $row['PatientID'] ?>')" class="icon-btn delete-icon" title="Delete">
                            <span class="material-symbols-rounded">delete</span>
                        </button>
                    </td>


                </tr>

                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Patient Modal (empty — will be filled by JS later) -->
<div id="patientModal" class="modal-backdrop" style="display:none;">
    <div class="modal-card">

        <div class="modal-header">
            <h2 class="txt-grey txt-extra-large">Patient Medical History</h2>
            <span class="material-symbols-rounded close-btn" onclick="closePatientModal()">close</span>
        </div>

        <div class="modal-body" id="patientModalContent">
            <!-- Filled dynamically -->
        </div>

    </div>
</div>


<script>
    function openPatientModal(id) {
    const modal = document.getElementById("patientModal");
    const content = document.getElementById("patientModalContent");

    modal.style.display = "flex";

    // TEMPORARY: Mock data (replace with AJAX later)
    fetch(`/Oro_Vision/admin/api/get_patient_history.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            content.innerHTML = `
                <div class="patient-info-block">
                    <h3>${data.Name}</h3>
                    <p class="sub">${data.Age} years old · ${data.Sex}</p>
                </div>

                <div class="history-grid">
                    <div>
                        <label>Allergies</label>
                        <p>${data.Allergies || "None"}</p>
                    </div>

                    <div>
                        <label>Surgery</label>
                        <p>${data.Surgery || "None"}</p>
                    </div>

                    <div>
                        <label>Injury</label>
                        <p>${data.Injury || "None"}</p>
                    </div>

                    <div>
                        <label>Chronic Illness</label>
                        <p>${data.ChronicIllness || "None"}</p>
                    </div>

                    <div>
                        <label>Medications</label>
                        <p>${data.Medications || "None"}</p>
                    </div>

                    <div>
                        <label>Eyedrops</label>
                        <p>${data.Eyedrops || "None"}</p>
                    </div>
                </div>
            `;
        })
        .catch(err => {
            content.innerHTML = `<p style="color:red;">Error loading data.</p>`;
        });
}

function closePatientModal() {
    document.getElementById("patientModal").style.display = "none";
}


function editPatient(id) {
    alert("Edit function not yet implemented. Patient: " + id);
}

function deletePatient(id) {
    if (confirm("Are you sure you want to delete this patient?")) {
        alert("Deleting patient " + id);
    }
}

// Real-time filtering
const searchInput = document.getElementById("searchInput");
const patientRows = document.querySelectorAll(".patient-table tbody tr");

searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase().trim();
    let visibleCount = 0;

    patientRows.forEach(row => {
        const cells = row.querySelectorAll("td");

        const patientID = cells[0].innerText.toLowerCase();
        const name = cells[1].innerText.toLowerCase();

        const matches =
            patientID.includes(query) ||
            name.includes(query);

        if (matches) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    // Show "No matching results"
    const noDataRow = document.getElementById("noDataRow");

    if (visibleCount === 0) {
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noDataRow";
            tr.innerHTML = `<td colspan="9" style="text-align:center; padding:20px; color:var(--muted);">
                No matching records found.
            </td>`;
            document.querySelector(".patient-table tbody").appendChild(tr);
        }
    } else {
        if (noDataRow) noDataRow.remove();
    }
});

function toggleActionMenu(btn) {
    const menu = btn.nextElementSibling;
    const allMenus = document.querySelectorAll(".action-dropdown");

    // Close all other menus
    allMenus.forEach(m => {
        if (m !== menu) m.style.display = "none";
    });

    // Toggle current menu
    menu.style.display = (menu.style.display === "flex") ? "none" : "flex";

    // Close menu when clicking outside
    document.addEventListener("click", function closeMenu(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
            document.removeEventListener("click", closeMenu);
        }
    });
}


function deletePatient(id) {
    if (!confirm("Are you sure you want to delete this patient? This cannot be undone.")) {
        return;
    }

    // Redirect to delete API
    window.location.href = `/Oro_Vision/admin/api/delete_patient.php?id=${id}`;
}




</script>