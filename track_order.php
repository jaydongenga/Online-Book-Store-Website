<?php
include 'config.php';

if(isset($_GET['ticket_id'])){
   $ticket_id = $_GET['ticket_id'];

   // Fetch the order with the provided ticket ID
   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE ticket_id = '$ticket_id'") or die('Query failed');
   $order = mysqli_fetch_assoc($order_query);

   if ($order) {
      $tracking_number = $order['tracking_number'];
      $tracking_status = $order['tracking_status'];
   } else {
      echo "Order not found.";
      exit;
   }
} else {
   echo "No ticket ID provided.";
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Track Your Order</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Track Your Order</h3>
   <p> <a href="home.php">Home</a> / Track Order </p>
</div>

<section class="order-tracking">
   <h1 class="title">Order Tracking Information</h1>

   <div class="box">
      <p>Tracking Number: <span><?php echo $tracking_number; ?></span></p>
      <p>Tracking Status: <span><?php echo $tracking_status; ?></span></p>
   </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
