<?php
session_start();
include "config.php";

// Ensure only admin can approve
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['candidate_id'])) {
    $id = intval($_GET['candidate_id']); // ensure it's safe
    $stmt = $conn->prepare("UPDATE candidates SET status= 'approved' WHERE candidate_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: approve_candidates.php");
exit;
?>
