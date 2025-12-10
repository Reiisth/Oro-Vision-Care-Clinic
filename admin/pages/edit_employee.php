<?php 
require '../config.php';

function proper_case($string) {
    return preg_replace_callback(
        "/\\b[\\w'-]+\\b/",
        function($word) {
            return ucfirst(strtolower($word[0]));
        },
        $string
    );
}

// ---------------------------
// CHECK IF ID PROVIDED
// ---------------------------
if (!isset($_GET['id'])) {
    echo "<p>No employee selected.</p>";
    exit;
}

$employeeID = $_GET['id'];

// ---------------------------
// FETCH EMPLOYEE DATA
// ---------------------------
$stmt = $conn->prepare("
    SELECT EmployeeID, FirstName, LastName, Contact,
           Province, City, Barangay,
           Role, DateHired
    FROM employee
    WHERE EmployeeID = ?
");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$emp = $result->fetch_assoc();

if (!$emp) {
    echo "<p>Employee not found.</p>";
    exit;
}

?>

<div class="admin-page-container">

    <header>
        <div class="page-header-wrapper">
            <div class="page-title-wrapper">
                <h1 class="page-title">
                    Edit Employee: 
                    <span class="gold-text"><?= htmlspecialchars($emp['FirstName'] . " " . $emp['LastName']) ?></span>
                </h1>
                <p class="page-subtitle">Update employee information below.</p>
            </div>

            <a href="index.php?page=employee" class="back-btn">
                <span class="material-symbols-rounded back-icon">arrow_back</span>
                Back to List
            </a>
        </div>
    </header>

    <main class="main-w-header">

        <form action="/Oro_Vision/admin/api/update_employee.php" class="signup-form" method="POST">

            <!-- Hidden ID -->
            <input type="hidden" name="EmployeeID" value="<?= $emp['EmployeeID'] ?>">

            <h4 class="section-title">Personal Information</h4>

            <div class="form-field">
                <label>First Name</label>
                <input type="text" name="FirstName" value="<?= htmlspecialchars($emp['FirstName']) ?>" required>
            </div>

            <div class="form-field">
                <label>Last Name</label>
                <input type="text" name="LastName" value="<?= htmlspecialchars($emp['LastName']) ?>" required>
            </div>

            <h4 class="section-title">Address</h4>

            <div class="form-field">
                <label for="Province">Province</label>
                <select id="province" name="Province" required data-current="<?= $emp['Province'] ?>"></select>
            </div>

            <div class="form-field">
                <label for="City">City / Municipality</label>
                <select id="city" name="City" required data-current="<?= $emp['City'] ?>"></select>
            </div>

            <div class="form-field">
                <label for="Barangay">Barangay</label>
                <select id="barangay" name="Barangay" required data-current="<?= $emp['Barangay'] ?>"></select>
            </div>

            <div class="form-field">
                <label>Phone Number</label>
                <input type="text" name="Contact" value="<?= htmlspecialchars($emp['Contact']) ?>" required>
            </div>

            <h4 class="section-title">Employment Information</h4>

            <div class="form-field">
                <label>Role</label>
                <select name="Role" required>
                    <option value="Doctor" <?= $emp['Role'] === "Doctor" ? "selected" : "" ?>>Doctor</option>
                    <option value="Sales Representative" <?= $emp['Role'] === "Sales Representative" ? "selected" : "" ?>>Sales Representative</option>
                </select>
            </div>

            <div class="form-field">
                <label>Date Hired</label>
                <input type="date" name="DateHired" value="<?= $emp['DateHired'] ?>" required>
            </div>

            <button type="submit" class="btn-lg btn-primary-pill wide-form">Save Changes</button>

        </form>

    </main>

    <script src="../js/address.js"></script>
</div>
