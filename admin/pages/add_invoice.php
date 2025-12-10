<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Oro_Vision/config.php";

// Fetch patients for dropdown
$patients = $conn->query("
    SELECT PatientID, CONCAT(FirstName, ' ', LastName) AS FullName
    FROM patient
    ORDER BY FirstName
");
?>
<main class="main-content-wrapper">
    <div class="invoice-wrapper">

        <h2>Create New Invoice</h2>

        <form action="/Oro_Vision/admin/api/save_invoice.php" method="POST" id="invoiceForm">

            <!-- Patient Selection with TomSelect -->
            <label class="form-label">Select Patient</label>
            <select id="patientSelect" name="PatientID" required>
                <option value="">Search patient...</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['PatientID'] ?>">
                        <?= $p['PatientID'] ?> – <?= $p['FullName'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <br><br>

            <!-- Invoice Dates -->
            <div class="date-fields">
                <div class="date-field">
                <label>Date</label>
                <input type="date" name="InvoiceDate" required>
                </div>
                
                <div class="date-field">
                <label>Due Date</label>
                <input type="date" name="DueDate" required>
                </div>
            </div>

            <h3>Invoice Items</h3>

            <!-- ITEMS TABLE -->
            <table class="invoice-table" id="itemsTable">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="itemsBody">
                    <!-- Dynamic rows go here -->
                </tbody>
            </table>

            <label class="form-label">Status</label>
            <select name="Status" id="statusSelect" required>
                <option value="Unpaid">Unpaid</option>
                <option value="Paid">Paid</option>
            </select>

            <button type="button" id="addItemBtn" class="btn btn-primary btn-shine" style="margin-top:10px;">
                Add Item
            </button>


            <div class="total-wrapper">
                <div class="total-box">
                    <p class="total">
                        <span>Total</span>
                        <span id="grandTotal">₱0.00</span>
                    </p>
                </div>
            </div>

            <br>

            <div class="form-actions">
                <a href="index.php?page=invoices" class="btn-back">
                    <span class="material-symbols-rounded">arrow_back</span>
                    Back
                </a>

            <button type="submit" class="btn btn-primary btn-pill save-btn">Save Invoice</button>
            </div>
        </form>
    </div>

    <script>
    new TomSelect("#patientSelect", {
        create: false,
        sortField: {field: "text", direction: "asc"}
    });
    
    </script>
    <script>
        let itemsBody = document.getElementById("itemsBody");
        let addItemBtn = document.getElementById("addItemBtn");
        let grandTotalDisplay = document.getElementById("grandTotal");

        function addRow() {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td><input type="text" name="Description[]" required></td>
                <td><input type="number" name="Quantity[]" class="qty" min="1" value="1" required></td>
                <td><input type="number" name="UnitPrice[]" class="price" step="0.01" min="0" required></td>
                <td class="lineTotal">₱0.00</td>
                <td><button type="button" class="remove-btn">✖</button></td>
            `;

            itemsBody.appendChild(row);
            updateEvents();
        }

        function updateEvents() {
            document.querySelectorAll(".qty, .price").forEach(input => {
                input.addEventListener("input", calculateTotals);
            });

            document.querySelectorAll(".remove-btn").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    e.target.closest("tr").remove();
                    calculateTotals();
                });
            });
        }

        function calculateTotals() {
            let grandTotal = 0;

            document.querySelectorAll("#itemsBody tr").forEach(row => {
                let qty = row.querySelector(".qty").value || 0;
                let price = row.querySelector(".price").value || 0;
                let total = qty * price;

                row.querySelector(".lineTotal").innerHTML = "₱" + total.toFixed(2);
                grandTotal += total;
            });

            grandTotalDisplay.innerHTML = "₱" + grandTotal.toFixed(2);
        }

        addItemBtn.addEventListener("click", addRow);

        // Add initial row
        addRow();
    </script>

</main>
