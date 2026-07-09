<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $u_id = intval($_POST['user_id']);
    $username = $conn->real_escape_string($_POST['new_username']);
    $password = $_POST['new_password'];

    // 1. Update Username
    $conn->query("UPDATE users SET username = '$username' WHERE id = $u_id");

    // 2. Update Password only if it's not empty
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password = '$hashed_password' WHERE id = $u_id");
    }

    header("Location: manage_users.php?msg=updated");
    exit();
}