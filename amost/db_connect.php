<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "finance_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// កែសម្រួលកន្លែងនេះ៖ ឆែកមើលថាមាន Session ឬអត់មុននឹងប្រើ
$current_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
?>