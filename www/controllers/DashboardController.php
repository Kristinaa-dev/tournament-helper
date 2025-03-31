<?php
// controllers/DashboardController.php

function dashboard() {
    if (!isset($_SESSION['user'])) {
        header("Location: /login");
        exit;
    }
    $user = $_SESSION['user'];
    ?>
    <!DOCTYPE html>
    <html class="dark" lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Dashboard</title>
      <!-- Tailwind CSS CDN -->
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
        tailwind.config = { darkMode: 'class' }
      </script>
    </head>
    <body class="bg-gray-900 text-white">
      <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">
          Welcome, <?php echo htmlspecialchars($user['username']); ?>
        </h1>
        <p>Your account type is: <?php echo htmlspecialchars($user['account_type']); ?></p>
        <?php if ($user['account_type'] === 'player'): ?>
          <p class="mt-4">
            Rating: <?php echo htmlspecialchars($user['rating']); ?><br>
            Wins: <?php echo htmlspecialchars($user['wins']); ?>, 
            Draws: <?php echo htmlspecialchars($user['draws']); ?>, 
            Losses: <?php echo htmlspecialchars($user['losses']); ?>
          </p>
        <?php endif; ?>
        <a href="/logout" class="mt-4 inline-block text-blue-400">Logout</a>
      </div>
    </body>
    </html>
    <?php
}
?>
