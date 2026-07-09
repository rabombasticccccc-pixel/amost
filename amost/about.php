<?php 
include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>អំពី SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Kantumruy Pro', sans-serif; 
            background-color: #f4f7fe;
        }
        .about-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 80px 0;
            border-radius: 0 0 50px 50px;
        }
        .card-tech {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s;
        }
        .card-tech:hover {
            transform: translateY(-10px);
        }
        .icon-box {
            width: 60px;
            height: 60px;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header class="about-header text-center mb-5">
    <div class="container">
        <h1 class="fw-bold">អំពីប្រព័ន្ធ SmartWallet</h1>
        <p class="lead">ដំណោះស្រាយឌីជីថល ដើម្បីវិន័យហិរញ្ញវត្ថុ និងការរស់នៅកាន់តែប្រសើរ</p>
    </div>
</header>

<div class="container mb-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4 text-primary">ចក្ខុវិស័យរបស់គម្រោង</h2>
            <p class="text-muted" style="line-height: 1.8;">
                **SmartWallet** ត្រូវបានបង្កើតឡើងក្នុងគោលបំណងជួយឱ្យបុគ្គលគ្រប់រូបអាចគ្រប់គ្រងចំណូល និងចំណាយប្រចាំថ្ងៃបានយ៉ាងងាយស្រួល។ 
                យើងជឿជាក់ថា ការមានតម្លាភាពលើចរន្តសាច់ប្រាក់ គឺជាជំហានដំបូងនៃភាពជោគជ័យផ្នែកហិរញ្ញវត្ថុ និងការកាត់បន្ថយបំណុលដែលមិនចាំបាច់។
            </p>
            <div class="row mt-4">
                <div class="col-6">
                    <h4 class="fw-bold text-dark">១០០%</h4>
                    <p class="small text-muted">សុវត្ថិភាពទិន្នន័យ</p>
                </div>
                <div class="col-6">
                    <h4 class="fw-bold text-dark">២៤/៧</h4>
                    <p class="small text-muted">តាមដានបានគ្រប់ពេល</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <img src="https://img.freepik.com/free-vector/personal-finance-concept-illustration_114360-5481.jpg" class="img-fluid rounded-4 shadow" alt="About SmartWallet">
        </div>
    </div>

    <h3 class="text-center fw-bold mb-5 mt-5">បច្ចេកវិទ្យាដែលប្រើប្រាស់</h3>
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="card card-tech shadow-sm p-4">
                <div class="icon-box mx-auto"><i class="bi bi-code-slash"></i></div>
                <h5 class="fw-bold">PHP 8.x</h5>
                <p class="small text-muted">ប្រើសម្រាប់គ្រប់គ្រងតក្កវិជ្ជាផ្នែក Backend និងការបញ្ជូនទិន្នន័យ។</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-tech shadow-sm p-4">
                <div class="icon-box mx-auto"><i class="bi bi-database"></i></div>
                <h5 class="fw-bold">MySQL</h5>
                <p class="small text-muted">ប្រព័ន្ធគ្រប់គ្រងមូលដ្ឋានទិន្នន័យ សម្រាប់រក្សាទុកប្រតិបត្តិការ។</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-tech shadow-sm p-4">
                <div class="icon-box mx-auto"><i class="bi bi-layout-text-window-reverse"></i></div>
                <h5 class="fw-bold">Bootstrap 5</h5>
                <p class="small text-muted">សម្រាប់ការរចនា UI/UX ឱ្យមានភាពទាក់ទាញ និង Responsive។</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-tech shadow-sm p-4">
                <div class="icon-box mx-auto"><i class="bi bi-pie-chart"></i></div>
                <h5 class="fw-bold">Chart.js</h5>
                <p class="small text-muted">ប្រើសម្រាប់បង្ហាញរបាយការណ៍សង្ខេបជារូបភាពក្រាហ្វិក។</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 py-5">
        <a href="index.php" class="btn btn-primary btn-lg rounded-pill px-5">
            <i class="bi bi-house-door me-2"></i>ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>