<?php 
include 'db_connect.php'; 
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
if(isset($_POST['save'])){
    $cat = $_POST['cat_id']; 
    $amt = $_POST['amt']; 
    $date = $_POST['date']; 
    $desc = $_POST['desc'];
    
    // ផ្នែកគ្រប់គ្រងការ Upload រូបភាព
    $image_name = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '.' . $ext; // ប្តូរឈ្មោះរូបភាពកុំឱ្យជាន់គ្នា
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
    }
    
    // បន្ថែម $image_name ទៅក្នុង Query
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, category_id, amount, date, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsss", $current_user, $cat, $amt, $date, $desc, $image_name);
    
    if($stmt->execute()){
        header("Location: history.php?status=success");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កត់ត្រាចំណាយ - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --expense-bg: linear-gradient(135deg, #feb47b 0%, #ff7e5f 100%);
            --btn-danger: #ef4444;
        }

        body { 
            background: #fff5f5; 
            font-family: 'Kantumruy Pro', sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card { 
            border-radius: 25px; 
            border: none; 
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.1);
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--expense-bg);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            border: 2px solid white;
        }

        .form-label { font-weight: 600; color: #4b5563; }
        
        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            border-color: var(--btn-danger);
        }

        .btn-save {
            background-color: var(--btn-danger);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-cancel {
            border-radius: 12px;
            padding: 12px;
            color: #6b7280;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header-custom">
                    <div class="icon-circle">
                        <i class="bi bi-cart-dash"></i>
                    </div>
                    <h3 class="fw-bold m-0 text-white">កត់ត្រាចំណាយ</h3>
                    <p class="mb-0 text-white opacity-75">គ្រប់គ្រងការចំណាយប្រចាំថ្ងៃរបស់អ្នក</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger small"><?= $error_msg ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
        <label class="form-label"><i class="bi bi-image me-2"></i>រូបភាព</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-grid me-2"></i>ប្រភេទចំណាយ</label>
                            <select name="cat_id" class="form-select" required>
                                <option value="" disabled selected>ជ្រើសរើសប្រភេទ...</option>
                                <?php 
                                $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC"); 
                                while($c = $cats->fetch_assoc()) {
                                    echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-currency-dollar me-2"></i>ចំនួនទឹកប្រាក់ ($)</label>
                            <input type="number" step="0.01" name="amt" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-calendar-event me-2"></i>ថ្ងៃខែ</label>
                            <input type="date" name="date" class="form-control" value="<?=date('Y-m-d')?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-chat-left-text me-2"></i>បរិយាយ (បើមាន)</label>
                            <textarea name="desc" class="form-control" rows="2" placeholder="ឧ. ទិញម្ហូបញ៉ាំថ្ងៃត្រង់..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button name="save" class="btn btn-save">
                                <i class="bi bi-plus-circle me-2"></i>រក្សាទុកចំណាយ
                            </button>
                            <a href="index.php" class="btn btn-cancel">បោះបង់</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>