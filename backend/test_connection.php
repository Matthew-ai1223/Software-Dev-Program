<?php
require_once 'databass.php';

try {
    $stmt = $pdo->query("SELECT 1");
    if ($stmt) {
        echo "<h1>✅ Backend is connected successfully!</h1>";
        echo "<p>Database: <strong>" . $dbname . "</strong> is active.</p>";
        echo "<hr>";
        echo "<h3>Next Steps:</h3>";
        echo "<ul>";
        echo "<li>Go to the <a href='../reg.html'>Registration Page</a> to test the form.</li>";
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "<h1>❌ Connection Failed</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please ensure XAMPP MySQL is running.</p>";
}
?>
