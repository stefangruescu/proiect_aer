<?php
require_once 'includes/functions.php';

// 1. Securitate: Verificăm dacă user-ul este logat și este admin
if (!isset($_SESSION['username']) || !is_admin()) {
    header("Location: index.php");
    exit;
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 2. Securitate: Validare Token CSRF și Origine
        validate_csrf_token($_POST['csrf_token'] ?? '');
        check_request_origin();

        // Calculăm automat statusul pe baza PM10 introdus
        $computed_status = calculate_air_quality($_POST['pm10']);

        // Colectăm datele (db_insert se ocupă de restul)
        $data = [
            'station_code' => e($_POST['station_code']),
            'city' => e($_POST['city']),
            'county' => e($_POST['county']),
            'pm25' => (float) $_POST['pm25'],
            'pm10' => (float) $_POST['pm10'],
            'temp' => (float) $_POST['temp'],
            'humidity' => (int) $_POST['humidity'],
            'status' => $computed_status // Status calculat automat
        ];

        // 3. Folosim mecanismul generic db_insert (care pune și recorded_at)
        if (db_insert($pdo, 'measurements', $data)) {
            $message = "Măsurătoare adăugată cu succes! Calitate aer: <strong>$computed_status</strong>";
            $message_type = "success";
        } else {
            $message = "Eroare tehnică la salvarea în baza de date.";
            $message_type = "error";
        }
    } catch (Exception $e) {
        $message = "Eroare: " . $e->getMessage();
        $message_type = "error";
    }
}

$page_title = "Adaugă Date";
?>

<main
    style="max-width: 600px; margin: 0 auto; background: #1a1a1a; padding: 30px; border-radius: 8px; border: 1px solid #333;">
    <h2 style="color: white; margin-top: 0; margin-bottom: 20px; border-left: 4px solid #28a745; padding-left: 15px;">
        Adăugare Măsurătoare Nouă
    </h2>

    <?php if ($message): ?>
        <div
            style="padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid; 
            <?= $message_type === 'success' ? 'background: rgba(40,167,69,0.1); color: #28a745; border-color: #28a745;' : 'background: rgba(220,53,69,0.1); color: #ff4d4d; border-color: #ff4d4d;' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="color: #888; font-size: 0.9rem;">Cod Stație</label>
                <input type="text" name="station_code" placeholder="ex: RO001" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
            <div>
                <label style="color: #888; font-size: 0.9rem;">Oraș</label>
                <input type="text" name="city" placeholder="ex: București" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
        </div>

        <div>
            <label style="color: #888; font-size: 0.9rem;">Județ</label>
            <input type="text" name="county" placeholder="ex: Ilfov" required
                style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="color: #888; font-size: 0.9rem;">PM 2.5 (µg/m³)</label>
                <input type="number" step="0.1" name="pm25" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
            <div>
                <label style="color: #888; font-size: 0.9rem;">PM 10 (µg/m³)</label>
                <input type="number" step="0.1" name="pm10" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="color: #888; font-size: 0.9rem;">Temp (°C)</label>
                <input type="number" step="0.1" name="temp" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
            <div>
                <label style="color: #888; font-size: 0.9rem;">Umiditate (%)</label>
                <input type="number" name="humidity" required
                    style="width: 100%; padding: 10px; background: #000; border: 1px solid #333; color: white; border-radius: 4px;">
            </div>
        </div>

        <p style="color: #666; font-size: 0.8rem; font-style: italic; margin: 0;">
            * Calitatea aerului va fi calculată automat pe baza valorilor introduse.
        </p>

        <button type="submit"
            style="background: #28a745; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; margin-top: 10px;">
            Salvează
        </button>
    </form>
</main>