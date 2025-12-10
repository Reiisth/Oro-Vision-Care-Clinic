<?php
$patients = $conn->query("SELECT PatientID, FirstName, LastName FROM patient ORDER BY LastName ASC");
?>

<header>
    <div class="main-content-header-container">
        <div class="page-title-wrapper">
            <h2 class="page-title">New Case Form</h2>
            <p class="page-subtitle">Fill out the form below to add a new patient case.</p>
        </div>
        <a href="index.php?page=patients/records" class="back-btn">
                    <span class="material-symbols-rounded back-icon">arrow_back</span>
                    Back to Case List
            </a>
    </div>
</header>
<main class="main-w-header">
    <form action="/Oro_Vision/admin/api/add_case.php" class="signup-form" method="POST">
                <h4 class="section-title">Case Information</h4>
                
                <div class="form-field">
                    <label for="patientID">Patient</label>
                    <select id="patientID" name="PatientID" required>
                        <option value="">Select patient...</option>
                        <?php while ($p = $patients->fetch_assoc()): ?>
                            <option value="<?= $p['PatientID'] ?>">
                                <?= $p['LastName'] ?>, <?= $p['FirstName'] ?> (ID: <?= $p['PatientID'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Date</label>
                    <input type="date" name="Date" required>
                </div>

                <div class="form-field wide-form">
                    <label>Chief Complaint</label>
                    <textarea name="ChiefComplaint" placeholder="Enter your patient's chief complaint" required></textarea>
                </div>
               
                <div class="form-field wide-form">
                    <label>Associated Complaint</label>
                    <textarea name="AssociatedComplaint" placeholder="Enter your patient's associated complaint" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>Ocular History</label>
                    <textarea name="OcularHistory" placeholder="Enter your patient's ocular history" required></textarea>
                </div>

                <h4 class="section-title">Test Record</h4>
                
                <div class="form-field wide-form">
                    <label>Cover Test</label>
                    <textarea name="CoverTest" placeholder="Enter your patient's cover test results" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>Motility Test</label>
                    <textarea name="MotilityTest" placeholder="Enter your patient's motility test results" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>PERRLA</label>
                    <textarea name="Perrla" placeholder="Enter your patient's Perrla results" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>Corneal Reflection</label>
                    <textarea name="CornealReflection" placeholder="Enter your patient's corneal reflection results" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>Diplopia Test</label>
                    <textarea name="DiplopiaTest" placeholder="Enter your patient's diplopia test results" required></textarea>
                </div>

                <div class="form-field wide-form">
                    <label>Notes</label>
                    <textarea name="Notes" placeholder="Enter your notes" required></textarea>
                </div>
                <p class="wide-form txt-grey txt-small">NOTE: Case Details cannot be edited once submitted. Double check before submitting.</p>

                <button type="submit" class="btn-primary-pill btn-lg wide-form">Add New Case</button>

            </form>
</main>