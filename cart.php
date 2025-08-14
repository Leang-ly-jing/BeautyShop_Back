<?php
session_start();
include 'components/connection.php';
$grand_total = 0; 

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? '';

$alert_msg = '';
$alert_type = '';

// DELETE from cart
if (isset($_POST['delete_item'])) {
    $cart_id = intval($_POST['cart_id']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    if ($stmt->execute()) {
        $alert_msg = "Item removed from cart.";
        $alert_type = "success";
    } else {
        $alert_msg = "Failed to remove item.";
        $alert_type = "error";
    }
    $stmt->close();
}

// UPDATE quantity in cart
if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $qty = intval($_POST['qty']);
    if ($qty > 0) {
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $qty, $cart_id, $user_id);
        if ($stmt->execute()) {
            $alert_msg = "Cart updated successfully.";
            $alert_type = "success";
        } else {
            $alert_msg = "Failed to update cart.";
            $alert_type = "error";
        }
        $stmt->close();
    } else {
        $alert_msg = "Quantity must be at least 1.";
        $alert_type = "warning";
    }
}
// EMPTY CART
if (isset($_POST['empty_cart'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $alert_msg = "Cart emptied successfully.";
        $alert_type = "success";
    } else {
        $alert_msg = "Failed to empty cart.";
        $alert_type = "error";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beauty SkinCare - Cart Page</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <style type="text/css">
        <?php include 'style.css'; ?>
    </style>
</head>

<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>my Cart</h1>
        </div>
        <div class="title2">
            <a href="home.php">home</a><span>/ cart</span>
        </div>

        <section class="products">
            <h1 class="title1">products add in cart</h1>
            <div class="box-container">
                <?php
                if (!$user_id) {
                    echo '<p class="empty" style="text-align:center;">Please log in to view your cart.</p>';
                } else {
                    $stmt = $conn->prepare("SELECT c.id AS cart_id, c.qty, p.name, p.image, p.price
                                            FROM cart c
                                            JOIN products p ON c.product_id = p.id
                                            WHERE c.user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $grand_total = 0;
                        while ($item = $result->fetch_assoc()) {
                            $sub_total = $item['qty'] * $item['price'];
                            $grand_total += $sub_total;
                ?>
                <form action="" method="post" class="box">
                    <img src="img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="button-group">
                        <button type="submit" name="update_cart" title="Update Quantity" class="update-btn">
                            <i class="bx bxs-edit"></i>
                        </button>
                        <button type="submit" name="delete_item" title="Delete Item" onclick="return confirm('Delete this item?');" class="delete-btn">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                    <h3 class="name"><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="price">Price $<?= number_format($item['price'], 2) ?></p>
                    <div class="flex">
                        <input type="number" name="qty" min="1" max="99" value="<?= htmlspecialchars($item['qty']) ?>" class="qty" required>
                    </div>
                    <p class="sub-total">Subtotal: <span>$<?= number_format($sub_total, 2) ?></span></p>
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                </form>
                <?php
                        } 
                ?>
                <?php
                    } else {
                        echo '<p class="empty" style="text-align:center;">Your cart is empty!</p>';
                    }
                    $stmt->close();
                }
                ?>
            </div>
            <?php 
                if($grand_total != 0){
                
            ?>
            <div class="card-total">
                <p>total amount payable : <span>$<?= $grand_total; ?>/-</span></p>
                <div class="button">
                    <form action="" method="post">
                        <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure to empty your cart?')">Empty Cart</button>
                    </form>
                    <a href="checkout.php" class="btn" style="text-decoration: none; text-align: center;">proceed to checkout</a>
                </div>
            </div>
            <?php 
                }
            ?>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if ($alert_msg): ?>
        Swal.fire({
            icon: '<?= $alert_type ?>',
            title: '<?= addslashes($alert_msg) ?>',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
    </script>
    <script src="script.js"></script>
</body>

</html>
