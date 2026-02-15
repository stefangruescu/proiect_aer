<?php
// setup_users.php
require_once 'includes/functions.php';

try {
    // 1. Curățăm tabelul 
    $pdo->query("DELETE FROM users WHERE username IN ('admin', 'user_test')");

    // 2. Pregătim datele pentru ambele conturi
    $users = [
        [
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin'
        ],
        [
            'username' => 'user_test',
            'password' => 'test123',
            'role' => 'user'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

    echo "<h2>Configurare Utilizatori Proiect</h2>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; background: #f9f9f9;'>
            <tr style='background: #28a745; color: white;'>
                <th>Username</th>
                <th>Parolă Clară</th>
                <th>Rol</th>
                <th>Hash Generat</th>
            </tr>";

    foreach ($users as $u) {
        $hash = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmt->execute([$u['username'], $hash, $u['role']]);

        echo "<tr>
                <td><b>{$u['username']}</b></td>
                <td><code>{$u['password']}</code></td>
                <td><i>{$u['role']}</i></td>
                <td style='font-size: 0.8rem;'><code>{$hash}</code></td>
              </tr>";
    }
    echo "</table>";

    echo "<p style='color: green;'>✅ Ambele conturi au fost create cu succes!</p>";
    echo "<p>Mergi la <a href='index.php?page=login'>Pagina de Login</a> pentru a le testa.</p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Eroare: " . $e->getMessage() . "</p>";
}
?>