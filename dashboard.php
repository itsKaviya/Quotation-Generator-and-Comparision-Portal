<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

include("includes/header.php");
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h1 class="fw-bold mb-1">Welcome, <span class="text-gradient"><?= htmlspecialchars($user_name) ?></span></h1>
        <p class="text-muted mb-0">You are logged in as <span class="badge badge-premium text-capitalize"><?= $user_role ?></span></p>
    </div>
    <?php if($user_role == 'management'): ?>
        <a href="post_job.php" class="btn btn-primary px-4 py-3"><i class="bi bi-plus-lg me-2"></i> Post New Job</a>
    <?php endif; ?>
</div>

<?php if($user_role == 'management'): ?>
    <!-- Management Dashboard -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Total Jobs</h5>
                </div>
                <?php 
                $count_jobs = $conn->query("SELECT COUNT(*) as total FROM jobs WHERE user_id = '$user_id'")->fetch_assoc();
                ?>
                <h2 class="fw-bold"><?= $count_jobs['total'] ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="bi bi-chat-dots fs-4 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Quotations</h5>
                </div>
                <?php 
                $count_quotes = $conn->query("SELECT COUNT(*) as total FROM quotations JOIN jobs ON quotations.job_id = jobs.id WHERE jobs.user_id = '$user_id'")->fetch_assoc();
                ?>
                <h2 class="fw-bold"><?= $count_quotes['total'] ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4 h-100 grad-bg text-white" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                <h5 class="fw-bold mb-3">Smart Recommendation</h5>
                <p class="small opacity-75">Click on any job to see our AI-driven recommendation for the best vendor.</p>
                <i class="bi bi-stars fs-1 opacity-25 float-end"></i>
            </div>
        </div>
    </div>

    <div class="glass-card p-4 border-0">
        <h4 class="fw-bold mb-4">My Posted Jobs</h4>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small uppercase">
                        <th>Job Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Quotes</th>
                        <th>Posted Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jobs_query = "SELECT jobs.*, services.name as service_name, (SELECT COUNT(*) FROM quotations WHERE job_id = jobs.id) as quote_count 
                                   FROM jobs 
                                   JOIN services ON jobs.service_id = services.id 
                                   WHERE jobs.user_id = '$user_id' 
                                   ORDER BY jobs.created_at DESC";
                    $jobs_res = $conn->query($jobs_query);
                    if($jobs_res->num_rows > 0):
                        while($job = $jobs_res->fetch_assoc()):
                    ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($job['title']) ?></div>
                            </td>
                            <td><span class="badge badge-premium"><?= htmlspecialchars($job['service_name']) ?></span></td>
                            <td>
                                <span class="badge rounded-pill bg-<?= $job['status'] == 'open' ? 'success' : 'secondary' ?> bg-opacity-10 text-<?= $job['status'] == 'open' ? 'success' : 'secondary' ?> px-3">
                                    <?= ucfirst($job['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-chat-left-text me-2 opacity-50"></i>
                                    <?= $job['quote_count'] ?> Quotes
                                </div>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($job['created_at'])) ?></td>
                            <td class="text-end">
                                <a href="view_job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary px-3 rounded-pill">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No jobs posted yet. <a href="post_job.php">Post your first job here.</a></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- Vendor Dashboard -->
    <div class="row g-4 mb-5">
        <div class="col-md-8">
            <div class="glass-card p-4">
                <h4 class="fw-bold mb-4">Marketplace: Available Jobs</h4>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Job Details</th>
                                <th>Category</th>
                                <th>Budget</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $market_query = "SELECT jobs.*, services.name as service_name FROM jobs 
                                             JOIN services ON jobs.service_id = services.id 
                                             WHERE jobs.status = 'open' 
                                             ORDER BY jobs.created_at DESC LIMIT 10";
                            $market_res = $conn->query($market_query);
                            if($market_res->num_rows > 0):
                                while($job = $market_res->fetch_assoc()):
                            ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($job['title']) ?></div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;"><?= htmlspecialchars($job['description']) ?></div>
                                    </td>
                                    <td><span class="badge badge-premium"><?= htmlspecialchars($job['service_name']) ?></span></td>
                                    <td class="fw-bold text-primary">₹<?= number_format($job['budget'], 0) ?></td>
                                    <td class="text-end">
                                        <a href="view_job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-primary px-4 rounded-pill">Quote Now</a>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No jobs available in the marketplace right now.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">My Stats</h5>
                <?php
                $vendor_id_res = $conn->query("SELECT id, rating FROM vendors WHERE user_id = '$user_id'")->fetch_assoc();
                $v_id = $vendor_id_res['id'];
                $v_rating = $vendor_id_res['rating'];
                $my_quotes_count = $conn->query("SELECT COUNT(*) as total FROM quotations WHERE vendor_id = '$v_id'")->fetch_assoc();
                ?>
                <div class="mb-3">
                    <label class="text-muted small">Active Quotes</label>
                    <h3 class="fw-bold mb-0"><?= $my_quotes_count['total'] ?></h3>
                </div>
                <div>
                    <label class="text-muted small">Profile Rating</label>
                    <div class="d-flex align-items-center">
                        <h3 class="fw-bold mb-0 me-2"><?= number_format($v_rating, 1) ?></h3>
                        <div class="text-warning">
                            <?php for($i=1; $i<=5; $i++) echo '<i class="bi bi-star'.($i <= round($v_rating) ? '-fill' : '').'"></i>'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="glass-card p-4 bg-primary bg-opacity-10 border-primary border-opacity-25">
                <h6 class="fw-bold text-primary mb-2">Pro Tip</h6>
                <p class="small text-muted mb-0">Provide accurate delivery timelines and competitive pricing to improve your Smart Score and get recommended!</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include("includes/footer.php"); ?>