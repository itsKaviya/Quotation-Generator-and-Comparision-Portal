<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$service_id = isset($_GET['service_id']) ? $_GET['service_id'] : null;

if(!$service_id){
    header("Location: dashboard.php");
    exit();
}

// Fetch quotations for comparison
$query = "SELECT vendors.name, vendors.rating, quotations.price, quotations.delivery_days
          FROM quotations
          JOIN vendors ON quotations.vendor_id = vendors.id
          WHERE service_id = '$service_id'
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

<h3 class="mb-4"><i class="bi bi-graph-up text-primary"></i> Detailed Visual Comparison</h3>

<div class="row g-4 mb-5">
    <div class="col-md-12">
        <div class="card p-4 shadow-sm border-0">
            <h5>Price Comparison Chart</h5>
            <hr>
            <?php foreach($data as $row): 
                $percentage = ($row['price'] / $max_price) * 100;
                $color = ($percentage < 50) ? 'bg-success' : (($percentage < 80) ? 'bg-warning' : 'bg-danger');
            ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold"><?= htmlspecialchars($row['name']) ?></span>
                        <span>₹<?= number_format($row['price']) ?></span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar <?= $color ?> progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: <?= $percentage ?>%" 
                             aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                             <?= number_format($percentage, 0) ?>%
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php foreach($data as $row): ?>
        <div class="col-md-4">
            <div class="card h-100 p-3 text-center border-0 shadow-sm">
                <div class="mb-2"><i class="bi bi-building fs-3 text-primary"></i></div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['name']) ?></h5>
                <div class="mb-3">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="bi bi-star-fill text-<?= ($i <= round($row['rating'])) ? 'warning' : 'secondary opacity-25' ?>"></i>
                    <?php endfor; ?>
                </div>
                <div class="display-6 fw-bold mb-2">₹<?= number_format($row['price']) ?></div>
                <p class="text-muted small"><i class="bi bi-truck"></i> Delivery: <?= $row['delivery_days'] ?> Days</p>
                <div class="mt-auto">
                    <a href="generate_quote.php?service_id=<?= $service_id ?>" class="btn btn-outline-primary w-100">Select Quote</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include("includes/footer.php"); ?>
