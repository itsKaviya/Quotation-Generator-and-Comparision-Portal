<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$msg = "";
$type = "";

if(isset($_POST['rate_vendor'])){
    $user_id = $_SESSION['user_id'];
    $vendor_id = mysqli_real_escape_string($conn, $_POST['vendor_id']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);

    // Update vendor rating (Simple average logic)
    $sql = "UPDATE vendors SET rating = (rating + $rating) / 2 WHERE id = '$vendor_id'";
    
    if($conn->query($sql)){
        $msg = "Thank you for your feedback! Your rating helps maintain service quality.";
        $type = "success";
    } else {
        $msg = "Error updating rating: " . $conn->error;
        $type = "danger";
    }
}

$vendor_id = isset($_GET['vendor_id']) ? mysqli_real_escape_string($conn, $_GET['vendor_id']) : null;
$vendor_name = "";
if($vendor_id){
    $v_res = $conn->query("SELECT name FROM vendors WHERE id = '$vendor_id'");
    if($v_res->num_rows > 0) $vendor_name = $v_res->fetch_assoc()['name'];
}

include("includes/header.php");
?>

<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-5 text-center">
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                <i class="bi bi-star-fill fs-2 text-warning"></i>
            </div>
            <h2 class="fw-bold mb-4">Rate Vendor</h2>
            
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> border-0 rounded-4 p-3 mb-4" role="alert"><?= $msg ?></div>
                <div class="d-grid"><a href="dashboard.php" class="btn btn-primary py-3">Back to Dashboard</a></div>
            <?php elseif(!$vendor_id): ?>
                <div class="alert alert-warning border-0 rounded-4">No vendor selected for rating.</div>
                <div class="mt-3"><a href="dashboard.php" class="btn btn-outline-primary px-4 py-2">Go to Dashboard</a></div>
            <?php else: ?>
                <p class="lead text-muted mb-5">How was your experience with<br><strong class="text-dark"><?= htmlspecialchars($vendor_name) ?></strong>?</p>
                
                <form method="POST">
                    <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>">
                    <div class="mb-5">
                        <select name="rating" class="form-select form-select-lg text-center fw-bold border-2" style="border-color: rgba(99, 102, 241, 0.2);" required>
                            <option value="5" selected>5 - Exceptional ⭐⭐⭐⭐⭐</option>
                            <option value="4">4 - Very Good ⭐⭐⭐⭐</option>
                            <option value="3">3 - Good ⭐⭐⭐</option>
                            <option value="2">2 - Fair ⭐⭐</option>
                            <option value="1">1 - Poor ⭐</option>
                        </select>
                    </div>
                    <div class="d-grid gap-3">
                        <button type="submit" name="rate_vendor" class="btn btn-primary py-3">Submit Review</button>
                        <a href="dashboard.php" class="btn btn-link text-muted text-decoration-none small">Skip for now</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
