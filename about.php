
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
    <title>Beauty SkinCare - about Page</title>

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
            <h1>about us</h1>
        </div>
        <div class="title2">
            <a href="home.php">home</a><span>/ about</span>
        </div>
        <div class="about-category">
            <div class="box">
                <img src="img/green/img/TorridenToner.jpg" alt="">
                <div class="detail">
                    <span>Torriden</span>
                    <h1>Cleansing</h1>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/green/img/TorridenToner.jpg" alt="">
                <div class="detail">
                    <span>Torriden</span>
                    <h1>Type brand</h1>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/green/img/TorridenToner.jpg" alt="">
                <div class="detail">
                    <span>Torriden</span>
                    <h1>Type brand</h1>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/green/img/TorridenToner.jpg" alt="">
                <div class="detail">
                    <span>Torriden</span>
                    <h1>Type brand</h1>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
        </div>
        <section class="services">
            <div class="title">
                <img src="img/green/img/download.png" alt="" class="logo">
                <h1>Why choose us?</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, repellat.</p>
            </div>
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
        <div class="about">
            <div class="row">
                <div class="img-box">
                    <img src="img/green/img/MaryClaymask.png" alt="">
                </div>
                <div class="detail">
                    <h1>visit our beautyful show produce</h1>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit fugit labore excepturi ea distinctio aut obcaecati omnis asperiores suscipit nisi, quisquam est maxime nulla amet quis, tempore placeat earum illum.</p>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
        </div>
            <div class="testimonial-container">
  <div class="title">
    <img src="img/green/img/download.png" alt="" class="logo" />
    <h1>what people say about us</h1>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi reiciendis adipisci ea eaque nesciunt ex a explicabo perferendis quisquam molestias itaque debitis ipsa laudantium, id maiores, corrupti voluptas aspernatur quae!</p>
  </div>
  <div class="container">
    <div class="testimonial-item active">
      <img src="img/green/img/01.jpg" alt="" />
      <h1>beauty skincare</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam eveniet voluptatibus fuga qui iste neque molestiae, quas temporibus fugit quo soluta iure quis nesciunt possimus quisquam rerum deleniti culpa. Corporis.</p>
    </div>
    <div class="testimonial-item">
      <img src="img/green/img/02.jpg" alt="" />
      <h1>beauty skincare</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam eveniet voluptatibus fuga qui iste neque molestiae, quas temporibus fugit quo soluta iure quis nesciunt possimus quisquam rerum deleniti culpa. Corporis.</p>
    </div>
    <div class="testimonial-item">
      <img src="img/green/img/03.jpg" alt="" />
      <h1>beauty skincare</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam eveniet voluptatibus fuga qui iste neque molestiae, quas temporibus fugit quo soluta iure quis nesciunt possimus quisquam rerum deleniti culpa. Corporis.</p>
    </div>

    <div class="left-arrow" onclick="prevSlide()"><i class="bx bxs-left-arrow-alt"></i></div>
    <div class="right-arrow" onclick="nextSlide()"><i class="bx bxs-right-arrow-alt"></i></div>
  </div>
</div>


   
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="script.js"></script>
</body>

</html>
