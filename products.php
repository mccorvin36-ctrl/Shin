<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Products Vault";
$activePage = "products";
$requireAuth = true;
include 'includes/header.php';

// Fetch products from API
$data = fetchAPI('https://dummyjson.com/products?limit=100');
$products = $data['products'] ?? [];
?>

<div class="page-hero">
    <p class="page-hero-eyebrow">Gremory Acquisitions</p>
    <h1 class="page-hero-title"><span>Products</span> Vault</h1>
    <p class="page-hero-sub">Enchanted artifacts catalogued by the House Gremory</p>
    <div class="ornamental-divider"><div class="divider-gem"></div></div>
</div>

<div class="container">
    <?php if (empty($products)): ?>
        <div style="text-align:center;padding:4rem 2rem;">
            <p style="font-family:'Cinzel',serif;color:var(--text-muted);font-size:0.9rem;letter-spacing:0.2em;">THE VAULT IS SEALED — API UNREACHABLE</p>
        </div>
    <?php else: ?>
        <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--text-muted);margin-bottom:1.5rem;">
            <?php echo count($products); ?> artifacts catalogued
        </p>

        <div class="grid-4">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <div class="product-img-wrap">
                    <?php if (!empty($p['thumbnail'])): ?>
                        <img class="product-img"
                             src="<?php echo htmlspecialchars($p['thumbnail']); ?>"
                             alt="<?php echo htmlspecialchars($p['title']); ?>"
                             loading="lazy"
                             onerror="this.style.display='none';this.parentElement.querySelector('.img-fallback').style.display='flex';">
                    <?php endif; ?>
                    <div class="img-fallback" style="display:<?php echo empty($p['thumbnail']) ? 'flex' : 'none'; ?>;position:absolute;inset:0;align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem;">⚗</div>
                </div>
                <div class="product-body">
                    <p class="product-category"><?php echo htmlspecialchars($p['category'] ?? '—'); ?></p>
                    <h3 class="product-name"><?php echo htmlspecialchars($p['title'] ?? ''); ?></h3>
                    <div class="product-meta">
                        <span class="product-price">$<?php echo number_format((float)($p['price'] ?? 0), 2); ?></span>
                        <span class="product-stock">Stock: <?php echo (int)($p['stock'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
