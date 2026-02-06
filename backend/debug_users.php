<?php
require_once 'databass.php';

echo "<h2>Users List</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    
    if ($users) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th><th>Track</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['fullname'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['track'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No users found in database.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
