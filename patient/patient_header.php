<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $page_title ?? 'Oro Vision Care'; ?></title>
        <link rel="icon" href="/Oro_Vision/images/favicon.png" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
        <link rel="stylesheet" href="/Oro_Vision/css/global.css">
        <link rel="stylesheet" href="/Oro_Vision/patient/css/header.css">
        <?php if (isset($custom_css)): ?>
            <link rel="stylesheet" href="<?php echo $custom_css; ?>">
        <?php endif; ?>
        
    </head>
    <body>
        <header>
            <div class="hdr-container">
                <a href="index.php" class="back-btn"> 
                    <svg class="back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m313-440 196 196q12 12 11.5 28T508-188q-12 11-28 11.5T452-188L188-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l264-264q11-11 27.5-11t28.5 11q12 12 12 28.5T508-715L313-520h447q17 0 28.5 11.5T800-480q0 17-11.5 28.5T760-440H313Z"/></svg>
                    Back to Dashboard
                </a>
                <h2 class="hdr-title">Oro Vision Care Clinic</h2>
                <h3 class="hdr-subtitle">Patient Portal</h3>
            </div>
        </header>
        <main class="container">