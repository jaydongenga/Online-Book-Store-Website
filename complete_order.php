<?php
include 'config.php';

session_start();

// Ensure the user is logged in
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
   exit;
}

// Check if ticket_id is provided in the POST request
if(isset($_POST['ticket_id'])) {
   $ticket_id = $_POST['ticket_id'];

   // Update the order status to 'completed' in the database
   $update_query = mysqli_query($conn, "UPDATE `orders` SET `payment_status` = 'completed' WHERE `ticket_id` = '$ticket_id' AND `user_id` = '$user_id'") 
                   or die('Query failed: ' . mysqli_error($conn));

   // Check if the query was successful
   if($update_query) {
      echo "<script>alert('Order marked as completed successfully!'); window.location.href = 'orders.php';</script>";
   } else {
      echo "<script>alert('Failed to complete the order. Please try again later.'); window.location.href = 'orders.php';</script>";
   }
} else {
   echo "<script>alert('Invalid order.'); window.location.href = 'orders.php';</script>";
}
?>
