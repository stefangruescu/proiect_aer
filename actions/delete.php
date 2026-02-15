<?php
// actions/delete.php

// 1. Securitate: Verificăm rolul (Separarea rolurilor)
// is_admin() este definită în functions.php și verifică sesiunea
if (!is_admin()) {
    die("Acces refuzat! Doar administratorii pot efectua această acțiune.");
}

// 2. Securitate: Verificăm originea cererii și metoda POST
// check_request_origin() verifică HTTP_REFERER pentru a preveni Request Spoofing
check_request_origin();

// 3. Securitate: Validăm Token-ul CSRF (Cerința: Protecție XRSF)
if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    die("Eroare de securitate: Token CSRF invalid sau expirat.");
}

// 4. Procesăm ștergerea folosind mecanismul generic
$id = isset($_POST['id']) ? (int) $_POST['id'] : null;

if ($id) {
    // Folosim db_query creat în noul functions.php pentru consistență
    $sql = "DELETE FROM measurements WHERE id = ?";
    $result = db_query($pdo, $sql, [$id]);

    if ($result) {
        // Redirect către dashboard cu confirmare
        header("Location: index.php?page=dashboard&msg=deleted");
        exit;
    } else {
        die("Eroare la ștergerea înregistrării din baza de date.");
    }
} else {
    die("Eroare: ID-ul lipsește sau este invalid.");
}