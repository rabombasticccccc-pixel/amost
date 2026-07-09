<?php 
include 'db_connect.php';
// សន្មតថា $current_user ត្រូវបានទាញចេញពី SESSION ក្នុង db_connect.php រួចហើយ
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
$current_user = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --income-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --expense-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        body { background:#f8f9fa; font-family: 'Kantumruy Pro', sans-serif; color: #333; }
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card { border-radius: 15px; border: none; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .stat-card-inc { background: var(--income-gradient); color: white; }
        .stat-card-exp { background: var(--expense-gradient); color: white; }
        .stat-card-bal { background: var(--primary-gradient); color: white; }
        .table thead { background: #f1f3f5; }
        .badge-cate { border-radius: 20px; padding: 5px 12px; font-weight: 400; }
        .chart-container { position: relative; height: 300px; width: 100%; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 p-3">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-wallet2 me-2"></i>SmartWallet</a>
        <div class="navbar-nav ms-auto align-items-center">
    <a class="nav-link active" href="index.php">ផ្ទាំងគ្រប់គ្រង</a>

    <?php if($user_role == 'admin'): ?>
        <a class="nav-link fw-bold text-info" href="admin_dashboard.php">
            <i class="bi bi-speedometer2"></i> គ្រប់គ្រងប្រព័ន្ធ
        </a>
    <?php endif; ?>

    <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ហិរញ្ញវត្ថុ
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="financeDropdown">
            <li><a class="dropdown-item" href="add-income.php"><i class="bi bi-plus-circle me-2 text-success"></i>បន្ថែមចំណូល</a></li>
            <li><a class="dropdown-item" href="add-expense.php"><i class="bi bi-dash-circle me-2 text-danger"></i>បន្ថែមចំណាយ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="history.php"><i class="bi bi-clock-history me-2"></i>ប្រវត្តិប្រតិបត្តិការ</a></li>
            <li><a class="dropdown-item" href="categories.php"><i class="bi bi-tags me-2"></i>ប្រភេទចំណាយ</a></li>
            <li><a class="dropdown-item" href="budgets.php"><i class="bi bi-wallet2 me-2"></i>កំណត់ថវិកា</a></li>
            <li><a class="dropdown-item" href="about.php"> <i class="bi bi-info-circle me-2"></i>អំពីកម្មវិធី</a></li>
            <li><a class="dropdown-item" href="contact.php"><i class="bi bi-envelope me-2"></i>ទាក់ទងយើង</a></li>
        </ul>
    </div>

    <a class="nav-link text-white ms-lg-2" href="view_profile.php">
        <i class="bi bi-person-circle"></i> ព័ត៌មានផ្ទាល់ខ្លួន
    </a>

    <a class="nav-link text-danger ms-lg-2" href="reset_data.php" 
       onclick="return confirm('⚠️ បញ្ជាក់៖ តើអ្នកពិតជាចង់លុបទិន្នន័យទាំងអស់មែនទេ?')">
       <i class="bi bi-trash3"></i> Reset
    </a>
    <a class="nav-link text-warning border-start ms-lg-2 ps-lg-3" href="logout.php">
        <i class="bi bi-box-arrow-right"></i> ចាកចេញ
    </a>
</div>
</div>
    </nav>

    <div class="container mb-5">
        <?php
        $inc = $conn->query("SELECT SUM(amount) as total FROM income WHERE user_id = $current_user")->fetch_assoc();
        $exp = $conn->query("SELECT SUM(amount) as total FROM expenses WHERE user_id = $current_user")->fetch_assoc();
        $total_inc = $inc['total'] ?? 0;
        $total_exp = $exp['total'] ?? 0;
        $balance = $total_inc - $total_exp;
        ?>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card p-4 shadow-sm stat-card-inc">
                    <div class="d-flex justify-content-between">
                        <div><h6>ចំណូលសរុប</h6><h3>$<?=number_format($total_inc, 2)?></h3></div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow-sm stat-card-exp">
                    <div class="d-flex justify-content-between">
                        <div><h6>ចំណាយសរុប</h6><h3>$<?=number_format($total_exp, 2)?></h3></div>
                        <i class="bi bi-graph-down-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow-sm stat-card-bal">
                    <div class="d-flex justify-content-between">
                        <div><h6>សមតុល្យនៅសល់</h6><h3>$<?=number_format($balance, 2)?></h3></div>
                        <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">ចំណាយថ្មីៗ</h5>
                        <a href="history.php" class="btn btn-sm btn-outline-primary">មើលទាំងអស់</a>
                    </div>
                    <div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>កាលបរិច្ឆេទ</th>
                <th>រូបភាព</th>
                <th>ប្រភេទ</th>
                <th>បរិយាយ</th>
                <th class="text-end">ចំនួនទឹកប្រាក់</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // បន្ថែម e.description និង e.image ទៅក្នុង Query
            $res = $conn->query("SELECT e.*, c.name FROM expenses e JOIN categories c ON e.category_id = c.id WHERE e.user_id = $current_user ORDER BY date DESC LIMIT 6");
            
            while($row = $res->fetch_assoc()): ?>
            <tr>
                <td class="text-muted small"><?= $row['date'] ?></td>
                
                <td>
                    <?php if(!empty($row['image'])): ?>
                        <img src="uploads/<?= $row['image'] ?>" alt="receipt" class="rounded" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd;">
                    <?php else: ?>
                        <span class="text-muted small">គ្មានរូប</span>
                    <?php endif; ?>
                </td>
                
                <td><span class="badge bg-light text-primary badge-cate border"><?= htmlspecialchars($row['name']) ?></span></td>
                
                <td><?= htmlspecialchars($row['description']) ?></td>
                
                <td class="text-danger fw-bold text-end">-$<?= number_format($row['amount'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-4">របាយការណ៍សង្ខេប</h5>
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="mt-4 small text-center text-muted">
                        ប្រៀបធៀបចំណូល និងចំណាយសរុប
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['ចំណូល', 'ចំណាយ'],
                datasets: [{
                    data: [<?= $total_inc ?>, <?= $total_exp ?>],
                    backgroundColor: ['#43e97b', '#f5576c'],
                    hoverOffset: 10,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>