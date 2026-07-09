<?php 
include 'db_connect.php'; 
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// កូដសម្រាប់លុបទិន្នន័យ (Delete)
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM expenses WHERE id = $id AND user_id = $current_user");
    header("Location: history.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រវត្តិចំណាយ - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body { 
            background-color: #f0f2f5; 
            font-family: 'Kantumruy Pro', sans-serif; 
        }
        .header-section {
            background: var(--primary-gradient);
            color: white;
            padding: 40px 0;
            margin-bottom: -50px;
        }
        .table-container { 
            background: white; 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: none;
        }
        .table thead {
            background-color: #f8f9fa;
        }
        .table thead th {
            border: none;
            color: #6c757d;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }
        .badge-category {
            background-color: #e0e7ff;
            color: #4338ca;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-edit { background-color: #fef3c7; color: #d97706; border: none; }
        .btn-edit:hover { background-color: #fcd34d; }
        .btn-delete { background-color: #fee2e2; color: #dc2626; border: none; }
        .btn-delete:hover { background-color: #fecaca; }
        
        /* Animation */
        tr { transition: all 0.2s; }
        tr:hover { background-color: #fbfcfe; }
    </style>
</head>
<body>

<div class="header-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold m-0"><i class="bi bi-clock-history me-2"></i>ប្រវត្តិប្រតិបត្តិការ</h2>
                <p class="opacity-75 mb-0">ពិនិត្យមើលរាល់រាយការណ៍ចំណាយរបស់អ្នក</p>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-light rounded-pill px-3 shadow-sm">
                    <i class="bi bi-house-door me-1"></i>  ត្រឡប់ទៅទំព័រដើម
                </a>
                <a href="add-expense.php" class="btn btn-dark rounded-pill px-3 shadow-sm text-white">
                    <i class="bi bi-plus-lg me-1"></i> បន្ថែមចំណាយ
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        
                        <th>កាលបរិច្ឆេទ</th>
                        <th>រូបភាព</th>
                        <th>ប្រភេទ</th>
                        <th>បរិយាយ</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់</th>
                        <th class="text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.*, c.name as category_name 
                            FROM expenses e 
                            JOIN categories c ON e.category_id = c.id 
                            WHERE e.user_id = $current_user 
                            ORDER BY e.date DESC";
                    
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>

                            <td class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i> <?= $row['date'] ?>
                            </td>
                                                      

<td>
    <?php if($row['image']): ?>
        <a href="uploads/<?= $row['image'] ?>" target="_blank">
            <img src="uploads/<?= $row['image'] ?>" width="40" height="40" style="object-fit: cover; border-radius: 5px;">
        </a>
    <?php else: ?>
        <span class="text-muted small">គ្មានរូប</span>
    <?php endif; ?>
</td>
                            <td>
                                <span class="badge-category"><?= htmlspecialchars($row['category_name']) ?></span>
                            </td>
                            <td class="text-secondary small">
                                <?= !empty($row['description']) ? htmlspecialchars($row['description']) : '<em>គ្មានបរិយាយ</em>' ?>
                            </td>
                            <td class="text-end fw-bold text-danger">
                                -$<?= number_format($row['amount'], 2) ?>
                            </td>
                            <td class="text-center">
                                <a href="edit-expense.php?id=<?= $row['id'] ?>" class="btn-action btn-edit me-1" title="កែប្រែ">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="history.php?delete_id=<?= $row['id'] ?>" 
                                   class="btn-action btn-delete" 
                                   onclick="return confirm('តើអ្នកប្រាកដថាចង់លុបទិន្នន័យនេះមែនទេ?')" title="លុប">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-muted'>មិនទាន់មានទិន្នន័យនៅឡើយទេ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>