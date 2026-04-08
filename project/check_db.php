<?php
include 'db_connection.php';

$result = $conn->query("SHOW COLUMNS FROM students");
if ($result) {
    echo "Columns in students table:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error showing columns: " . $conn->error;
}
?>
