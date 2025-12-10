<?php
require_once '../../config.php';

$caseID = $_GET['caseID'] ?? null;

if (!$caseID) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT TestID, CaseID, CoverTest, MotilityTest, Perrla, CornealReflection, DiplopiaTest, Notes
    FROM test_record
    WHERE CaseID = ?
");
$stmt->bind_param("i", $caseID);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result ?: []);
