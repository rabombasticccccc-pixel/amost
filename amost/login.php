<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <title>ចូលប្រើប្រាស់ - SmartWallet</title>
    <style>
        body {
            /* បន្ថែមពណ៌ Gradient ខាងក្រោយ */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Kantumruy Pro', sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            background: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .login-header h3 {
            color: #4a4a4a;
            font-weight: 700;
            margin-bottom: 0;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.2);
            border-color: #764ba2;
        }
        .btn-login {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            color: white;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            color: white;
        }
        .register-link {
            text-decoration: none;
            color: #764ba2;
            font-weight: bold;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card mx-auto p-4" style="max-width: 400px;">
        <div class="login-header">
            <h3>ចូលប្រើប្រាស់</h3>
            <p class="text-muted small">សូមបំពេញព័ត៌មានដើម្បីបន្ត</p>
        </div>
<?php
include 'db_connect.php';

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql); // បង្កើត variable $result នៅទីនេះ

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // ផ្ទៀងផ្ទាត់ Password (បើអ្នកប្រើ password_hash)
        $user_input_password = $_POST['password']; // តម្លៃដែល User វាយចូល
      $db_hashed_password = $user['password'];   // តម្លៃដែលទាញចេញពី Database
if (password_verify($user_input_password, $db_hashed_password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "លេខសម្ងាត់មិនត្រឹមត្រូវ!";
        }
    } else {
        $error = "រកមិនឃើញឈ្មោះអ្នកប្រើប្រាស់នេះទេ!";
    }
}
?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">ឈ្មោះអ្នកប្រើ</label>
                <input type="text" name="username" class="form-control" placeholder="បញ្ចូលឈ្មោះអ្នកប្រើ" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">ពាក្យសម្ងាត់</label>
                <input type="password" name="password" class="form-control" placeholder="បញ្ចូលពាក្យសម្ងាត់" required>
            </div>
            <button name="login" class="btn btn-login w-100 mb-3">ចូលប្រើប្រាស់</button>
            
            <div class="text-center small">
                មិនទាន់មានគណនី? <a href="register.php" class="register-link">ចុះឈ្មោះឥឡូវនេះ</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>