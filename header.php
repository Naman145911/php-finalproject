<?php

// Check if user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $profileName = "Hi, " . $_SESSION['username'];
} else {
    $profileName = "";
}
?>

<header class="site-header">
    <div class="logo">
        <a href="index.php">
            <img src="imgs/logo.png" alt="Logo">
        </a>
    </div>
    <nav class="main-navigation">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($profileName !== "") { ?>
                <li><a href="dashboard.php"><?php echo $profileName; ?></a>
                    <ul class="sub-menu">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>
