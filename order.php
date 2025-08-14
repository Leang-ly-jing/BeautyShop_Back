<?php
session_start();
include 'components/connection.php';
include 'components/alert.php';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? '';

$alert_msg = '';
$alert_type = '';

// ADD TO CART
if (isset($_POST['add_to_cart'])) {
    if ($user_id != '') {
        $product_id = intval($_POST['product_id']);
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();

        if ($price > 0) {
            $stmt = $conn->prepare("SELECT id, qty FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($cart_id, $existing_qty);
                $stmt->fetch();
                $stmt->close();

                $new_qty = $existing_qty + $qty;
                $stmt_update = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
                $stmt_update->bind_param("ii", $new_qty, $cart_id);
                $stmt_update->execute();
                $stmt_update->close();

                $alert_msg = 'Product quantity updated in your cart!';
                $alert_type = 'success';
            } else {
                $stmt->close();
                $stmt_insert = $conn->prepare("INSERT INTO cart (user_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("iidi", $user_id, $product_id, $price, $qty);
                $stmt_insert->execute();
                $stmt_insert->close();

                $alert_msg = 'Product added to your cart!';
                $alert_type = 'success';
            }
        } else {
            $alert_msg = 'Invalid product price.';
            $alert_type = 'error';
        }
    } else {
        $alert_msg = 'Please log in to add items to your cart.';
        $alert_type = 'warning';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beauty SkinCare - My Orders</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>My Orders</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a> / Order
        </div>

        <section class="orders">
            <div class="title1">
                <img src="img/green/img/download.png" alt="">
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat suscipit reiciendis aperiam nisi neque est in culpa recusandae sunt magni.</p>
            </div>

            <div class="box-container">
                <?php
                $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
                $select_orders->bind_param("i", $user_id);
                $select_orders->execute();
                $result_orders = $select_orders->get_result();

                if ($result_orders->num_rows > 0) {
                    while ($fetch_order = $result_orders->fetch_assoc()) {
                        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $select_products->bind_param("i", $fetch_order['product_id']);
                        $select_products->execute();
                        $result_products = $select_products->get_result();

                        if ($result_products->num_rows > 0) {
                            $fetch_products = $result_products->fetch_assoc();
                ?>
                            <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>" class="order-link">
                                <div class="box" <?php if ($fetch_order['status'] == 'canceled') { echo 'style="border:2px solid red;"'; } ?>>
                                    <p class="date"><i class="bi bi-calendar-fill"></i> <?= $fetch_order['date']; ?></p>
                                    <img src="image/<?= $fetch_products['image']; ?>" alt="" class="image">
                                    <div class="details">
                                        <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                        <p class="price">Price: $<?= $fetch_products['price']; ?> x <?= $fetch_order['qty']; ?></p>
                                        <p class="status" style="color: <?php 
                                            if ($fetch_order['status'] == 'delivered') {
                                                echo 'green';
                                            } elseif ($fetch_order['status'] == 'canceled') {
                                                echo 'red';
                                            } else {
                                                echo 'orange';
                                            }
                                        ?>;">
                                            <?= ucfirst($fetch_order['status']); ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                <?php
                        }
                        $select_products->close();
                    }
                } else {
                    echo '<p class="empty">No orders placed yet!</p>';
                }
                $select_orders->close();
                ?>
            </div>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
