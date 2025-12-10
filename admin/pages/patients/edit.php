<?php
if (!isset($_GET['id'])) {
    echo "<p>No patient selected.</p>";
    exit;
}

$patientID = $_GET['id'];

// Fetch patient data
$stmt = $conn->prepare("
    SELECT PatientID, FirstName, LastName, Birthday, Sex, CivilStatus,
           Barangay, City, Province, Email, Contact,
           Allergies, Surgery, Injury, ChronicIllness, Medications, Eyedrops
    FROM patient
    WHERE PatientID = ?
");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    echo "<p>Patient not found.</p>";
    exit;
}
?>

<div class="edit-page">

    <div class="edit-header">
        <h1><span class="gold-text">Edit Patient:</span> <?= htmlspecialchars($patient['FirstName'] . " " . $patient['LastName']) ?></h1>

        <a href="index.php?page=patients/patient_list" class="back-btn">
            <span class="material-symbols-rounded back-icon">arrow_back</span>
            Back to List
        </a>
    </div>

    <form action="/Oro_Vision/admin/api/update_patient.php" class="edit-form" method="POST">



        <input type="hidden" name="PatientID" value="<?= $patient['PatientID'] ?>">

        <!-- PERSONAL INFO -->
        <section class="edit-section grd-off-white-soft">
            <h2 class="section-title">Personal Information</h2>

            <div class="edit-grid">
                <div class="form-field">
                    <label>First Name</label>
                    <input type="text" name="FirstName" value="<?= $patient['FirstName'] ?>" required>
                </div>

                <div class="form-field">
                    <label>Last Name</label>
                    <input type="text" name="LastName" value="<?= $patient['LastName'] ?>" required>
                </div>

                <div class="form-field">
                    <label>Birthday</label>
                    <input type="date" name="Birthday" value="<?= $patient['Birthday'] ?>" required>
                </div>

                <div class="form-field">
                    <label>Sex</label>
                    <select name="Sex">
                        <option <?= $patient['Sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option <?= $patient['Sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="form-field">
                    <label>Civil Status</label>
                    <select name="CivilStatus">
                        <?php
                        $statuses = ["Single", "Married", "Separated", "Widowed"];
                        foreach ($statuses as $status) {
                            $sel = ($patient['CivilStatus'] == $status) ? "selected" : "";
                            echo "<option $sel>$status</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Province</label>
                    <select id="province" name="Province" data-current="<?= $patient['Province'] ?>"></select>
                </div>

                <div class="form-field">
                    <label>City/Municipality</label>
                    <select id="city" name="City" data-current="<?= $patient['City'] ?>"></select>
                </div>

                <div class="form-field">
                    <label>Barangay</label>
                    <select id="barangay" name="Barangay" data-current="<?= $patient['Barangay'] ?>"></select>
                </div>


                <div class="form-field">
                    <label>Email</label>
                    <input type="email" name="Email" value="<?= $patient['Email'] ?>">
                </div>

                <div class="form-field">
                    <label>Phone</label>
                    <input type="text" name="Contact" value="<?= $patient['Contact'] ?>">
                </div>
            </div>
        </section>

        <!-- MEDICAL INFO -->
        <section class="edit-section">
            <h2 class="section-title">Medical Information</h2>

            <div class="edit-grid">
                <div class="form-field">
                    <label>Allergies</label>
                    <input type="text" name="Allergies" value="<?= $patient['Allergies'] ?>">
                </div>

                <div class="form-field">
                    <label>Surgery</label>
                    <input type="text" name="Surgery" value="<?= $patient['Surgery'] ?>">
                </div>

                <div class="form-field">
                    <label>Injury</label>
                    <input type="text" name="Injury" value="<?= $patient['Injury'] ?>">
                </div>

                <div class="form-field">
                    <label>Chronic Illness</label>
                    <input type="text" name="ChronicIllness" value="<?= $patient['ChronicIllness'] ?>">
                </div>

                <div class="form-field">
                    <label>Medications</label>
                    <input type="text" name="Medications" value="<?= $patient['Medications'] ?>">
                </div>

                <div class="form-field">
                    <label>Eyedrops</label>
                    <input type="text" name="Eyedrops" value="<?= $patient['Eyedrops'] ?>">
                </div>
            </div>
        </section>

        <div class="edit-actions">
            <button type="submit" class="btn btn-primary btn-pill btn-shine">Save Changes</button>
        </div>

    </form>
    <script src="/Oro_Vision/js/address.js"></script>

</div>

