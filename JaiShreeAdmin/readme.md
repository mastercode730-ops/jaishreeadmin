# Jai Shree Admin & User Web Application

This project is built using the **CodeIgniter 3 (PHP)** framework.

## Prerequisites

- **PHP 7.4+ or PHP 8.x** installed and added to system PATH.
- **MySQL / MariaDB** database server running (via XAMPP, WAMP, or standalone MySQL).
- Database named `jaishreematka` created/imported into MySQL.

## Database Configuration

The database settings are located in [`application/config/database.php`](file:///c:/Users/Lenovo/Downloads/FullCode/JaiShreeAdmin/application/config/database.php):
- **Hostname:** `localhost`
- **Username:** `root`
- **Password:** `(empty)`
- **Database:** `jaishreematka`

---

## How to Run the Application

### Option 1: Using Built-in PHP Development Server (Recommended)

1. Open PowerShell or Command Prompt in the project directory:
   ```powershell
   cd c:\Users\Lenovo\Downloads\FullCode\JaiShreeAdmin
   ```

2. Run the PHP server using the router script:
   ```powershell
   php -S localhost:8000 router.php
   ```

3. Open your browser and navigate to:
   - **Admin Panel:** [http://localhost:8000/admin](http://localhost:8000/admin) (or [http://localhost:8000/](http://localhost:8000/))
   - **User / Web Services API:** [http://localhost:8000/webservices](http://localhost:8000/webservices)

---

### Option 2: Using XAMPP / WAMP Web Server

1. Copy or move the `JaiShreeAdmin` project folder into your web server root (e.g., `C:\xampp\htdocs\JaiShreeAdmin`).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Access the application in your browser:
   - **Admin Panel:** [http://localhost/JaiShreeAdmin/admin](http://localhost/JaiShreeAdmin/admin)
   - **User / Web Services API:** [http://localhost/JaiShreeAdmin/webservices](http://localhost/JaiShreeAdmin/webservices)

---

## Key Controllers

- **Admin Panel:** [`application/controllers/Admin.php`](file:///c:/Users/Lenovo/Downloads/FullCode/JaiShreeAdmin/application/controllers/Admin.php)
- **User / Web Services API:** [`application/controllers/Webservices.php`](file:///c:/Users/Lenovo/Downloads/FullCode/JaiShreeAdmin/application/controllers/Webservices.php)
- **AJAX Requests:** [`application/controllers/Ajax.php`](file:///c:/Users/Lenovo/Downloads/FullCode/JaiShreeAdmin/application/controllers/Ajax.php)
