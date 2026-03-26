<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

include("includes/header.php");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome back, <span class="text-primary"><?= htmlspecialchars($user_name) ?></span>!</h2>
    <a href="post_job.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Post New Requirement</a>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-8">
        <div class="card p-4">
            <h4 class="mb-4">Generate New Quotation</h4>
            <form action="generate_quote.php" method="POST">
                <div class="row g-3 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label fw-bold">Select Service Category</label>
                        <select name="service_id" class="form-select form-select-lg" required>
                            <option value="" disabled selected>Choose a category...</option>
                            <?php
                            $services = $conn->query("SELECT * FROM services");
                            while($row = $services->fetch_assoc()){
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Compare Quotes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100 bg-primary text-white">
            <h4>Quick Info</h4>
            <p class="mb-0">Our system uses a unique scoring algorithm to find the most value-for-money vendor based on Price, Delivery, and Ratings.</p>
        </div>
    </div>
</div>

<div class="card p-4">
    <h4 class="mb-4">My Recent Requirements</h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date Posted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $my_jobs = $conn->query("SELECT jobs.*, services.name as service_name FROM jobs JOIN services ON jobs.service_id = services.id WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 5");
                if($my_jobs->num_rows > 0):
                    while($row = $my_jobs->fetch_assoc()):
                ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($row['title']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['service_name']) ?></span></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td><a href="generate_quote.php?service_id=<?= $row['service_id'] ?>" class="btn btn-sm btn-outline-primary">Compare Quotes</a></td>
                    </tr>
                <?php 
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No requirements posted yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>