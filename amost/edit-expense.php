<?php 
include 'db_connect.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM expenses WHERE id = $id AND user_id = $current_user");
    $data = $result->fetch_assoc();

    if (!$data) {
        header("Location: history.php");
        exit();
    }
} else {
    header("Location: history.php");
    exit();
}

// នៅពេលអ្នកប្រើប្រាស់ចុចប៊ូតុង "រក្សាទុកការកែប្រែ"
if (isset($_POST['update_expense'])) {
    $cat_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date   = $_POST['date'];
    $desc   = $conn->real_escape_string($_POST['description']);
    $image_name = $data['image']; // រក្សាទុកឈ្មោះរូបភាពចាស់ជាមុន

    // ពិនិត្យមើលថាតើមានការ Upload រូបភាពថ្មីឬទេ
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '.' . $ext; 
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name)){
            // បើ Upload ជោគជ័យ ហើយមានរូបភាពចាស់ ត្រូវលុបរូបភាពចាស់ចេញពី Folder ដើម្បីកុំឱ្យធ្ងន់ Server
            if(!empty($data['image']) && file_exists('uploads/' . $data['image'])){
                unlink('uploads/' . $data['image']);
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE expenses SET category_id=?, amount=?, date=?, description=?, image=? WHERE id=? AND user_id=?");
    $stmt->bind_param("idssssi", $cat_id, $amount, $date, $desc, $image_name, $id, $current_user);
    
    if ($stmt->execute()) {
        header("Location: history.php?status=updated");
        exit();
    } else {
        $error_msg = "មានបញ្ហា: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>កែសម្រួលការចំណាយ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro&display=swap" rel="stylesheet">
    <style>body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8f9fa; }</style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h4 class="mb-4">កែសម្រួលការចំណាយ</h4>
                    
                    <form method="POST" enctype="multipart/form-data"> <div class="mb-3">
                            <label class="form-label">ប្រភេទចំណាយ</label>
                            <select name="category_id" class="form-select">
                                <?php 
                                $cats = $conn->query("SELECT * FROM categories");
                                while($c = $cats->fetch_assoc()): 
                                ?>
                                    <option value="<?= $c['id']; ?>" <?= ($c['id'] == $data['category_id']) ? 'selected' : ''; ?>>
                                        <?= $c['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ចំនួនទឹកប្រាក់ ($)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" value="<?= $data['amount']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ថ្ងៃខែ</label>
                            <input type="date" name="date" class="form-control" value="<?= $data['date']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">រូបភាពបច្ចុប្បន្ន</label>
                            <div class="mb-2">
                                <?php if(!empty($data['image'])): ?>
                                    <img src="uploads/<?= $data['image']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                <?php else: ?>
                                    <p class="text-muted small">មិនមានរូបភាពទេ</p>
                                <?php endif; ?>
                            </div>
                            <label class="form-label">ប្តូររូបភាពថ្មី</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">កំណត់ចំណាំ</label>
                            <textarea name="description" class="form-control" rows="2"><?= $data['description']; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_expense" class="btn btn-primary">រក្សាទុកការកែប្រែ</button>
                            <a href="history.php" class="btn btn-light">បោះបង់</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>