<?php
$current = $_GET['page'] ?? "dashboard";
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="/Oro_Vision/images/OVCC_Logo.png" alt="Oro Vision Logo" class="logo-img">
        </div>
        <div class="sidebar-titles">
            <h1 class="sidebar-title txt-gold-gradient">Oro Vision</h1>
            <h1 class="sidebar-subtitle">Admin Dashboard</h1>
        </div>
    </div>
    <ul class="sidebar-menu">

        <!-- Dashboard -->
        <li class="menu-item <?= $current === 'dashboard' ? 'active' : '' ?>">
            <a href="index.php?page=dashboard">
                <span class="material-symbols-rounded menu-icon">dashboard</span>
                Dashboard
            </a>
        </li>


        <!-- Patients Group -->
        <?php $patients_open = strpos($current, 'patients') !== false; ?>
        <li class="menu-group">
            <button class="group-toggle <?= $patients_open ? 'open' : '' ?>">
                <span class="material-symbols-rounded menu-icon">group</span>
                Patients
                <span class="material-symbols-rounded group-arrow <?= $patients_open ? 'open' : 'closed' ?>">chevron_right</span>
            </button>

            <ul class="submenu <?= $patients_open ? 'show' : '' ?>">
                <li class="<?= $current === 'patients/patient_list' ? 'active' : '' ?>">
                    <a href="index.php?page=patients/patient_list">Patient List</a>
                </li>

                <li class="<?= $current === 'patients/records' ? 'active' : '' ?>">
                    <a href="index.php?page=patients/records">Records & Tests</a>
                </li>
            </ul>
        </li>



        <!-- Prescriptions -->
        <li class="menu-item <?= $current === 'prescriptions' ? 'active' : '' ?>">
            <a href="index.php?page=prescriptions">
                <span class="material-symbols-rounded menu-icon">clinical_notes</span>
                Prescriptions
            </a>
        </li>

        <li class="menu-item <?= $current === 'employee' ? 'active' : '' ?>">
            <a href="index.php?page=employee">
                <span class="material-symbols-rounded menu-icon">badge</span>
                Employees
            </a>
        </li>

         <li class="menu-item <?= $current === 'inventory' ? 'active' : '' ?>">
            <a href="index.php?page=inventory">
                <span class="material-symbols-rounded menu-icon">inventory_2</span>
                Inventory
            </a>
        </li>

        <li class="menu-item <?= $current === 'invoices' ? 'active' : '' ?>">
            <a href="index.php?page=invoices">
                <span class="material-symbols-rounded menu-icon">receipt_long</span>
                Invoices
            </a>
        </li>



    </ul>
</aside>
<script src="../assets/js/sidebar.js"></script>