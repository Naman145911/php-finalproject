<?php
// edit_user.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Retrieve user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM admin WHERE id=?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
} else {
    // Handle database error
    echo "Database error: " . mysqli_error($conn);
    exit();
}

// Handle form submission for updating user data
if (isset($_POST['submit'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
    
    // Handle user image update
    $newUserImage = ""; // Default value
    
    if (!empty($_FILES['new_user_image']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["new_user_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["new_user_image"]["tmp_name"]);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $errors[] = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["new_user_image"]["size"] > 500000) {
            $errors[] = "Image file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $errors[] = "Image upload failed.";
        } else {
            if (move_uploaded_file($_FILES["new_user_image"]["tmp_name"], $targetFile)) {
                $newUserImage = $targetFile;
            } else {
                $errors[] = "Image upload failed.";
            }
        }
    }
    
    // Update user information
    $updateSql = "UPDATE admin SET username=?, image=? WHERE id=?";
    $updateStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($updateStmt, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "ssi", $newUsername, $newUserImage, $user_id);
        mysqli_stmt_execute($updateStmt);
        header("Location: user_management.php");
        exit();
    } else {
        // Handle update error
        echo "Update error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/editUserStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Edit User Profile</h1>
        <?php
        if (!empty($errors)) {
            echo '<div class="error-box">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="edit_user.php" method="POST" enctype="multipart/form-data">
            <label for="new_username">New Username:</label>
            <input type="text" name="new_username" value="<?php echo $user['username']; ?>"><br>
            <label for="new_user_image">New Profile Image:</label>
            <input type="file" name="new_user_image" id="new_user_image">
            <button type="submit" name="submit">Update Profile</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
