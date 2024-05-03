<?php
// Load the database configuration file
include_once 'dbConfig.php';

// Calculate total net premium
$totalNetPremium = 0;
$result = $db->query("SELECT SUM(net_premium) AS total FROM members");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalNetPremium = $row['total'];
}

// Redirect back to index.php with the calculated total
header("Location: index.php?totalNetPremium=$totalNetPremium");
?>
