<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Sacred Scrolls";
$activePage = "posts";
$requireAuth = true;
include 'includes/header.php';

// Fetch posts from API
$data = fetchAPI('https://dummyjson.com/posts?limit=100');
$posts = $data['posts'] ?? [];
?>

<div class="page-hero">
    <p class="page-hero-eyebrow">Forbidden Knowledge</p>
    <h1 class="page-hero-title"><span>Sacred</span> Scrolls</h1>
    <p class="page-hero-sub">Ancient texts and tomes from the underworld archives</p>
    <div class="ornamental-divider"><div class="divider-gem"></div></div>
</div>

<div class="container">
    <?php if (empty($posts)): ?>
        <div style="text-align:center;padding:4rem 2rem;">
            <p style="font-family:'Cinzel',serif;color:var(--text-muted);font-size:0.9rem;letter-spacing:0.2em;">THE SCROLLS ARE SEALED — API UNREACHABLE</p>
        </div>
    <?php else: ?>
        <p style="font-family:'Cinzel',serif;font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--text-muted);margin-bottom:1.5rem;">
            <?php echo count($posts); ?> scrolls recovered
        </p>

        <div class="grid-3">
        <?php foreach ($posts as $p): ?>
            <div class="post-card">
                <h3 class="post-title"><?php echo htmlspecialchars($p['title'] ?? ''); ?></h3>
                <p class="post-body"><?php echo htmlspecialchars($p['body'] ?? ''); ?></p>

                <?php if (!empty($p['tags'])): ?>
                <div class="post-tags">
                    <?php foreach ($p['tags'] as $tag): ?>
                        <span class="post-tag">#<?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="post-reactions">
                    <?php if (!empty($p['reactions'])): ?>
                        <?php if (is_array($p['reactions'])): ?>
                            <?php foreach ($p['reactions'] as $type => $count): ?>
                                <span>
                                    <?php echo $type === 'likes' ? '👍' : '👎'; ?>
                                    <?php echo (int)$count; ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span>❤ <?php echo (int)$p['reactions']; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($p['views'])): ?>
                        <span>👁 <?php echo (int)$p['views']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
