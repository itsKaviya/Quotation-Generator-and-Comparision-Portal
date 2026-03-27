<?php
session_start();
include("config/db.php");

$msg = "";
$type = "";

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if($check->num_rows > 0){
        $msg = "Email already registered!";
        $type = "danger";
    } else {
        if($conn->query("INSERT INTO users(name,email,password,role) VALUES('$name','$email','$password','$role')")){
            $user_id = $conn->insert_id;
            if($role == 'vendor'){
                $conn->query("INSERT INTO vendors(user_id, name) VALUES('$user_id', '$name')");
            }
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
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4 text-center">Create Account</h2>
            <?php if($msg): ?>
                <div class="alert alert-<?= $type ?> p-3 text-center border-0 rounded-4" role="alert"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-medium">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">I am a...</label>
                    <div class="d-flex gap-3">
                        <div class="flex-fill">
                            <input type="radio" class="btn-check" name="role" id="role_mgmt" value="management" checked>
                            <label class="btn btn-outline-primary w-100 py-3" for="role_mgmt">
                                <i class="bi bi-briefcase me-2"></i> Management
                            </label>
                        </div>
                        <div class="flex-fill">
                            <input type="radio" class="btn-check" name="role" id="role_vendor" value="vendor">
                            <label class="btn btn-outline-primary w-100 py-3" for="role_vendor">
                                <i class="bi bi-truck me-2"></i> Vendor
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100 py-3">Create Account</button>
            </form>
            <div class="mt-4 text-center">
                <span class="text-muted">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Login</a></span>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>