<?php
/**
 * contact.php 
 */

// Generăm numere noi la FIECARE refresh pentru a forța un nou Captcha
$_SESSION['captcha_a'] = rand(1, 9);
$_SESSION['captcha_b'] = rand(1, 9);

// Preluăm datele persistente în caz de eroare (Cerința: Persistența parametrilor)
$temp_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$page_title = "Contact | Monitorizare Aer";
?>

<main
    style="max-width: 600px; margin: 40px auto; background: #1a1a1a; padding: 30px; border-radius: 12px; border: 1px solid #333;">
    <h2 style="color: #28a745; margin-top: 0;">📧 Contactează-ne</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'sent'): ?>
        <p style="color: #28a745; background: rgba(40,167,69,0.1); padding: 10px; border: 1px solid;">Mesaj trimis cu
            succes!</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: #ff4d4d; background: rgba(220,53,69,0.1); padding: 10px; border: 1px solid;">
            <?= $_GET['error'] === 'captcha' ? 'Captcha incorect!' : 'Eroare la trimitere. Verificați câmpurile.' ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=send_contact" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">

        <label style="color: #aaa; margin-bottom: -10px;">Nume:</label>
        <input type="text" name="nume" value="<?= e($temp_data['nume'] ?? '') ?>"
            style="background: #000; color: white; padding: 10px; border: 1px solid #444; border-radius: 4px;" required>

        <label style="color: #aaa; margin-bottom: -10px;">Email:</label>
        <input type="email" name="email" value="<?= e($temp_data['email'] ?? '') ?>"
            style="background: #000; color: white; padding: 10px; border: 1px solid #444; border-radius: 4px;" required>

        <label style="color: #aaa; margin-bottom: -10px;">Mesaj:</label>
        <textarea name="mesaj"
            style="background: #000; color: white; padding: 10px; border: 1px solid #444; height: 100px; border-radius: 4px; font-family: inherit;"
            required><?= e($temp_data['mesaj'] ?? '') ?></textarea>

        <div style="background: #222; padding: 15px; text-align: center; border: 1px dashed #444; border-radius: 8px;">
            <p style="color: #ffc107; margin-bottom: 10px; font-weight: bold;">🛡️ Anti-Bot: Cât face
                <?= $_SESSION['captcha_a'] ?> + <?= $_SESSION['captcha_b'] ?>?
            </p>
            <input type="number" name="captcha_challenge" required
                style="width: 80px; text-align: center; background: #000; color: #ffc107; border: 1px solid #555; padding: 8px; border-radius: 4px; font-size: 1.1rem;">
        </div>

        <button type="submit"
            style="background: #28a745; color: white; padding: 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: background 0.3s;">
            Trimite Mesaj
        </button>
    </form>
</main>