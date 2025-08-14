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
        <section class="home-section">
            <div class="slider">
            <div class="slider__slider slide1">
                <div class="overlay"></div>
                <div class="slide-detail">
                    <h1>Lorem ipsum dolor sit</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi suscipit perspiciatis neque dolor eos ducimus magni, at praesentium rem pariatur porro molestiae reprehenderit corrupti recusandae id dignissimos saepe. Quibusdam, esse!</p>
                    <a href="view_products.php" class="btn">Shop now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <!-- -------------------------------------------------------- -->
             <div class="slider__slider slide2">
                <div class="overlay"></div>
                <div class="slide-detail">
                    <h1>Welcome to my shop</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi suscipit perspiciatis neque dolor eos ducimus magni, at praesentium rem pariatur porro molestiae reprehenderit corrupti recusandae id dignissimos saepe. Quibusdam, esse!</p>
                    <a href="view_products.php" class="btn">Shop now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <!-- -------------------------------------------------------- -->
             <div class="slider__slider slide3">
                <div class="overlay"></div>
                <div class="slide-detail">
                    <h1>Lorem ipsum dolor sit</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi suscipit perspiciatis neque dolor eos ducimus magni, at praesentium rem pariatur porro molestiae reprehenderit corrupti recusandae id dignissimos saepe. Quibusdam, esse!</p>
                    <a href="view_products.php" class="btn">Shop now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <!-- -------------------------------------------------------- -->
             <div class="slider__slider slide4">
                <div class="overlay"></div>
                <div class="slide-detail">
                    <h1>Lorem ipsum dolor sit</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi suscipit perspiciatis neque dolor eos ducimus magni, at praesentium rem pariatur porro molestiae reprehenderit corrupti recusandae id dignissimos saepe. Quibusdam, esse!</p>
                    <a href="view_products.php" class="btn">Shop now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <!-- -------------------------------------------------------- -->
             <div class="slider__slider slide5">
                <div class="overlay"></div>
                <div class="slide-detail">
                    <h1>Lorem ipsum dolor sit</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi suscipit perspiciatis neque dolor eos ducimus magni, at praesentium rem pariatur porro molestiae reprehenderit corrupti recusandae id dignissimos saepe. Quibusdam, esse!</p>
                    <a href="view_products.php" class="btn">Shop now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <!-- -------------------------------------------------------- -->
             <div class="left-arrow"><i class="bx bxs-left-arrow"></i></div>
             <div class="right-arrow"><i class="bx bxs-right-arrow"></i></div>
            </div>
        </section>

        <section class="thumb">
            <div class="box-container">
                <div class="box">
                    <img src="img/green/img/thump17.png" alt="">
                    <h3>Cream</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/green/img/thump12.png" alt="">
                    <h3>Tonner</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/green/img/thump13.png" alt="">
                    <h3>Serum</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/green/img/thump14.png" alt="">
                    <h3>Sun Cream</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
            </div>
        </section>
        <section class="container">
            <div class="box-container ">
                <div class="box">
                    <img src="img/green/img/about1.png" alt="">
                </div>
                <div class="box">
                    <img src="img/green/img/download.png" alt="">
                    <span>Beauty Shop</span>
                    <h1>save up to 50% off</h1>
                    <p>Lorem ipsum dolor sit amet, excepturi provident dolores illo necessitatibus eius.</p>
                </div>
            </div>
        </section>
        <section class="shop">
            <div class="title">
                <img src="img/green/img/download.png" alt="">
                <h1>Trending Products</h1>
            </div>
            <div class="row">
                <img src="img/green/img/rom1.webp" alt="">
                <div class="row-detail">
                    <img src="img/green/img/discount1.jpg" alt="">
                    <div class="top-footer">
                        <h1>Beauty Shop Give You confidence</h1>
                    </div>
                </div>
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="img/green/img/TorridenClean.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="img/green/img/TorridenToner.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="img/green/img/MaryCream.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="img/green/img/MaryMask1.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="img/green/img/MaryMask2.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="img/green/img/Mary mask3.jpg" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
        </section>

        <section class="shop-category">
            <div class="box-container">
                <div class="box">
                    <img src="img/green/img/torridenG1.webp" alt="">
                    <div class="detail">
                        <span>BIG OFFERS</span>
                        <h1>Extra 15% off</h1>
                        <a href="view_products.php" class="btn">shop now</a>
                    </div>
                </div>
                <div class="box">
                    <img src="img/green/img/torridenG2.webp" alt="">
                    <div class="detail">
                        <span>new in taste</span>
                        <h1>coffee house</h1>
                        <a href="view_products.php" class="btn">shop now</a>
                    </div>
                </div>
            </div>
        </section>
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
        <section class="brand">
            <div class="box-container">
                <div class="box">
                    <img src="img/green/img/brand (1).jpg" alt="">
                </div>
                <div class="box">
                    <img src="img/green/img/brand (2).jpg" alt="">
                </div>
                <div class="box">
                    <img src="img/green/img/brand (3).jpg" alt="">
                </div>
                <div class="box">
                    <img src="img/green/img/brand (4).jpg" alt="">
                </div>
                <div class="box">
                    <img src="img/green/img/brand (5).jpg" alt="">
                </div>
            </div>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>
    
    <!-- Your page content -->

    <script src="script.js"></script>
</body>

</html>
