<?php
session_start();
include 'components/connection.php';
include 'components/alert.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['get_id'])) {
    $get_id = intval($_GET['get_id']);
} else {
    header('Location: order.php');
    exit;
}

// Cancel order
if (isset($_POST['cancel'])) {
    $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $status = 'canceled';
    $update_order->bind_param("si", $status, $get_id);
    $update_order->execute();
    $update_order->close();
    header("Location: order.php");
    exit;
}

// Fetch order
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1");
$order_stmt->bind_param("ii", $get_id, $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

$order = $order_result->fetch_assoc();
$order_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Beauty SkinCare - Order Detail</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Order Detail</h1>
    </div>
    <div class="title2">
        <a href="home.php">Home</a> / Order Detail
    </div>

    <section class="orders-detail">
        <div class="title1">
            <img src="img/green/img/download.png" alt="">
            <h1>Order Detail</h1>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat suscipit reiciendis aperiam nisi neque est in culpa recusandae sunt magni.</p>
        </div>

        <div class="box-container">
        <?php if ($order): 
            $product_stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $product_stmt->bind_param("i", $order['product_id']);
            $product_stmt->execute();
            $product_result = $product_stmt->get_result();
            $product = $product_result->fetch_assoc();
            $product_stmt->close();

            if ($product):
                $grand_total = $order['price'] * $order['qty'];
        ?>
        <div class="box">
            <div class="col">
                <p class="title"><i class="bi bi-calendar-fill"></i> <?= $order['date']; ?></p>
                <img src="image/<?= $product['image']; ?>" class="image">
                <p class="price">$<?= $product['price']; ?> x <?= $order['qty']; ?></p>
                <h3 class="name"><?= $product['name']; ?></h3>
                <p class="grand-total">Total amount payable: <span>$<?= $grand_total; ?></span></p>
            </div>
            <div class="col">
                <p class="title">Billing Address</p>
                <p class="user"><i class="bi bi-person-bounding-box"></i> Name: <?= $order['name']; ?></p>
                <p class="user"><i class="bi bi-phone"></i> Phone: <?= $order['number']; ?></p>
                <p class="user"><i class="bi bi-envelope"></i> Email: <?= $order['email']; ?></p>
                <p class="user"><i class="bi bi-pin-map-fill"></i> Address: <?= $order['address']; ?></p>


                <p class="title">Status</p>
                <p class="status" style="color: <?php
                    if ($order['status'] == 'delivered') echo 'green';
                    elseif ($order['status'] == 'canceled') echo 'red';
                    else echo 'orange';
                ?>"><?= ucfirst($order['status']); ?></p>

                <?php if ($order['status'] == 'canceled'): ?>
                    <a href="checkout.php?get_id=<?= $product['id']; ?>" class="btn">Order Again</a>
                <?php else: ?>
                    <form method="post">
                        <button type="submit" name="cancel" class="btn" onclick="return confirm('Do you want to cancel this order?');">Cancel Order</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php else: ?>
            <p class="empty">Product not found.</p>
        <?php endif; ?>
        <?php else: ?>
            <p class="empty">Order not found.</p>
        <?php endif; ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script src="script.js"></script>
</body>
</html>
