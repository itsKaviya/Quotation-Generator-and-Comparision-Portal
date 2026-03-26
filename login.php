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
    <div class="col-md-5 col-lg-4">
        <div class="card p-4">
            <h3 class="text-center mb-4">Login to Your Account</h3>
            <?php if($error): ?>
                <div class="alert alert-danger p-2 text-center" role="alert"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-4 text-center">
                <span>Don't have an account? <a href="register.php" class="text-decoration-none">Register</a></span>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>