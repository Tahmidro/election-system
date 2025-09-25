<?php
session_start();
include "config.php";

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all candidates waiting for approval
$sql = "SELECT u.name,u.email,u.nid,c.status,c.candidate_id FROM candidates c JOIN users u ON c.user_id = u.user_id WHERE c.status = 'pending';";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approve Candidates</title>
</head>
<body>
    <h2>Approve Candidates</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>NID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['nid']; ?></td>
                <td>
                  <?php echo ($row['status'] == 'approved') ? "Approved" : "Pending"; ?>
                </td>
                <td>
                    <?php if ($row['status'] !='approved') { ?>
                        <a href="approve_candidate.php?candidate_id=<?php echo $row['candidate_id']; ?>">Approve</a>
                    <?php } else { ?>
                        ✅ Already Approved
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <p><a href="dashboard_admin.php">⬅ Back to Dashboard</a></p>
</body>
</html>
