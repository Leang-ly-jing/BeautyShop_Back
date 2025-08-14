<header class="header">
    <div class="flex">
        <a href="home.php" class="logo"><img src="img/logo1.png" alt="Logo"></a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="view_products.php">Products</a>
            <a href="order.php">Orders</a>
            <a href="about.php">About us</a>
            <a href="contact.php">Contact us</a>
        </nav>
        <div class="icons">
            <i class="bx bxs-user" id="user-btn"></i>
            <?php
                $total_wishlist_item = 0;
                $total_cart_item = 0;

                if (isset($user_id) && !empty($user_id)) {
                    // Wishlist count
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->bind_result($total_wishlist_item);
                    $stmt->fetch();
                    $stmt->close();

                    // Cart count
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->bind_result($total_cart_item);
                    $stmt->fetch();
                    $stmt->close();
                }
            ?>

            <a href="wishlist.php" class="cart-btn"><i class="bx bx-heart"></i><sup><?= $total_wishlist_item ?></sup></a>
            <a href="cart.php" class="card-btn"><i class="bx bx-cart"></i><sup><?= $total_cart_item ?></sup></a>
            <i class="bx bx-list-plus" id="menu-btn" style="font-size: 2rem;" ></i>
        </div>
        <div class="user-box">
            <!-- Uncomment and use session for logged in user -->

            <p>username : <span><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?></span></p>
            <p>Email : <span><?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?></span></p>

            <a href="login.php" class="btn">login</a>
            <a href="register.php" class="btn">register</a>
            <form action="" method="post">
                <button type="submit" name="logout" class="logout-btn">log out</button>
            </form>
        </div>
    </div>
</header>
