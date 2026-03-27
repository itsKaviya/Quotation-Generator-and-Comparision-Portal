<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$job_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if(!$job_id){
    header("Location: dashboard.php");
    exit();
}

// Fetch job details
$job_res = $conn->query("SELECT jobs.*, services.name as service_name, users.name as posted_by 
                         FROM jobs 
                         JOIN services ON jobs.service_id = services.id 
                         JOIN users ON jobs.user_id = users.id
                         WHERE jobs.id = '$job_id'");

if($job_res->num_rows == 0){
    header("Location: dashboard.php");
    exit();
}

$job = $job_res->fetch_assoc();

$msg = "";
$type = "";

// Handle Vendor Bidding
if(isset($_POST['submit_quote']) && $user_role == 'vendor'){
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $delivery_days = mysqli_real_escape_string($conn, $_POST['delivery_days']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Get vendor_id
    $vendor_data = $conn->query("SELECT id FROM vendors WHERE user_id = '$user_id'")->fetch_assoc();
    $vendor_id = $vendor_data['id'];

    // Check if already quoted
    $check_quote = $conn->query("SELECT id FROM quotations WHERE job_id = '$job_id' AND vendor_id = '$vendor_id'");
    
    if($check_quote->num_rows > 0){
        $sql = "UPDATE quotations SET price = '$price', delivery_days = '$delivery_days', message = '$message' 
                WHERE job_id = '$job_id' AND vendor_id = '$vendor_id'";
    } else {
        $sql = "INSERT INTO quotations (job_id, vendor_id, price, delivery_days, message) 
                VALUES ('$job_id', '$vendor_id', '$price', '$delivery_days', '$message')";
    }

    if($conn->query($sql)){
        $msg = "Quote submitted successfully!";
        $type = "success";
    } else {
        $msg = "Error submitting quote: " . $conn->error;
        $type = "danger";
    }
}

include("includes/header.php");
?>

<div class="row g-4">
    <!-- Job Details Column -->
    <div class="col-lg-8">
        <div class="glass-card p-5 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <span class="badge badge-premium mb-2"><?= htmlspecialchars($job['service_name']) ?></span>
                    <h1 class="fw-bold mb-1"><?= htmlspecialchars($job['title']) ?></h1>
                    <p class="text-muted"><i class="bi bi-person me-1"></i> Posted by <?= htmlspecialchars($job['posted_by']) ?> • <?= date('M d, Y', strtotime($job['created_at'])) ?></p>
                </div>
                <div class="text-end">
                    <div class="h3 fw-bold text-primary mb-0">₹<?= number_format($job['budget'], 2) ?></div>
                    <div class="small text-muted">Budget</div>
                </div>
            </div>
            
            <h5 class="fw-bold mb-3">Description</h5>
            <p class="mb-4" style="white-space: pre-line; line-height: 1.6;"><?= htmlspecialchars($job['description']) ?></p>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="p-3 border rounded-4 bg-light bg-opacity-50">
                        <div class="small text-muted mb-1">Target Deadline</div>
                        <div class="fw-bold"><i class="bi bi-calendar-event me-2"></i><?= date('M d, Y', strtotime($job['deadline'])) ?></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 border rounded-4 bg-light bg-opacity-50">
                        <div class="small text-muted mb-1">Current Status</div>
                        <div class="fw-bold text-success"><i class="bi bi-check-circle me-2"></i><?= ucfirst($job['status']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($user_role == 'management' && $job['user_id'] == $user_id): ?>
            <!-- Quote Comparison for Management -->
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Received Quotations</h4>
                    <a href="compare.php?job_id=<?= $job_id ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                        <i class="bi bi-graph-up me-1"></i> Visual Comparison
                    </a>
                </div>

                <?php
                $quotes_query = "SELECT quotations.*, vendors.name as vendor_name, vendors.rating, vendors.company_name 
                                 FROM quotations 
                                 JOIN vendors ON quotations.vendor_id = vendors.id 
                                 WHERE job_id = '$job_id'";
                $quotes_res = $conn->query($quotes_query);
                
                if($quotes_res->num_rows > 0):
                    $quotes_data = [];
                    $best_score = PHP_INT_MAX;
                    $best_quote_id = null;

                    while($q = $quotes_res->fetch_assoc()){
                        // Smart Score Algorithm
                        $score = $q['price'] + ($q['delivery_days'] * 10) - ($q['rating'] * 20);
                        $q['smart_score'] = $score;
                        $quotes_data[] = $q;

                        if($score < $best_score){
                            $best_score = $score;
                            $best_quote_id = $q['id'];
                        }
                    }

                    // Sort by score
                    usort($quotes_data, function($a, $b) { return $a['smart_score'] <=> $b['smart_score']; });
                ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Vendor</th>
                                    <th>Price</th>
                                    <th>Delivery</th>
                                    <th>Rating</th>
                                    <th class="text-end">Recommendation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($quotes_data as $q): 
                                    $is_best = ($q['id'] == $best_quote_id);
                                ?>
                                    <tr class="<?= $is_best ? 'recommendation-banner fw-bold' : '' ?>">
                                        <td>
                                            <div><?= htmlspecialchars($q['vendor_name']) ?></div>
                                            <div class="small text-muted fw-normal"><?= htmlspecialchars($q['company_name']) ?></div>
                                        </td>
                                        <td class="text-primary">₹<?= number_format($q['price'], 2) ?></td>
                                        <td><?= $q['delivery_days'] ?> Days</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?= number_format($q['rating'], 1) ?> <i class="bi bi-star-fill ms-1"></i>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if($is_best): ?>
                                                <span class="badge rounded-pill bg-success px-3">
                                                    <i class="bi bi-stars me-1"></i> Highly Recommended
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small fw-normal">Score: <?= round($q['smart_score']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-2"></i> Our recommendation engine calculates the best value based on price, delivery time, and vendor reputation.
                        </small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="display-1 text-muted opacity-25 mb-3"><i class="bi bi-chat-square-dots"></i></div>
                        <p class="text-muted">Waiting for vendors to submit quotes...</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar / Action Column -->
    <div class="col-lg-4">
        <?php if($user_role == 'vendor'): ?>
            <div class="glass-card p-4 mb-4 sticky-top" style="top: 100px;">
                <h4 class="fw-bold mb-4">Submit Your Quote</h4>
                <?php if($msg): ?>
                    <div class="alert alert-<?= $type ?> p-3 text-center border-0 rounded-4 mb-4" role="alert"><?= $msg ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Your Price (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">₹</span>
                            <input type="number" name="price" class="form-control border-start-0" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Delivery Days</label>
                        <div class="input-group">
                            <input type="number" name="delivery_days" class="form-control border-end-0" placeholder="e.g., 7" required>
                            <span class="input-group-text bg-transparent border-start-0">Days</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Proposal Message</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Tell the management why you are the best fit..." required></textarea>
                    </div>
                    <button type="submit" name="submit_quote" class="btn btn-primary w-100 py-3">Submit Quotation</button>
                </form>
            </div>
        <?php else: ?>
            <div class="glass-card p-4 mb-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-3">Job Management</h5>
                <hr class="opacity-10 mb-4">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary py-3 text-start px-3 rounded-4">
                        <i class="bi bi-pencil me-2"></i> Edit Requirement
                    </button>
                    <button class="btn btn-outline-danger py-3 text-start px-3 rounded-4">
                        <i class="bi bi-trash me-2"></i> Pull Recruitment
                    </button>
                    <hr class="opacity-10 my-3">
                    <p class="small text-muted text-center mb-0">Marketplace ID: JOB-<?= str_pad($job_id, 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
