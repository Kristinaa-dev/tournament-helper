<?php
// controllers/QuickStartController.php

function quickStart() {
    $pdo = $GLOBALS['pdo'];
    
    // Array of users to create for testing purposes.
    $users = [
        ['username' => 'player1', 'password' => 'player', 'account_type' => 'player'],
        ['username' => 'player2', 'password' => 'player', 'account_type' => 'player'],
        ['username' => 'admin',   'password' => 'admin',  'account_type' => 'admin'],
    ];
    
    $output = "";
    foreach ($users as $user) {
        // Hash the password before storing
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$user['username'], $hashedPassword, $user['account_type']]);
            $output .= "Created user: " . htmlspecialchars($user['username']) . "<br>";
        } catch (PDOException $e) {
            // If the user already exists, output an error message
            $output .= "Error creating user " . htmlspecialchars($user['username']) . ": " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>Quick Start Data</title>
  <script src='https://cdn.tailwindcss.com'></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class='bg-gray-900 text-white flex items-center justify-center h-screen'>
  <div class='bg-gray-800 p-8 rounded shadow-md w-full max-w-md'>
    <h2 class='text-2xl mb-6 text-center'>Quick Start Data</h2>
    <div>$output</div>
    <p class='mt-4 text-center'><a href='/login' class='text-blue-400'>Go to Login</a></p>
  </div>
</body>
</html>";
}
