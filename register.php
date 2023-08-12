<?php
// register.php
include 'database.php';

// Initialize error messages array
$errors = array();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    // Check if all fields are filled
    if (empty($username) || empty($email) || empty($password) || empty($password2)) {
        $errors[] = "All fields are required.";
    }
    // Check if email is valid
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email is already registered
        $sql = "SELECT id FROM admin WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $errors[] = "Database error.";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                $errors[] = "Email is already registered.";
            } else {
                // Proceed with registration

                // Check if passwords match
                if ($password !== $password2) {
                    $errors[] = "Passwords do not match.";
                }
                // Check password length and format
                elseif (strlen($password) < 8 || !preg_match('/[0-9]/', $password)) {
                    $errors[] = "Password must be at least 8 characters long and contain numbers.";
                } else {
                    // Hash password
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    // Upload user image
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($_FILES["user_image"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $check = getimagesize($_FILES["user_image"]["tmp_name"]);
                    
                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $errors[] = "File is not an image.";
                        $uploadOk = 0;
                    }
                    
                    if ($_FILES["user_image"]["size"] > 500000) {
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
                        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $targetFile)) {
                            // Insert user into database
                            $sql = "INSERT INTO admin (username, email, password, image) VALUES (?, ?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                $errors[] = "Database error.";
                            } else {
                                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPwd, $targetFile);
                                mysqli_stmt_execute($stmt);
                                header("Location: login.php?signup=success");
                                exit();
                            }
                        } else {
                            $errors[] = "Image upload failed.";
                        }
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/RegisterStyle.css">

</head>

<body>
    <?php include 'header.php'?>
    <div class="container">
        <h1>Register</h1>
        <?php
        if (!empty($errors)) {
            echo '<div class="error-box">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="text" name="email" placeholder="Email"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="password" name="password2" placeholder="Confirm Password"><br>
            <label for="user_image">Profile Image:</label>
            <input type="file" name="user_image" id="user_image">
            <button type="submit" name="submit">Register</button>
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </form>
    </div>
</body>

</html>
