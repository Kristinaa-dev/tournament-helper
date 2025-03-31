<?php
// routes.php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
    case '/login':
        require_once __DIR__ . '/controllers/AuthController.php';
        login();
        break;
    case '/register':
        require_once __DIR__ . '/controllers/AuthController.php';
        register();
        break;
    case '/dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        dashboard();
        break;
    case '/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        logout();
        break;
    case '/tournament/create':
        require_once __DIR__ . '/controllers/TournamentController.php';
        createTournament();
        break;
    case '/tournament/join':
        require_once __DIR__ . '/controllers/TournamentController.php';
        joinTournament();
        break;
    case '/start':
        require_once __DIR__ . '/controllers/QuickStartController.php';
        quickStart();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>
