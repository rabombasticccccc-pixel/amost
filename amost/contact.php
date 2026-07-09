<?php 
include 'db_connect.php'; 

$message = "";
if (isset($_POST['send_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // បញ្ចូលទៅក្នុង Table (អ្នកត្រូវបង្កើត Table ឈ្មោះ contact_messages ជាមុន)
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$content')";
    
    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success mt-3'>សាររបស់អ្នកត្រូវបានរក្សាទុកក្នុងប្រព័ន្ធ!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ទាក់ទងមកយើង - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f0f2f5; }
        .contact-card { border: none; border-radius: 20px; overflow: hidden; }
        .info-sidebar { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; padding: 40px; }
        .btn-send { background-color: #6366f1; color: white; border-radius: 10px; padding: 12px; border: none; }
        .btn-send:hover { background-color: #4f46e5; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card contact-card shadow-lg">
                <div class="row g-0">
                    <div class="col-md-5 info-sidebar d-flex flex-column justify-content-center text-center text-md-start">
                        <h2 class="fw-bold mb-4">ទាក់ទងមកយើង</h2>
                        <p class="mb-5">ប្រសិនបើអ្នកមានចម្ងល់ ឬបញ្ហាបច្ចេកទេសក្នុងកម្មវិធី សូមផ្ញើសារមកកាន់ពួកយើងដោយសេរី។</p>
                        
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-geo-alt-fill fs-4 me-3"></i>
                            <span>វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ឈើទាល, ខេត្តកំពង់ធំ</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-telephone-fill fs-4 me-3"></i>
                            <span>+855 17 641 363</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope-at-fill fs-4 me-3"></i>
                            <span>rabombasticccccc@gmail.com</span>
                        </div>
                    </div>

                    <div class="col-md-7 p-4 p-md-5 bg-white">
                        <h4 class="fw-bold mb-4">ផ្ញើសារមកកាន់ក្រុមការងារ</h4>
                        <?= $message; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">ឈ្មោះពេញ</label>
                                <input type="text" name="name" class="form-control" placeholder="បញ្ចូលឈ្មោះរបស់អ្នក" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">អ៊ីមែល</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">សាររបស់អ្នក</label>
                                <textarea name="content" class="form-control" rows="4" placeholder="តើអ្នកចង់ឱ្យយើងជួយអ្វីខ្លះ?" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="send_message" class="btn btn-send shadow-sm">
                                    <i class="bi bi-send-fill me-2"></i>ផ្ញើសារឥឡូវនេះ
                                </button>
                                <a href="index.php" class="btn btn-link text-decoration-none text-muted mt-2">ត្រឡប់ក្រោយ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>