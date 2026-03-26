<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Generator & Comparison Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --bg-color: #ffffff;
            --text-color: #212529;
            --card-bg: #ffffff;
        }

        [data-bs-theme="dark"] {
            --primary-color: #3d8bfd;
            --bg-color: #121212;
            --text-color: #f8f9fa;
            --card-bg: #1e1e1e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            font-weight: 600;
        }

        .best-option {
            background-color: #d1e7dd !important; /* light green */
            color: #0f5132 !important;
        }

        [data-bs-theme="dark"] .best-option {
            background-color: #0f5132 !important;
            color: #d1e7dd !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">QuotePortal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_job.php">Post Requirement</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-danger btn-sm ms-lg-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary btn-sm ms-lg-2 text-white" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3">
                        <button class="btn btn-sm btn-outline-secondary" id="themeToggle">
                             <i class="bi bi-moon-fill"></i> Toggle Mode
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-4">