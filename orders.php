<?php
include 'config.php';

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}
if (isset($_POST['place_order'])) {
    // Validate and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, $_POST['payment_method']) : null;
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    $payment_status = 'pending';
    
    // Set a default delivery time (15:00 PM today) if not provided
    $delivery_time = isset($_POST['delivery_time']) ? $_POST['delivery_time'] : date('Y-m-d') . ' 15:00:00';

    // Validation
    if (empty($name) || empty($number) || empty($email) || empty($address) || empty($payment_method)) {
        echo "<p style='color: red;'>All fields are required!</p>";
        exit;
    }

    // Generate unique ticket ID
    $ticket_id = 'TICKET-' . uniqid();

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, address, payment_method, total_price, ticket_id, payment_status, order_date, delivery_time) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("isssssssss", $user_id, $name, $number, $email, $address, $payment_method, $total_price, $ticket_id, $payment_status, $delivery_time);

    if ($stmt->execute()) {
        header('Location: thank_you.php?ticket_id=' . $ticket_id);
        exit();
    } else {
        echo "<p style='color: red;'>An error occurred: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .order-actions form {
            display: inline-block;
            margin-right: 10px;
        }
        .order-actions button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .edit-btn {
            background-color:  #008CBA;
            color: white;
        }
        .cancel-btn {
            background-color: #f44336;
            color: white;
        }
        .complete-btn {
            background-color: #4CAF50;
            color: white;
        }
        .refund-btn, .track-btn {
            background-color: #ff9800;
            color: white;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
    <h3>Your Orders</h3>
    <p> <a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">
    <h1 class="title">Placed Orders</h1>

    <div class="box-container">

        <?php
        // Fetch orders for the current user
        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
        ?>

        <div class="box">
            <p>Placed on: <span><?php echo $fetch_orders['order_date']; ?></span></p>
            <p>Ticket ID: <span><?php echo $fetch_orders['ticket_id']; ?></span></p>
            <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
            <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
            <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
            <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
            <p>Payment Method: <span><?php echo isset($fetch_orders['payment_method']) ? $fetch_orders['payment_method'] : 'Not Available'; ?></span></p>
            <p>Total Price: <span>R<?php echo $fetch_orders['total_price']; ?>/-</span></p>
            <p>Payment Status: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['payment_status']; ?></span></p>

            <div class="order-actions">
                <!-- Only show Edit Order button if payment is not completed -->
                <?php if ($fetch_orders['payment_status'] != 'completed'): ?>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="ticket_id" value="<?php echo $fetch_orders['ticket_id']; ?>">
                        <button type="submit" class="btn edit-btn">Edit Order</button>
                    </form>
                <?php endif; ?>

                <!-- Always show Cancel Order button -->
                <form action="cancel_order.php" method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $fetch_orders['ticket_id']; ?>">
                    <button type="submit" class="btn cancel-btn">Cancel Order</button>
                </form>

                <!-- Always show Complete Order button if the order isn't completed yet -->
                <?php if ($fetch_orders['payment_status'] != 'completed'): ?>
                    <form action="complete_order.php" method="POST">
                        <input type="hidden" name="ticket_id" value="<?php echo $fetch_orders['ticket_id']; ?>">
                        <button type="submit" class="btn complete-btn">Complete Order</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Show Refund and Track Order buttons after completing the order -->
            <?php if ($fetch_orders['payment_status'] == 'completed'): ?>
                <form action="refund_order.php" method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $fetch_orders['ticket_id']; ?>">
                    <button type="submit" class="btn refund-btn">Request Refund</button>
                </form>

                <form action="track_order.php" method="GET">
                    <input type="hidden" name="ticket_id" value="<?php echo $fetch_orders['ticket_id']; ?>">
                    <button type="submit" class="btn track-btn">Track Order</button>
                </form>
            <?php endif; ?>

        </div>

        <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
