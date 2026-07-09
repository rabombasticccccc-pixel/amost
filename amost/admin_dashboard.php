<?php 
include 'db_connect.php'; 
// កូដត្រួតពិនិត្យសិទ្ធិ Admin (ឧទាហរណ៍៖ ប្រសិនបើ user_id មិនមែនជាលេខ ១ គឺមិនឱ្យចូល)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // បើមិនមែនជា Admin ទេ មិនឱ្យចូលមើលដាច់ខាត
    header("Location: index.php"); 
    exit();
}

// ១. ទាញទិន្នន័យសរុបសម្រាប់ Dashboard
$total_users = $conn->query("SELECT COUNT(id) as count FROM users")->fetch_assoc()['count'];
$total_inc = $conn->query("SELECT SUM(amount) as total FROM income")->fetch_assoc()['total'] ?? 0;
$total_exp = $conn->query("SELECT SUM(amount) as total FROM expenses")->fetch_assoc()['total'] ?? 0;
$system_balance = $total_inc - $total_exp;

// ២. ទាញទិន្នន័យសម្រាប់ Chart (ចំណាយតាមប្រភេទ)
$chart_data = $conn->query("SELECT c.name, SUM(e.amount) as total 
                            FROM expenses e 
                            JOIN categories c ON e.category_id = c.id 
                            GROUP BY c.name");
$labels = []; $values = [];
while($row = $chart_data->fetch_assoc()){
    $labels[] = $row['name'];
    $values[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f4f7fa; font-family: 'Kantumruy Pro', sans-serif; }
        .sidebar { width: 250px; height: 100vh; background: #1e293b; color: white; position: fixed; }
        .main-content { margin-left: 250px; padding: 30px; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .nav-link { color: rgba(255,255,255,0.7); margin-bottom: 10px; }
         .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1e293b; color: #38bdf8; }
               body { background: #f4f7fa; font-family: 'Kantumruy Pro', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; color: white; position: fixed; }
        .main-content { margin-left: 260px; padding: 40px; }
        .user-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1e293b; color: #38bdf8; }
        .table thead { background: #f1f5f9; }
        .table th { border: none; padding: 15px; color: #64748b; font-size: 0.85rem; }
       
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h4 class="text-center fw-bold mb-4 mt-2 text-info">ADMIN PANEL</h4>
    <hr>
    <nav class="nav flex-column">
        <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a class="nav-link" href="manage_users.php"><i class="bi bi-people me-2"></i> គ្រប់គ្រងអ្នកប្រើ</a>
        <a class="nav-link" href="categories.php"><i class="bi bi-tags me-2"></i> គ្រប់គ្រងប្រភេទ</a>
        <a class="nav-link" href="all_transactions.php"><i class="bi bi-list-check me-2"></i> ប្រតិបត្តិការទាំងអស់</a>
        <a class="nav-link" href="index.php"><i class="bi bi-house-door me-2"></i> ត្រឡប់ទៅទំព័រដើម</a>
        <hr>
        <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> ចាកចេញ</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">ផ្ទាំងគ្រប់គ្រងប្រព័ន្ធ (Overview)</h3>
        <span class="text-muted small">ធ្វើបច្ចុប្បន្នភាពចុងក្រោយ៖ <?= date('d-M-Y H:i') ?></span>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card p-3 shadow-sm border-start border-primary border-5">
                <h6 class="text-muted small uppercase">អ្នកប្រើប្រាស់សរុប</h6>
                <h2 class="fw-bold text-primary"><?= $total_users ?> នាក់</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 shadow-sm border-start border-success border-5">
                <h6 class="text-muted small uppercase">ចំណូលសរុបក្នុង App</h6>
                <h2 class="fw-bold text-success">$<?= number_format($total_inc, 2) ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 shadow-sm border-start border-danger border-5">
                <h6 class="text-muted small uppercase">ចំណាយសរុបក្នុង App</h6>
                <h2 class="fw-bold text-danger">$<?= number_format($total_exp, 2) ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 shadow-sm border-start border-info border-5 text-white bg-dark">
                <h6 class="text-light small opacity-75 uppercase">សមតុល្យប្រព័ន្ធ</h6>
                <h2 class="fw-bold text-info">$<?= number_format($system_balance, 2) ?></h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
                <h5 class="fw-bold mb-4">ការវិភាគចំណាយតាមប្រភេទ (System Wide)</h5>
                <canvas id="adminChart" height="250"></canvas>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
                <h5 class="fw-bold mb-4">អ្នកប្រើប្រាស់ចុះឈ្មោះថ្មីៗ</h5>
                <ul class="list-group list-group-flush">
                    <?php 
                    $recent_users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
                    while($u = $recent_users->fetch_assoc()):
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($u['username']) ?></div>
                        </div>
                        <span class="badge bg-light text-dark border">ID: <?= $u['id'] ?></span>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('adminChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'ចំនួនចំណាយសរុប ($)',
                data: <?= json_encode($values) ?>,
                backgroundColor: '#6366f1',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

</body>
</html>