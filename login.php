<?php
session_start();
include("config/db.php");

$error = "";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $res = $conn->query($query);

    if($res->num_rows > 0){
        $user = $res->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}

include("includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4 text-center">Login</h2>
            <?php if($error): ?>
                <div class="alert alert-danger p-3 text-center border-0 rounded-4" role="alert"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-medium">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-3 mb-3">Login</button>
            </form>
            <div class="mt-2 text-center">
                <span class="text-muted">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Register</a></span>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>