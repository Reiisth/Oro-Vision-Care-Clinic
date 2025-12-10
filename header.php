<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $page_title ?? 'Oro Vision Care'; ?></title>
        <link rel="icon" href="/Oro_Vision/images/favicon.png" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

        <link rel="stylesheet" href="/Oro_Vision/css/global.css">
        <?php if (isset($custom_css)): ?>
            <link rel="stylesheet" href="<?php echo $custom_css; ?>">
        <?php endif; ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    </head>
    <body>

<!-- $page_title = "Dashboard";
$custom_css = "css/dashboard.css";
include 'header.php'; -->