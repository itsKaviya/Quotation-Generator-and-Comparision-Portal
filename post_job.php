<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'management'){
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
    $budget = mysqli_real_escape_string($conn, $_POST['budget']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    $sql = "INSERT INTO jobs (user_id, service_id, title, description, budget, deadline) 
            VALUES ('$user_id', '$service_id', '$title', '$description', '$budget', '$deadline')";
    
    if($conn->query($sql)){
        $msg = "Job requirement posted successfully!";
        $type = "success";
    } else {
        $msg = "Error posting requirement: " . $conn->error;
        $type = "danger";
    }
}

include("includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4 text-center">Post Job Requirement</h2>
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> p-3 text-center border-0 rounded-4 mb-4" role="alert">
                    <?= $msg ?>
                    <?php if($type == 'success'): ?>
                        <div class="mt-2"><a href="dashboard.php" class="btn btn-sm btn-<?= $type ?> px-4 rounded-pill">Back to Dashboard</a></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Service Category</label>
                        <select name="service_id" class="form-select" required>
                            <option value="" disabled selected>Choose category...</option>
                            <?php
                            $services = $conn->query("SELECT * FROM services");
                            while($row = $services->fetch_assoc()){
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Campus Network Security" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe the requirements in detail..." required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Estimated Budget (₹)</label>
                        <input type="number" name="budget" class="form-control" placeholder="50000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Target Deadline</label>
                        <input type="date" name="deadline" class="form-control" required>
                    </div>
                    <div class="col-12 mt-5">
                        <button type="submit" name="post_job" class="btn btn-primary w-100 py-3">Publish Job Requirement</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
