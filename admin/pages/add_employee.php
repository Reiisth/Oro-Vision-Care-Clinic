<?php 
require '../config.php';

// Proper casing for names
function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        $string
    );
}
?>

<div class="admin-page-container">
    <header>
        <div class="page-header-wrapper">
            <div class="page-title-wrapper">
                <h1 class="page-title">Add New Employee</h1>
                <p class="page-subtitle">Fill out the form below to add a new employee.</p>
            </div>

            <a href="index.php?page=employee" class="back-btn">
                <span class="material-symbols-rounded back-icon">arrow_back</span>
                Back to List
            </a>
        </div>
    </header>

    <main class="main-w-header">
        <form action="/Oro_Vision/admin/api/add_employee.php" class="signup-form" method="POST">

            <h4 class="section-title">Personal Information</h4>

            <div class="form-field">
                <label>First Name</label>
                <input type="text" name="FirstName" placeholder="Enter employee's first name" required>
            </div>

            <div class="form-field">
                <label>Last Name</label>
                <input type="text" name="LastName" placeholder="Enter employee's last name" required>
            </div>

            <h4 class="section-title">Address</h4>

            <div class="form-field">
                <label for="Province">Province</label>
                <select id="province" name="Province" required>
                    <option value="">Select Province</option>
                </select>
            </div>

            <div class="form-field">
                <label for="City">City / Municipality</label>
                <select id="city" name="City" required>
                    <option value="">Select City / Municipality</option>
                </select>
            </div>

            <div class="form-field">
                <label for="Barangay">Barangay</label>
                <select id="barangay" name="Barangay" required>
                    <option value="">Select Barangay</option>
                </select>
            </div>

            <div class="form-field">
                <label>Phone Number</label>
                <input type="text" name="Contact" placeholder="09XX XXX XXXX" required>
            </div>

            <h4 class="section-title">Employment Information</h4>

            <div class="form-field">
                <label>Role</label>
                <select name="Role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Sales Representative">Sales Representative</option>
                </select>
            </div>

            <div class="form-field">
                <label>Date Hired</label>
                <input type="date" name="DateHired" required>
            </div>

            <button type="submit" class="btn-primary-pill">Register New Employee</button>

        </form>
    </main>

    <script src="../js/address.js"></script>
</div>
