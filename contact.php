<?php session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/Contact.css">
</head>
<body>
<?php include 'header.php' ?>
    <main>
        <!-- Contact section -->
        <div class="contact-section">
            <!-- Contact form -->
            <div class="contact-form">
                <h2>Contact Us</h2>
                <form action="" method="POST">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <textarea name="message" placeholder="Message" rows="6" required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </main>
    <?php include 'footer.php' ?>

</body>
</html>
