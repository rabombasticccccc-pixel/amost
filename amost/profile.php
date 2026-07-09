<?php
// ចាប់ផ្ដើមប្រើប្រាស់ Session
session_start();

// ឆែកមើលថា តើ User បាន Login រួចរាល់ហើយឬនៅ?
if (!isset($_SESSION['user_id'])) {
    // បើមិនទាន់ Login ទេ ឱ្យត្រឡប់ទៅទំព័រ login.php វិញ
    header("Location: login.php");
    exit();
}

// ឧបមាថាទិន្នន័យត្រូវបានរក្សាទុកក្នុង Session ពេល Login ជោគជ័យ
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ព័ត៌មានផ្ទាល់ខ្លួន</title>
    <style>
        body { font-family: sans-serif; padding: 50px; }
        .profile-card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 300px; }
    </style>
</head>
<body>

    <h2>ស្វាគមន៍មកកាន់ Profile របស់អ្នក</h2>
    
    <div class="profile-card">
        <p><strong>ឈ្មោះអ្នកប្រើប្រាស់:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>អ៊ីមែល:</strong> <?php echo htmlspecialchars($email); ?></p>
        <hr>
        <a href="logout.php">ចាកចេញ (Logout)</a>
    </div>

</body>
</html>