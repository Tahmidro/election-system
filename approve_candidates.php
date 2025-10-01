<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT u.name,u.email,u.nid,c.status,c.candidate_id FROM candidates c JOIN users u ON c.user_id = u.user_id WHERE c.status = 'pending';";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approve Candidates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #444;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a.button {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            margin: 2px;
        }
        a.approve {
            background-color: #28a745;
        }
        a.approve:hover {
            background-color: #218838;
        }
        a.reject {
            background-color: #dc3545;
        }
        a.reject:hover {
            background-color: #c82333;
        }
        .approved {
            color: green;
            font-weight: bold;
        }
        .btn-back {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Approve Candidates</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>NID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['nid']); ?></td>
                    <td class="<?php echo $row['status']=='approved' ? 'approved' : ''; ?>">
                        <?php echo ($row['status'] == 'approved') ? "Approved" : "Pending"; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] !='approved') { ?>
                            <a href="approve_candidate.php?candidate_id=<?php echo $row['candidate_id']; ?>" class="button approve">Approve</a>
                            <a href="reject_candidate.php?candidate_id=<?php echo $row['candidate_id']; ?>" class="button reject">Reject</a>
                        <?php } else { ?>
                            ✅ Already Approved
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No pending candidates.</td>
            </tr>
        <?php endif; ?>
    </table>
    <a href="dashboard_admin.php" class="btn-back">⬅ Back to Dashboard</a>
</body>
</html>


