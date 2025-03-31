<?php
// controllers/AuthController.php

function login() {
    // Process login if form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        // Query using the global PDO connection
        $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /dashboard");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html class="dark" lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Login</title>
      <!-- Tailwind CSS CDN -->
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
        tailwind.config = { darkMode: 'class' }
      </script>
    </head>
    <body class="bg-gray-900 text-white flex items-center justify-center h-screen">
      <div class="bg-gray-800 p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Login</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
          <div class="mb-4">
            <label class="block text-gray-200" for="username">Username</label>
            <input type="text" id="username" name="username" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <label class="block text-gray-200" for="password">Password</label>
            <input type="password" id="password" name="password" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
          </div>
          <div class="text-center">
            Don't have an account? <a href="/register" class="text-blue-400">Register here</a>
          </div>
        </form>
      </div>
    </body>
    </html>
    <?php
}

function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $account_type = $_POST['account_type'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $GLOBALS['pdo']->prepare("INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $hashedPassword, $account_type])) {
            echo "Registration successful. <a href='/login'>Login</a>";
            exit;
        } else {
            $error = "Registration failed.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html class="dark" lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Register</title>
      <!-- Tailwind CSS CDN -->
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
        tailwind.config = { darkMode: 'class' }
      </script>
    </head>
    <body class="bg-gray-900 text-white flex items-center justify-center h-screen">
      <div class="bg-gray-800 p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Register</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="/register">
          <div class="mb-4">
            <label class="block text-gray-200" for="username">Username</label>
            <input type="text" id="username" name="username" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <label class="block text-gray-200" for="password">Password</label>
            <input type="password" id="password" name="password" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <label class="block text-gray-200" for="account_type">Account Type</label>
            <select id="account_type" name="account_type" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md">
              <option value="player">Player</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="mb-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Register</button>
          </div>
          <div class="text-center">
            Already have an account? <a href="/login" class="text-blue-400">Login here</a>
          </div>
        </form>
      </div>
    </body>
    </html>
    <?php
}

function logout() {
    session_destroy();
    header("Location: /login");
    exit;
}
?>
