

<?php
session_start();
include "config.php"; // must contain $conn = new mysqli(...)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $role     = $_POST["role"];
    $nid      = trim($_POST["nid"]);

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields except NID are required.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $sql = "INSERT INTO users (name, email, password_hash, role, nid) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $nid);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            // Insert voter or candidate profile
            if ($role === "voter") {
                $stmt2 = $conn->prepare("INSERT INTO voters (user_id) VALUES (?)");
                $stmt2->bind_param("i", $user_id);
                $stmt2->execute();
                $success = "user registered successfully!";
            } elseif ($role === "candidate") {
                $stmt2 = $conn->prepare("INSERT INTO candidates (user_id) VALUES (?)");
                $stmt2->bind_param("i", $user_id);
                $stmt2->execute();
                $success = "Candidate registered. Waiting for admin approval.";
            }
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
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="voter">Voter</option>
            <option value="candidate">Candidate</option>
        </select><br><br>

        <label>NID:</label><br>
        <input type="text" name="nid"><br><br>

        <button type="submit">Register</button>
    </form>

    <p><a href="login.php">Already have an account? Login here</a></p>
</body>
</html>
