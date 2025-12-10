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
                <h1 class="page-title">Add New Product</h1>
                <p class="page-subtitle">Fill out the form below to add a new product.</p>
            </div>

            <a href="index.php?page=inventory" class="back-btn">
                <span class="material-symbols-rounded back-icon">arrow_back</span>
                Back to List
            </a>
        </div>
    </header>

    <main class="main-w-header">
        <form action="/Oro_Vision/admin/api/add_product.php" class="signup-form" method="POST">

            <h4 class="section-title">Product Information</h4>

            <div class="form-field wide-form">
                <label>Brand</label>
                <input type="text" name="Brand" placeholder="Enter product brand" required>
            </div>

            <div class="form-field wide-form">
                <label>Description</label>
                <textarea name="Description" placeholder="Enter product description" required></textarea>
            </div>

            
            <button type="submit" class="btn-primary-pill">Add New Product</button>

        </form>
    </main>
</div>
