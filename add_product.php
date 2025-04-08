<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Connect to MySQL
$connect = mysqli_connect('localhost', 'root', '', 'SimpleMensClothing');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_description'])) {
        die("<p class='text-red-500 text-center'>⚠️ Please fill in all the information!</p>");
    }

    if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] != 0) {
        die("<p class='text-red-500 text-center'>⚠️ You have not selected an image or the image is invalid.</p>");
    }

    $product_name = mysqli_real_escape_string($connect, $_POST['product_name']);
    $product_price = (float) $_POST['product_price'];
    $product_description = mysqli_real_escape_string($connect, $_POST['product_description']);

    $target_dir = "Images/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = time() . "_" . basename($_FILES['product_image']['name']);
    $target_file = $target_dir . $image_name;

    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        die("<p class='text-red-500 text-center'>⚠️ Unable to upload the image.</p>");
    }

    $sql = "INSERT INTO Products (ProductName, Price, Description, Photo) 
            VALUES ('$product_name', '$product_price', '$product_description', '$image_name')";

    if (mysqli_query($connect, $sql)) {
        header("Location: index.php"); // Redirect to index.php
        exit();
    } else {
        echo "<p class='text-red-500 text-center'>❌ Error: " . mysqli_error($connect) . "</p>";
    }
}

// Close connection
mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">➕ Add Product</h2>
        
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">📌 Product Name:</label>
                <input type="text" name="product_name" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">💰 Product Price:</label>
                <input type="number" name="product_price" step="0.01" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">📖 Product Description:</label>
                <textarea name="product_description" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">📷 Product Image:</label>
                <input type="file" name="product_image" accept="image/*" required 
                       class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                ➕ Add Product
            </button>
        </form>
    </div>

</body>
</html>