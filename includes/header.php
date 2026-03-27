<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotify | Professional Quotation Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-bs-theme="light">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <svg class="logo-img" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 8H10M7 12H14M18 14V6C18 4.89543 17.1046 4 16 4H4C2.89543 4 2 4.89543 2 6V18L6 14H16C17.1046 14 18 13.1046 18 12M18 14L22 18V10C22 8.89543 21.1046 8 20 8H18V14Z" stroke="url(#logo-grad)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <defs>
                        <linearGradient id="logo-grad" x1="2" y1="4" x2="22" y2="18" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#6366f1"/>
                            <stop offset="1" stop-color="#ec4899"/>
                        </linearGradient>
                    </defs>
                </svg>
                Quotify
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link fw-medium" href="index.php">Home</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link fw-medium" href="dashboard.php">Dashboard</a></li>
                        <?php if($_SESSION['user_role'] == 'management'): ?>
                            <li class="nav-item"><a class="nav-link fw-medium" href="post_job.php">Post Job</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link btn btn-outline-danger btn-sm px-3 ms-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link fw-medium" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary btn-sm px-4 text-white ms-2" href="register.php">Get Started</a></li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-2">
                        <button class="btn btn-link nav-link" id="themeToggle">
                             <i class="bi bi-moon-stars-fill"></i> 
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">