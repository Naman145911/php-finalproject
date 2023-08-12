<?php
// Start or resume the session
session_start();

// Clear session data
session_unset();
session_destroy();

// Redirect to the homepage or desired page
header("Location: index.php");
exit();
?>
