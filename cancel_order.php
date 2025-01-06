<?php
include 'config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$user_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($user_id)) {
   header('location:login.php');
   exit;
}

// Check if ticket_id is provided via POST
if (isset($_POST['ticket_id'])) {
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    
    // Update the payment status of the order to 'canceled'
    $cancel_query = mysqli_query($conn, "UPDATE `orders` SET payment_status = 'canceled' WHERE ticket_id = '$ticket_id' AND user_id = '$user_id'");

    // Check if the query was successful
    if ($cancel_query) {
        // Successfully canceled, redirect to orders page
        header("Location: orders.php?status=canceled");
        exit;
    } else {
        // Display error if the query fails
        echo "Error: Could not cancel the order.";
    }
} else {
    // Handle missing ticket_id
    echo "Invalid order. Please try again.";
}
?>
