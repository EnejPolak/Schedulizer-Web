<?php
// show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';

// 1) Handle form submission (avatar + other fields)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A) Avatar upload
    if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (
            in_array($_FILES['avatar']['type'], $allowed) &&
            $_FILES['avatar']['size'] <= 2 * 1024 * 1024
        ) {
            $ext     = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $newName = bin2hex(random_bytes(8)) . ".$ext";
            $dest    = __DIR__ . "/uploads/avatars/$newName";
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?")
                    ->execute(["uploads/avatars/$newName", $_SESSION['user_id']]);
            }
        }
    }

    // B) Update username, email, phone
    $updates = [];
    $params  = [];
    if (isset($_POST['username'])) {
        $updates[] = "username = ?";
        $params[]  = trim($_POST['username']);
    }
    if (isset($_POST['email'])) {
        $updates[] = "email = ?";
        $params[]  = trim($_POST['email']);
    }
    if (array_key_exists('phone', $_POST)) {
        $updates[] = "phone = ?";
        $params[]  = trim($_POST['phone']);
    }
    if ($updates) {
        $params[] = $_SESSION['user_id'];
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $pdo->prepare($sql)->execute($params);

        // sync session username
        if (isset($_POST['username'])) {
            $_SESSION['username'] = trim($_POST['username']);
        }
    }


    // C) Company (free-text), admins only

        // C) Company (free-text), admins only

    $roleStmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
    $roleStmt->execute([$_SESSION['user_id']]);
    $userRole = $roleStmt->fetchColumn();


    if (
        in_array($userRole, ['admin', 'Premium'], true)
        && array_key_exists('company', $_POST)

    if ( in_array($userRole, ['admin','Premium'], true)
      && array_key_exists('company', $_POST)

    ) {
        $newName = trim($_POST['company']);

        // 1) Find the existing company link (if any)
        $stmtC = $pdo->prepare("
            SELECT uc.company_id
              FROM user_companies uc
             WHERE uc.user_id = ?
             LIMIT 1
        ");
        $stmtC->execute([$_SESSION['user_id']]);
        $existingCompanyId = $stmtC->fetchColumn();

        if ($newName === '') {
            // if they cleared the field, just unlink
            if ($existingCompanyId) {
                $pdo->prepare("DELETE FROM user_companies WHERE user_id = ?")
                    ->execute([$_SESSION['user_id']]);
            }
        } else {
            if ($existingCompanyId) {
                // update the existing company’s name
                $pdo->prepare("UPDATE companies SET name = ? WHERE id = ?")
                    ->execute([$newName, $existingCompanyId]);
            } else {
                // no existing link – create a new company + link
                $pdo->prepare("INSERT INTO companies (name) VALUES (?)")
                    ->execute([$newName]);
                $newCompanyId = $pdo->lastInsertId();
                $pdo->prepare("INSERT INTO user_companies (user_id, company_id) VALUES (?, ?)")
                    ->execute([$_SESSION['user_id'], $newCompanyId]);
            }
        }
    }







    header("Location: aboutme.php");
    exit;
}

// 2) Include toolbar & pull current user + company
include 'toolbar.php';

$user = $role = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare(
        "SELECT username, email, user_role, avatar, phone
         FROM users
        WHERE id = ?"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $role = $user['user_role'] ?? null;
}

// 3) Fetch free-text company name (if any)
$companyName = '';
$uc = $pdo->prepare(
    "SELECT c.name
     FROM companies c
     JOIN user_companies uc ON c.id = uc.company_id
    WHERE uc.user_id = ?"
);
$uc->execute([$_SESSION['user_id']]);
$companyName = $uc->fetchColumn() ?: '';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile | Schedulizer</title>
    <!-- Font imports -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>About Me</title>

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #0284c7;
            --accent-color: #38bdf8;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #94a3b8;
            --bg-light: #ffffff;
            --bg-dark: #0f172a;
            --bg-card: #ffffff;
            --bg-input: #f1f5f9;
            --bg-hover: #f8fafc;
            --transition-speed: 0.3s;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius-sm: 6px;
            --border-radius: 12px;
            --border-radius-lg: 16px;
        }

        body.dark {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --accent-color: #818cf8;
            --bg-card: #1e293b;
            --bg-input: #0f172a;
            --bg-hover: #1e293b;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
            transition: all var(--transition-speed) ease;
        }

        body.dark {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        /* Theme toggle, globals */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            z-index: 999;
            transition: all var(--transition-speed) ease;
        }

        .theme-toggle:hover {
            transform: rotate(30deg);
        }

        /* === SCOPED TO #main-content === */
        #main-content .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }

        #main-content .page-header {
            margin-bottom: 2rem;
        }

        #main-content .page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        #main-content .page-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        #main-content .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            animation: fadeInDown 0.5s ease;
        }

        #main-content .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        #main-content .alert-icon {
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #main-content .profile-card {
            background-color: var(--bg-card);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
        }

        #main-content .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            position: relative;
        }

        #main-content .header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        #main-content .card-body {
            padding: 2rem;
        }

        #main-content .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        #main-content .form-group {
            margin-bottom: 1.5rem;
        }

        #main-content .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        #main-content .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: var(--border-radius-sm);
            border: 1px solid #e2e8f0;
            background-color: var(--bg-input);
            color: var(--text-dark);
            transition: all var(--transition-speed) ease;
        }

        #main-content .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        body.dark #main-content .form-control {
            border-color: #2d3748;
            color: var(--text-light);
        }

        body.dark #main-content .form-control:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        #main-content .form-control::placeholder {
            color: var(--text-muted);
        }

        #main-content .profile-image-container {
            position: relative;
            width: 120px;
            height: 120px;
        }

        #main-content .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
            background-color: #f3f4f6;
        }

        #main-content .image-upload-label {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: var(--primary-color);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            box-shadow: var(--shadow-md);
        }

        #main-content .image-upload-label:hover {
            background-color: var(--secondary-color);
            transform: scale(1.1);
        }

        #main-content .hidden-upload {
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }

        #main-content .user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #main-content .user-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        #main-content .user-role {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #main-content .role-badge {
            background-color: #ffffff;
            /* belo ozadje */
            color: var(--text-dark);
            /* temna barva besedila */
            border-radius: 100px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        #main-content .btn-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        #main-content .password-section {
            margin-top: 2rem;
            background-color: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        #main-content .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            #main-content {
                padding: 1rem;
            }

            #main-content .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            #main-content .header-content {
                flex-direction: column;
                text-align: center;
            }

            #main-content .profile-image-container {
                margin: 0 auto;
            }

            #main-content .user-info {
                align-items: center;
            }
        }

        /* Dark mode tweaks scoped too */
        body.dark #main-content .profile-card,
        body.dark #main-content .password-section {
            background-color: var(--bg-card);
        }

        body.dark #main-content .alert-success {
            background-color: rgba(16, 185, 129, 0.05);
        }

        body.dark #main-content .profile-image {
            border-color: var(--bg-card);
        }

        #main-content .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            gap: 0.5rem;
        }

        /* Primary “Save Changes” */
        #main-content .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }


        #main-content .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Outline “Change Password” */
        #main-content .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        #main-content .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Full-width when .btn-block */
        #main-content .btn-block {
            width: 100%;
        }
    </style>

</head>

<body class="dark">
    <!-- Theme toggle for demo -->
    <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-sun"></i>
    </button>



    <div id="main-content">
        <div class="profile-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">My Profile</h1>
                <p class="page-subtitle">Manage your personal information and account settings</p>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    Profile updated successfully!
                </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data" id="profileForm">
                <div class="profile-card">
                    <div class="card-header">
                        <div class="header-content">
                            <div class="profile-image-container">
                                <img
                                    id="profileImage"
                                    src="<?= htmlspecialchars($avatarUrl) ?>"
                                    alt="Profile Picture"
                                    class="profile-image">
                                <label for="uploadImage" class="image-upload-label" title="Change profile picture">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input
                                    type="file"
                                    id="uploadImage"
                                    name="avatar"
                                    accept="image/*"
                                    class="hidden-upload">
                            </div>
                            <div class="user-info">
                                <h2 class="user-name"><?= htmlspecialchars($user['username']) ?></h2>
                                <div class="user-role">
                                    <span class="role-badge"><?= htmlspecialchars($user['user_role']) ?></span>
                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['username']) ?>"
                                    placeholder="Enter your username">
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['email']) ?>"
                                    placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['phone']) ?>"
                                    placeholder="Enter your phone number">
                            </div>

                            <?php if ($role === 'admin'): ?>
                                <div class="form-group">
                                    <label for="company" class="form-label">Company Name</label>
                                    <input
                                        type="text"
                                        id="company"
                                        name="company"
                                        class="form-control"
                                        value="<?= htmlspecialchars($companyName) ?>"
                                        placeholder="Enter your company name">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="btn-actions">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Password Section -->
            <div class="password-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i> Security Settings
                </h3>
                <p style="margin-bottom: 1.5rem; color: var(--text-muted);">Update your password to keep your account secure.</p>

                <a href="reset-password.php" class="btn btn-outline">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </div>
        </div>
    </div>

    <script>
        // Preview image before upload
        document.getElementById('uploadImage').addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;

            const reader = new FileReader();
            const profileImage = document.getElementById('profileImage');

            reader.onload = function(e) {
                profileImage.src = e.target.result;
            };

            reader.readAsDataURL(this.files[0]);

            // Demo only - would normally submit the form
            console.log("Image selected, normally would submit form");
        });

        // Theme toggle for demo
        const themeToggle = document.getElementById("themeToggle");
        const body = document.body;

        themeToggle.addEventListener("click", function() {
            body.classList.toggle("light");
            body.classList.toggle("dark");

            // Change icon based on theme
            const icon = themeToggle.querySelector("i");
            if (body.classList.contains("light")) {
                icon.classList.remove("fa-sun");
                icon.classList.add("fa-moon");
            } else {
                icon.classList.remove("fa-moon");
                icon.classList.add("fa-sun");
            }
        });

        body.dark #main-content {
            background: radial-gradient(circle at top left, #1E1B2E, #140B2D, #0F0C1D);
            color: #EDE9FE;
        }

        body.dark h2 {
            color: #EDE9FE;
        }

        body.dark label {
            color: #C4B5FD;
        }

        body.dark input {
            background: rgba(255, 255, 255, 0.07);
            color: #F3E8FF;
            border: none;
        }

        body.dark input::placeholder {
            color: rgba(235, 235, 245, 0.5);
        }

        body.dark input[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
        }

        body.dark .reset-password-btn {
            background: linear-gradient(135deg, #4C1D95, #5B21B6, #7C3AED);
            color: #E0E7FF;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        body.dark .reset-password-btn::before {
            background: linear-gradient(135deg, #7C3AED, #5B21B6, #2E1065);
        }

        body.dark .reset-password-btn:hover {
            color: #EDE9FE;
            box-shadow: 0 6px 18px rgba(124, 58, 237, 0.5);
        }

        body.dark .input-wrapper input {
            background: rgba(255, 255, 255, 0.05);
            color: #F3E8FF;
        }

        body.dark .input-wrapper input::placeholder {
            color: rgba(240, 240, 255, 0.4);
        }

        body.dark .profile-section {
            color: #D8B4FE;
        }

        body.dark .profile-image {
            background: #2E1065;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
        }

        body.dark .upload-label {
            background: #6D28D9;
            color: #F3E8FF;
        }

        body.dark .upload-label:hover {
            background: #7C3AED;
        }

        body.dark .save-changes-btn {
            background: linear-gradient(135deg, #4C1D95, #6D28D9, #7C3AED);
            color: #F3E8FF;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        body.dark .save-changes-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 18px rgba(124, 58, 237, 0.5);
        }

        body.dark h2 {
            color: #6D28D9;
            /* enaka barva kot SHEDULIZER */
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body>
    <div id="main-content">
        <form action="aboutme.php" method="POST" enctype="multipart/form-data">
            <div class="input-wrapper">
                <h2>Account Info</h2>

                <!-- Avatar -->
                <div class="profile-section">
                    <img
                        id="profileImage"
                        src="<?= htmlspecialchars($user['avatar'] ?: 'https://via.placeholder.com/90') ?>"
                        alt="Profile Picture"
                        class="profile-image"><br>
                    <label for="uploadImage" class="upload-label">Change your picture</label>
                    <input type="file"
                        id="uploadImage"
                        name="avatar"
                        accept="image/*"
                        style="display:none;">
                </div>

                <!-- Username -->
                <label for="username">Username:</label>
                <input type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars($user['username'] ?? '') ?>">

                <!-- Email -->
                <label for="email">Email:</label>
                <input type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email'] ?? '') ?>">

                <!-- Phone -->
                <label for="phone">Phone Number:</label>
                <input type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                    placeholder="Enter your phone number">

                <!-- Company (admins only) -->
                <?php if (in_array($role, ['admin','Premium'], true)): ?>
                    <label for="company">Company Name:</label>
                    <input type="text"
                        id="company"
                        name="company"
                        value="<?= htmlspecialchars($companyName) ?>"
                        placeholder="Enter your company name">
                <?php endif; ?>

                <button type="submit" class="save-changes-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        // trigger file input
        document.querySelector('.upload-label')
            .addEventListener('click', () =>
                document.getElementById('uploadImage').click()
            );

        // preview & auto-submit avatar
        document.getElementById('uploadImage')
            .addEventListener('change', function() {
                if (!this.files[0]) return;
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
                this.form.submit();
            });

    </script>
</body>

</html>