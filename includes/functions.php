<?php
// functions.php - MOTORUL APLICATIEI (Logic Layer)
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 1. MECANISME GENERICE BD (Cerința: Mecanism generic de inserare/obținere)
 */

// Funcție generică pentru orice interogare SQL
function db_query($pdo, $sql, $params = [])
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Mecanism generic de inserare MODIFICAT pentru a include automat timestamp-ul
function db_insert($pdo, $table, $data)
{
    if (!isset($data['recorded_at'])) {
        $data['recorded_at'] = date('Y-m-d H:i:s');
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    return db_query($pdo, $sql, array_values($data));
}

// Mecanism generic de update
function db_update($pdo, $table, $data, $id)
{
    $setPart = [];
    foreach (array_keys($data) as $key) {
        $setPart[] = "$key = ?";
    }
    $sql = "UPDATE $table SET " . implode(', ', $setPart) . " WHERE id = ?";

    $params = array_values($data);
    $params[] = $id; // Adăugăm ID-ul pentru clauza WHERE

    return db_query($pdo, $sql, $params);
}

/**
 * 2. LOGICĂ DE BUSINESS (Cerința: Procese specifice aplicației)
 */

// Calculează automat statusul în funcție de PM10 (fără input manual de la utilizator)
function calculate_air_quality($pm10)
{
    if ($pm10 <= 20)
        return 'Excelent';
    if ($pm10 <= 40)
        return 'Bun';
    if ($pm10 <= 50)
        return 'Moderat';
    if ($pm10 <= 100)
        return 'Poluat';
    return 'Critic';
}

function get_filtered_measurements($pdo, $city = null)
{
    $sql = "SELECT * FROM measurements";
    $params = [];
    if ($city) {
        $sql .= " WHERE city = ?";
        $params[] = $city;
    }
    $sql .= " ORDER BY recorded_at DESC";
    return db_query($pdo, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 3. AUTENTIFICARE ȘI ROLURI (Cerința: Separarea rolurilor)
 */

function login($pdo, $username, $password)
{
    // Folosim db_query-ul tău existent
    $stmt = db_query($pdo, "SELECT * FROM users WHERE username = ?", [$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // <--- Verifică să fie 'role'
        return true;
    }
    return false;
}

function is_admin()
{
    // Verificăm aceeași cheie: 'role'
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * 4. SECURITATE (Cerința: XSS, CSRF, Request Spoofing)
 */

/**
 * Anti-XSS: Versiune sigură care acceptă și valori null
 */
function e($value)
{
    // Dacă valoarea e null, returnăm un șir vid, altfel aplicăm htmlspecialchars
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Anti-CSRF
function get_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("Eroare de securitate: CSRF Token invalid.");
    }
    return true;
}

// Anti-Request Spoofing (Verifică dacă POST-ul vine din interiorul site-ului)
function check_request_origin()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
            die("Eroare: Originea cererii neautorizată.");
        }
    }
}

/**
 * 5. AFIȘARE GENERICĂ (Cerința: Mecanism generic de parcurgere/afișare)
 */

function get_status_badge($status)
{
    $badges = [
        'excelent' => 'bg-success',
        'bun' => 'bg-info',
        'moderat' => 'bg-warning text-dark',
        'poluat' => 'bg-danger',
        'critic' => 'bg-dark'
    ];
    $class = $badges[strtolower($status)] ?? 'bg-secondary';
    return "<span class='badge $class'>" . e($status) . "</span>";
}

function render_measurement_row($row, $is_admin)
{
    $city = e($row['city']);
    $pm10 = e($row['pm10']);
    $status = get_status_badge($row['status']);
    $date = date('d.m.Y H:i', strtotime($row['recorded_at']));
    $csrf = get_csrf_token();

    $html = "<tr>
                <td>$city</td>
                <td style='font-weight: bold; color: white;'>$pm10</td>
                <td>$status</td>
                <td style='color: #888;'>$date</td>";

    if ($is_admin) {
        // Stil comun aplicat ambelor elemente pentru consistență vizuală
        $common_style = "display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 32px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; border: none; cursor: pointer; text-decoration: none; box-sizing: border-box; transition: opacity 0.2s;";

        $html .= "<td style='text-align: center; white-space: nowrap;'>
        <div style='display: flex; gap: 8px; justify-content: center; align-items: center;'>
            
            <a href='index.php?page=edit&id={$row['id']}' 
               style='{$common_style} background-color: #ffc107; color: #000;'>
               Editează
            </a>
            
            <form method='POST' action='index.php?page=delete' style='margin: 0; display: inline;' onsubmit='return confirm(\"Sigur doriți să ștergeți această înregistrare?\")'>
                <input type='hidden' name='id' value='{$row['id']}'>
                <input type='hidden' name='csrf_token' value='$csrf'>
                <button type='submit' 
                        style='{$common_style} background-color: #ff4d4d; color: white;'>
                    Șterge
                </button>
            </form>
            
        </div>
    </td>";
    }
    $html .= "</tr>";
    return $html;
}

/**
 * 6. INTEGRARE MODULE (Email & API Extern)
 */

function send_system_email($to, $subject, $message, $from = 'no-reply@aer-romania.ro')
{
    $log_path = __DIR__ . '/../data/mail_log.txt';
    $log_entry = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJ: $subject | MSG: $message\n";

    // Cerința: Simularea funcționalității de email pe localhost
    if (!is_dir(__DIR__ . '/../data'))
        mkdir(__DIR__ . '/../data');
    file_put_contents($log_path, $log_entry, FILE_APPEND);

    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: <$from>";
    return @mail($to, $subject, "<html><body><p>$message</p></body></html>", $headers);
}

function get_external_weather()
{
    $url = "https://api.open-meteo.com/v1/forecast?latitude=44.43&longitude=26.10&current_weather=true";

    // Folosim un timeout scurt pentru a nu bloca pagina dacă API-ul e jos
    $context = stream_context_create(['http' => ['timeout' => 2]]);
    $response = @file_get_contents($url, false, $context);

    if (!$response)
        return null;

    $data = json_decode($response, true);

    // Verificăm dacă structura răspunsului este cea așteptată
    if (isset($data['current_weather']['temperature'])) {
        return [
            'temp' => $data['current_weather']['temperature'],
            'wind' => $data['current_weather']['windspeed'] ?? 0,
            'time' => $data['current_weather']['time'] ?? ''
        ];
    }

    return null;
}