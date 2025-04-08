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
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        die("<p class='text-red-500 text-center'>⚠️ Please fill in all fields!</p>");
    }

    if ($password !== $confirm_password) {
        die("<p class='text-red-500 text-center'>⚠️ Passwords do not match!</p>");
    }

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        die("<p class='text-red-500 text-center'>⚠️ Username already exists!</p>");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
    if (mysqli_query($connect, $sql)) {
        header("Location: login.php"); // Redirect to login page
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
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">📝 Register</h2>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">📧 Username:</label>
                <input type="text" name="username" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">🔒 Password:</label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">🔒 Confirm Password:</label>
                <input type="password" name="confirm_password" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                📝 Register
            </button>
        </form>
    </div>

</body>
</html>