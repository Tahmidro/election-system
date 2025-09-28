<?php
session_start();
include "config.php";

// Ensure only admin can approve
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // ensure it's safe
    $stmt = $conn->prepare("delete from users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

header("Location: approve_candidates.php");
exit;
?>
