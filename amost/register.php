<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <title>ចុះឈ្មោះ - SmartWallet</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Kantumruy Pro', sans-serif;
            padding: 20px 0;
        }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .form-control { border-radius: 10px; padding: 10px; }
        .btn-register {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border: none; border-radius: 10px; padding: 12px;
            font-weight: bold; color: white; transition: 0.3s;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4); color: white; }
        .login-link { text-decoration: none; color: #764ba2; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="card mx-auto p-4 shadow" style="max-width: 450px; background: #fff;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">បង្កើតគណនី</h3>
            <p class="text-muted small">ចាប់ផ្តើមគ្រប់គ្រងចំណាយជាមួយយើង</p>
        </div>
<?php include 'db_connect.php'; ?>

<?php
if(isset($_POST['register'])){
    // ទទួលទិន្នន័យ
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $_POST['password'];

    // 👉 បម្លែង password ទៅ hash
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // ពិនិត្យ username ស្ទួន
    $checkUser = $conn->query("SELECT id FROM users WHERE username = '$user'");

    if($checkUser->num_rows > 0) {
        echo "<div class='alert alert-warning py-2 small text-center'>ឈ្មោះនេះមានគេប្រើរួចហើយ!</div>";
    } else {

        // 👉 បញ្ចូល hashed password
        $sql = "INSERT INTO users (username, password, role) 
                VALUES ('$user', '$hashed_password', 'user')";

        if($conn->query($sql)){
            echo "<div class='alert alert-success py-2 small text-center'>
                    ចុះឈ្មោះជោគជ័យ! <a href='login.php' class='fw-bold'>ចូលប្រើ</a>
                  </div>";
        } else {
            echo "<div class='alert alert-danger py-2 small text-center'>មានបញ្ហា៖ " . $conn->error . "</div>";
        }
    }
}
?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">ឈ្មោះអ្នកប្រើ</label>
                <input type="text" name="username" class="form-control" placeholder="បង្កើតឈ្មោះអ្នកប្រើ" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">ពាក្យសម្ងាត់</label>
                <input type="password" name="password" class="form-control" placeholder="បង្កើតពាក្យសម្ងាត់" required>
            </div>

            <button name="register" class="btn btn-register w-100 mb-3">ចុះឈ្មោះឥឡូវនេះ</button>
            
            <div class="text-center small">
                មានគណនីរួចហើយ? <a href="login.php" class="login-link">ចូលប្រើប្រាស់</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>