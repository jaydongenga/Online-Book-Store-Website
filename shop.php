<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Exit after header redirect to stop further script execution
}

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Use prepared statements to avoid SQL injection
    $check_cart_numbers = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    mysqli_stmt_bind_param($check_cart_numbers, "si", $product_name, $user_id); // "si" means string, integer
    mysqli_stmt_execute($check_cart_numbers);
    $result = mysqli_stmt_get_result($check_cart_numbers);

    if (mysqli_num_rows($result) > 0) {
        $message[] = 'This product is already in your cart!';
    } else {
        $insert_query = mysqli_prepare($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_query, "isdis", $user_id, $product_name, $product_price, $product_quantity, $product_image); // "isdis" means integer, string, decimal, integer, string
        mysqli_stmt_execute($insert_query);
        $message[] = 'Product added to your cart!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Align buttons horizontally in the form */
        .button-container {
            display: flex;
            gap: 10px; /* Space between buttons */
            margin-top: 10px;
        }

        .button-container .btn {
            flex: 1;
            text-align: center;
        }
    </style>
</head>
<body>
    
<?php include 'header.php'; ?>

<div class="heading">
    <h3>Our Shop</h3>
    <p><a href="home.php">Home</a> / Shop</p>
</div>

<section class="products">

    <h1 class="title">Latest Books</h1>

    <div class="box-container">

        <?php  
            // Query to select products from the database
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
        <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">R<?php echo $fetch_products['price']; ?>/-</div>
            <input type="number" min="1" name="product_quantity" value="1" class="qty">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

            <!-- Button Container -->
            <div class="button-container">
                <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                <a href="cart.php" class="btn">View Cart</a>
            </div>
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">No books available at the moment. Check back later!</p>';
        }
        ?>
    </div>

</section>

<?php include 'footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
