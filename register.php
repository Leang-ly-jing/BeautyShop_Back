
<?php
// session_start();
// include 'components/connection.php'; 

// $message = [];

// if (isset($_POST['submit'])) {
//     $name  = trim(htmlspecialchars($_POST['name']));
//     $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
//     $pass  = $_POST['pass'];
//     $cpass = $_POST['cpass'];

//     if (!$email) {
//         $message[] = 'Invalid email address';
//     }
//     if (empty($name) || empty($pass) || empty($cpass)) {
//         $message[] = 'Please fill in all required fields';
//     }
//     if ($pass !== $cpass) {
//         $message[] = 'Passwords do not match';
//     }

//     if (empty($message)) {
//         $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
//         $stmt->bind_param("s", $email);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         if ($result->num_rows > 0) {
//             $message[] = 'Email already exists';
//         } else {
//             $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
//             $user_type = 'user';

//             $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
//             $stmt->bind_param("ssss", $name, $email, $hashedPass, $user_type);

//             if ($stmt->execute()) {
//                 $user_id = $conn->insert_id;
//                 $_SESSION['user_id']    = $user_id;
//                 $_SESSION['user_name']  = $name;
//                 $_SESSION['user_email'] = $email;
//                 header('Location: dashboard.php');
//                 exit;
//             } else {
//                 $message[] = 'Registration failed, please try again.';
//             }
//         }
//         $stmt->close();
//     }
// }
// $conn->close();
session_start();
include 'components/connection.php'; // Must be MySQLi connection

$message = [];

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $name  = trim(htmlspecialchars($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $pass  = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Validate inputs
    if (!$email) {
        $message[] = 'Invalid email address';
    }
    if (empty($name) || empty($pass) || empty($cpass)) {
        $message[] = 'Please fill in all required fields';
    }
    if ($pass !== $cpass) {
        $message[] = 'Passwords do not match';
    }

    if (empty($message)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'Email already exists';
        } else {
            // Hash password
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            $user_type = 'user';

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPass, $user_type);

            if ($stmt->execute()) {
                $user_id = $conn->insert_id;

                $_SESSION['user_id']    = $user_id;
                $_SESSION['user_name']  = $name;
                $_SESSION['user_email'] = $email;

                // Redirect where you want after register (change if needed)
                header('Location: home.php');
                exit;
            } else {
                $message[] = 'Registration failed, please try again.';
            }
        }
        $stmt->close();
    }
}
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
    <title>Beauty - Register</title>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <img src="img/green/img/download.png" alt="">
                <h1>Register Now</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium facere enim!</p>
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
                    <p>Your Name <sup>*</sup></p>
                    <input type="text" name="name" required placeholder="Enter your name" maxlength="50">
                </div>

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

                <div class="input-field">
                    <p>Confirm Password <sup>*</sup></p>
                    <input type="password" name="cpass" required placeholder="Re-enter your password"
                           maxlength="50" autocomplete="off"
                           oninput="this.value = this.value.replace(/\s/g,'')">
                </div>

                <input type="submit" name="submit" value="Register Now" class="btn">
                <p>Already have an account? <a href="login.php">Login now</a></p>
            </form>
        </section>
    </div>
</body>
</html>
