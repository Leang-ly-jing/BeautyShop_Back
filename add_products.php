<?php
session_start();
include 'components/connection.php';

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['submit'])) {
    $name           = trim($_POST['name']);
    $price          = floatval($_POST['price']);
    $product_detail = trim($_POST['product_detail']);

    // Handle image upload
    $image_name = basename($_FILES['image']['name']);
    $image_tmp  = $_FILES['image']['tmp_name'];
    $upload_dir = "img/" . $image_name;

    if (move_uploaded_file($image_tmp, $upload_dir)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, product_detail) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $image_name, $product_detail);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Product added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>Failed to upload image.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Beauty SkinCare - add products Page</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php include 'components/header.php'; ?>
<?php include 'components/alert.php'; ?>

<div class="main">
    <div class="banner"><h1>Add Beauty Shop</h1></div>
    <div class="title2">
        <a href="home.php">home</a><span>/ add products</span>
    </div>
    <div class="form-container">
        <div class="title" style="text-align: center;">
            <img src="img/green/img/download.png" alt="">
            <h1>Admin Add new Products</h1>
            <p></p>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Product Name <span>*</span></label><br>
            <input type="text" name="name" required><br><br>

            <label>Price <span>*</span></label><br>
            <input type="number" step="0.01" name="price" required><br><br>

            <label>Product Image <span>*</span></label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <label>Product Detail <span>*</span></label><br>
            <textarea name="product_detail" rows="4" cols="50" required></textarea><br><br>

            <button type="submit" name="submit" class="btn">Add Product</button>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
</div>
</body>
</html>
