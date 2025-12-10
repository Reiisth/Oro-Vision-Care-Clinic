
<?php
session_start();
require_once '../config.php';

// Must match dashboard session structure
if(!isset($_SESSION['PatientID']) || $_SESSION['Role'] !== 'Patient') {
    header("Location: ../auth/login.php");
    exit();
}

$patientID = $_SESSION['PatientID'];

// Fetch patient details (name, age, address) for printing
$stmt = $conn->prepare("SELECT FirstName, LastName, Birthday, Province, City, Barangay, Sex, CivilStatus, Email, Contact, Occupation, Allergies, Surgery, Injury, ChronicIllness, Medications, Eyedrops FROM patient WHERE PatientID = ?");
$stmt->bind_param("s", $patientID);
$stmt->execute();
$stmt->store_result();

$patientName = "";
$patientAge = "";
$patientAddress = "";

if ($stmt->num_rows > 0) {
    $stmt->bind_result($firstName, $lastName, $birthday, $province, $city, $barangay, $sex, $civilStatus, $email, $contact, $occupation, $allergies, $surgery, $injury, $chronicIllness, $medications, $eyedrops);
    $stmt->fetch();

    // Full name
    $patientName = htmlspecialchars($firstName . " " . $lastName);

    // Age from Birthday
    if (!empty($birthday)) {
        $birthDate = new DateTime($birthday);
        $today = new DateTime('today');
        $patientAge = $birthDate->diff($today)->y;
    }

    // Full formatted address
    $patientAddress = htmlspecialchars("$barangay, $city, $province");
}

$stmt->close();?>

<?php $page_title = "Patient Records";
$custom_css = "css/record.css";
include 'patient_header.php'; ?>


  <h1>Your Record</h1>
  <div class="layout">

    <!-- PERSONAL INFO CARD -->
    <div class="card">
      <h2>Personal Information</h2>

      <div class="group"><label>Patient ID</label><div class="value"><?php echo htmlspecialchars($patientID); ?></div></div>

      <div class="two-col">
        <div class="group"><label>First Name</label><div class="value"><?php echo htmlspecialchars($firstName); ?></div></div>
        <div class="group"><label>Last Name</label><div class="value"><?php echo htmlspecialchars($lastName); ?></div></div>
      </div>

      <div class="two-col">
        <div class="group"><label>Birthday</label><div class="value"><?php echo htmlspecialchars($birthday); ?></div></div>
        <div class="group"><label>Age</label><div class="value"><?php echo htmlspecialchars($patientAge); ?></div></div>
      </div>

      <div class="two-col">
        <div class="group"><label>Sex</label><div class="value"><?php echo htmlspecialchars($sex); ?></div></div>
        <div class="group"><label>Civil Status</label><div class="value"><?php echo htmlspecialchars($civilStatus); ?></div></div>
      </div>

      <div class="group"><label>Address</label><div class="value"><?php echo htmlspecialchars($patientAddress); ?></div></div>

      <div class="two-col">
        <div class="group"><label>Email</label><div class="value"><?php echo !empty($email) ? htmlspecialchars($email) : 'None'; ?></div></div>
        <div class="group"><label>Contact</label><div class="value"><?php echo !empty($contact) ? htmlspecialchars($contact) : 'None'; ?></div></div>
      </div>

      <div class="group"><label>Occupation</label><div class="value"><?php echo !empty($occupation) ? htmlspecialchars($occupation) : 'None'; ?> </div></div>
    </div>

    <!-- MEDICAL INFO CARD -->
    <div class="card">
      <h2>Medical Information</h2>

      <div class="group"><label>Allergies</label><div class="value"><?php echo !empty($allergies) ? htmlspecialchars($allergies) : 'None'; ?></div></div>
      <div class="group"><label>Surgery</label><div class="value"><?php echo !empty($surgery) ? htmlspecialchars($surgery) : 'None'; ?></div></div>
      <div class="group"><label>Injury</label><div class="value"><?php echo !empty($injury) ? htmlspecialchars($injury) : 'None'; ?></div></div>
      <div class="group"><label>Chronic Illness</label><div class="value"><?php echo !empty($chronicIllness) ? htmlspecialchars($chronicIllness) : 'None'; ?></div></div>
      <div class="group"><label>Medications</label><div class="value"><?php echo !empty($medications) ? htmlspecialchars($medications) : 'None'; ?></div></div>
      <div class="group"><label>Eyedrops</label><div class="value"><?php echo !empty($eyedrops) ? htmlspecialchars($eyedrops) : 'None'; ?></div></div>

    </div>

  </div>


<?php include 'footer.php'; ?>