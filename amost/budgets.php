<?php 
include 'db_connect.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// កំណត់ខែដែលត្រូវបង្ហាញ (Default គឺខែបច្ចុប្បន្ន)
$view_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// ១. មុខងារ Reset/លុប កញ្ចប់ថវិកា
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM budgets WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $del_id, $current_user);
    $stmt->execute();
    header("Location: budgets.php?month=" . $view_month);
    exit();
}

// ២. ការបញ្ចូល ឬ Update កញ្ចប់ថវិកា
if (isset($_POST['set_budget'])) {
    $cat_id = $_POST['category_id'];
    $limit  = $_POST['amount_limit'];
    $month  = $_POST['month_year'];

    $check = $conn->prepare("SELECT id FROM budgets WHERE user_id = ? AND category_id = ? AND month_year = ?");
    $check->bind_param("iis", $current_user, $cat_id, $month);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE budgets SET amount_limit = ? WHERE user_id = ? AND category_id = ? AND month_year = ?");
        $stmt->bind_param("diis", $limit, $current_user, $cat_id, $month);
    } else {
        $stmt = $conn->prepare("INSERT INTO budgets (user_id, category_id, amount_limit, month_year) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $current_user, $cat_id, $limit, $month);
    }
    $stmt->execute();
    header("Location: budgets.php?month=" . $month);
    exit();
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងថវិកា - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Khmer OS Battambang', sans-serif; }
        .card { border-radius: 15px; border: none; }
        .progress { height: 14px; border-radius: 7px; background-color: #e9ecef; overflow: hidden; }
        .progress-bar { transition: width 0.6s ease; }
        .month-picker { background: #fff; padding: 10px; border-radius: 10px; display: inline-flex; align-items: center; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">SmartWallet</a>
        <a href="index.php" class="btn btn-outline-light btn-sm">ត្រឡប់ទៅ Dashboard</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"></h4>
        <form method="GET" class="month-picker shadow-sm border">
            <label class="me-2 small fw-bold">មើលខែ៖</label>
            <input type="month" name="month" class="form-control form-control-sm me-2" 
                   value="<?= $view_month ?>" onchange="this.form.submit()">
        </form>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3 text-primary fw-bold">កំណត់កញ្ចប់ថវិកា</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small">ប្រភេទចំណាយ</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- រើសប្រភេទ --</option>
                            <?php 
                            $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC"); 
                            while($c = $cats->fetch_assoc()) echo "<option value='{$c['id']}'>{$c['name']}</option>"; 
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">កញ្ចប់ថវិកា ($)</label>
                        <input type="number" step="0.01" name="amount_limit" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">សម្រាប់ខែ</label>
                        <input type="month" name="month_year" class="form-control" value="<?= $view_month ?>" required>
                    </div>
                    <button type="submit" name="set_budget" class="btn btn-primary w-100 py-2 fw-bold">រក្សាទុក</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h5 class="mb-4 fw-bold text-secondary">របាយការណ៍ខែ <?= date('m-Y', strtotime($view_month)) ?></h5>
                
                <?php
                // Query ទាញយកកញ្ចប់ថវិកា និងបូកសរុបការចំណាយដែលត្រូវនឹងខែដែលកំពុងមើល (view_month)
                $sql = "SELECT b.*, c.name, 
                        (SELECT SUM(amount) FROM expenses 
                         WHERE category_id = b.category_id 
                         AND user_id = b.user_id 
                         AND date LIKE '$view_month%') as spent
                        FROM budgets b 
                        JOIN categories c ON b.category_id = c.id
                        WHERE b.user_id = $current_user AND b.month_year = '$view_month'
                        ORDER BY b.amount_limit DESC";
                
                $budgets = $conn->query($sql);
                
                if ($budgets->num_rows > 0):
                    while($row = $budgets->fetch_assoc()):
                        $spent = $row['spent'] ?? 0;
                        $limit = $row['amount_limit'];
                        $percent = ($limit > 0) ? ($spent / $limit) * 100 : 0;
                        
                        // កំណត់ពណ៌
                        $color = 'bg-success';
                        if($percent >= 80 && $percent < 100) $color = 'bg-warning';
                        if($percent >= 100) $color = 'bg-danger';
                ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($row['name']); ?></h6>
                                <small class="text-muted">ប្រើអស់ <?= number_format($percent, 1); ?>%</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold fs-5 <?= ($percent >= 100) ? 'text-danger' : 'text-primary' ?>">
                                    $<?= number_format($spent, 2); ?>
                                </span>
                                <span class="text-muted small">/ $<?= number_format($limit, 2); ?></span>
                                <a href="budgets.php?delete_id=<?= $row['id']; ?>&month=<?= $view_month ?>" 
                                   class="ms-2 text-secondary" title="លុប" 
                                   onclick="return confirm('តើអ្នកចង់លុបកញ្ចប់ថវិកានេះ?')">
                                   <small>&times;</small>
                                </a>
                            </div>
                        </div>
                        <div class="progress border shadow-sm" style="height: 16px;">
                            <div class="progress-bar <?= $color; ?> progress-bar-striped" 
                                 role="progressbar" 
                                 style="width: <?= min($percent, 100); ?>%">
                            </div>
                        </div>
                        <?php if($percent >= 100): ?>
                            <div class="text-danger small mt-1 fw-bold">⚠️ អ្នកបានចំណាយលើសផែនការចំនួន $<?= number_format($spent - $limit, 2) ?></div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="text-center py-5 border rounded bg-light">
                        <img src="https://cdn-icons-png.flaticon.com/512/6598/6598519.png" width="80" class="mb-3 opacity-50">
                        <p class="text-muted">មិនទាន់មានកញ្ចប់ថវិកាសម្រាប់ខែ <?= $view_month ?> ទេ។</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>