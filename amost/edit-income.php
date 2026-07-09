<?php 
include 'db_connect.php'; 

// ១. ពិនិត្យមើលថាតើមាន ID បញ្ជូនមកតាម URL ឬទេ
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // ទាញទិន្នន័យចាស់ពី Table income
    $result = $conn->query("SELECT * FROM income WHERE id = $id AND user_id = $current_user");
    $data = $result->fetch_assoc();

    if (!$data) {
        die("រកមិនឃើញទិន្នន័យឡើយ!");
    }
}

// ២. នៅពេលអ្នកប្រើប្រាស់ចុចប៊ូតុង "រក្សាទុកការកែប្រែ"
if (isset($_POST['update_income'])) {
    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $date   = $_POST['date'];
    
    $sql = "UPDATE income SET source='$source', amount='$amount', date='$date' 
            WHERE id=$id AND user_id=$current_user";
    
    if ($conn->query($sql)) {
        header("Location: index.php"); // ឬទៅកាន់ history-income.php បើអ្នកមាន
        exit();
    } else {
        echo "បញ្ហា: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>កែសម្រួលចំណូល - SmartWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0fdf4; font-family: 'Khmer OS Battambang', sans-serif; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm p-4">
                <h4 class="text-success mb-4 text-center fw-bold">កែសម្រួលព័ត៌មានចំណូល</h4>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">ប្រភពចំណូល</label>
                        <input type="text" name="source" class="form-control" 
                               value="<?= $data['source']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ចំនួនទឹកប្រាក់ ($)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" 
                               value="<?= $data['amount']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ថ្ងៃខែ</label>
                        <input type="date" name="date" class="form-control" 
                               value="<?= $data['date']; ?>" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="update_income" class="btn btn-success py-2 fw-bold">រក្សាទុកការកែប្រែ</button>
                        <a href="index.php" class="btn btn-light py-2">បោះបង់</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>