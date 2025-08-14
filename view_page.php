<?php
session_start();
include 'components/connection.php';
include 'components/alert.php';

// Logout
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
                $alert_type = 'info';
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

// ADD TO WISHLIST
if (isset($_POST['add_to_wishlist'])) {
    if ($user_id != '') {
        $product_id = intval($_POST['product_id']);

        $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $alert_msg = 'Product already exists in your wishlist!';
            $alert_type = 'warning';
        } else {
            $stmt->close();
            $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $user_id, $product_id);

            if ($stmt_insert->execute()) {
                $alert_msg = 'Product added to your wishlist!';
                $alert_type = 'success';
            } else {
                $alert_msg = 'Error adding product to wishlist.';
                $alert_type = 'error';
            }
            $stmt_insert->close();
        }
    } else {
        $alert_msg = 'Please log in to add items to your wishlist.';
        $alert_type = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beauty SkinCare - Product Detail</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product_detail.css">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Product Detail</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a><span>/ Product Detail</span>
        </div>
        <section class="view_page">
            <?php 
            if (isset($_GET['pid'])) {
                $pid = intval($_GET['pid']);
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $pid);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($fetch_products = $result->fetch_assoc()) {
                        ?>
                        <form method="post">
                            <img src="image/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="">
                            <div class="detail">
                                <div class="price">$<?php echo number_format($fetch_products['price'], 2); ?>/-</div>
                                <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
                                <div class="product-detail">
                                    <p><?php echo nl2br(htmlspecialchars($fetch_products['product_detail'] ?? 'No details available.')); ?></p>
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                                <input type="hidden" name="qty" value="1">
                                <div class="button">
                                    <button type="submit" name="add_to_wishlist" class="btn">Add to Wishlist <i class="bx bx-heart"></i></button>
                                    <button type="submit" name="add_to_cart" class="btn">Add to Cart <i class="bx bx-cart"></i></button>
                                    
                                </div>
                            </div>
                        </form>   
                        <?php
                    }
                }
                $stmt->close();
            }
            ?>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>


    <script src="script.js"></script>
</body>
</html>
