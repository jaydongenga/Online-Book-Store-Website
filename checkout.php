<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Stop further script execution after redirect
}

if (isset($_POST['order_btn'])) {
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $address = mysqli_real_escape_string($conn, "Flat No. {$_POST['flat']}, {$_POST['street']}, {$_POST['city']}, {$_POST['state']}, {$_POST['country']} - {$_POST['pin_code']}");
    $placed_on = date('Y-m-d');

    $message = []; // To store error messages

    // Validate inputs
    if (!$email) {
        $message[] = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[0-9]{10}$/', $number)) {
        $message[] = 'Please enter a valid 10-digit phone number.';
    } else {
        // Calculate total
        $cart_total = 0;
        $cart_products = [];

        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = "{$cart_item['name']} ({$cart_item['quantity']})";
            $cart_total += $cart_item['price'] * $cart_item['quantity'];
        }

        if ($cart_total == 0) {
            $message[] = 'Your cart is empty!';
        } else {
            // Prepare order data
            $total_products = implode(', ', $cart_products);

            // Check if order already exists
            $order_query = mysqli_prepare($conn, "SELECT * FROM `orders` WHERE user_id = ? AND total_price = ? AND placed_on = ?");
            mysqli_stmt_bind_param($order_query, "iis", $user_id, $cart_total, $placed_on);
            mysqli_stmt_execute($order_query);
            $result = mysqli_stmt_get_result($order_query);

            if (mysqli_num_rows($result) > 0) {
                $message[] = 'Order already placed!';
            } else {
                // Insert order without delivery time
                $insert_order = mysqli_prepare($conn, "INSERT INTO `orders` (user_id, name, number, email, payment_method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($insert_order, "issssssss", $user_id, $name, $number, $email, $payment_method, $address, $total_products, $cart_total, $placed_on);

                if (mysqli_stmt_execute($insert_order)) {
                    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
                    $_SESSION['message'] = 'Order placed successfully!';
                    header('location:orders.php');
                    exit();
                } else {
                    $message[] = 'Failed to place the order. Try again later.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .view-order-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .view-order-btn:hover {
            background-color: #45a049;
        }

        .btn {
            background-color: #ff5722;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
    <h3>Checkout</h3>
    <p><a href="home.php">Home</a> / Checkout</p>
</div>

<section class="display-order">
    <?php  
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>
        <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'R' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span> </p>
    <?php
        }
    } else {
        echo '<p class="empty">Your cart is empty</p>';
    }
    ?>
    <div class="grand-total"> Grand Total: <span>R<?php echo $grand_total; ?>/-</span> </div>
</section>

<section class="checkout">
    <form action="checkout.php" method="post">
        <h3>Place Your Order</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Your Name:</span>
                <input type="text" name="name" required placeholder="Enter your name">
            </div>
            <div class="inputBox">
                <span>Your Number:</span>
                <input type="text" name="number" required pattern="\d{10}" placeholder="Enter your number">
            </div>
            <div class="inputBox">
                <span>Your Email:</span>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="inputBox">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="inputBox">
                <span>Address Line 01:</span>
                <input type="number" name="flat" required placeholder="e.g. Flat No.">
            </div>
            <div class="inputBox">
                <span>Address Line 02:</span>
                <input type="text" name="street" required placeholder="e.g. Street Name">
            </div>
            <div class="inputBox">
                <span>City:</span>
                <input type="text" name="city" required placeholder="e.g. Cape Town">
            </div>
            <div class="inputBox">
                <span>Province/State:</span>
                <input type="text" name="state" required placeholder="e.g. California">
            </div>
            <div class="inputBox">
                <span>Country:</span>
                <input type="text" name="country" required placeholder="e.g. South Africa">
            </div>
            <div class="inputBox">
                <span>Pin Code:</span>
                <input type="number" name="pin_code" required placeholder="e.g. 123456">
            </div>
        </div>
        
        <div class="button-container">
            <input type="submit" value="Order Now" class="btn" name="order_btn">
            <a href="orders.php" class="view-order-btn">View Order</a>
        </div>
    </form>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
