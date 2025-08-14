<?php
session_start();
include 'components/connection.php';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? '';

// Initialize alert arrays
$success_msg = [];
$warning_msg = [];
$error_msg = [];

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

                $success_msg[] = 'Product quantity updated in your cart!';
            } else {
                $stmt->close();
                $stmt_insert = $conn->prepare("INSERT INTO cart (user_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("iidi", $user_id, $product_id, $price, $qty);
                $stmt_insert->execute();
                $stmt_insert->close();

                $success_msg[] = 'Product added to your cart!';
            }
        } else {
            $error_msg[] = 'Invalid product price.';
        }
    } else {
        $warning_msg[] = 'Please log in to add items to your cart.';
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
            $warning_msg[] = 'Product already exists in your wishlist!';
        } else {
            $stmt->close();
            $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $user_id, $product_id);
            if ($stmt_insert->execute()) {
                $success_msg[] = 'Product added to your wishlist!';
            } else {
                $error_msg[] = 'Error adding product to wishlist.';
            }
            $stmt_insert->close();
        }
    } else {
        $warning_msg[] = 'Please log in to add items to your wishlist.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Beauty SkinCare - Shop Page</title>

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Our Shop</h1>
    </div>
    <div class="title2">
        <a href="home.php">home</a><span>/ our shop</span>
    </div>
    <section class="products">
        <div class="box-container">
            <?php
            $result = $conn->query("SELECT * FROM products");
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
            ?>
            <form action="" method="post" class="box">
                <div class="btn">Buy Now</div>
                <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="button-group">
                    <button type="submit" name="add_to_cart" title="Add to Cart"><i class="bx bx-cart"></i></button>
                    <button type="submit" name="add_to_wishlist" title="Add to Wishlist"><i class="bx bx-heart"></i></button>
                    <a href="view_page.php?pid=<?= $product['id'] ?>" class="bx bxs-show" title="View Product"></a>
                </div>
                <h3 class="name"><?= htmlspecialchars($product['name']) ?></h3>
                <p class="price">price $<?= number_format($product['price'], 2) ?>/-</p>
                <div class="flex">
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                </div>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            </form>
            <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<!-- Include alert.php here so SweetAlert triggers -->
<?php include 'components/alert.php'; ?>

<script src="script.js"></script>
</body>
</html>
