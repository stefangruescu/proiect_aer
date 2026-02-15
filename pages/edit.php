<?php
/**
 * pages/edit.php
 * Notă: Acest fișier este inclus de index.php după header.php.
 */

// 1. Verificare acces (Engine-ul de securitate)
if (!is_admin()) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// 2. Preluare și validare ID
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location.href='index.php?page=dashboard';</script>";
    exit;
}

// 3. Extragere date actuale (Persistence Layer)
$stmt = db_query($pdo, "SELECT * FROM measurements WHERE id = ?", [$id]);
$m = $stmt->fetch();

if (!$m) {
    die("<div style='color:white; padding:20px;'>Eroare: Înregistrarea nu a fost găsită în baza de date.</div>");
}

// 4. Procesare Formular (Update Logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token($_POST['csrf_token']);

    $pm10 = $_POST['pm10'];
    $data = [
        'station_code' => e($_POST['station_code']),
        'city' => e($_POST['city']),
        'pm10' => (float) $pm10,
        'status' => calculate_air_quality($pm10) // Recalculare automată a indicatorului
    ];

    if (db_update($pdo, 'measurements', $data, $id)) {
        // Redirecționare client-side pentru a evita eroarea "Headers already sent"
        echo "<script>window.location.href='index.php?page=dashboard&updated=1';</script>";
        exit;
    }
}
?>

<main
    style="max-width: 600px; margin: 40px auto; background: #1a1a1a; padding: 30px; border-radius: 12px; border: 1px solid #333; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
    <h2
        style="color: #ffc107; border-left: 4px solid #ffc107; padding-left: 15px; margin-bottom: 25px; font-size: 1.5rem;">
        Editează Măsurătoarea #<?= e($id) ?>
    </h2>

    <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="color: #888; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Cod
                Stație</label>
            <input type="text" name="station_code" value="<?= e($m['station_code']) ?>" required
                style="padding: 12px; background: #000; color: white; border: 1px solid #444; border-radius: 5px; outline: none;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="color: #888; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Oraș</label>
            <input type="text" name="city" value="<?= e($m['city']) ?>" required
                style="padding: 12px; background: #000; color: white; border: 1px solid #444; border-radius: 5px; outline: none;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="color: #888; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Valoare PM10
                (µg/m³)</label>
            <input type="number" step="0.1" name="pm10" value="<?= e($m['pm10']) ?>" required
                style="padding: 12px; background: #000; color: white; border: 1px solid #444; border-radius: 5px; outline: none;">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 10px;">
            <button type="submit"
                style="flex: 2; background: #ffc107; color: black; padding: 14px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                💾 Salvează Modificările
            </button>

            <a href="index.php?page=dashboard"
                style="flex: 1; display: flex; align-items: center; justify-content: center; background: #333; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                Anulează
            </a>
        </div>
    </form>
</main>