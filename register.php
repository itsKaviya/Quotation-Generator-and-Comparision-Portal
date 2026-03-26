<?php
session_start();
include("config/db.php");

$msg = "";
$type = "";

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if($check->num_rows > 0){
        $msg = "Email already registered!";
        $type = "danger";
    } else {
        if($conn->query("INSERT INTO users(name,email,password) VALUES('$name','$email','$password')")){
            $msg = "Registered successfully! You can now <a href='login.php' class='alert-link'>login</a>.";
            $type = "success";
        } else {
            $msg = "Generic error occurred. Please try again.";
            $type = "danger";
        }
    }
}

include("includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card p-4">
            <h3 class="text-center mb-4">Create an Account</h3>
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> p-2 text-center" role="alert"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="mt-4 text-center">
                <span>Already have an account? <a href="login.php" class="text-decoration-none">Login</a></span>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>