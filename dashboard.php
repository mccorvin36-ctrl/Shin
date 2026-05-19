<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Dashboard";
$activePage = "dashboard";
$requireAuth = true;
include 'includes/header.php';

$username  = htmlspecialchars($_SESSION['username']);
$full_name = htmlspecialchars($_SESSION['full_name']);
?>

<style>
.welcome-banner {
    background: linear-gradient(135deg, var(--dark-3), var(--dark-4));
    border: 1px solid var(--border);
    border-left: 3px solid var(--crimson);
    border-radius: 4px;
    padding: 1.75rem 2rem;
    margin-bottom: 2.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    overflow: hidden;
}

.welcome-banner::after {
    content: '⬡';
    position: absolute;
    right: 2rem;
    font-size: 6rem;
    color: rgba(139,0,0,0.06);
    line-height: 1;
    pointer-events: none;
}

.welcome-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--crimson), #4a0000);
    border: 2px solid var(--crimson);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 0 20px rgba(204,0,0,0.3);
}

.welcome-text-eyebrow {
    font-family: 'Cinzel', serif;
    font-size: 0.65rem;
    letter-spacing: 0.3em;
    color: var(--gold);
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}

.welcome-text-name {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1.4rem;
    color: var(--text-primary);
}

.welcome-text-name span { color: var(--crimson-light); }

.welcome-text-sub {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-style: italic;
    margin-top: 0.25rem;
}

.section-heading {
    font-family: 'Cinzel', serif;
    font-size: 0.7rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-heading::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border), transparent);
}
</style>

<div class="page-hero">
    <p class="page-hero-eyebrow">Command Center</p>
    <h1 class="page-hero-title">The <span>Gremory</span> Dashboard</h1>
    <div class="ornamental-divider"><div class="divider-gem"></div></div>
</div>

<div class="container">

    <div class="welcome-banner">
        <div class="welcome-avatar">🔴</div>
        <div>
            <p class="welcome-text-eyebrow">Vassal Recognized</p>
            <h2 class="welcome-text-name">Welcome, <span><?php echo $username; ?></span></h2>
            <p class="welcome-text-sub">The crimson seal acknowledges you, <?php echo $full_name; ?>. The Archives are open.</p>
        </div>
    </div>

    <p class="section-heading">Navigate the Sanctum</p>

    <div class="stats-grid">
        <a href="products.php" class="stat-card">
            <span class="stat-icon">⚗</span>
            <p class="stat-label">Products Vault</p>
            <p class="stat-desc">Enchanted artifacts and devil tools</p>
        </a>
        <a href="users.php" class="stat-card">
            <span class="stat-icon">⚔</span>
            <p class="stat-label">Peerage Registry</p>
            <p class="stat-desc">All registered servants & their carts</p>
        </a>
        <a href="users.php" class="stat-card">
            <span class="stat-icon">🛒</span>
            <p class="stat-label">Cart Archives</p>
            <p class="stat-desc">Market acquisitions of each vassal</p>
        </a>
        <a href="posts.php" class="stat-card">
            <span class="stat-icon">📜</span>
            <p class="stat-label">Sacred Scrolls</p>
            <p class="stat-desc">Ancient texts and forbidden tomes</p>
        </a>
    </div>

    <p class="section-heading">API Connections</p>

    <div class="grid-2">
        <div class="card">
            <div class="card-corner tl"></div><div class="card-corner br"></div>
            <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:0.75rem;">Active Seals</p>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                <?php
                $endpoints = [
                    ['dummyjson.com/products', 'Products', '⚗'],
                    ['dummyjson.com/users', 'Peerage Members', '⚔'],
                    ['dummyjson.com/carts', 'Market Carts', '🛒'],
                    ['dummyjson.com/posts', 'Sacred Scrolls', '📜'],
                ];
                foreach ($endpoints as $ep): ?>
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid rgba(139,0,0,0.12);">
                    <span style="font-size:1rem;"><?php echo $ep[2]; ?></span>
                    <div style="flex:1;">
                        <p style="font-size:0.85rem;color:var(--text-primary);"><?php echo $ep[1]; ?></p>
                        <p style="font-size:0.75rem;color:var(--text-muted);font-family:monospace;">https://<?php echo $ep[0]; ?></p>
                    </div>
                    <span style="width:8px;height:8px;border-radius:50%;background:#4a8b2a;box-shadow:0 0 6px rgba(74,139,42,0.6);flex-shrink:0;"></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-corner tl"></div><div class="card-corner br"></div>
            <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:0.75rem;">System Status</p>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid rgba(139,0,0,0.12);">
                    <span style="font-size:0.9rem;color:var(--text-secondary);">Session Status</span>
                    <span class="badge badge-crimson">Active</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid rgba(139,0,0,0.12);">
                    <span style="font-size:0.9rem;color:var(--text-secondary);">Database</span>
                    <span class="badge badge-gold">MySQL Connected</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid rgba(139,0,0,0.12);">
                    <span style="font-size:0.9rem;color:var(--text-secondary);">Security</span>
                    <span class="badge badge-crimson">Bcrypt + PDO</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;">
                    <span style="font-size:0.9rem;color:var(--text-secondary);">Vassal</span>
                    <span style="font-size:0.9rem;color:var(--crimson-light);font-family:'Cinzel',serif;"><?php echo $username; ?></span>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
