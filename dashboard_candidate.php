<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php"); exit;
}
$user_id = $_SESSION['user_id'];

// Get candidate status
$stmt = $conn->prepare("SELECT status, party, manifesto FROM candidates WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if($row==null){
  $status="deleted";
  $party = "";
  $manifesto = "";
} else {
  $status = $row['status'];
  $party = $row['party'] ?? "";
  $manifesto = $row['manifesto'] ?? "";
}

// Handle party & manifesto update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $status === 'approved') {
    $party = trim($_POST['party']);
    $manifesto = trim($_POST['manifesto']);

    $stmt = $conn->prepare("UPDATE candidates SET party=?, manifesto=? WHERE user_id=?");
    $stmt->bind_param("ssi", $party, $manifesto, $user_id);
    if ($stmt->execute()) {
        $success = "Details updated successfully!";
    } else {
        $error = "Error updating details.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Candidate Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #2c3e50, #34495e);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .dashboard {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.3);
      width: 500px;
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }
    h2 {
      margin-bottom: 15px;
      color: #2c3e50;
    }
    p {
      font-size: 16px;
      color: #333;
      margin: 12px 0;
    }
    a, button {
      display: inline-block;
      padding: 10px 20px;
      margin-top: 10px;
      text-decoration: none;
      color: white;
      background: #2c3e50;
      border-radius: 6px;
      transition: background 0.3s;
      border: none;
      cursor: pointer;
    }
    a:hover, button:hover {
      background: #1a252f;
    }
    .danger {
      background: #e74c3c;
    }
    .danger:hover {
      background: #c0392b;
    }
    .success {
      color: green;
      font-weight: bold;
    }
    .error {
      color: red;
      font-weight: bold;
    }
    input, textarea {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    textarea {
      height: 100px;
      resize: none;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Candidate Dashboard</h2>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <?php if($status === 'approved'): ?>
      <form method="POST">
        <h3>Update Party & Manifesto</h3>
        <input type="text" name="party" placeholder="Enter Party Name" value="<?php echo htmlspecialchars($party); ?>" required>
        <textarea name="manifesto" placeholder="Write your manifesto here..." required><?php echo htmlspecialchars($manifesto); ?></textarea>
        <button type="submit">Save Details</button>
      </form>
      <p><a href="view_results.php">View Results</a></p>

    <?php elseif($status === 'deleted'): ?>
      <p style="color:red;">You are not qualified as a candidate.</p>
      <a href="delete_account.php" class="danger" 
         onclick="return confirm('Are you sure you want to delete your account?');">Delete your account</a>

    <?php else: ?>
      <p style="color:orange;">Your candidacy is pending admin approval.</p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
  </div>
</body>
</html>
