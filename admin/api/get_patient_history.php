<?php
require_once "../../config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing ID"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        CONCAT(FirstName, ' ', LastName) AS Name,
        TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) AS Age,
        Sex,
        Allergies,
        Surgery,
        Injury,
        ChronicIllness,
        Medications,
    Eyedrops
    FROM patient
    WHERE PatientID = ?
");

$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());
?>