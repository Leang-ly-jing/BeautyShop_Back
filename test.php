
<?php
session_start();
include 'components/connection.php';

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
//delete from wishlist
if(isset($_POST['delete_item'])){
    $cartlist_id = $_POST['cartlist_id'];
    $cartlist_id = filter_var($cartlist_id, FILTER_SANITIZE_STRING);
    $varify_delete_items = $conn->prepare("SELECT * FROM `cartlist` WHERE id = ?");
    $varify_delete_items->execute([$cartlist_id]);
    if($varify_delete_items->rowCount>0){
        $delete_cartlist_id = $conn->prepare("DELETE FROM `cartlist` WHERE id = ?");
        $delete_cartlist_id->execute([$cartlist_id]);
        $success_msg[] = "cartlist item delete successfully";
    }else{
        $warning_msg[] = "cartlist item already delete";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beauty SkinCare - cart Page</title>

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
            <h1 class="title">products add in cart</h1>
            <div class="box-container">
                <?php 
                    $grand_total = 0;
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? ");


                    $select_cart->execute([$user_id]);
                    if($select_cart->rowCount()>0){
                        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC));
                            $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                            $select_products->execute([$fetch_wishlist['product_id']]);
                            if($select_products->rowCount()>0){
                                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                           
                        
                ?>
                <form method="post" action="" class="box">
                    <input type="hidden" name="cart_id" id="" value="<?= $fetch_cart['id'] ?>">
                    <img src="image/<?= $fetch_products['image']; ?>" alt="" class="img">
                    <h3 class="name"></h3><?= $fetch_products['name']; ?></h3>
                    <div class="flex">
                        <p class="price">price $<?= $fetch_products['price'] ?></p>
                        <input type="number" name="qty" id="" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty">
                        <button type="submit" name="update_cart" class="bx bxs-edit fa-edit"></button>
                    </div>
                    <p class="sub-total">sub total :  <span><?= $sub_total = ($fetch_cart['qty'] * $fetch_cart['price'])?></span></p>
                    <button type="submit" name="delete_item" class="btn" onclick="return confirm('delete this item')">delete</button>
                    
                </form>
                <?php
                    $grand_total += $sub_total ;
                    }
                }
                else {
                    echo '<p class="empty">product was not found!</p>';
                }
                ?>
            </div>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if ($alert_msg): ?>
        Swal.fire({
            icon: '<?php
                // Map PHP alert_type to SweetAlert2 icons
                switch ($alert_type) {
                    case 'success': echo 'success'; break;
                    case 'warning': echo 'warning'; break;
                    case 'error': echo 'error'; break;
                    case 'info': echo 'info'; break;
                    default: echo 'question';
                }
            ?>',
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
