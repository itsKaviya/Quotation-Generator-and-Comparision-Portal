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
    // In a real system, we'd have a reviews table, but I'll update the vendors.rating directly for simplicity as per user's schema
    $sql = "UPDATE vendors SET rating = (rating + $rating) / 2 WHERE id = '$vendor_id'";
    
    if($conn->query($sql)){
        $msg = "Thank you for your rating!";
        $type = "success";
    } else {
        $msg = "Error updating rating: " . $conn->error;
        $type = "danger";
    }
}

$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : null;
$vendor_name = "";
if($vendor_id){
    $v_res = $conn->query("SELECT name FROM vendors WHERE id = '$vendor_id'");
    if($v_res->num_rows > 0) $vendor_name = $v_res->fetch_assoc()['name'];
}

include("includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4">
            <h3 class="mb-4 text-center">Rate Vendor</h3>
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> text-center" role="alert"><?= $msg ?></div>
                <div class="text-center mt-3"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></div>
            <?php elseif(!$vendor_id): ?>
                <div class="alert alert-warning text-center">No vendor selected.</div>
            <?php else: ?>
                <p class="text-center lead">How was your experience with <strong><?= htmlspecialchars($vendor_name) ?></strong>?</p>
                <form method="POST">
                    <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>">
                    <div class="mb-4 text-center">
                        <select name="rating" class="form-select form-select-lg text-center mx-auto" style="max-width: 200px;" required>
                            <option value="5">5 - Excellent ⭐⭐⭐⭐⭐</option>
                            <option value="4">4 - Very Good ⭐⭐⭐⭐</option>
                            <option value="3">3 - Good ⭐⭐⭐</option>
                            <option value="2">2 - Fair ⭐⭐</option>
                            <option value="1">1 - Poor ⭐</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="rate_vendor" class="btn btn-primary btn-lg">Submit Rating</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
