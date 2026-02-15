#  Sistem de Monitorizare a Calității Aerului (v1.0)

Acesta este un proiect web complet pentru monitorizarea și gestionarea indicilor de calitate a aerului, dezvoltat în PHP cu o arhitectură de tip **Front Controller**. Sistemul include autentificare securizată și drepturi de acces diferențiate (RBAC).

## Caracteristici principale

* **Arhitectură Front Controller**: Toate cererile sunt procesate prin `index.php` pentru o securitate sporită și rute curate.
* **Sistem de Autentificare**: Login securizat folosind algoritmul `password_hash()` (BCRYPT).
* **RBAC (Role-Based Access Control)**:
    * **Admin**: Drepturi depline (Vizualizare, Adăugare, Editare, Ștergere date).
    * **User**: Acces Read-Only (Vizualizare dashboard și export date).
* **Interfață Dinamică**: Meniuri și butoane care se adaptează în funcție de rolul utilizatorului.
* **Export Date**: Funcționalitate pentru descărcarea rapoartelor în format CSV.

## Tehnologii Utilizate

* **Backend**: PHP 8.x (PDO pentru interacțiunea cu baza de date).
* **Baza de date**: MySQL / MariaDB.
* **Frontend**: HTML5, CSS3 (Design Minimalist Dark Mode).
* **Server recomandat**: MAMP / XAMPP.

## Structura Proiectului

* `/actions` - Scripturi logice (logout, ștergere, generare hash-uri).
* `/includes` - Fișiere reutilizabile (conexiune DB, header, footer, funcții).
* `/pages` - View-urile aplicației (dashboard, login, add/edit).
* `index.php` - Punctul central de intrare (Routing).
* `database.sql` - Structura bazei de date.

## Instalare și Configurare

1. **Baza de date**:
   - Creați o bază de date numită `proiect_aer`.
   - Importați fișierul `database.sql`.

2. **Configurare**:
   - Verificați `includes/db.php` pentru setările de conexiune (implicit: `root`/`root` pentru MAMP).

3. **Creare utilizatori**:
   - Accesați în browser: `http://localhost:8888/proiect_aer/index.php?page=setup_users` pentru a genera conturile de test.

## Conturi de Test

| Utilizator | Parolă | Rol |
| :--- | :--- | :--- |
| **admin** | `admin123` | Administrator (Full Access) |
| **user_test** | `test123` | Utilizator (Vizualizare) |

## Arhitectura Sistemului

Aplicația folosește un flux de rutare centralizat pentru a preveni accesul direct la fișierele sensibile:

## 📊 Sursa Datelor

Datele utilizate în acest proiect provin din surse oficiale guvernamentale:
* **Portalul de Date Deschise al Guvernului României ([data.gov.ro](https://data.gov.ro))**.
* Valorile monitorizate (PM10, PM2.5, NO2, SO2) sunt conforme cu rețeaua națională de monitorizare a calității aerului.
* Datele au fost prelucrate și importate în sistem pentru a simula monitorizarea în timp real și pentru a genera rapoarte statistice.
