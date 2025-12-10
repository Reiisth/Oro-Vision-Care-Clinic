<?php
// Fetch employees
$sql = "
    SELECT 
        EmployeeID,
        FirstName,
        LastName,
        CONCAT(FirstName, ' ', LastName) AS EmployeeName,
        Contact,
        CONCAT(Barangay, ', ', City, ', ', Province) AS Address,
        Role,
        DateHired
    FROM employee
    ORDER BY DateHired DESC
";

$result = $conn->query($sql);
?>
<main class="main-content-wrapper">
    <div class="thick-header">
        <div class="main-content-header-container-thick">
            <div class="page-title-wrapper">
                <h2 class="page-title">Employee Management</h2>
                <p class="page-subtitle">Manage and review employee records.</p>
            </div>

            <div class="page-actions">
                <?php 
                if (isset($_GET['deleted']) || isset($_GET['added']) || isset($_GET['updated'])): 
                    
                    $message =
                        isset($_GET['deleted']) ? "Employee record deleted successfully." :
                        (isset($_GET['updated']) ? "Employee record updated successfully." :
                        "New employee added successfully.");
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

        <div class="search-and-add-wrapper">
            <div class="search-container">
                <input 
                    type="text" 
                    id="search" 
                    class="search-input" 
                    placeholder="Search Employee ID or Name..."
                >
                <span class="material-symbols-rounded search-icon">search</span>
            </div>

            <a href="index.php?page=add_employee" class="btn btn-sm btn-primary btn-pill btn-shine">
                <span class="material-symbols-rounded">add</span>
                New Employee
            </a>
        </div>
    </div>

<div class="main-with-thick-header">
    <div class="table-wrapper">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Address</th>
                    <th>Date Hired</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="employeeTableBody">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="gold-text txt-bold txt-medium"><?= $row['EmployeeID'] ?></span></td>
                    <td><span class="name-main"><?= $row['EmployeeName'] ?></span></td>
                    <td class="contact-line"> <span class="material-symbols-rounded contact-icon">call</span> <?= $row['Contact'] ?></td>
                    <td><?= $row['Role'] ?></td>
                    <td><?= $row['Address'] ?></td>
                    <td><?= $row['DateHired'] ?></td>

                    <!-- ACTIONS -->
                    <td class="action-icons">
                        <a href="index.php?page=edit_employee&id=<?= $row['EmployeeID'] ?>" 
                           class="icon-btn edit-icon" title="Edit">
                            <span class="material-symbols-rounded">edit</span>
                        </a>

                        <button class="icon-btn delete-icon" 
                                onclick="deleteEmployee('<?= $row['EmployeeID'] ?>')" 
                                title="Delete">
                            <span class="material-symbols-rounded icon-sm">delete</span>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>

</main>

<script>
// REAL-TIME FILTERING
const searchInput = document.getElementById("search");
const rows = document.querySelectorAll("#employeeTableBody tr");

searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase().trim();
    let visibleCount = 0; // ✅ MUST be defined

    rows.forEach(row => {
        const id = row.children[0].innerText.toLowerCase();
        const name = row.children[1].innerText.toLowerCase();

        const match = id.includes(query) || name.includes(query);

        if (match) {
            row.style.display = "";
            visibleCount++;             // ✅ Count visible rows
        } else {
            row.style.display = "none";
        }
    });

    const noDataRow = document.getElementById("noDataRow");

    if (visibleCount === 0) {
        // Create the row if it doesn't exist
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noDataRow";
            tr.innerHTML = `
                <td colspan="7" style="text-align:center; padding:20px; color:var(--muted);">
                    No matching records found.
                </td>
            `;
            document.querySelector(".patient-table tbody").appendChild(tr);
        }
    } else {
        // Remove message if results exist
        if (noDataRow) noDataRow.remove();
    }
});


// Delete function
function deleteEmployee(id) {
    if (!confirm("Are you sure you want to delete this employee? This cannot be undone.")) return;
    window.location.href = `/Oro_Vision/admin/api/delete_employee.php?id=${id}`;
}
</script>
