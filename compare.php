<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$job_id = isset($_GET['job_id']) ? mysqli_real_escape_string($conn, $_GET['job_id']) : null;

if(!$job_id){
    header("Location: dashboard.php");
    exit();
}

// Fetch quotations for this specific job
$query = "SELECT vendors.name, vendors.rating, quotations.price, quotations.delivery_days
          FROM quotations
          JOIN vendors ON quotations.vendor_id = vendors.id
          WHERE job_id = '$job_id'
          ORDER BY price ASC";

$result = $conn->query($query);
$data = [];
$max_price = 1;
while($row = $result->fetch_assoc()){
    if($row['price'] > $max_price) $max_price = $row['price'];
    $data[] = $row;
}

include("includes/header.php");
?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Visual Comparison Analysis</h2>
        <p class="text-muted">Analyzing price and efficiency across all submitted quotes.</p>
    </div>
    <a href="view_job.php?id=<?= $job_id ?>" class="btn btn-outline-primary px-4 rounded-pill">Back to Job</a>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-12">
        <div class="glass-card p-5">
            <h5 class="fw-bold mb-4">Price Distribution Index</h5>
            <?php if(empty($data)): ?>
                <div class="text-center py-5 text-muted">No data available for comparison.</div>
            <?php else: ?>
                <?php foreach($data as $row): 
                    $percentage = ($row['price'] / $max_price) * 100;
                    $color = ($percentage < 50) ? 'var(--primary)' : (($percentage < 80) ? '#f59e0b' : '#ef4444');
                ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold"><?= htmlspecialchars($row['name']) ?></span>
                            <span class="fw-bold">₹<?= number_format($row['price']) ?></span>
                        </div>
                        <div class="progress rounded-pill" style="height: 12px; background: rgba(0,0,0,0.05);">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: <?= $percentage ?>%; background-color: <?= $color ?>;" 
                                 aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php foreach($data as $row): ?>
        <div class="col-md-4">
            <div class="glass-card h-100 p-4 text-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="bi bi-building fs-3 text-primary"></i>
                </div>
                <h5 class="fw-bold mb-2"><?= htmlspecialchars($row['name']) ?></h5>
                <div class="mb-3">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="bi bi-star<?= ($i <= round($row['rating'])) ? '-fill' : '' ?> text-warning"></i>
                    <?php endfor; ?>
                </div>
                <div class="display-6 fw-bold text-gradient mb-3">₹<?= number_format($row['price']) ?></div>
                <div class="p-2 bg-light rounded-3 small text-muted mb-0">
                    <i class="bi bi-clock-history me-1"></i> Delivery: <?= $row['delivery_days'] ?> Days
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include("includes/footer.php"); ?>
