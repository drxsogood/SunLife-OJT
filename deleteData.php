<?php
include_once 'dbConfig.php';

// Delete all data
$db->query("TRUNCATE TABLE members");

// Redirect to the main page
header("Location: index.php?status=succ");
exit();
?>
