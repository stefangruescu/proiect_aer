<?php
// pages/about.php
$page_title = "Documentație și Arhitectură Sistem | Detalii Implementare";
?>

<main style="color: #eee; line-height: 1.6; max-width: 1100px; margin: 0 auto; padding: 20px; font-family: sans-serif;">

    <section style="margin-bottom: 40px; text-align: center;">
        <h2 style="color: #28a745; font-size: 2.2rem; margin-bottom: 10px;">Documentație Tehnică Detaliată</h2>
        <hr style="border: 0; border-top: 1px solid #333; margin: 20px auto; width: 60%;">
    </section>

    <section
        style="margin-bottom: 40px; background: #1a1a1a; padding: 25px; border-radius: 12px; border: 1px solid #333; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
        <h3
            style="color: #28a745; font-size: 1.3rem; margin-bottom: 25px; border-bottom: 1px solid #333; padding-bottom: 10px;">
            1. Arhitectura și Fluxul de Date
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; color: #ccc; margin-bottom: 30px;">
            <div>
                <h4 style="color: #17a2b8; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 10px;">Obiectiv
                    Sistem</h4>
                <p style="font-size: 0.95rem; line-height: 1.5;">
                    Platformă de monitorizare integrată pentru colectarea și procesarea indicatorilor de mediu (PM10,
                    PM2.5).
                    Sistemul transformă input-ul brut în indici de calitate prin algoritmi de validare server-side.
                </p>
            </div>
            <div>
                <h4 style="color: #17a2b8; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 10px;">Stack
                    Tehnologic</h4>
                <ul style="font-size: 0.9rem; list-style: none; padding: 0;">
                    <li><strong style="color: #eee;">Backend:</strong> PHP 8.x (Logic Layer decuplat)</li>
                    <li><strong style="color: #eee;">Database:</strong> MySQL + PDO (Prepared Statements)</li>
                    <li><strong style="color: #eee;">Security:</strong> CSRF Protection, XSS Filtering, Anti-Bot</li>
                </ul>
            </div>
        </div>

        <div style="background: #000; padding: 25px; border-radius: 8px; border: 1px solid #222;">
            <h4
                style="color: #ffc107; font-size: 0.8rem; text-align: center; margin-bottom: 20px; text-transform: uppercase;">
                Pipeline Execuție</h4>

            <div style="max-width: 550px; margin: 0 auto; display: flex; flex-direction: column; gap: 8px;">

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="background: #111; border: 1px solid #17a2b8; padding: 8px; border-radius: 4px; width: 120px; text-align: center; color: #17a2b8; font-weight: bold; font-size: 0.8rem;">
                        [1] INPUT</div>
                    <div style="color: #888; font-size: 0.85rem;">Preluare POST + Captcha Challenge</div>
                </div>
                <div style="padding-left: 55px; color: #333; font-size: 0.8rem;">▼</div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="background: #111; border: 1px solid #ff4d4d; padding: 8px; border-radius: 4px; width: 120px; text-align: center; color: #ff4d4d; font-weight: bold; font-size: 0.8rem;">
                        [2] SECURITY</div>
                    <div style="color: #888; font-size: 0.85rem;">Validare Token CSRF + Referer Check</div>
                </div>
                <div style="padding-left: 55px; color: #333; font-size: 0.8rem;">▼</div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="background: #111; border: 1px solid #ffc107; padding: 8px; border-radius: 4px; width: 120px; text-align: center; color: #ffc107; font-weight: bold; font-size: 0.8rem;">
                        [3] LOGIC</div>
                    <div style="color: #888; font-size: 0.85rem;">Sanitizare XSS + Calcul Status Aer</div>
                </div>
                <div style="padding-left: 55px; color: #333; font-size: 0.8rem;">▼</div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="background: #111; border: 1px solid #28a745; padding: 8px; border-radius: 4px; width: 120px; text-align: center; color: #28a745; font-weight: bold; font-size: 0.8rem;">
                        [4] STORAGE</div>
                    <div style="color: #888; font-size: 0.85rem;">PDO Insert + Auto-Timestamp (NOW)</div>
                </div>
                <div style="padding-left: 55px; color: #333; font-size: 0.8rem;">▼</div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="background: #28a745; border: 1px solid #28a745; padding: 8px; border-radius: 4px; width: 120px; text-align: center; color: #fff; font-weight: bold; font-size: 0.8rem;">
                        [5] RENDER</div>
                    <div style="color: #888; font-size: 0.85rem;">Dashboard Update / Export CSV</div>
                </div>

            </div>
        </div>
    </section>
    <section
        style="margin-bottom: 40px; background: #1a1a1a; padding: 25px; border-radius: 8px; border: 1px solid #333;">
        <h3 style="color: #17a2b8;">2. Descrierea Arhitecturii și Soluția de Implementare</h3>
        <p>Proiectul adoptă o arhitectură <strong>Front Controller</strong>, unde <code>index.php</code> dirijează
            execuția. Am implementat o separare clară între logica de business (<code>functions.php</code>) și
            prezentare.</p>



        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <div
                style="background: rgba(40, 167, 69, 0.05); padding: 20px; border-radius: 8px; border-left: 4px solid #28a745;">
                <h4 style="margin-top: 0; color: #28a745;">Entități și Roluri</h4>
                <ul style="font-size: 0.9rem;">
                    <li><strong>Vizitator:</strong> Acces la date publice, filtrare și modul contact.</li>
                    <li><strong>Administrator:</strong> Drepturi depline CRUD cu protecție CSRF și validare manuală.
                    </li>
                    <li><strong>Măsurătoare:</strong> Obiect de date complex cu <strong>calcul automat de
                            status</strong>.</li>
                </ul>
            </div>
            <div
                style="background: rgba(23, 162, 184, 0.05); padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                <h4 style="margin-top: 0; color: #17a2b8;">Procese Principale (Logica Sistem)</h4>
                <ul style="font-size: 0.9rem;">
                    <li><strong>Auto-Timestamp:</strong> Înregistrarea datei (<code>recorded_at</code>) gestionată de
                        funcția generică <code>db_insert</code>.</li>
                    <li><strong>Business Logic:</strong> Funcția <code>calculate_air_quality()</code> determină gradul
                        de poluare fără input de la utilizator.</li>
                    <li><strong>Persistență:</strong> Managementul datelor din formulare prin sesiuni în caz de eroare.
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section
        style="margin-bottom: 40px; background: #1a1a1a; padding: 25px; border-radius: 8px; border: 1px solid #333;">
        <h3 style="color: #17a2b8; margin-top: 0;">3. Implementare Bază de Date și Reutilizare Cod</h3>
        <p>Interacțiunea cu BD se face prin <strong>PDO</strong>, folosind un <strong>mecanism generic de
                inserare</strong> care automatizează procesele repetitive.</p>



        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; margin-top: 15px; background: #111;">
            <thead>
                <tr style="background: #333; color: #28a745; text-align: left;">
                    <th style="padding: 12px; border: 1px solid #444;">Tabel</th>
                    <th style="padding: 12px; border: 1px solid #444;">Rol în Aplicație</th>
                    <th style="padding: 12px; border: 1px solid #444;">Funcționalitate Automatizată</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 12px; border: 1px solid #444;"><code>users</code></td>
                    <td style="padding: 12px; border: 1px solid #444;">Autentificare și autorizare pe roluri.</td>
                    <td style="padding: 12px; border: 1px solid #444;">Hash-uire parole (<code>password_hash</code>).
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px; border: 1px solid #444;"><code>measurements</code></td>
                    <td style="padding: 12px; border: 1px solid #444;">Stocare indici PM2.5, PM10 și climă.</td>
                    <td style="padding: 12px; border: 1px solid #444;">Generare automată <code>recorded_at</code> și
                        <code>status</code>.
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="font-size: 0.85rem; font-style: italic; margin-top: 10px; color: #888;">
            * S-a utilizat <code>PDO::prepare</code> pentru a garanta protecția împotriva SQL Injection în toate
            interogările de scriere și citire.
        </p>
    </section>

    <section
        style="margin-bottom: 40px; background: #1a1a1a; padding: 25px; border-radius: 8px; border: 1px solid #333;">
        <h3 style="color: #17a2b8;">4. Securitatea și Protecția Datelor</h3>
        <p>Aplicația utilizează mai multe straturi de protecție pentru a preveni vulnerabilitățile comune și accesul
            neautorizat:</p>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px;">
            <div style="background: #222; padding: 15px; border-radius: 6px; border: 1px solid #444;">
                <strong style="color: #ffc107;">Prevenire SQL Injection:</strong><br>
                <small>Toate interacțiunile cu baza de date folosesc <code>Prepared Statements</code> (PDO), eliminând
                    riscul injectării de cod malițios în interogări.</small>
            </div>
            <div style="background: #222; padding: 15px; border-radius: 6px; border: 1px solid #444;">
                <strong style="color: #ffc107;">Protecție XSS și CSRF:</strong><br>
                <small>Datele afișate sunt sanitizate prin funcția <code>e()</code>, iar formularele sunt protejate prin
                    <code>CSRF Tokens</code> pentru a valida legitimitatea cererilor POST.</small>
            </div>
            <div style="background: #222; padding: 15px; border-radius: 6px; border: 1px solid #444;">
                <strong style="color: #ffc107;">Validarea Originii:</strong><br>
                <small>Sistemul verifică proveniența cererilor (Request Origin) pentru a bloca execuția acțiunilor
                    critice din surse externe neautorizate.</small>
            </div>
            <div style="background: #222; padding: 15px; border-radius: 6px; border: 1px solid #444;">
                <strong style="color: #ffc107;">Mecanism Anti-Bot:</strong><br>
                <small>Integrarea unui Captcha matematic dinamic care se regenerează la fiecare accesare a paginii,
                    prevenind trimiterea automată de spam.</small>
            </div>
        </div>
    </section>

    <section
        style="margin-bottom: 40px; background: rgba(23, 162, 184, 0.1); padding: 25px; border-radius: 8px; border: 1px solid #17a2b8;">
        <h3 style="color: #17a2b8; margin-top: 0;">5. Module Avansate și Integrare Externă</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 12px;">🌍 <strong>Open-Meteo API:</strong> Integrare date meteo live prin parsare
                JSON server-side, oferind context climatic măsurătorilor de aer.</li>
            <li style="margin-bottom: 12px;">📧 <strong>Sistem Notificări:</strong> Modul de contact cu simulare email
                și jurnalizare securizată (<code>mail_log.txt</code>).</li>
            <li style="margin-bottom: 12px;">📊 <strong>Export CSV:</strong> Funcționalitate de extragere a datelor
                pentru analiză externă, respectând structura tabelară a bazei de date.</li>
        </ul>
    </section>

    <footer style="text-align: center; color: #555; font-size: 0.8rem; margin-top: 50px;">
        <p>Proiect realizat pentru disciplina DAW</p>
    </footer>

</main>