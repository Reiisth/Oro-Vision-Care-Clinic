<?php
$patients = $conn->query("SELECT PatientID, FirstName, LastName FROM patient ORDER BY LastName ASC");
?>

<header>
    <div class="main-content-header-container">
        <div class="page-title-wrapper">
            <h2 class="page-title">New Prescription Form</h2>
            <p class="page-subtitle">Fill out the form below to add a new patient prescription.</p>
        </div>
        <a href="index.php?page=prescriptions" class="back-btn">
                    <span class="material-symbols-rounded back-icon">arrow_back</span>
                    Back to Prescription List
            </a>
    </div>
</header>
<main class="main-w-header">
    <form action="/Oro_Vision/admin/api/add_prescription.php" class="signup-form" method="POST">
                <h4 class="section-title">Prescription Information</h4>
                
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

                <h4 class="section-title">Prescription Details</h4>

                <div class="wide-form">
                    <table class="prescription-input-table">
                        <thead>
                            <tr>
                                <th>Eye</th>
                                <th>SPH</th>
                                <th>CYL</th>
                                <th>AXIS</th>
                                <th>ADD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>OD</strong></td>
                                <td><input type="text" name="Sph_OD" required></td>
                                <td><input type="text" name="Cyl_OD" required></td>
                                <td><input type="text" name="Axis_OD" required></td>
                                <td><input type="text" name="AddPower" required></td>
                            </tr>

                            <tr>
                                <td><strong>OS</strong></td>
                                <td><input type="text" name="Sph_OS" required></td>
                                <td><input type="text" name="Cyl_OS" required></td>
                                <td><input type="text" name="Axis_OS" required></td>
                                <td><input type="text" name="AddPower_OS" placeholder="Same as OD (optional)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PD -->
                <div class="form-field">
                    <label>Pupillary Distance (PD)</label>
                    <input type="text" name="pd" required>
                </div>

                <!-- Prism -->
                <div class="form-field">
                    <label>Prism</label>
                    <input type="text" name="Prism" placeholder="Optional">
                </div>

                <!-- Lens Type -->
                <div class="form-field">
                    <label>Lens Type</label>
                    <select name="LensType" required>
                        <option value="">Select lens type...</option>
                        <option value="Single">Single</option>
                        <option value="Bifocal">Bifocal</option>
                        <option value="Progressive">Progressive</option>
                        <option value="Reading">Reading</option>
                    </select>
                </div>

                <!-- Material -->
                <div class="form-field">
                    <label>Material</label>
                    <select name="Material" required>
                        <option value="">Select material...</option>
                        <option value="Plastic">Plastic</option>
                        <option value="Polycarbonate">Polycarbonate</option>
                        <option value="High-Index">High-Index</option>
                        <option value="Glass">Glass</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="form-field wide-form">
                    <label>Additional Notes</label>
                    <textarea name="Notes" placeholder="Enter any special instructions"></textarea>
                </div>

                <p class="wide-form txt-grey txt-small">NOTE: Prescription details cannot be edited once submitted. Double check before submitting.</p>


                <button type="submit" class="btn-primary-pill btn-lg wide-form">Add New Prescription</button>

            </form>
</main>