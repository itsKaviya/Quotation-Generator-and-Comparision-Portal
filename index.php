<?php 
session_start();
include("includes/header.php"); 
?>

<div class="hero-section py-5 mb-5 text-center">
    <h1 class="display-4 fw-bold text-primary mb-3">Smarter Quotations. Better Decisions.</h1>
    <p class="lead mb-4 mx-auto" style="max-width: 700px;">
        Unlike traditional platforms, our system focuses on personalized quotation generation and structured comparison rather than just product listing.
    </p>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="register.php" class="btn btn-primary btn-lg px-5 me-sm-3">Get Started</a>
        <a href="login.php" class="btn btn-outline-primary btn-lg px-5">Login</a>
    <?php else: ?>
        <a href="dashboard.php" class="btn btn-primary btn-lg px-5">Go to Dashboard</a>
    <?php endif; ?>
</div>

<div class="row g-4 py-5">
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="feature-icon mb-3">
                <i class="bi bi-bar-chart-fill fs-2 text-primary"></i>
            </div>
            <h3 class="h5 fw-bold">Smart Comparison</h3>
            <p>Our intelligent engine compares vendors based on price, rating, and delivery time to find the best fit for you.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="feature-icon mb-3">
                <i class="bi bi-file-earmark-pdf-fill fs-2 text-primary"></i>
            </div>
            <h3 class="h5 fw-bold">Auto-Quotation</h3>
            <p>Generate professional quotations instantly based on your specific service requirements.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="feature-icon mb-3">
                <i class="bi bi-star-fill fs-2 text-primary"></i>
            </div>
            <h3 class="h5 fw-bold">Vendor Ratings</h3>
            <p>Make informed decisions with our built-in vendor rating and review system.</p>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>