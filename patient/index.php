<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/Oro_Vision/config.php';


if(!isset($_SESSION['PatientID']) || $_SESSION['Role'] !== 'Patient') {
    header("Location: ../auth/login.php");
    exit();
}

$patientID = $_SESSION['PatientID'];

$stmt = $conn->prepare("SELECT FirstName FROM patient WHERE PatientID = ?");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$stmt->store_result();

$firstName = "Patient";

if ($stmt->num_rows > 0) {
    $stmt->bind_result($firstName);
    $stmt->fetch();
}

$stmt->close();
$conn->close();

$page_title = "Patient Dashboard";
$custom_css = "css/patient_dashboard.css";
include $_SERVER['DOCUMENT_ROOT'] . '/Oro_Vision/header.php';

?>

    <div class="page">
        <header role="banner">
            <div class="hdr-container">
                <h2 class="trns-gradient-logo">Oro Vision Care Clinic</h2>
                
                <p class="dash-title">Patient Dashboard</p>
                <a href="../auth/logout.php" class="btn-logout" aria-label="Logout">
                    <span style="font-weight:700; color:var(--text-darker)">Logout</span>
                    <svg class="logout-icon" viewBox="0 0 24 24" fill="none"><path d="M15 7l5 5-5 5M20 12H9M11 19H6a2 2 0 01-2-2V7a2 2 0 012-2h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </header>

        <main class="container">

        <!-- Welcome / Hero -->
        <section class="hero" id="dashboard-heading">
            <p class="welcome-sub">Welcome back,</p>
            <h1 class="welcome-main">It's great to see you <?php echo htmlspecialchars($firstName); ?>!</h1>
        </section>

        <!-- Cards -->
        <section class="cards-grid" aria-label="Patient actions">
            <!-- Card 1: Prescriptions -->
            <a href="prescription.php" class="card-link">
            <article class="card" role="article" aria-labelledby="prescriptions-title">
            <div class="icon-pill" aria-hidden="true">
                <!-- File/document icon (white) -->
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                    <path d="m678-134 46-46-64-64-46 46q-14 14-14 32t14 32q14 14 32 14t32-14Zm102-102 46-46q14-14 14-32t-14-32q-14-14-32-14t-32 14l-46 46 64 64ZM735-77q-37 37-89 37t-89-37q-37-37-37-89t37-89l148-148q37-37 89-37t89 37q37 37 37 89t-37 89L735-77ZM200-200v-560 560Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v245q-20-5-40-5t-40 3v-243H200v560h243q-3 20-3 40t5 40H200Zm280-670q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM280-600v-80h400v80H280Zm0 160v-80h400v34q-8 5-15.5 11.5T649-460l-20 20H280Zm0 160v-80h269l-49 49q-8 8-14.5 15.5T474-280H280Z"/>
                </svg>
            </div>
            <h3 id="prescriptions-title">See Prescriptions</h3>
            <p class="description">View and manage your current prescriptions and medication history.</p>
            <div class="spacer" aria-hidden="true"></div>
            <div class="accent-line" aria-hidden="true"></div>
            </article>
            </a>

            <!-- Card 2: Record -->
            <a href="record.php" class="card-link">
            <article class="card" role="article" aria-labelledby="records-title">
            <div class="icon-pill" aria-hidden="true">
                <!-- Record icon -->
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                    <path d="M280-320v40q0 17 11.5 28.5T320-240q17 0 28.5-11.5T360-280v-40h40q17 0 28.5-11.5T440-360q0-17-11.5-28.5T400-400h-40v-40q0-17-11.5-28.5T320-480q-17 0-28.5 11.5T280-440v40h-40q-17 0-28.5 11.5T200-360q0 17 11.5 28.5T240-320h40Zm270-60h180q13 0 21.5-8.5T760-410q0-13-8.5-21.5T730-440H550q-13 0-21.5 8.5T520-410q0 13 8.5 21.5T550-380Zm0 120h100q13 0 21.5-8.5T680-290q0-13-8.5-21.5T650-320H550q-13 0-21.5 8.5T520-290q0 13 8.5 21.5T550-260ZM160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm0-80h640v-440H600q0 33-23.5 56.5T520-520h-80q-33 0-56.5-23.5T360-600H160v440Zm280-440h80v-200h-80v200Zm40 220Z"/>
                </svg>
            </div>
            <h3 id="records-title">See Records</h3>
            <p class="description">Access your medical records and vision assessment documents.</p>
            <div class="spacer" aria-hidden="true"></div>
            <div class="accent-line" aria-hidden="true"></div>
            </article>
            </a>

            <!-- Card 3: Invoices -->
            <a href="invoice.php" class="card-link">
            <article class="card" role="article" aria-labelledby="invoices-title">
            <div class="icon-pill" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-80q-50 0-85-35t-35-85v-80q0-17 11.5-28.5T160-320h80v-536q0-7 6-9.5t11 2.5l29 29q6 6 14 6t14-6l32-32q6-6 14-6t14 6l32 32q6 6 14 6t14-6l32-32q6-6 14-6t14 6l32 32q6 6 14 6t14-6l32-32q6-6 14-6t14 6l32 32q6 6 14 6t14-6l32-32q6-6 14-6t14 6l32 32q6 6 14 6t14-6l29-29q5-5 11-2.5t6 9.5v656q0 50-35 85t-85 35H240Zm480-80q17 0 28.5-11.5T760-200v-560H320v440h320q17 0 28.5 11.5T680-280v80q0 17 11.5 28.5T720-160ZM400-680h160q17 0 28.5 11.5T600-640q0 17-11.5 28.5T560-600H400q-17 0-28.5-11.5T360-640q0-17 11.5-28.5T400-680Zm0 120h160q17 0 28.5 11.5T600-520q0 17-11.5 28.5T560-480H400q-17 0-28.5-11.5T360-520q0-17 11.5-28.5T400-560Zm280-40q-17 0-28.5-11.5T640-640q0-17 11.5-28.5T680-680q17 0 28.5 11.5T720-640q0 17-11.5 28.5T680-600Zm0 120q-17 0-28.5-11.5T640-520q0-17 11.5-28.5T680-560q17 0 28.5 11.5T720-520q0 17-11.5 28.5T680-480ZM240-160h360v-80H200v40q0 17 11.5 28.5T240-160Zm-40 0v-80 80Z"/></svg>
            </div>

            <h3 id="invoices-title">See Invoices</h3>
            <p class="description">Review your billing statements and payment history for services.</p>
            <div class="spacer" aria-hidden="true"></div>
            <div class="accent-line" aria-hidden="true"></div>
            </article>
            </a>
        </section>
    </main>
    </div>
    </body>
</html>
