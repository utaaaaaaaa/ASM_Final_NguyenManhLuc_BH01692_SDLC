<?php
// Display PHP errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL
$connect = mysqli_connect('localhost', 'root', '', 'SimpleMensClothing'); // Updated database name
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8 encoding
mysqli_set_charset($connect, "utf8");

// Query products (clothing)
$sql = "SELECT p.ProductID, p.ProductName, p.Price, p.Photo, c.CategoryName 
        FROM Products p 
        LEFT JOIN Categories c ON p.CategoryID = c.CategoryID"; // Updated query to join with Categories
$result = mysqli_query($connect, $sql);
if (!$result) {
    die("Query error: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men's Clothing Store</title>
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
            <div class="flex items-center space-x-4">
                <a href="cart.php" class="relative bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    🛒 Cart
                    <?php if (isset($_SESSION['cart'])) : ?>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="login.php" class="flex items-center hover:underline">
                    <img src="images/user_icon.png" alt="Login" class="h-6 w-6 mr-1"> <!-- User icon for login -->
                    Login
                </a>
                <a href="register.php" class="flex items-center hover:underline">
                    <img src="images/register_icon.png" alt="Register" class="h-6 w-6 mr-1"> <!-- User icon for register -->
                    Register
                </a>
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white py-3">
        <ul class="flex justify-center space-x-8">
            <li><a href="index.php" class="hover:underline">Home</a></li>
            <li><a href="detail.php" class="hover:underline">Manage Clothing</a></li>
            <li><a href="add_product.php" class="hover:underline">Add Clothing</a></li>
            <li><a href="about.php" class="hover:underline">About Us</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto flex mt-6 space-x-6 px-6">
        
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white shadow-lg p-6 rounded-lg">
            <h2 class="text-lg font-bold border-b pb-2">Clothing Categories</h2>
            <ul class="space-y-2 mt-4">
                <?php
                // Fetch categories for sidebar
                $category_sql = "SELECT * FROM Categories";
                $category_result = mysqli_query($connect, $category_sql);
                while ($category = mysqli_fetch_assoc($category_result)) {
                    echo '<li><a href="#" class="text-gray-700 hover:text-blue-600">' . htmlspecialchars($category['CategoryName']) . '</a></li>';
                }
                ?>
            </ul>
        </aside>

        <!-- Product List -->
        <main class="w-3/4">
            <h2 class="text-center text-2xl font-bold text-gray-800">🧥 Latest Men's Clothing</h2>
            <div class="grid grid-cols-3 gap-6 mt-6">
                <?php while ($row_clothing = mysqli_fetch_array($result)) : 
                    $clothing_name = htmlspecialchars($row_clothing['ProductName'] ?? 'No name');
                    $clothing_image = htmlspecialchars($row_clothing['Photo'] ?? 'default.jpg');
                    $clothing_price = number_format($row_clothing['Price'] ?? 0, 2, ',', '.');
                    $clothing_id = htmlspecialchars($row_clothing['ProductID'] ?? 0);
                ?>
                    <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition">
                        <h3 class="text-lg font-semibold"><?php echo $clothing_name; ?></h3>
                        <img src="images/<?php echo $clothing_image; ?>" 
                             onerror="this.src='images/clothing/default.jpg';" class="w-full h-48 object-cover rounded-md mt-2">
                        <p class="text-blue-500 font-bold mt-2"><?php echo $clothing_price; ?> VND</p>
                        <div class="mt-4 flex justify-between">
                            <a href="detail.php?id=<?php echo $clothing_id; ?>" class="text-blue-600 hover:underline">🔍 View Details</a>
                            <a href="add_to_cart.php?id=<?php echo $clothing_id; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                🛒 Add to Cart
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center py-6 mt-10">
        <p>&copy; 2025 Men's Clothing Store ABC</p>
    </footer>

    <?php
    // Close database connection
    mysqli_close($connect);
    ?>
</body>
</html>