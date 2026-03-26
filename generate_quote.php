<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$service_id = isset($_POST['service_id']) ? $_POST['service_id'] : (isset($_GET['service_id']) ? $_GET['service_id'] : null);

if(!$service_id){
    header("Location: dashboard.php");
    exit();
}

// Fetch service name
$service_res = $conn->query("SELECT name FROM services WHERE id = '$service_id'");
$service_name = ($service_res->num_rows > 0) ? $service_res->fetch_assoc()['name'] : "Unknown Service";

// Fetch quotations with vendor info
$query = "SELECT vendors.name, vendors.rating, quotations.price, quotations.delivery_days, quotations.id as quote_id
          FROM quotations
          JOIN vendors ON quotations.vendor_id = vendors.id
          WHERE service_id = '$service_id'";

$result = $conn->query($query);

$data = [];
$best_score = PHP_INT_MAX;
$best_vendor_name = "";

while($row = $result->fetch_assoc()){
    // Weighted logic: Price (lower better), Delivery (lower better), Rating (higher better)
    // Custom Score = Price + (Delivery * 10) - (Rating * 20)
    $score = $row['price'] + ($row['delivery_days'] * 10) - ($row['rating'] * 20);
    
    $row['score'] = $score;
    $data[] = $row;

    if($score < $best_score){
        $best_score = $score;
        $best_vendor_name = $row['name'];
    }
}

// Sort data by score (ascending)
usort($data, function($a, $b) {
    return $a['score'] <=> $b['score'];
});

include("includes/header.php");
?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Comparison</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="compare.php?service_id=<?= $service_id ?>" class="btn btn-outline-primary">
            <i class="bi bi-graph-up"></i> Visual Comparison
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-file-earmark-pdf"></i> Download PDF
        </button>
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>

<div class="card shadow-lg border-0 mb-4 print-only-full">
    <div class="card-header bg-primary text-white py-3">
        <h4 class="mb-0">Smart Quotation Comparison for: <span class="fw-light"><?= htmlspecialchars($service_name) ?></span></h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="comparisonTable">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Vendor Name</th>
                        <th class="py-3">Price (₹)</th>
                        <th class="py-3">Delivery Time</th>
                        <th class="py-3">Vendor Rating</th>
                        <th class="py-3">Smart Score</th>
                        <th class="text-center py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No quotations found for this service.</td></tr>
                    <?php else: ?>
                        <?php foreach($data as $row): 
                            $is_best = ($row['name'] == $best_vendor_name);
                        ?>
                            <tr class="<?= $is_best ? 'best-option fw-bold' : '' ?>">
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building me-2 opacity-50"></i>
                                        <?= htmlspecialchars($row['name']) ?>
                                    </div>
                                </td>
                                <td>₹<?= number_format($row['price'], 2) ?></td>
                                <td><?= $row['delivery_days'] ?> Days</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?= $row['rating'] ?> <i class="bi bi-star-fill"></i>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= number_format($row['score'], 0) ?></small>
                                </td>
                                <td class="text-center px-4">
                                    <?php if($is_best): ?>
                                        <div class="mb-1">
                                            <span class="badge rounded-pill bg-success px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i> Best Value
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <a href="vendor_rating.php?vendor_id=<?= $row['id'] ?>" class="btn btn-sm btn-link text-decoration-none p-0 no-print">Rate This Vendor</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(!empty($data)): ?>
<div class="row no-print">
    <div class="col-md-12">
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                <strong>How we calculate:</strong> Our comparison engine uses a weighted algorithm: 
                <code class="mx-1">Price + (Delivery × 10) - (Rating × 20)</code>. 
                A lower score indicates better overall value for your requirements.
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
    @media print {
        .no-print { display: none !important; }
        .navbar { display: none !important; }
        .footer { display: none !important; }
        body { background-color: white !important; padding: 0 !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
        .best-option { background-color: #f0fff4 !important; color: black !important; }
        .print-only-full { width: 100% !important; margin: 0 !important; }
    }
</style>

<?php include("includes/footer.php"); ?>