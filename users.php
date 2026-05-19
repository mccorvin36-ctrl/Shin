<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Peerage Registry";
$activePage = "users";
$requireAuth = true;
include 'includes/header.php';

// Fetch users from API
$usersData = fetchAPI('https://dummyjson.com/users?limit=100');
$users = $usersData['users'] ?? [];

// Fetch all carts once (for modal)
$cartsData = fetchAPI('https://dummyjson.com/carts?limit=200');
$allCarts = $cartsData['carts'] ?? [];

// Encode carts as JSON for JS
$cartsJson = json_encode($allCarts);
?>

<div class="page-hero">
    <p class="page-hero-eyebrow">Bound Servants</p>
    <h1 class="page-hero-title"><span>Peerage</span> Registry</h1>
    <p class="page-hero-sub">All vassals bound by the crimson seal — click View Cart to inspect their holdings</p>
    <div class="ornamental-divider"><div class="divider-gem"></div></div>
</div>

<div class="container">
    <?php if (empty($users)): ?>
        <div style="text-align:center;padding:4rem 2rem;">
            <p style="font-family:'Cinzel',serif;color:var(--text-muted);font-size:0.9rem;letter-spacing:0.2em;">THE REGISTRY IS SEALED — API UNREACHABLE</p>
        </div>
    <?php else: ?>
        <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--text-muted);margin-bottom:1.5rem;">
            <?php echo count($users); ?> vassals registered
        </p>

        <div class="grid-2">
        <?php foreach ($users as $u): ?>
            <div class="user-card">
                <?php if (!empty($u['image'])): ?>
                    <img class="user-avatar"
                         src="<?php echo htmlspecialchars($u['image']); ?>"
                         alt="<?php echo htmlspecialchars($u['firstName']); ?>"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 60 60\'><rect width=\'60\' height=\'60\' rx=\'30\' fill=\'%238B0000\'/><text x=\'50%25\' y=\'54%25\' font-size=\'28\' text-anchor=\'middle\' fill=\'white\'>⚔</text></svg>'">
                <?php endif; ?>
                <div class="user-info">
                    <h3 class="user-name"><?php echo htmlspecialchars(($u['firstName'] ?? '') . ' ' . ($u['lastName'] ?? '')); ?></h3>
                    <p class="user-detail"><strong>Email:</strong> <?php echo htmlspecialchars($u['email'] ?? '—'); ?></p>
                    <p class="user-detail"><strong>Age:</strong> <?php echo htmlspecialchars($u['age'] ?? '—'); ?></p>
                    <p class="user-detail"><strong>Phone:</strong> <?php echo htmlspecialchars($u['phone'] ?? '—'); ?></p>
                    <div style="margin-top:0.75rem;">
                        <button class="btn btn-primary btn-sm"
                                onclick="openCart(<?php echo (int)$u['id']; ?>, '<?php echo htmlspecialchars(addslashes($u['firstName'].' '.$u['lastName'])); ?>')">
                            🛒 View Cart
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Cart Modal -->
<div id="cartOverlay" class="cart-modal-overlay" style="display:none;" onclick="closeCartOnOverlay(event)">
    <div class="cart-modal" id="cartModal">
        <div class="cart-modal-header">
            <div>
                <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:0.25rem;">Market Holdings</p>
                <h3 id="cartModalTitle" style="font-family:'Cinzel Decorative',serif;font-size:1.1rem;color:var(--text-primary);">Loading...</h3>
            </div>
            <button class="cart-close" onclick="closeCart()">✕</button>
        </div>
        <div class="cart-modal-body" id="cartModalBody">
            <div class="loading-wrap">
                <svg class="sigil-spinner" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="50,5 61,35 95,35 68,57 79,91 50,70 21,91 32,57 5,35 39,35" fill="none" stroke="#CC0000" stroke-width="3"/>
                </svg>
                <p style="font-family:'Cinzel',serif;font-size:0.7rem;letter-spacing:0.2em;color:var(--text-muted);">CONSULTING THE ARCHIVES...</p>
            </div>
        </div>
    </div>
</div>

<script>
const allCarts = <?php echo $cartsJson; ?>;

function openCart(userId, userName) {
    document.getElementById('cartOverlay').style.display = 'flex';
    document.getElementById('cartModalTitle').textContent = userName + "'s Holdings";
    document.body.style.overflow = 'hidden';

    // Filter carts for this user
    const userCarts = allCarts.filter(c => c.userId === userId);
    renderCart(userCarts, userName);
}

function renderCart(carts, userName) {
    const body = document.getElementById('cartModalBody');

    if (!carts || carts.length === 0) {
        body.innerHTML = `
            <div style="text-align:center;padding:3rem 1rem;">
                <p style="font-size:3rem;margin-bottom:1rem;">🛒</p>
                <p style="font-family:'Cinzel',serif;font-size:0.8rem;letter-spacing:0.2em;color:var(--text-muted);text-transform:uppercase;">No market holdings found for this vassal</p>
            </div>`;
        return;
    }

    let html = '';

    carts.forEach((cart, idx) => {
        const totalPrice = cart.total || cart.products.reduce((s, p) => s + p.total, 0);
        const totalQty = cart.totalQuantity || cart.products.reduce((s, p) => s + p.quantity, 0);

        html += `
        <div style="margin-bottom:2rem;">
            <div class="cart-summary">
                <div class="cart-stat">
                    <span class="cart-stat-value">${cart.id}</span>
                    <span class="cart-stat-label">Cart ID</span>
                </div>
                <div class="cart-stat">
                    <span class="cart-stat-value">${cart.products.length}</span>
                    <span class="cart-stat-label">Unique Items</span>
                </div>
                <div class="cart-stat">
                    <span class="cart-stat-value" style="color:var(--gold-light);">$${parseFloat(totalPrice).toFixed(2)}</span>
                    <span class="cart-stat-label">Total Value</span>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Artifact</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>`;

        cart.products.forEach(p => {
            html += `
                    <tr>
                        <td style="color:var(--text-primary);">${escHtml(p.title)}</td>
                        <td><span class="badge badge-crimson">${p.quantity}</span></td>
                        <td style="color:var(--text-secondary);">$${parseFloat(p.price).toFixed(2)}</td>
                        <td style="color:var(--gold-light);font-family:'Cinzel',serif;">$${parseFloat(p.total).toFixed(2)}</td>
                    </tr>`;
        });

        html += `
                </tbody>
            </table>
        </div>`;

        if (idx < carts.length - 1) {
            html += `<hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0;">`;
        }
    });

    body.innerHTML = html;
}

function closeCart() {
    document.getElementById('cartOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

function closeCartOnOverlay(e) {
    if (e.target === document.getElementById('cartOverlay')) closeCart();
}

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });
</script>

<?php include 'includes/footer.php'; ?>
