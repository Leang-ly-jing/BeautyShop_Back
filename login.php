<?php
session_start();
include 'components/connection.php'; 

$message = [];

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $pass  = $_POST['pass'];

    if (!$email) {
        $message[] = 'Invalid email address';
    }
    if (empty($pass)) {
        $message[] = 'Please enter your password';
    }

    if (empty($message)) {
        $stmt = $conn->prepare("SELECT id, name, email, password, user_type FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($pass, $user['password'])) {
                // Set session variables
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type']  = $user['user_type'];

                // Redirect after login
                header('Location: home.php');
                exit;
            } else {
                $message[] = 'Incorrect password';
            }
        } else {
            $message[] = 'Email not found';
        }
        $stmt->close();
    }
}
$conn->close();
?>

<style type="text/css">
<?php include 'style.css'; ?>
.message-box { background: #ffdddd; padding: 10px; border: 1px solid red; margin-bottom: 15px; }
.message-box p { margin: 0; color: red; }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty - Login</title>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <img src="img/green/img/download.png" alt="">
                <h1>Login Now</h1>
                <p>Welcome back! Please log in to continue.</p>
            </div>

            <form action="" method="post">
                <?php if (!empty($message)) : ?>
                    <div class="message-box">
                        <?php foreach ($message as $msg) : ?>
                            <p><?php echo $msg; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="input-field">
                    <p>Your Email <sup>*</sup></p>
                    <input type="email" name="email" required placeholder="Enter your email"
                           oninput="this.value = this.value.replace(/\s/g,'')" maxlength="50">
                </div>

                <div class="input-field">
                    <p>Your Password <sup>*</sup></p>
                    <input type="password" name="pass" required placeholder="Enter your password"
                           maxlength="50" autocomplete="off"
                           oninput="this.value = this.value.replace(/\s/g,'')">
                </div>

                <input type="submit" name="submit" value="Login Now" class="btn">
                <p>Don't have an account? <a href="register.php">Register now</a></p>
            </form>
        </section>
    </div>
</body>
</html>
