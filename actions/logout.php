<?php
// actions/logout.php

// 1. Inițializăm sesiunea 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Golim toate variabilele de sesiune
$_SESSION = array();

// 3. Distrugem cookie-ul de sesiune în browser 
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Distrugem sesiunea pe server
session_destroy();

// 5. Redirecționare forțată către rădăcina proiectului
header("Location: /proiect_aer/index.php?page=login");
exit;