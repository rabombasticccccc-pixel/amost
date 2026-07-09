<?php 
include 'db_connect.php'; 
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// ប្រើ ID របស់អ្នកប្រើប្រាស់ដែលកំពុង Login
$current_user = $_SESSION['user_id'];

if (isset($_POST['save_income'])) {
    $source = $conn->real_escape_string($_POST['source']);
    $amount = $_POST['amount'];
    $date   = $_POST['date'];
    
    // --- ផ្នែកគ្រប់គ្រងការ Upload រូបភាព ---
    $image_name = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '.' . $ext; // បង្កើតឈ្មោះថ្មីការពារការជាន់គ្នា
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
    }
    // ---------------------------------

    $sql = "INSERT INTO income (user_id, amount, source, date, image) 
            VALUES ($current_user, '$amount', '$source', '$date', '$image_name')";
    
    if ($conn->query($sql)) {
        header("Location: index.php?status=success");
        exit();
    } else {
        $error_msg = "មានបញ្ហា: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បញ្ចូលចំណូល - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --income-bg: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
            --btn-green: #10b981;
        }

        body { 
            background: #f0fdf4; 
            font-family: 'Kantumruy Pro', sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card { 
            border-radius: 25px; 
            border: none; 
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--income-bg);
            padding: 40px 20px;
            text-align: center;
            color: #15803d;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-label { font-weight: 600; color: #4b5563; }
        
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            border-color: var(--btn-green);
        }

        .btn-save {
            background-color: var(--btn-green);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-cancel {
            border-radius: 12px;
            padding: 12px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-cancel:hover { color: #111827; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header-custom">
                    <div class="icon-circle text-success">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h3 class="fw-bold m-0">កត់ត្រាចំណូល</h3>
                    <p class="opacity-75 mb-0">បន្ថែមទឹកប្រាក់ចូលក្នុងកាបូបរបស់អ្នក</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger"><?= $error_msg ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-tag me-2"></i>ប្រភពចំណូល</label>
                            <input type="text" name="source" class="form-control" 
                                   placeholder="ឧ. ប្រាក់ខែ, លក់អីវ៉ាន់..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-currency-dollar me-2"></i>ចំនួនទឹកប្រាក់</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">$</span>
                                <input type="number" step="0.01" name="amount" class="form-control border-start-0" 
                                       placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-calendar3 me-2"></i>កាលបរិច្ឆេទ</label>
                            <input type="date" name="date" class="form-control" 
                                   value="<?= date('Y-m-d'); ?>" required>
                        </div>
                       <div class="mb-4">
        <label class="form-label">
            <i class="bi bi-camera me-2"></i>រូបភាពបង្កាន់ដៃ (បើមាន)
        </label>
        <input type="file" name="image" class="form-control border-0 bg-light shadow-sm" accept="image/*">
        <div class="form-text small">ប្រភេទឯកសារ៖ JPG, PNG (ទំហំមិនលើស ២MB)</div>
    </div>

    <div class="d-grid gap-2 mt-5">
        <button type="submit" name="save_income" class="btn btn-save">
            <i class="bi bi-check-circle me-2"></i>រក្សាទុកចំណូល
        </button>
        <a href="index.php" class="btn btn-cancel text-center">បោះបង់</a>
    </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">
                &copy; 2024 SmartWallet - រក្សាលុយឱ្យមានសណ្តាប់ធ្នាប់
            </p>
        </div>
    </div>
</div>

</body>
</html>