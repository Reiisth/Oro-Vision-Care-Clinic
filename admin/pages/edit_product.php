<?php
require '../config.php';

// Fetch product ID
if (!isset($_GET['id'])) {
    echo "<p>No product selected.</p>";
    exit;
}

$productID = $_GET['id'];

// Retrieve product
$stmt = $conn->prepare("
    SELECT ProductID, Brand, Description 
    FROM product 
    WHERE ProductID = ?
");
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}
?>

<div class="admin-page-container">
    <header>
        <div class="page-header-wrapper">
            <div class="page-title-wrapper">
                <h1 class="page-title">
                    Edit Product
                </h1>
                <p class="page-subtitle">
                    Update product details below.
                </p>
            </div>

            <a href="index.php?page=inventory" class="back-btn">
                <span class="material-symbols-rounded back-icon">arrow_back</span>
                Back to List
            </a>
        </div>
    </header>

    <main class="main-w-header">
        <form action="/Oro_Vision/admin/api/update_product.php" class="signup-form" method="POST">

            <input type="hidden" name="ProductID" value="<?= $product['ProductID'] ?>">

            <h4 class="section-title">Product Information</h4>

            <div class="form-field wide-form">
                <label>Brand</label>
                <input type="text" 
                       name="Brand" 
                       value="<?= htmlspecialchars($product['Brand']) ?>" 
                       required>
            </div>

            <div class="form-field wide-form">
                <label>Description</label>
                <textarea name="Description" required><?= htmlspecialchars($product['Description']) ?></textarea>
            </div>

            <button type="submit" class="btn-primary-pill">Save Changes</button>

        </form>
    </main>
</div>
