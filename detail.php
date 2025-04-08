<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Clothing Item Details</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-7xl mx-auto p-6">
    <?php
    // Step 1: Connect to the database
    $connect = mysqli_connect('localhost', 'root', '', 'SimpleMensClothing'); // Updated database name
    if (!$connect) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Step 2: Get ProductID from URL
    if (isset($_GET['id'])) {
      $id = (int) $_GET['id'];
      echo "ID from URL: " . $id . "<br>"; // Check ID

      // Step 3: Query clothing information from the database
      $sql = "SELECT p.ProductID, p.ProductName, p.Price, p.Photo, p.Description, c.CategoryName 
              FROM Products p 
              LEFT JOIN Categories c ON p.CategoryID = c.CategoryID 
              WHERE p.ProductID = {$id}"; // Updated query to match new schema
      $result = mysqli_query($connect, $sql);

      if (!$result) {
        die("Query error: " . mysqli_error($connect));
      }

      // Step 4: Display clothing information
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $product_id = $row['ProductID'];
          $product_name = htmlspecialchars($row['ProductName']);
          $product_price = number_format($row['Price'], 0, ',', '.');
          $product_image = htmlspecialchars($row['Photo']);
          $product_description = htmlspecialchars($row['Description']);
    ?>
          <!-- Display clothing image -->
          <div class="flex space-x-8">
            <div class="w-1/2">
              <img src="images/<?php echo $product_image; ?>" 
                   onerror="this.src='images/clothing/default.jpg';" 
                   class="w-full h-auto rounded-lg shadow-lg">
            </div>

            <!-- Display clothing information -->
            <div class="w-1/2">
              <h2 class="text-3xl font-bold text-gray-800 mb-4"><?php echo $product_name; ?></h2>
              <p class="text-blue-600 text-2xl font-bold mb-6"><?php echo $product_price; ?> VND</p>

              <!-- Add to cart button -->
              <a href="add_to_cart.php?id=<?php echo $product_id; ?>" 
                 class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                🛒 Add to Cart
              </a>

              <!-- Horizontal line -->
              <div class="border-b border-gray-300 my-6"></div>

              <!-- Clothing description -->
              <h2 class="text-xl font-bold text-gray-800 mb-4">Basic Information:</h2>
              <p class="text-gray-700"><?php echo $product_description; ?></p>
            </div>
          </div>
    <?php
        }
      } else {
        echo "<p class='text-red-500'>No clothing item found with ID: {$id}.</p>";
      }
    } else {
      echo "<p class='text-red-500'>Please provide a clothing ID.</p>";
    }

    // Step 5: Close the connection
    mysqli_close($connect);
    ?>
  </div>
</body>
</html>