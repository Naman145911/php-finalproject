<?php
// user_management.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Handle user deletion
if (isset($_POST['delete'])) {
    $deleteUserId = mysqli_real_escape_string($conn, $_POST['delete_user_id']);

    // Check if there's more than one user before deletion
    $countSql = "SELECT COUNT(*) as count FROM admin";
    $countResult = mysqli_query($conn, $countSql);
    $countRow = mysqli_fetch_assoc($countResult);
    $userCount = $countRow['count'];

    if ($userCount > 1) {
        // Check if the user is deleting their own account
        if ($_SESSION['user_id'] == $deleteUserId) {
            session_destroy();
            header("Location: login.php");
            exit();
        }

        $deleteSql = "DELETE FROM admin WHERE id=?";
        $deleteStmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($deleteStmt, $deleteSql)) {
            mysqli_stmt_bind_param($deleteStmt, "i", $deleteUserId);
            mysqli_stmt_execute($deleteStmt);
            // You can add further redirect or success message here
        } else {
            // Handle delete error
            echo "Delete error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/userManagementStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>User Management</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM admin";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['username'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td><a href="edit_user.php?id=' . $row['id'] . '">Edit</a></td>';
                    $countSql = "SELECT COUNT(*) as count FROM admin";
                    $countResult = mysqli_query($conn, $countSql);
                    $countRow = mysqli_fetch_assoc($countResult);
                    $userCount = $countRow['count'];
                    if ($userCount > 1) {
                        echo '<td>
                                <form action="user_management.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">
                                    <input type="hidden" name="delete_user_id" value="' . $row['id'] . '">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                              </td>';
                    } else {
                        echo '<td>Cannot delete</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
