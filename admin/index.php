<?php
session_start();
require_once "../config.php";

// Prevent access if not admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Determine which page to load
$page = $_GET['page'] ?? "dashboard";
$page_path = "pages/$page.php";

// Fallback if page doesn't exist
if (!file_exists($page_path)) {
    $page_path = "pages/dashboard.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Oro Vision Admin - <?= ucfirst(explode('/', $page)[0]) ?></title>
    <link rel="icon" href="/Oro_Vision/images/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/Oro_Vision/css/global.css">
    <link rel="stylesheet" href="layout/css/sidebar.css">    
    
    
    <?php
    $page_css = "pages/$page.css";
    if (file_exists($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>


</head>

<body>
    <div class="page-bg">
        <div class="page-bg-extra"></div>
    </div>

    <?php include "layout/sidebar.php"; ?>

    <main class="admin-main">

        <?php include $page_path; ?>
    </main>

    <script src="js/sidebar.js"></script>
    <script>
        new TomSelect("#patientID", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Search for patient...",
        });
        </script>
</body>
</html>
