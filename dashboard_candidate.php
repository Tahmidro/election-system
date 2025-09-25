<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php"); exit;
}
$user_id = $_SESSION['user_id'];
// get candidate status
$stmt = $conn->prepare("SELECT status FROM candidates WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$status = $row ? $row['status'] : 'not registered';

?>
<!DOCTYPE html>
<html><head><title>Candidate Dashboard</title></head><body>
  <h2>Candidate Dashboard</h2>
  <p>Status: <?php echo htmlspecialchars($status); ?></p>
  <?php if($status === 'approved'): ?>
    <p><a href="view_results.php">View Results</a></p>
  <?php else: ?>
    <p>Your candidacy is pending admin approval.</p>
  <?php endif; ?>
  <p><a href="logout.php">Logout</a></p>
</body></html>
