<?php
/**
 * index.php - Front Controller
 */

require_once 'includes/functions.php';

$page = $_GET['page'] ?? 'dashboard';

$routes = [
    // Pagini vizuale
    'dashboard' => 'pages/dashboard.php',
    'add' => 'pages/add.php',
    'edit' => 'pages/edit.php',
    'contact' => 'pages/contact.php',
    'login' => 'pages/login.php',
    'about' => 'pages/about.php',
    '404' => 'pages/404.php',

    // Acțiuni și Scripturi de sistem
    'delete' => 'actions/delete.php',
    'logout' => 'actions/logout.php',
    'export' => 'actions/export.php',
    'send_contact' => 'actions/send_contact.php',
    'setup_users' => 'actions/setup_users.php'
];

if (array_key_exists($page, $routes)) {
    $target = $routes[$page];

    if (!file_exists($target)) {
        http_response_code(404);
        $target = 'pages/404.php';
    }

    // Identificăm tipul paginii pentru a decide dacă punem Header/Footer
    $is_action = (strpos($target, 'actions/') === 0);
    $is_login = ($page === 'login');

    if ($is_action) {
        // Acțiunile (inclusiv setup_users) rulează fără elemente de design
        include $target;
    } elseif ($is_login) {
        // Pagina de login rulează singură
        include $target;
    } else {
        // Pagini normale cu design complet
        include 'includes/header.php';
        include $target;
        include 'includes/footer.php';
    }

} else {
    http_response_code(404);
    include 'includes/header.php';
    include 'pages/404.php';
    include 'includes/footer.php';
}