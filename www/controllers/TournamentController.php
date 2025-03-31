<?php
// controllers/TournamentController.php

function createTournament() {
    // Only allow admin users to create a tournament.
    if (!isset($_SESSION['user']) || $_SESSION['user']['account_type'] !== 'admin') {
        header("Location: /");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tournamentName = trim($_POST['name']);
        // Generate a random tournament code (6 alphanumeric characters)
        $tournamentCode = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
        $adminId = $_SESSION['user']['id'];
        $pdo = $GLOBALS['pdo'];

        $stmt = $pdo->prepare("INSERT INTO tournaments (code, name, admin_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$tournamentCode, $tournamentName, $adminId])) {
            echo "Tournament created successfully!<br>Tournament Code: <strong>" . htmlspecialchars($tournamentCode) . "</strong>";
            exit;
        } else {
            $error = "Failed to create tournament.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html class="dark" lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Create Tournament</title>
      <!-- Tailwind CSS CDN with dark mode enabled -->
      <script src="https://cdn.tailwindcss.com"></script>
      <script>tailwind.config = { darkMode: 'class' }</script>
    </head>
    <body class="bg-gray-900 text-white flex items-center justify-center h-screen">
      <div class="bg-gray-800 p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Create Tournament</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="/tournament/create">
          <div class="mb-4">
            <label class="block text-gray-200" for="name">Tournament Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Create Tournament</button>
          </div>
        </form>
      </div>
    </body>
    </html>
    <?php
}

function joinTournament() {
    // All logged-in users (including players and admin) can join.
    if (!isset($_SESSION['user'])) {
        header("Location: /login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = strtoupper(trim($_POST['code']));
        $userId = $_SESSION['user']['id'];
        $pdo = $GLOBALS['pdo'];

        // Verify that the tournament exists.
        $stmt = $pdo->prepare("SELECT * FROM tournaments WHERE code = ?");
        $stmt->execute([$code]);
        $tournament = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tournament) {
            $error = "Tournament not found.";
        } else {
            // Check if the user has already joined.
            $stmt = $pdo->prepare("SELECT * FROM tournament_participants WHERE tournament_id = ? AND user_id = ?");
            $stmt->execute([$tournament['id'], $userId]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $error = "You have already joined this tournament.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO tournament_participants (tournament_id, user_id) VALUES (?, ?)");
                if ($stmt->execute([$tournament['id'], $userId])) {
                    echo "Successfully joined tournament: " . htmlspecialchars($tournament['name']);
                    exit;
                } else {
                    $error = "Failed to join tournament.";
                }
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html class="dark" lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Join Tournament</title>
      <!-- Tailwind CSS CDN with dark mode enabled -->
      <script src="https://cdn.tailwindcss.com"></script>
      <script>tailwind.config = { darkMode: 'class' }</script>
    </head>
    <body class="bg-gray-900 text-white flex items-center justify-center h-screen">
      <div class="bg-gray-800 p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Join Tournament</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="/tournament/join">
          <div class="mb-4">
            <label class="block text-gray-200" for="code">Tournament Code</label>
            <input type="text" id="code" name="code" class="mt-1 block w-full border-gray-700 bg-gray-700 text-white rounded-md" required>
          </div>
          <div class="mb-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Join Tournament</button>
          </div>
        </form>
      </div>
    </body>
    </html>
    <?php
}
?>
