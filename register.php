<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isLoggedIn()) { header('Location: dashboard.php'); exit(); }

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($full_name))  $errors[] = "Full name is required.";
    if (empty($email))      $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($username))   $errors[] = "Username is required.";
    elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) $errors[] = "Username: 3-30 chars, letters/numbers/underscores only.";
    if (empty($password))   $errors[] = "Password is required.";
    elseif (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        $db = getDB();

        // Check duplicate
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email or username is already bound to another soul.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $insert = $db->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $full_name, $email, $username, $hashed);
            if ($insert->execute()) {
                $success = "The crimson seal has been placed. You may now enter.";
            } else {
                $errors[] = "A dark power prevented registration. Try again.";
            }
            $insert->close();
        }
        $stmt->close();
        $db->close();
    }
}

$pageTitle = "Swear the Oath";
$requireAuth = false;
include 'includes/header.php';
?>

<style>
.auth-wrapper { padding-top: 2rem; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Corner decorations -->
        <div class="card-corner tl"></div>
        <div class="card-corner tr"></div>
        <div class="card-corner bl"></div>
        <div class="card-corner br"></div>

        <div class="auth-brand">
            <svg class="auth-sigil" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <polygon points="50,3 62,36 97,36 70,57 80,91 50,70 20,91 30,57 3,36 38,36" fill="none" stroke="#CC0000" stroke-width="2.5"/>
                <polygon points="50,18 58,43 83,43 63,56 70,81 50,68 30,81 37,56 17,43 42,43" fill="#8B0000" opacity="0.7"/>
                <circle cx="50" cy="50" r="11" fill="#CC0000" opacity="0.9"/>
            </svg>
            <h1 class="auth-title">Swear the Oath</h1>
            <p class="auth-subtitle">Become a vassal of House Gremory</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <span>⚠</span>
                <div><?php foreach ($errors as $e): echo htmlspecialchars($e) . "<br>"; endforeach; ?></div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <span>✓</span>
                <div>
                    <?php echo htmlspecialchars($success); ?><br>
                    <a href="login.php" style="color: inherit; font-weight: 600;">→ Proceed to the Sanctum</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="register.php" novalidate>
            <div class="form-group">
                <label class="form-label" for="full_name">True Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control"
                    placeholder="Your full name..." value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Magical Sigil (Email)</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="your@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="username">Vassal Name</label>
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="Choose a username..." value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Blood Oath (Password)</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Min. 6 characters..." required>
            </div>
            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Oath</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                    placeholder="Repeat your password..." required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top: 0.5rem;">
                ✦ Bind the Seal
            </button>
        </form>
        <?php endif; ?>

        <p style="text-align:center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted); font-style: italic;">
            Already sworn? <a href="login.php" style="color: var(--crimson-light); text-decoration: none;">Return to the Sanctum →</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
