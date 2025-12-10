<?php
// Fetch products including Quantity
$sql = "
    SELECT 
        ProductID,
        Brand,
        Description,
        Quantity
    FROM product
    ORDER BY Quantity DESC,
    Brand ASC
";

$result = $conn->query($sql);
?>
<main class="main-content-wrapper">
    <div class="thick-header">
        <div class="main-content-header-container-thick">
            <div class="page-title-wrapper">
                <h2 class="page-title">Inventory</h2>
                <p class="page-subtitle">Manage your products and stock.</p>
            </div>

           <div class="page-actions">
                <?php 
                if (isset($_GET['deleted']) || isset($_GET['added']) || isset($_GET['updated'])):

                    $message =
                        isset($_GET['deleted']) ? "Product record deleted successfully." :
                        (isset($_GET['updated']) ? "Product record updated successfully." :
                        "New product added successfully.");
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
                    placeholder="Search Product ID or Name..."
                >
                <span class="material-symbols-rounded search-icon">search</span>
            </div>

            <a href="index.php?page=add_product" class="btn btn-sm btn-primary btn-pill btn-shine">
                <span class="material-symbols-rounded">add</span>
                New Product
            </a>
        </div>
    </div>

<div class="main-with-thick-header">
    <div class="table-wrapper">
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th class="centered">Quantity</th>
                    <th class="centered">Action</th>
                </tr>
            </thead>

            <tbody id="productTableBody">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="gold-text txt-bold txt-medium"><?= $row['ProductID'] ?></span></td>
                    <td><span class="name-main"><?= $row['Brand'] ?></span></td>
                    <td><?= $row['Description'] ?></td>

                    <!-- QUANTITY -->
                    <td class="centered quantity-cell">
                        <div class="qty-wrapper">
                            <button class="qty-btn minus" data-id="<?= $row['ProductID'] ?>">−</button>
                            <input type="number" 
                                class="qty-input" 
                                value="<?= $row['Quantity'] ?>" 
                                min="0" 
                                data-id="<?= $row['ProductID'] ?>">
                            <button class="qty-btn plus" data-id="<?= $row['ProductID'] ?>">+</button>
                        </div>
                    </td>


                    <!-- ACTIONS -->
                    <td class="action-icons">
                        <a href="index.php?page=edit_product&id=<?= $row['ProductID'] ?>" 
                           class="icon-btn edit-icon" title="Edit">
                            <span class="material-symbols-rounded">edit</span>
                        </a>

                        <button class="icon-btn delete-icon" 
                                onclick="deleteProduct('<?= $row['ProductID'] ?>')" 
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
const rows = document.querySelectorAll("#productTableBody tr");

searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase().trim();
    let visibleCount = 0; // ✅ MUST be added

    rows.forEach(row => {
        const id = row.children[0].innerText.toLowerCase();
        const name = row.children[1].innerText.toLowerCase();
        const desc = row.children[2].innerText.toLowerCase();

        const match =
            id.includes(query) ||
            name.includes(query) ||
            desc.includes(query);

        if (match) {
            row.style.display = "";
            visibleCount++; // ✅ Count visible rows
        } else {
            row.style.display = "none";
        }
    });

    const noDataRow = document.getElementById("noDataRow");

    if (visibleCount === 0) {
        if (!noDataRow) {
            const tr = document.createElement("tr");
            tr.id = "noDataRow";
            tr.innerHTML = `
                <td colspan="5" style="text-align:center; padding:20px; color:var(--muted);">
                    No matching records found.
                </td>`;
            document.querySelector(".patient-table tbody").appendChild(tr);
        }
    } else {
        if (noDataRow) noDataRow.remove();
    }
});


// Delete function
function deleteProduct(id) {
    if (!confirm("Are you sure you want to delete this product? This cannot be undone.")) return;
    window.location.href = `/Oro_Vision/admin/api/delete_product.php?id=${id}`;
}

// Listen for clicks on + and -
document.querySelectorAll(".qty-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        const input = btn.parentElement.querySelector(".qty-input");
        let value = parseInt(input.value);

        if (btn.classList.contains("plus")) value++;
        if (btn.classList.contains("minus") && value > 0) value--;

        input.value = value;
        updateQuantity(id, value);
    });
});

// Listen for manual typing in quantity field
document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("change", () => {
        let value = parseInt(input.value);
        if (value < 0 || isNaN(value)) value = 0;
        input.value = value;

        updateQuantity(input.dataset.id, value);
    });
});

// AJAX update function
function updateQuantity(productID, quantity) {
    fetch("/Oro_Vision/admin/api/update_quantity.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `ProductID=${productID}&Quantity=${quantity}`
    })
    .then(res => res.text())
    .then(data => {
        console.log("Updated:", data);
    })
    .catch(err => console.error(err));
}


</script>
