<?php

session_start();

// Check if the order details are stored in the session
if (isset($_SESSION['order'])) {
    $order = $_SESSION['order'];
   // echo "Thank you for your order. You have ordered {$order['quantity']} {$order['pizza_type']} pizza(s). The total price is RM {$order['total_price']}.";
} else {
   // echo "No order has been placed.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="confirm.css">
</head>
<body>
    <h1>Order Confirmation</h1>
    <p><?php echo isset($order) ? "Thank you for your order. You have ordered {$order['quantity']} {$order['pizza_type']} pizza(s). The total price is RM {$order['total_price']}." : "No order has been placed."; ?></p>
</body>
</html>