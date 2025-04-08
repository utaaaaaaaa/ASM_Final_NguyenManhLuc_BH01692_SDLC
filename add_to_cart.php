<?php
// Start session
session_start();

// Check if there is no cart in the session, create a new cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if a product ID has been sent from the form
if (isset($_GET['id'])) {
    $flower_id = (int) $_GET['id'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$flower_id])) {
        // If it is, increase the quantity by 1
        $_SESSION['cart'][$flower_id]['quantity'] += 1;
    } else {
        // If not, add the product to the cart with a quantity of 1
        $_SESSION['cart'][$flower_id] = [
            'quantity' => 1,
        ];
    }

    // Redirect the user to the cart page
    header("Location: cart.php");
    exit();
} else {
    // If no product ID is provided, redirect to the home page
    header("Location: index.php");
    exit();
}
?>