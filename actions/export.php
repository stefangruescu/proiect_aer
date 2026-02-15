<?php
// actions/export.php

// 1. Securitate: Verificăm dacă user-ul este logat (folosind sesiunea pornită deja în index)
if (!isset($_SESSION['user_id'])) {
    die("Acces neautorizat. Vă rugăm să vă autentificați.");
}

// 2. Curățăm orice output buffer pentru a preveni coruperea fișierului CSV
// (Elimină orice HTML sau spații goale trimise accidental de alte fișiere)
if (ob_get_length())
    ob_end_clean();

// 3. Preluăm datele folosind variabila $pdo definită global în config/db.php
$city = $_GET['city'] ?? null;
$data = get_filtered_measurements($pdo, $city);

// 4. Setăm headerele pentru descărcare
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=raport_calitate_aer_' . date('Y-m-d') . '.csv');
header('Pragma: no-cache');
header('Expires: 0');

// 5. Deschidem fluxul de ieșire
$output = fopen('php://output', 'w');

// 6. Adăugăm BOM pentru Excel (diacritice UTF-8)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// 7. Scriem capul de tabel
fputcsv($output, ['ID', 'Statie', 'Oras', 'Judet', 'PM2.5', 'PM10', 'Temp', 'Umiditate', 'Status', 'Data']);

// 8. Scriem rândurile
if (!empty($data)) {
    foreach ($data as $row) {
        fputcsv($output, [
            $row['id'],
            $row['station_code'],
            $row['city'],
            $row['county'],
            $row['pm25'],
            $row['pm10'],
            $row['temp'],
            $row['humidity'],
            $row['status'],
            $row['recorded_at']
        ]);
    }
}

// 9. oprim execuția
fclose($output);
exit;