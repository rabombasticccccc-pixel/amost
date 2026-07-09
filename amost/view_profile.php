<?php 
include 'db_connect.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
$current_user_id = $_SESSION['user_id'];

// កែសម្រួល SQL ឱ្យត្រូវជាមួយ Column ដែលមានក្នុង Database ពិតប្រាកដ (id, username, role)
$sql = "SELECT username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("SQL Error: " . $conn->error); 
}

$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ព័ត៌មានផ្ទាល់ខ្លួន - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        body { background:#f8f9fa; font-family: 'Kantumruy Pro', sans-serif; }
        .navbar { background: var(--primary-gradient) !important; }
        .profile-header { background: var(--primary-gradient); color: white; border-radius: 15px 15px 0 0; padding: 40px 20px; }
        .profile-img { width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 50px; color: #764ba2; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 p-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-wallet2 me-2"></i>SmartWallet</a>
            <a href="index.php" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> ត្រឡប់ទៅ Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="profile-header text-center">
                        <div class="profile-img shadow">
                            <i class="bi bi-person"></i>
                        </div>
                        <h3 class="fw-bold mb-0"><?= htmlspecialchars($user_data['username']) ?></h3>
                        <p class="opacity-75">ឋានៈ៖ <?= ucfirst(htmlspecialchars($user_data['role'])) ?></p>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            គណនីរបស់អ្នកត្រូវបានការពារដោយប្រព័ន្ធសុវត្ថិភាព។
                        </div>
                        
                        <div class="mt-4 d-grid gap-2">
                            <a href="edit_profile.php" class="btn btn-primary rounded-pill">
                                <i class="bi bi-pencil-square me-2"></i>កែប្រែឈ្មោះប្រើប្រាស់
                            </a>
                            <a href="change_password.php" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-key me-2"></i>ប្តូរលេខសម្ងាត់
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>