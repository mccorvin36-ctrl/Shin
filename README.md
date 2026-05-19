# 🔴 Gremory Archives — DummyJSON API Web App
### A High School DxD Themed Integrative Programming Project

---

## 📁 Project Structure

```
dxd-app/
├── index.php           ← Landing page (public)
├── register.php        ← Registration page
├── login.php           ← Login page
├── dashboard.php       ← Dashboard (protected)
├── products.php        ← Products from API (protected)
├── users.php           ← Users + Cart integration (protected)
├── posts.php           ← Posts from API (protected)
├── logout.php          ← Session destroy + redirect
├── setup.sql           ← Database setup script
├── includes/
│   ├── db.php          ← DB constants & getDB()
│   ├── auth.php        ← requireLogin(), isLoggedIn(), fetchAPI()
│   ├── header.php      ← Navigation + HTML head
│   └── footer.php      ← Footer + closing HTML
└── assets/
    └── css/
        └── style.css   ← Full themed stylesheet
```

---

## ⚙️ Setup Instructions

### 1. Requirements
- PHP 7.4+ with cURL enabled
- MySQL 5.7+ or MariaDB
- Apache/Nginx (XAMPP, WAMP, MAMP, or Laragon recommended)

### 2. Place Files
Copy the entire `dxd-app/` folder into your web server root:
- XAMPP: `C:/xampp/htdocs/dxd-app/`
- WAMP: `C:/wamp64/www/dxd-app/`
- Linux: `/var/www/html/dxd-app/`

### 3. Set Up Database
Open **phpMyAdmin** or your MySQL client and run the contents of `setup.sql`:

```sql
CREATE DATABASE IF NOT EXISTS dxd_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dxd_app;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Configure Database (if needed)
Edit `includes/db.php` with your credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // ← your MySQL username
define('DB_PASS', '');          // ← your MySQL password
define('DB_NAME', 'dxd_app');
```

### 5. Open in Browser
```
http://localhost/dxd-app/
```

---

## 🔐 Security Features
- Passwords hashed with `password_hash()` (bcrypt)
- Verified with `password_verify()`
- All DB queries use **prepared statements** via MySQLi
- PHP sessions protect all post-login pages
- Input validation & sanitization with `htmlspecialchars()`
- Email format validated with `filter_var()`

---

## 🌐 API Endpoints Used
| Page | Endpoint |
|------|----------|
| Products | `https://dummyjson.com/products` |
| Users | `https://dummyjson.com/users` |
| Carts | `https://dummyjson.com/carts` |
| Posts | `https://dummyjson.com/posts` |

---

## 🎨 Theme
**House Gremory** from *High School DxD* — crimson & gold devil aesthetic with:
- Cinzel Decorative + EB Garamond typography
- Deep crimson/dark color palette with gold accents
- Pentagram sigil motifs
- Devil house terminology (Vassal, Sanctum, Blood Oath, etc.)
