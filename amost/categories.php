<?php 
include 'db_connect.php'; 

// ១. មុខងារបន្ថែមប្រភេទថ្មី
if(isset($_POST['add'])){
    $name = $conn->real_escape_string($_POST['cat_name']);
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header("Location: categories.php");
    exit();
}

// ២. មុខងារកែសម្រួលប្រភេទ (Update)
if(isset($_POST['update'])){
    $id = $_POST['cat_id'];
    $name = $conn->real_escape_string($_POST['cat_name']);
    $conn->query("UPDATE categories SET name = '$name' WHERE id = $id");
    header("Location: categories.php");
    exit();
}

// ៣. មុខងារលុប
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM expenses WHERE category_id = $id");
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: categories.php");
    exit();
}

// ៤. ទាញទិន្នន័យមកបង្ហាញក្នុង Form
$edit_name = "";
$edit_id = "";
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $res = $conn->query("SELECT * FROM categories WHERE id = $id");
    $row = $res->fetch_assoc();
    $edit_name = $row['name'];
    $edit_id = $row['id'];
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <title>គ្រប់គ្រងប្រភេទ</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body { 
            font-family: 'Kantumruy Pro', sans-serif; 
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 20px;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .btn-success { background-color: #10b981; border: none; }
        .btn-warning { background-color: #f59e0b; border: none; color: white; }
        
        .list-group-item {
            border: none;
            margin-bottom: 8px;
            border-radius: 12px !important;
            transition: all 0.3s ease;
            background: #fff;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            background-color: #f9fafb;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e5e7eb;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            border-color: var(--primary-color);
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg mx-auto" style="max-width: 550px;">
        <div class="card-header">
            <i class="bi bi-tags-fill me-2"></i> គ្រប់គ្រងប្រភេទចំណាយ
        </div>
        
        <div class="card-body p-4">
            <form method="POST" class="mb-4">
                <input type="hidden" name="cat_id" value="<?= $edit_id ?>">
                <div class="input-group">
                    <input type="text" name="cat_name" class="form-control" 
                           placeholder="បញ្ចូលឈ្មោះប្រភេទ..." value="<?= $edit_name ?>" required>
                    
                    <?php if ($edit_id): ?>
                        <button name="update" class="btn btn-warning px-4">កែប្រែ</button>
                        <a href="categories.php" class="btn btn-light border px-3">លុបចោល</a>
                    <?php else: ?>
                        <button name="add" class="btn btn-success px-4">+ បន្ថែម</button>
                    <?php endif; ?>
                </div>
            </form>

            <h6 class="text-muted mb-3">បញ្ជីប្រភេទដែលមានស្រាប់</h6>
            <ul class="list-group">
                <?php 
                $res = $conn->query("SELECT * FROM categories ORDER BY id DESC"); 
                if ($res->num_rows > 0):
                    while($row = $res->fetch_assoc()): 
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center shadow-sm">
                        <span class="fw-bold text-dark"><?= $row['name'] ?></span>
                        <div class="btn-group">
                            <a href="categories.php?edit_id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-outline-warning me-1 btn-action" title="កែ">
                                ✎
                            </a>
                            <a href="categories.php?delete_id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-outline-danger btn-action" 
                               onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')" title="លុប">
                                ✖
                            </a>
                        </div>
                    </li>
                <?php 
                    endwhile; 
                else:
                    echo "<p class='text-center text-muted'>មិនទាន់មានទិន្នន័យ</p>";
                endif;
                ?>
            </ul>
            
            <hr>
            <a href="index.php" class="btn btn-link w-100 text-decoration-none text-muted">
                ← ត្រឡប់ទៅទំព័រដើម
            </a>
        </div>
    </div>
</div>

</body>
</html>