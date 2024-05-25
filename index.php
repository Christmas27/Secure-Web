<?php
include 'conn.php'; // Include the database connection

session_start();

// Define the pizza prices
$pizza_prices = [
    'margherita' => 10,
    'pepperoni' => 12,
    'hawaiian' => 14,
];

// Define the generate_auth_value function
function generate_auth_value($user) {
    // Generate a random string
    $random_string = bin2hex(random_bytes(15));

    // Check if $user is an array and contains a 'name' key
    if (is_array($user) && isset($user['name'])) {
        // Combine the username with the random string
        $auth_value = $user['name'] . $random_string;
    } else {
        // $user is not an array or does not contain a 'name' key
        // Handle this case as needed
        $auth_value = $random_string;
    }

    return $auth_value;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the keys exist in the POST data
    if (isset($_POST['pizza_type'], $_POST['quantity'])) {
        // Client-State Manipulation countermeasure
        $_SESSION['pizza_type'] = $_POST['pizza_type'];
        $_SESSION['quantity'] = $_POST['quantity'];
    }
}

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Generate a new auth value for the user
    $auth_value = generate_auth_value($_SESSION['user']);

    // Spoofing Authentication Cookies countermeasure
    setcookie('auth', $auth_value, [
        'secure' => true,   // Cookie will only be sent over HTTPS
        'httponly' => true, // Cookie can't be accessed by JavaScript
    ]);
    // Session Fixation countermeasure
    session_regenerate_id(true);
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the input
    $pizza_type = filter_input(INPUT_POST, 'pizza_type', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

    // Check if the input is valid
    if ($pizza_type && $quantity) {
        // Calculate the total price
        $total_price = $pizza_prices[$pizza_type] * $quantity;

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO orders (user_id, pizza_type, quantity, total_price) VALUES (:user_id, :pizza_type, :quantity, :total_price)");

        // Bind the parameters
        $stmt->bindParam(':user_id', $_SESSION['user']['id']);
        $stmt->bindParam(':pizza_type', $pizza_type);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);

        // Execute the statement
        $stmt->execute();

        // Store the order details in session variables
        $_SESSION['order'] = [
            'pizza_type' => $pizza_type,
            'quantity' => $quantity,
            'total_price' => $total_price,
        ];

        // Redirect to the confirmation page
        header('Location: confirmation.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Delivery</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <form method="post" action="index.php">
    <label for="pizza_type">Choose a pizza:</label>
    <select name="pizza_type" id="pizza_type">
        <option value="margherita">Margherita - RM <?php echo $pizza_prices['margherita']; ?></option>
        <option value="pepperoni">Pepperoni - RM <?php echo $pizza_prices['pepperoni']; ?></option>
        <option value="hawaiian">Hawaiian - RM <?php echo $pizza_prices['hawaiian']; ?></option>
    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="10">
    <input type="submit" value="Order">
</form>
</body>
</html>