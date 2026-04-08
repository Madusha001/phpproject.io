<?php
session_start();
session_unset();
session_destroy();

// Redirect to index.php (Home Page)
header("Location: index.php");
exit();
?>