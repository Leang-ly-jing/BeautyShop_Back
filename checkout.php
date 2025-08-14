<?php
session_start();
include 'components/connection.php';
include 'components/alert.php';

$user_id = $_SESSION['user_id'] ?? '';

// LOGOUT
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// PLACE ORDER
if (isset($_POST['place_order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['flat'] . ',' . $_POST['street'] . ',' . $_POST['city'] . ',' . $_POST['country'] . ',' . $_POST['pincode'], FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // SINGLE PRODUCT ORDER
    if (isset($_GET['get_id'])) {
        $get_id = intval($_GET['get_id']);
        $stmt = $conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $get_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $qty = 1;
            while ($product = $result->fetch_assoc()) {
                $insert_order = $conn->prepare("INSERT INTO orders (user_id, name, number, email, address, address_type, method, product_id, price, qty, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')");
                $insert_order->bind_param("isssssiddi", $user_id, $name, $number, $email, $address, $address_type, $method, $product['id'], $product['price'], $qty);
                $insert_order->execute();
            }
            $success_msg[] = "Order placed successfully!";
            header("Location: order.php");
            exit;
        } else {
            $error_msg[] = "Product not found!";
        }
    }
    // CART ORDER
    else {
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();

        if ($cart_result->num_rows > 0) {
            while ($cart_item = $cart_result->fetch_assoc()) {
                $stmt_p = $conn->prepare("SELECT * FROM products WHERE id=?");
                $stmt_p->bind_param("i", $cart_item['product_id']);
                $stmt_p->execute();
                $product_result = $stmt_p->get_result();
                $product = $product_result->fetch_assoc();

                $insert_order = $conn->prepare("INSERT INTO orders (user_id, name, number, email, address, address_type, method, product_id, price, qty, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')");
                $insert_order->bind_param("isssssiddi", $user_id, $name, $number, $email, $address, $address_type, $method, $product['id'], $cart_item['price'], $cart_item['qty']);
                $insert_order->execute();
            }
            // DELETE CART AFTER ORDER
            $del_cart = $conn->prepare("DELETE FROM cart WHERE user_id=?");
            $del_cart->bind_param("i", $user_id);
            $del_cart->execute();

            $success_msg[] = "Order placed successfully!";
            header("Location: order.php");
            exit;
        } else {
            $warning_msg[] = "Your cart is empty!";
        }
    }
}

// ADD TO CART
if (isset($_POST['add_to_cart'])) {
    if ($user_id != '') {
        $product_id = intval($_POST['product_id']);
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

        $stmt = $conn->prepare("SELECT price FROM products WHERE id=?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();

        if ($price > 0) {
            $stmt = $conn->prepare("SELECT id, qty FROM cart WHERE user_id=? AND product_id=?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($cart_id, $existing_qty);
                $stmt->fetch();
                $stmt->close();
                $new_qty = $existing_qty + $qty;

                $stmt_update = $conn->prepare("UPDATE cart SET qty=? WHERE id=?");
                $stmt_update->bind_param("ii", $new_qty, $cart_id);
                $stmt_update->execute();
                $stmt_update->close();

                $success_msg[] = "Product quantity updated in your cart!";
            } else {
                $stmt->close();
                $stmt_insert = $conn->prepare("INSERT INTO cart (user_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("iidi", $user_id, $product_id, $price, $qty);
                $stmt_insert->execute();
                $stmt_insert->close();

                $success_msg[] = "Product added to your cart!";
            }
        } else {
            $error_msg[] = "Invalid product price!";
        }
    } else {
        $warning_msg[] = "Please log in to add items to your cart.";
    }
}

// ADD TO WISHLIST
if (isset($_POST['add_to_wishlist'])) {
    if ($user_id != '') {
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $warning_msg[] = "Product already exists in your wishlist!";
        } else {
            $stmt->close();
            $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $user_id, $product_id);
            if ($stmt_insert->execute()) {
                $success_msg[] = "Product added to your wishlist!";
            } else {
                $error_msg[] = "Error adding product to wishlist!";
            }
            $stmt_insert->close();
        }
    } else {
        $warning_msg[] = "Please log in to add items to your wishlist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beauty SkinCare - Checkout</title>
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<style>
<?php include 'style.css'; ?>

</style>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner"><h1>checkout</h1></div>
    <div class="title2"><a href="home.php">home</a><span>/ checkout</span></div>

    <section class="checkout">
        <div class="title1">
            <img src="img/green/img/download.png" class="logo">
            <h1>checkout summary</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos exercitationem ad quam veniam impedit voluptatum.</p>

            <div class="row">
                <form action="" method="post">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="inpit-field"><p>your name <span>*</span></p>
                                <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="input">
                            </div>
                            <div class="inpit-field"><p>your number <span>*</span></p>
                                <input type="text" name="number" required maxlength="50" placeholder="Enter your number" class="input">
                            </div>
                            <div class="inpit-field"><p>your email <span>*</span></p>
                                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="input">
                            </div>
                            <div class="inpit-field"><p>payment method<span>*</span></p>
                                <select name="method" class="input">
                                    <option value="cash on delivery">cash on delivery</option>
                                    <option value="credit or debit card">credit or debit card</option>
                                    <option value="net banking">net banking</option>
                                    <option value="UPI or Rupay">UPI or Rupay</option>
                                    <option value="office">office</option>
                                </select>
                            </div>
                            <div class="inpit-field"><p>address type<span>*</span></p>
                                <select name="address_type" class="input">
                                    <option value="home">home</option>
                                    <option value="office">office</option>
                                </select>
                            </div>
                        </div>
                        <div class="box">
                            <div class="input-field"><p>address line 01 <span>*</span></p>
                                <input type="text" name="flat" required maxlength="50" placeholder="Flat/Building" class="input">
                            </div>
                            <div class="input-field"><p>address line 02 <span>*</span></p>
                                <input type="text" name="street" required maxlength="50" placeholder="Street Name" class="input">
                            </div>
                            <div class="input-field"><p>city <span>*</span></p>
                                <input type="text" name="city" required maxlength="50" placeholder="City" class="input">
                            </div>
                            <div class="input-field"><p>country <span>*</span></p>
                                <input type="text" name="country" required maxlength="50" placeholder="Country" class="input">
                            </div>
                            <div class="input-field"><p>pincode <span>*</span></p>
                                <input type="text" name="pincode" required maxlength="10" placeholder="112233" class="input">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">place order</button>
                </form>

                <div class="summary">
                    <h3>my bag</h3>
                    <div class="box-container">
                    <?php
                        $grand_total = 0;
                        $stmt_cart = $conn->prepare("SELECT c.qty, c.price, p.name, p.image FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?");
                        $stmt_cart->bind_param("i", $user_id);
                        $stmt_cart->execute();
                        $cart_result = $stmt_cart->get_result();

                        if ($cart_result->num_rows > 0) {
                            while ($item = $cart_result->fetch_assoc()) {
                                $sub_total = $item['price'] * $item['qty'];
                                $grand_total += $sub_total;
                                ?>
                                <div class="flex">
                                    <img src="image/<?= $item['image']; ?>" alt="<?= $item['name']; ?>">
                                    <div>
                                        <h3 class="name"><?= $item['name']; ?></h3>
                                        <p class="price"><?= $item['price']; ?> x <?= $item['qty']; ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p class="empty">your cart is empty</p>';
                        }
                    ?>
                    </div>
                    <div class="grand-total">
                        <span>total amount payable :</span> $<?= $grand_total ?>/-
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php include 'components/footer.php'; ?>

</div>

</body>
</html>
