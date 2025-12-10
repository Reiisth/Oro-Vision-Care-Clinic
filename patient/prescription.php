<?php
session_start();
require_once '../config.php';

// Must match dashboard session structure
if(!isset($_SESSION['PatientID']) || $_SESSION['Role'] !== 'Patient') {
    header("Location: ../auth/login.php");
    exit();
}

$patientID = $_SESSION['PatientID'];

// Fetch patient details (name, age, address) for printing
$stmt = $conn->prepare("SELECT FirstName, LastName, Birthday, Province, City, Barangay FROM patient WHERE PatientID = ?");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$stmt->store_result();

$patientName = "";
$patientAge = "";
$patientAddress = "";

if ($stmt->num_rows > 0) {
    $stmt->bind_result($firstName, $lastName, $birthday, $province, $city, $barangay);
    $stmt->fetch();

    // Full name
    $patientName = htmlspecialchars($firstName . " " . $lastName);

    // Age from Birthday
    if (!empty($birthday)) {
        $birthDate = new DateTime($birthday);
        $today = new DateTime('today');
        $patientAge = $birthDate->diff($today)->y;
    }

    // Full formatted address
    $patientAddress = htmlspecialchars("$barangay, $city, $province");
}

$stmt->close();

// Fetch prescriptions
$sql = "SELECT PrescriptionID, Date, Sph_OD, Cyl_OD, Axis_OD, Sph_OS, Cyl_OS, Axis_OS, AddPower, Prism, LensType, Material, pd, Notes 
        FROM prescription 
        WHERE PatientID = $patientID 
        ORDER BY Date DESC";

$result = $conn->query($sql);
?>
<?php
$page_title = "Prescriptions";
$custom_css = "css/prescription.css";
include 'patient_header.php'; ?>

        <h1><?= htmlspecialchars($firstName) ?>'s Prescriptions</h1>
        <ul id="prescriptionList">
        <?php if($result && $result->num_rows>0): while($row=$result->fetch_assoc()): ?>
            <li data-od-sph="<?= $row['Sph_OD'] ?>" data-od-cyl="<?= $row['Cyl_OD'] ?>" data-od-axis="<?= $row['Axis_OD'] ?>"
                data-os-sph="<?= $row['Sph_OS'] ?>" data-os-cyl="<?= $row['Cyl_OS'] ?>" data-os-axis="<?= $row['Axis_OS'] ?>"
                data-add="<?= $row['AddPower'] ?>" data-prism="<?= $row['Prism'] ?>" data-lens="<?= $row['LensType'] ?>"
                data-material="<?= $row['Material'] ?>" data-pd="<?= $row['pd'] ?>" data-notes="<?= htmlspecialchars($row['Notes']) ?>"
                data-date="<?= $row['Date'] ?>">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#d4af37"><path d="M218-320q-42 0-75.5-27T100-416L71-550h-4q-17 2-29.5-9T23-587q-2-17 9.5-29T61-630q62-5 108-7.5t84-2.5q65 0 105 6t72 21q14 7 26.5 10t23.5 3q11 0 21.5-3t24.5-9q33-15 76-21.5t114-6.5q46 0 97 2.5t87 6.5q17 2 28 14t9 29q-2 17-14 28t-29 9h-4l-30 136q-9 42-42 68.5T743-320h-89q-42 0-74-25.5T538-411l-27-107h-61l-27 107q-11 41-43 66t-73 25h-89Zm-40-112q3 14 14 23t25 9h89q14 0 25-8.5t14-21.5l31-121q-27-5-61-6.5t-62-1.5q-23 0-49.5.5T154-556l24 124Zm437 2q3 13 14 21.5t25 8.5h89q14 0 25-9t14-23l26-125q-20-1-46-1.5t-46-.5q-30 0-66.5 1.5T584-551l31 121Z"/></svg>
            </span>
            <span class="date">
                <?= $row['Date'] ?>
            </span>
            </li>
        <?php endwhile; else: ?><li>No prescriptions found.</li><?php endif; ?>
        </ul>

        <div class="modal-backdrop" id="modalOverlay">
        <div class="modal">

        <div class="print-header">
        <h2>Oro Vision Care Clinic</h2>
        <p>Nagcarlan, Laguna</p>
        </div>

        <div class="patient-info" id="patientInfo">
        <p><strong>Patient:</strong> <?= $patientName ?><br>
        <strong>Age:</strong> <?= $patientAge ?><br>
        <strong>Address:</strong> <?= $patientAddress ?></p>
        </div>

        <h3>Prescription Details</h3>
        <strong><p id="dateDisplay"></p></strong>

        <table>
        <thead><tr><th>Eye</th><th>SPH</th><th>CYL</th><th>AX</th><th>ADD</th></tr></thead>
        <tbody id="modalTableBody"></tbody>
        </table>
        
        <p id="pdDisplay" style="margin-top:10px;font-weight:600"></p>
        <div class="additional-info">
        <p id="prismDisplay"></p>
        <p id="lensDisplay"></p>
        <p id="materialDisplay"></p>
        <p id="notesDisplay" style="white-space:pre-wrap;"></p>
        </div>

        <div class="doctor-sign">
        <p><strong>Dr. Melrose Oro</strong><br>License No: 0011740</p>
        </div>

        <div class="modal-buttons">
        <button onclick="window.print()" class="btn btn-sm btn-rounded btn-primary shadow-hov">Print</button>
        <button class="btn btn-sm btn-rounded btn-ghost shadow-hov" onclick="closeModal()">Close</button>
        </div>

        </div></div>

        <script>
        const modalOverlay=document.getElementById('modalOverlay');
        const tableBody=document.getElementById('modalTableBody');

        document.querySelectorAll('#prescriptionList li').forEach(item=>{
        item.addEventListener('click',()=>{
        const od = {
            sph: item.dataset.odSph,
            cyl: item.dataset.odCyl,
            axis: item.dataset.odAxis,
            add: item.dataset.add
        };
        const os = {
            sph: item.dataset.osSph,
            cyl: item.dataset.osCyl,
            axis: item.dataset.osAxis,
            add: item.dataset.add
        };

        tableBody.innerHTML = `
            <tr><td>OD</td><td>${od.sph}</td><td>${od.cyl}</td><td>${od.axis}</td><td>${od.add}</td></tr>
            <tr><td>OS</td><td>${os.sph}</td><td>${os.cyl}</td><td>${os.axis}</td><td>${os.add}</td></tr>`;

        document.getElementById('dateDisplay').textContent = "Date: " + item.dataset.date;
        document.getElementById('pdDisplay').textContent = "PD: " + item.dataset.pd;
        document.getElementById('prismDisplay').textContent = "Prism: " + (item.dataset.prism || 'None');
        document.getElementById('lensDisplay').textContent = "Lens Type: " + (item.dataset.lens || '');
        document.getElementById('materialDisplay').textContent = "Material: " + (item.dataset.material || '');
        document.getElementById('notesDisplay').textContent = "Notes: " + (item.dataset.notes || '');

        modalOverlay.style.display='flex';
        });
        });
        function closeModal(){ modalOverlay.style.display='none'; }
        </script>
        </main>
    </body>
</html>
<?php $conn->close(); ?>