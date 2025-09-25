<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'voter') {
    header("Location: login.php"); exit;
}
?>
<!DOCTYPE html>
<html><head><title>Voter</title></head><body>
  <h2>Welcome Voter</h2>
  <p><a href="face_auth.php">Verify Face & Vote</a></p>
  <p><a href="view_results.php">View Results</a></p>
  <p><a href="logout.php">Logout</a></p>
</body></html>
