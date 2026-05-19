<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isLoggedIn()) { header('Location: dashboard.php'); exit(); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credential = trim($_POST['credential'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($credential) || empty($password)) {
        $error = "All fields are required to enter the sanctum.";
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, full_name, password FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $credential, $credential);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid credentials. The seal does not recognize you.";
            }
        } else {
            $error = "No soul found with that name. Have you sworn the oath?";
        }

        $stmt->close();
        $db->close();
    }
}

$pageTitle = "Enter the Sanctum";
$requireAuth = false;
include 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
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
            <h1 class="auth-title">Enter the Sanctum</h1>
            <p class="auth-subtitle">Verify your crimson seal</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <span>⚠</span>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label class="form-label" for="credential">Vassal Name or Sigil</label>
                <input type="text" id="credential" name="credential" class="form-control"
                    placeholder="Username or email..." value="<?php echo htmlspecialchars($_POST['credential'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Blood Oath (Password)</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Your password..." required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top: 0.5rem;">
                ⬡ Break the Seal
            </button>
        </form>

        <p style="text-align:center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted); font-style: italic;">
            Not yet sworn? <a href="register.php" style="color: var(--gold); text-decoration: none;">Swear the Oath →</a>
        </p>
        <p style="text-align:center; margin-top: 0.5rem;">
            <a href="index.php" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none;">← Return to the gates</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
