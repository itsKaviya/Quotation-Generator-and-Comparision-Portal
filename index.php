<?php 
session_start();
include("includes/header.php"); 
?>

<div class="hero-section py-5 mb-5 text-center">
    <h1 class="display-3 fw-bold mb-3">Smarter <span class="text-gradient">Quotations</span>.<br>Better Decisions.</h1>
    <p class="lead mb-5 mx-auto text-muted" style="max-width: 700px; font-weight: 400;">
        A premium portal for management to post requirements and vendors to provide high-value quotations with intelligent comparison logic.
    </p>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="d-flex justify-content-center gap-3">
            <a href="register.php" class="btn btn-primary btn-lg px-5 py-3">Get Started Today</a>
            <a href="login.php" class="btn btn-outline-primary btn-lg px-5 py-3">Partner Login</a>
        </div>
    <?php else: ?>
        <a href="dashboard.php" class="btn btn-primary btn-lg px-5 py-3">Enter Dashboard <i class="bi bi-arrow-right ms-2"></i></a>
    <?php endif; ?>
</div>

<div class="row g-4 py-5">
    <div class="col-md-4">
        <div class="glass-card h-100 p-5">
            <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block mb-4">
                <i class="bi bi-bar-chart-fill fs-2 text-primary"></i>
            </div>
            <h3 class="fw-bold h4 mb-3">Smart Bidding</h3>
            <p class="text-muted">Our intelligent engine ranks vendor quotations based on price, reputation, and delivery efficiency.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card h-100 p-5">
            <div class="bg-secondary bg-opacity-10 p-3 rounded-4 d-inline-block mb-4">
                <i class="bi bi-lightning-charge-fill fs-2 text-secondary"></i>
            </div>
            <h3 class="fw-bold h4 mb-3">Instant Insights</h3>
            <p class="text-muted">Generate detailed comparison reports and visual analytics to make data-driven procurement decisions.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card h-100 p-5">
            <div class="bg-success bg-opacity-10 p-3 rounded-4 d-inline-block mb-4">
                <i class="bi bi-shield-check fs-2 text-success"></i>
            </div>
            <h3 class="fw-bold h4 mb-3">Vendor Trust</h3>
            <p class="text-muted">Every vendor is vetted and rated by management to ensure the highest quality of service delivery.</p>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>