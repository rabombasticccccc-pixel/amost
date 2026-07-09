<?php 
include 'db_connect.php'; 

// ត្រួតពិនិត្យសិទ្ធិ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // បើមិនមែនជា Admin ទេ មិនឱ្យចូលមើលដាច់ខាត
    header("Location: index.php"); 
    exit();
}

// មុខងារលុបអ្នកប្រើប្រាស់
if (isset($_GET['delete_user'])) {
    $u_id = intval($_GET['delete_user']);
    
    // ការពារកុំឱ្យ Admin លុបខ្លួនឯង
    if ($u_id != 1) {
        // លុបទិន្នន័យពាក់ព័ន្ធទាំងអស់ជាមុនសិន (Incomes & Expenses)
        $conn->query("DELETE FROM income WHERE user_id = $u_id");
        $conn->query("DELETE FROM expenses WHERE user_id = $u_id");
        // បន្ទាប់មកលុប User
        $conn->query("DELETE FROM users WHERE id = $u_id");
        header("Location: manage_users.php?msg=deleted");
    } else {
        header("Location: manage_users.php?msg=error");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងអ្នកប្រើប្រាស់ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
       body { background: #f4f7fa; font-family: 'Kantumruy Pro', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; color: white; position: fixed; }
        .main-content { margin-left: 260px; padding: 40px; }
        .user-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1e293b; color: #38bdf8; }
        .table thead { background: #f1f5f9; }
        .table th { border: none; padding: 15px; color: #64748b; font-size: 0.85rem; }
        .avatar-circle { width: 40px; height: 40px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; color: #475569; }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h4 class="text-center fw-bold mb-4 mt-2 text-info">ADMIN PANEL</h4>
    <hr>
    <nav class="nav flex-column">
        <a class="nav-link" href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a class="nav-link active" href="manage_users.php"><i class="bi bi-people me-2"></i> គ្រប់គ្រងអ្នកប្រើ</a>
        <a class="nav-link" href="categories.php"><i class="bi bi-tags me-2"></i> គ្រប់គ្រងប្រភេទ</a>
         <a class="nav-link" href="all_transactions.php"><i class="bi bi-list-check me-2"></i> ប្រតិបត្តិការទាំងអស់</a>
         <a class="nav-link" href="index.php"><i class="bi bi-house-door me-2"></i> ត្រឡប់ទៅទំព័រដើម</a>
        <hr>
        <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> ចាកចេញ</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-slate-800">បញ្ជីអ្នកប្រើប្រាស់ទាំងអស់</h3>
        <button class="btn btn-primary rounded-pill px-4" onclick="window.print()">
            <i class="bi bi-download me-2"></i> ទាញយករបាយការណ៍
        </button>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i> លុបគណនីអ្នកប្រើប្រាស់បានជោគជ័យ!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="user-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ព័ត៌មានអ្នកប្រើ</th>
                        <th>អ៊ីមែល</th>
                        <th>ថ្ងៃចុះឈ្មោះ</th>
                        <th class="text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $users = $conn->query("SELECT * FROM users ORDER BY id ASC");
                    while($u = $users->fetch_assoc()):
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <?= strtoupper(substr($u['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($u['username']) ?></div>
                                    <small class="text-muted">ID: #<?= $u['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small"><?= date('d M, Y', strtotime($u['created_at'] ?? date('Y-m-d'))) ?></td>
                        <td class="text-center">
                            <?php if($u['id'] != 1): ?>
                                <a href="manage_users.php?delete_user=<?= $u['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger px-3 rounded-pill"
                                   onclick="return confirm('តើអ្នកប្រាកដថាចង់លុបអ្នកប្រើប្រាស់នេះទេ? រាល់ទិន្នន័យហិរញ្ញវត្ថុរបស់ពួកគេនឹងត្រូវបាត់បង់!')">
                                    <i class="bi bi-trash me-1"></i> លុប
                                </a>
                            <?php else: ?>
                                <span class="badge bg-soft-primary text-primary border border-primary px-3 py-2">ម្ចាស់ប្រព័ន្ធ</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>