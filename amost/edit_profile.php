<?php 
include 'db_connect.php';

// ១. ពិនិត្យមើល Login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
$current_user_id = $_SESSION['user_id'];
$message = "";

// ២. ផ្នែក Update ទិន្នន័យ (នៅពេលចុចប៊ូតុង Save)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);

    if (!empty($new_username)) {
        $update_sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt_up = $conn->prepare($update_sql);
        $stmt_up->bind_param("si", $new_username, $current_user_id);

        if ($stmt_up->execute()) {
            $message = "<div class='alert alert-success'>កែប្រែបានជោគជ័យ!</div>";
        } else {
            $message = "<div class='alert alert-danger'>មានបញ្ហា៖ " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>សូមបញ្ចូលឈ្មោះប្រើប្រាស់!</div>";
    }
}

// ៣. ទាញទិន្នន័យបច្ចុប្បន្នមកបង្ហាញក្នុង Form
$sql = "SELECT username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កែប្រែព័ត៌មាន - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        body { background:#f8f9fa; font-family: 'Kantumruy Pro', sans-serif; }
        .navbar { background: var(--primary-gradient) !important; }
        .card { border-radius: 15px; border: none; }
        .btn-primary { background: var(--primary-gradient); border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 p-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-wallet2 me-2"></i>SmartWallet</a>
            <a href="view_profile.php" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm p-4">
                    <h4 class="fw-bold text-center mb-4">កែប្រែព័ត៌មានផ្ទាល់ខ្លួន</h4>
                    
                    <?= $message; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted">ឈ្មោះប្រើប្រាស់ (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" 
                                       value="<?= htmlspecialchars($user_data['username']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">តួនាទី (Role)</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= ucfirst(htmlspecialchars($user_data['role'])) ?>" readonly>
                            <small class="text-muted">*អ្នកមិនអាចកែប្រែតួនាទីដោយខ្លួនឯងបានទេ។</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_profile" class="btn btn-primary rounded-pill py-2">
                                <i class="bi bi-save me-2"></i>រក្សាទុកការផ្លាស់ប្តូរ
                            </button>
                            <a href="view_profile.php" class="btn btn-light rounded-pill">បោះបង់</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>