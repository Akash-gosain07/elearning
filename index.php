<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $portal = $_POST['portal'];

    // Query to fetch user from the database
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify credentials and role
    if ($user && password_verify($password, $user['password'])) {
        // Check if the selected portal matches the user's role
        if (strtolower($user['role']) === $portal) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . $portal . ".php");
            exit();
        } else {
            echo "<script>alert('Selected portal does not match your role!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='index.php';</script>";
    }
}
?>