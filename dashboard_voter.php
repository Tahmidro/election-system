<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'voter') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voter Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        h2 {
            color: #444;
            margin-bottom: 30px;
        }
        .button {
            display: block;
            width: 220px;
            text-align: center;
            padding: 12px;
            margin: 12px 0;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .logout {
            background-color: #dc3545;
        }
        .logout:hover {
            background-color: #c82333;
        }
        .container {
            background-color: #fff;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome Voter</h2>
        <a href="face_auth.php" class="button">Verify Face & Vote</a>
        <a href="view_results.php" class="button">View Results</a>
        <a href="logout.php" class="button logout">Logout</a>
    </div>
</body>
</html>

