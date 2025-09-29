

<?php
session_start();
include "config.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $role     = $_POST["role"];
    $nid      = trim($_POST["nid"]);

    if (empty($name) || empty($email) || empty($password) || empty($role) || empty($nid)) {
        $error = "All fields  are required.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password_hash, role, nid) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $nid);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            if ($role === "voter") {
                $stmt2 = $conn->prepare("INSERT INTO voters (user_id) VALUES (?)");
                $stmt2->bind_param("i", $user_id);
                $stmt2->execute();
                $_SESSION['success'] = "User registered successfully! Please log in.";

            } elseif ($role === "candidate") {
                $stmt2 = $conn->prepare("INSERT INTO candidates (user_id) VALUES (?)");
                $stmt2->bind_param("i", $user_id);
                $stmt2->execute();
                $_SESSION['success'] = "Candidate registered. Waiting for admin approval.";
            }
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>


    <form method="POST" onsubmit="return validateEmail() && validateNID()">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="voter">Voter</option>
            <option value="candidate">Candidate</option>
        </select><br><br>

        <label>NID:</label><br>
        <input type="text" name="nid" id="nid" maxlength="13" minlength="13" pattern="\d{13}" title="NID must be exactly 13 digits" required ><br><br>

        <button type="submit">Register</button>
    </form>
  

  <script>
    function validateEmail() {
      const email = document.getElementById("email").value.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
      }

      if (!email.toLowerCase().endsWith(".com")) {
        alert("Email must end with '.com'");
        return false;
      }

      return true; 
    }

  function validateNID() {
  const nid = document.getElementById("nid").value.trim();

  if (!/^\d{13}$/.test(nid)) {
    alert("NID must be exactly 13 digits.");
    return false;
  }

  return true;
}
 </script>

    <p><a href="login.php">Already have an account? Login here</a></p>
</body>
</html>

