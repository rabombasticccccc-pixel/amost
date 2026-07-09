<?php 
include 'db_connect.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['user_id'];

// ទាញយកខែសម្រាប់ Filter (Default ខែបច្ចុប្បន្ន)
$filter_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Query រួមបញ្ចូលគ្នាដោយប្រើ UNION ALL
$sql = "(SELECT id, amount, date, source as title, 'income' as type, NULL as category_name, image 
         FROM income 
         WHERE user_id = $current_user AND DATE_FORMAT(date, '%Y-%m') = '$filter_month')
        UNION ALL
        (SELECT e.id, e.amount, e.date, e.description as title, 'expense' as type, c.name as category_name, e.image 
         FROM expenses e 
         LEFT JOIN categories c ON e.category_id = c.id 
         WHERE e.user_id = $current_user AND DATE_FORMAT(e.date, '%Y-%m') = '$filter_month')
        ORDER BY date DESC";

$result = $conn->query($sql);

// គណនាសរុបសម្រាប់ខែដែលជ្រើសរើស
$stats = $conn->query("
    SELECT 
        (SELECT SUM(amount) FROM income WHERE user_id = $current_user AND DATE_FORMAT(date, '%Y-%m') = '$filter_month') as month_inc,
        (SELECT SUM(amount) FROM expenses WHERE user_id = $current_user AND DATE_FORMAT(date, '%Y-%m') = '$filter_month') as month_exp
")->fetch_assoc();

$m_inc = $stats['month_inc'] ?? 0;
$m_exp = $stats['month_exp'] ?? 0;
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រតិបត្តិការទាំងអស់ - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f0f2f5; }
        .card { border-radius: 15px; border: none; }
        .table thead { background-color: #f8f9fa; }
        .badge-inc { background: #d1e7dd; color: #0f5132; }
        .badge-exp { background: #f8d7da; color: #842029; }
        .img-preview { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0 text-primary"><i class="bi bi-arrow-left-right me-2"></i>ប្រតិបត្តិការទាំងអស់</h4>
        <div class="d-flex gap-2">
            <input type="month" class="form-control form-control-sm" id="monthFilter" value="<?= $filter_month ?>" onchange="filterData()">
            <a href="index.php" class="btn btn-light btn-sm rounded-pill px-3">ត្រឡប់ក្រោយ</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm border-start border-success border-4">
                <small class="text-muted d-block">ចំណូលខែនេះ</small>
                <h5 class="fw-bold text-success mb-0">+$<?= number_format($m_inc, 2) ?></h5>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-3 shadow-sm border-start border-danger border-4">
                <small class="text-muted d-block">ចំណាយខែនេះ</small>
                <h5 class="fw-bold text-danger mb-0">-$<?= number_format($m_exp, 2) ?></h5>
            </div>
        </div>
    </div>

    <div class="card shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">កាលបរិច្ឆេទ</th>
                        <th>ប្រតិបត្តិការ</th>
                        <th>ប្រភេទ</th>
                        <th>រូបភាព</th>
                        <th class="text-end pe-4">ចំនួនទឹកប្រាក់</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted small"><?= date('d, M Y', strtotime($row['date'])) ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['title']) ?: 'មិនមានចំណងជើង' ?></div>
                                <?php if($row['category_name']): ?>
                                    <small class="text-muted badge bg-light border text-dark fw-normal"><?= $row['category_name'] ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $row['type'] == 'income' ? 'badge-inc' : 'badge-exp' ?> px-3">
                                    <?= $row['type'] == 'income' ? 'ចំណូល' : 'ចំណាយ' ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['image']): ?>
                                    <img src="uploads/<?= $row['image'] ?>" class="img-preview shadow-sm" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4 fw-bold <?= $row['type'] == 'income' ? 'text-success' : 'text-danger' ?>">
                                <?= $row['type'] == 'income' ? '+' : '-' ?> $<?= number_format($row['amount'], 2) ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted italic">មិនទាន់មានទិន្នន័យសម្រាប់ខែនេះនៅឡើយទេ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterData() {
    const month = document.getElementById('monthFilter').value;
    window.location.href = 'all_transactions.php?month=' + month;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>