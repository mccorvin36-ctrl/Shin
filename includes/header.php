<?php
// Accepts: $pageTitle, $activePage, $requireAuth (bool)
if (!isset($pageTitle)) $pageTitle = "Gremory Archives";
if (!isset($activePage)) $activePage = "";
if (!isset($requireAuth)) $requireAuth = false;

if ($requireAuth) {
    requireLogin();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> — Gremory Archives</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔴</text></svg>">
</head>
<body>

<?php if ($requireAuth || isLoggedIn()): ?>
<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">
        <svg class="navbar-sigil" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <polygon points="50,5 61,35 95,35 68,57 79,91 50,70 21,91 32,57 5,35 39,35" fill="none" stroke="#CC0000" stroke-width="2.5"/>
            <polygon points="50,20 57,42 80,42 62,55 69,77 50,64 31,77 38,55 20,42 43,42" fill="#8B0000" opacity="0.6"/>
            <circle cx="50" cy="50" r="10" fill="#CC0000" opacity="0.8"/>
        </svg>
        <div class="navbar-title">
            Gremory Archives
            <span>DxD Data Vault</span>
        </div>
    </a>
    <ul class="navbar-nav">
        <li><a href="dashboard.php" class="nav-link <?php echo $activePage==='dashboard' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="products.php" class="nav-link <?php echo $activePage==='products' ? 'active' : ''; ?>">Products</a></li>
        <li><a href="users.php" class="nav-link <?php echo $activePage==='users' ? 'active' : ''; ?>">Peerage</a></li>
        <li><a href="posts.php" class="nav-link <?php echo $activePage==='posts' ? 'active' : ''; ?>">Scrolls</a></li>
        <li><a href="logout.php" class="nav-link logout">Depart</a></li>
    </ul>
</nav>
<?php endif; ?>

<div class="main-content">
