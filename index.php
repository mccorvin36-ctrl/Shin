<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$pageTitle = "Welcome";
$requireAuth = false;
include 'includes/header.php';
?>

<style>
.landing-hero {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.landing-bg-sigil {
    position: absolute;
    width: min(600px, 80vw);
    height: min(600px, 80vw);
    opacity: 0.04;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: slow-spin 30s linear infinite;
    pointer-events: none;
}

@keyframes slow-spin { to { transform: translate(-50%, -50%) rotate(360deg); } }

.landing-sigil-main {
    width: 100px;
    height: 100px;
    margin-bottom: 2rem;
    filter: drop-shadow(0 0 30px rgba(204,0,0,0.7));
    animation: pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%,100% { filter: drop-shadow(0 0 20px rgba(204,0,0,0.5)); }
    50% { filter: drop-shadow(0 0 40px rgba(204,0,0,0.9)) drop-shadow(0 0 60px rgba(204,0,0,0.4)); }
}

.landing-eyebrow {
    font-family: 'Cinzel', serif;
    font-size: 0.7rem;
    letter-spacing: 0.5em;
    color: var(--gold);
    text-transform: uppercase;
    margin-bottom: 1.5rem;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 0.2s forwards;
}

.landing-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: clamp(2.5rem, 7vw, 5rem);
    line-height: 1.1;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 0.4s forwards;
}

.landing-title .accent {
    color: var(--crimson-light);
    text-shadow: 0 0 30px var(--glow-red), 0 0 60px rgba(204,0,0,0.3);
}

.landing-subtitle {
    font-family: 'Cinzel Decorative', serif;
    font-size: clamp(1rem, 2.5vw, 1.6rem);
    color: var(--gold);
    margin-bottom: 1.5rem;
    text-shadow: 0 0 15px var(--glow-gold);
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 0.6s forwards;
}

.landing-desc {
    max-width: 560px;
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.8;
    font-style: italic;
    margin: 0 auto 2.5rem;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 0.8s forwards;
}

.landing-actions {
    display: flex;
    gap: 1.25rem;
    flex-wrap: wrap;
    justify-content: center;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 1s forwards;
}

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.landing-crest {
    margin-top: 3rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 1.2s forwards;
}

.crest-line {
    width: 80px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-gold));
}

.crest-line.right {
    background: linear-gradient(90deg, var(--border-gold), transparent);
}

.crest-text {
    font-family: 'Cinzel', serif;
    font-size: 0.6rem;
    letter-spacing: 0.3em;
    color: var(--text-muted);
    text-transform: uppercase;
    white-space: nowrap;
}

.feature-strip {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 2rem;
    opacity: 0;
    animation: fadeSlideUp 0.8s ease 1.4s forwards;
}

.feature-pill {
    font-family: 'Cinzel', serif;
    font-size: 0.65rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.feature-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    background: var(--crimson);
    border-radius: 50%;
    box-shadow: 0 0 6px var(--glow-red);
}
</style>

<div class="landing-hero">
    <!-- Background sigil -->
    <svg class="landing-bg-sigil" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <polygon points="100,5 118,65 180,65 130,105 150,170 100,130 50,170 70,105 20,65 82,65" fill="none" stroke="#CC0000" stroke-width="1.5"/>
        <circle cx="100" cy="100" r="95" fill="none" stroke="#CC0000" stroke-width="0.5"/>
        <circle cx="100" cy="100" r="70" fill="none" stroke="#c9a84c" stroke-width="0.5"/>
        <polygon points="100,15 115,58 160,58 125,83 138,127 100,102 62,127 75,83 40,58 85,58" fill="none" stroke="#c9a84c" stroke-width="0.5"/>
    </svg>

    <!-- Main sigil -->
    <svg class="landing-sigil-main" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <polygon points="50,3 62,36 97,36 70,57 80,91 50,70 20,91 30,57 3,36 38,36" fill="none" stroke="#CC0000" stroke-width="2"/>
        <polygon points="50,18 58,43 83,43 63,56 70,81 50,68 30,81 37,56 17,43 42,43" fill="#8B0000" opacity="0.7"/>
        <circle cx="50" cy="50" r="12" fill="#CC0000" opacity="0.9"/>
        <circle cx="50" cy="50" r="6" fill="#ff6b6b"/>
    </svg>

    <p class="landing-eyebrow">House Gremory · Data Sanctum</p>

    <h1 class="landing-title">
        Gremory<br><span class="accent">Archives</span>
    </h1>

    <h2 class="landing-subtitle">DummyJSON API Portal</h2>

    <p class="landing-desc">
        Welcome, servant of the Gremory household. This sacred vault holds the forbidden knowledge of products, peerage members, scrolls, and market tomes — accessible only to those bearing the crimson seal.
    </p>

    <div class="landing-actions">
        <a href="login.php" class="btn btn-primary" style="padding: 0.875rem 2.5rem; font-size: 0.8rem;">
            ⬡ Enter the Sanctum
        </a>
        <a href="register.php" class="btn btn-gold" style="padding: 0.875rem 2.5rem; font-size: 0.8rem;">
            ✦ Swear the Oath
        </a>
    </div>

    <div class="landing-crest">
        <div class="crest-line"></div>
        <span class="crest-text">Seal of the 72 Pillars</span>
        <div class="crest-line right"></div>
    </div>

    <div class="feature-strip">
        <span class="feature-pill">Products Vault</span>
        <span class="feature-pill">Peerage Registry</span>
        <span class="feature-pill">Cart Archives</span>
        <span class="feature-pill">Sacred Scrolls</span>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
