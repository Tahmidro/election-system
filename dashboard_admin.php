<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
  <h2>Admin Dashboard</h2>
  <ul>
    <li><a href="approve_candidates.php">Approve Candidates</a></li>
    <li><a href="create_election.php">Create Election</a></li>
    <li><a href="view_results.php">View Live Results</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</body>
</html>
