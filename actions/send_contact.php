<?php
/**
 * actions/send_contact.php
 * Procesarea formularului de contact (Cerința: Modul Email & Securitate)
 */

// 1. Verificăm securitatea (CSRF și Origine)
check_request_origin();
validate_csrf_token($_POST['csrf_token'] ?? '');

// 2. Validare Captcha dinamic
$user_answer = (int) ($_POST['captcha_challenge'] ?? 0);
$correct_answer = ($_SESSION['captcha_a'] ?? 0) + ($_SESSION['captcha_b'] ?? 0);

if ($user_answer !== $correct_answer) {
    // Dacă greșește, salvăm datele în sesiune pentru persistență și ne întoarcem cu eroare
    $_SESSION['form_data'] = $_POST;
    header("Location: index.php?page=contact&error=captcha");
    exit;
}

// 3. Preluare și curățare date
$nume = e($_POST['nume']);
$email = e($_POST['email']);
$mesaj = e($_POST['mesaj']);

if (empty($nume) || empty($email) || empty($mesaj)) {
    $_SESSION['form_data'] = $_POST;
    header("Location: index.php?page=contact&error=empty");
    exit;
}

// 4. Trimitere Email (Simulare prin log)
$to = "admin@calitate-aer.ro";
$subject = "Mesaj nou de la: " . $nume;

if (send_system_email($to, $subject, $mesaj, $email)) {
    // Curățăm datele temporare și captcha după succes
    unset($_SESSION['captcha_a'], $_SESSION['captcha_b'], $_SESSION['form_data']);
    header("Location: index.php?page=contact&status=sent");
} else {
    header("Location: index.php?page=contact&error=fail");
}
exit;