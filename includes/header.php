<?php
// NOTĂ: Nu mai punem require_once 'functions.php' aici, 
// deoarece index.php îl încarcă deja ca punct central.

// Preluăm datele meteo de la API-ul extern (Cerința: Integrare informație externă)
$weather = get_external_weather();
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= isset($page_title) ? $page_title : "Monitorizare Calitate Aer România" ?> | Date Timp Real
    </title>
    <meta name="description"
        content="Platformă online pentru verificarea indicilor de poluare în orașele din România. Date live parate prin API.">
    <meta name="author" content="Cristian Gruescu">

    <meta property="og:title" content="Monitorizare Aer România">
    <meta property="og:description" content="Date live despre calitatea aerului.">
    <meta property="og:image" content="assets/img/logo.png">

    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/1163/1163624.png">
</head>

<body>

    <div class="container">
        <header
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 10px 0;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="https://cdn-icons-png.flaticon.com/512/1163/1163624.png" alt="Logo" style="width: 50px;">
                <h1 style="color: white; font-size: 1.6rem; margin: 0; letter-spacing: -0.5px;">Sistem Aer România</h1>
            </div>

            <?php if ($weather): ?>
                <div class="weather-card"
                    style="background: rgba(255,255,255,0.05); padding: 8px 15px; border-radius: 12px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
                    <div style="text-align: right;">
                        <div style="color: #ffc107; font-weight: bold; font-size: 1.1rem; line-height: 1;">
                            ☀️
                            <?= $weather['temp'] ?>°C
                        </div>
                        <div style="color: #888; font-size: 0.7rem; text-transform: uppercase; margin-top: 3px;">
                            București (Live API)
                        </div>
                    </div>
                    <div style="font-size: 1.2rem; border-left: 1px solid #444; padding-left: 10px; opacity: 0.8;">☁️</div>
                </div>
            <?php endif; ?>
        </header>

        <section class="hero-banner"
            style="height: 150px; border-radius: 8px; margin-bottom: 25px; background: #2c3e50; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://picsum.photos/id/116/1200/400'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; border: 1px solid #444;">
            <p
                style="color: white; font-size: 1.5rem; font-weight: bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.8); text-align: center; padding: 0 10px; margin: 0;">
                Aerul pe care îl respiri contează.
            </p>
        </section>

        <nav aria-label="Navigație principală"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">
            <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
                <a href="index.php?page=dashboard"
                    style="color: white; text-decoration: none; font-weight: bold; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s;"
                    onmouseover="this.style.color='#28a745'" onmouseout="this.style.color='white'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="index.php?page=about"
                    style="color: white; text-decoration: none; font-weight: bold; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s;"
                    onmouseover="this.style.color='#17a2b8'" onmouseout="this.style.color='white'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    <span>Documentație</span>
                </a>
            </div>

            <?php if (isset($_SESSION['username'])): ?>
                <div style="text-align: right; display: flex; align-items: center; gap: 15px;">
                    <div>
                        <span style="color: #ccc; font-size: 0.9rem;">Salut, <b style="color: white;">
                                <?= e($_SESSION['username']) ?>
                            </b>!</span>
                        <span class="role-badge"
                            style="background: #28a745; color: white; padding: 2px 8px; font-size: 10px; border-radius: 4px; margin-left: 5px; text-transform: uppercase; font-weight: bold;">
                            <?= e($_SESSION['role']) ?>
                        </span>
                    </div>
                    <a href="index.php?page=logout"
                        style="color: #ff4d4d; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #ff4d4d; padding: 4px 10px; border-radius: 4px;"
                        title="Ieșire din cont">Logout</a>
                </div>
            <?php endif; ?>
        </nav>