<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$msg = "";
$type = "";

if(isset($_POST['post_job'])){
    $user_id = $_SESSION['user_id'];
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO jobs (user_id, service_id, title, description) VALUES ('$user_id', '$service_id', '$title', '$description')";
    
    if($conn->query($sql)){
        $msg = "Requirement posted successfully!";
        $type = "success";
    } else {
        $msg = "Error posting requirement: " . $conn->error;
        $type = "danger";
    }
}

include("includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4">
            <h3 class="mb-4"><i class="bi bi-pencil-square text-primary"></i> Post Your Requirement</h3>
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                    <?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Service Category</label>
                    <select name="service_id" class="form-select" required>
                        <option value="" disabled selected>Choose a category...</option>
                        <?php
                        $services = $conn->query("SELECT * FROM services");
                        while($row = $services->fetch_assoc()){
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Requirement Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., E-commerce Website Design" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Detailed Description</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Describe your needs in detail..." required></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" name="post_job" class="btn btn-primary btn-lg">Submit Requirement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
