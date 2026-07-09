<?php
include 'db_connect.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['user_id'];

// លុបទិន្នន័យចំណាយ និងចំណូលរបស់ User បច្ចុប្បន្ន
$sql_exp = "DELETE FROM expenses WHERE user_id = $current_user";
$sql_inc = "DELETE FROM income WHERE user_id = $current_user";

if ($conn->query($sql_exp) && $conn->query($sql_inc)) {
    // លុបរូបភាពក្នុង Folder uploads ផងដែរ (Optional)
    // ប្រសិនបើអ្នកចង់រក្សារូបភាពទុក មិនបាច់ប្រើ Loop នេះទេ
    header("Location: index.php?status=reset_success");
} else {
    echo "មានបញ្ហា: " . $conn->error;
}
?>