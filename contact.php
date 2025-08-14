<?php
session_start();
include 'components/connection.php';
if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }else{
        $user_id = '';
    }
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beauty SkinCare - contact Page</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <style type="text/css">
        <?php include 'style.css'; ?>
    </style>
</head>

<body>
    <?php include 'components/header.php'; ?>
    <?php include 'components/alert.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>contact us</h1>
        </div>
        <div class="title2">
            <a href="home.php">home</a><span>/ contact us</span>
        </div>
        <section class="services">
            <div class="box-container">
                <div class="box">
                    <img src="img/green/img/icon2.png" alt="">
                    <div class="detail">
                        <h3>great savings</h3>
                        <p>save big every order</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/green/img/icon1.png" alt="">
                    <div class="detail">
                        <h3>24*7 support</h3>
                        <p>one on one support</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/green/img/icon0.png" alt="">
                    <div class="detail">
                        <h3>gift vouchers</h3>
                        <p>vouchers on every festivals</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/green/img/icon.png" alt="">
                    <div class="detail">
                        <h3>worldwide delivery</h3>
                        <p>dropship worldwide</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="form-container">
            <form action="" method="post">
                <div class="title">
                    <img src="img/green/img/download.png" alt="" class="logo">
                    <h1>leave a message</h1>
                </div>
                <div class="input-field">
                    <p>your name <sup>*</sup></p>
                    <input type="text" name="name">
                </div>
                <div class="input-field">
                    <p>your email <sup>*</sup></p>
                    <input type="email" name="email">
                </div>
                <div class="input-field">
                    <p>your number <sup>*</sup></p>
                    <input type="text" name="number">
                </div>
                <div class="input-field">
                    <p>your message <sup>*</sup></p>
                    <textarea name="message" id=""></textarea>
                </div>
                <button type="submit" name="submit-btn" class="btn">send message</button>
            </form>
        </div>
        <div class="address">
                <div class="title">
                    <img src="img/green/img/download.png" alt="" class="logo">
                    <h1>contact detail</h1>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim velit harum,</p>
                </div>
                <div class="box-container">
                    <div class="box">
                        <i class="bx bxs-map-pin"></i>
                        <div>
                            <h4>address</h4>
                            <p>Lorem ipsum dolor sit amet consectetur.</p>
                        </div>
                    </div>
                    <div class="box">
                        <i class="bx bxs-phone-call"></i>
                        <div>
                            <h4>Phone number</h4>
                            <p>+855 1256789</p>
                        </div>
                    </div>
                    <div class="box">
                        <i class="bx bxs-map-pin"></i>
                        <div>
                            <h4>email</h4>
                            <p>leanglijing@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php include 'components/footer.php'; ?>
    </div>
    
    <!-- Your page content -->

    <script src="script.js"></script>
</body>

</html>
