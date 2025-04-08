<?php
// Start session
session_start();

// Connect to MySQL
$connect = mysqli_connect('localhost', 'root', '', 'SimpleMensClothing'); // Updated database name
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8
mysqli_set_charset($connect, "utf8");

// Handle quantity update or item removal requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]); // Remove item if quantity <= 0
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]); // Remove item from cart
        }
    }
}

// Get product information from the cart
$cart_items = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $sql = "SELECT * FROM Products WHERE ProductID = $product_id"; // Updated table name
        $result = mysqli_query($connect, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $product['quantity'] = $item['quantity'];
            $cart_items[] = $product;
        }
    }
}

// Close connection
mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6">
            <img src="images/mens_clothing_logo.png" alt="Logo" class="h-12"> <!-- Update logo -->
            <form method="get" action="" class="flex items-center">
                <input type="text" name="user_query" placeholder="Search for clothing..." 
                       class="border border-gray-300 rounded-l-lg p-2 focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">🔍</button>
            </form>
            <a href="cart.php" class="relative bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                🛒 Cart
                <?php if (isset($_SESSION['cart'])) : ?>
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        <?php echo count($_SESSION['cart']); ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white py-3">
        <ul class="flex justify-center space-x-8">
            <li><a href="index.php" class="hover:underline">Home</a></li>
            <li><a href="detail.php" class="hover:underline">Manage Clothing</a></li>
            <li><a href="add_clothing.php" class="hover:underline">Add Clothing</a></li>
            <li><a href="about.php" class="hover:underline">About Us</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto mt-6 px-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">🛒 Your Cart</h2>

        <?php if (empty($cart_items)) : ?>
            <p class="text-center text-gray-600">Your cart is empty.</p>
        <?php else : ?>
            <form method="post" action="cart.php" class="bg-white p-6 rounded-lg shadow-lg">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Product</th>
                            <th class="text-left py-2">Quantity</th>
                            <th class="text-left py-2">Price</th>
                            <th class="text-left py-2">Total</th>
                            <th class="text-left py-2">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $product) : 
                            $total_price = $product['Price'] * $product['quantity']; // Updated field name
                        ?>
                            <tr class="border-b">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img src="images/<?php echo htmlspecialchars($product['Photo']); ?>" 
                                             class="w-16 h-16 object-cover rounded-md">
                                        <span class="ml-4"><?php echo htmlspecialchars($product['ProductName']); ?></span> <!-- Updated field name -->
                                    </div>
                                </td>
                                <td class="py-4">
                                    <input type="number" name="quantity[<?php echo $product['ProductID']; ?>]" 
                                           value="<?php echo $product['quantity']; ?>" min="1" 
                                           class="w-20 px-2 py-1 border border-gray-300 rounded-lg">
                                </td>
                                <td class="py-4"><?php echo number_format($product['Price'], 0, ',', '.'); ?> VND</td> <!-- Updated field name -->
                                <td class="py-4"><?php echo number_format($total_price, 0, ',', '.'); ?> VND</td>
                                <td class="py-4">
                                    <form method="post" action="cart.php" class="inline">
                                        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>"> <!-- Updated field name -->
                                        <button type="submit" name="remove_item" class="text-red-600 hover:text-red-800">🗑️ Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="mt-6 text-right">
                    <p class="text-xl font-bold">Total: <?php echo number_format(array_sum(array_map(function($item) {
                        return $item['Price'] * $item['quantity']; // Updated field name
                    }, $cart_items)), 0, ',', '.'); ?> VND</p>
                    <button type="submit" name="update_cart" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Update Cart
                    </button>
                    <button type="button" class="mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        Checkout
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center py-6 mt-10">
        <p>&copy; 2025 Men's Clothing Store ABC</p>
    </footer>

</body>
</html>