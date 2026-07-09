<?php 
include 'db_connect.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['user_id'];

// មុខងារលុបទិន្នន័យ (ប្តូរតាមប្រភេទ)
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $id = intval($_GET['delete_id']);
    $type = $_GET['type'];
    
    if ($type == 'income') {
        $conn->query("DELETE FROM income WHERE id = $id AND user_id = $current_user");
    } else {
        $conn->query("DELETE FROM expenses WHERE id = $id AND user_id = $current_user");
    }
    header("Location: history.php");
    exit();
}

// ទាញយកទាំងចំណូល និងចំណាយ រួចតម្រៀបតាមកាលបរិច្ឆេទ
$sql = "(SELECT id, amount, date, source as title, 'income' as type, NULL as cat_name, image 
         FROM income WHERE user_id = $current_user)
        UNION ALL
        (SELECT e.id, e.amount, e.date, e.description as title, 'expense' as type, c.name as cat_name, e.image 
         FROM expenses e 
         LEFT JOIN categories c ON e.category_id = c.id 
         WHERE e.user_id = $current_user)
        ORDER BY date DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ប្រវត្តិហិរញ្ញវត្ថុ - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8f9fa; }
        .badge-inc { background: #d1e7dd; color: #0f5132; border-radius: 50px; }
        .badge-exp { background: #f8d7da; color: #842029; border-radius: 50px; }
        .img-preview { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary"><i class="bi bi-journal-text me-2"></i>ប្រវត្តិប្រតិបត្តិការសរុប</h4>
        <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill">ត្រឡប់ក្រោយ</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ថ្ងៃខែ</th>
                        <th>ប្រតិបត្តិការ</th>
                        <th>ប្រភេទ</th>
                        <th>រូបភាព</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់</th>
                        <th class="text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4 small text-muted"><?= date('d/m/Y', strtotime($row['date'])) ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['title']) ?: 'មិនមានចំណងជើង' ?></div>
                                <?php if($row['cat_name']): ?>
                                    <small class="text-muted"><?= htmlspecialchars($row['cat_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $row['type'] == 'income' ? 'badge-inc' : 'badge-exp' ?> px-3">
                                    <?= $row['type'] == 'income' ? 'ចំណូល' : 'ចំណាយ' ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['image']): ?>
                                    <a href="uploads/<?= $row['image'] ?>" target="_blank">
                                        <img src="uploads/<?= $row['image'] ?>" class="img-preview border">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold <?= $row['type'] == 'income' ? 'text-success' : 'text-danger' ?>">
                                <?= $row['type'] == 'income' ? '+' : '-' ?> $<?= number_format($row['amount'], 2) ?>
                            </td>
                            <td class="text-center">
                                <?php $edit_link = ($row['type'] == 'income') ? "edit-income.php?id=" : "edit-expense.php?id="; ?>
                                <a href="<?= $edit_link . $row['id'] ?>" class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil"></i></a>
                                <a href="history.php?delete_id=<?= $row['id'] ?>&type=<?= $row['type'] ?>" 
                                   class="btn btn-sm btn-light text-danger" 
                                   onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>