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
        $allowed = ['image/jpeg','image/png','image/gif'];
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
    $roleStmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
    $roleStmt->execute([$_SESSION['user_id']]);
    $userRole = $roleStmt->fetchColumn();

    if ($userRole === 'admin' && array_key_exists('company', $_POST)) {
        $companyName = trim($_POST['company']);
        // remove existing link
        $pdo->prepare("DELETE FROM user_companies WHERE user_id = ?")
            ->execute([$_SESSION['user_id']]);

        if ($companyName !== '') {
            // find or create company
            $cStmt = $pdo->prepare("SELECT id FROM companies WHERE name = ?");
            $cStmt->execute([$companyName]);
            $companyId = $cStmt->fetchColumn();

            if (!$companyId) {
                $pdo->prepare("INSERT INTO companies (name) VALUES (?)")
                    ->execute([$companyName]);
                $companyId = $pdo->lastInsertId();
            }

            // link user ↔ company
            $pdo->prepare(
                "INSERT INTO user_companies (user_id, company_id) VALUES (?, ?)"
            )->execute([$_SESSION['user_id'], $companyId]);
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
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About Me</title>
  <style>
        #main-content {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A82F7 0%, #00C2FF 100%);
            color: white;
            min-height: 100vh;
            padding-top: 80px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        input[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .reset-password-btn {
            background: linear-gradient(135deg, #ffffff, #d9f3ff);
            color: #007aad;
            border: none;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 4px 14px rgba(0, 194, 255, 0.4);
            transition: 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .reset-password-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00c2ff, #3a82f7);
            transition: left 0.4s ease;
            z-index: -1;
        }

        .reset-password-btn:hover::before {
            left: 0;
        }

        .reset-password-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 194, 255, 0.6);
        }

        .input-wrapper {
            margin-left: 300px;
            max-width: 500px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
        }

        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .profile-section {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .profile-image {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }

        .upload-label {
            display: inline-block;
            background: #00c2ff;
            color: white;
            font-weight: 500;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-label:hover {
            background: #3a82f7;
            transform: scale(1.05);
        }

        .save-changes-btn {
            background: linear-gradient(135deg, #00c2ff, #3a82f7);
            color: white;
            border: none;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 25px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .save-changes-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
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
            class="profile-image"
          ><br>
          <label for="uploadImage" class="upload-label">Change your picture</label>
          <input type="file"
                 id="uploadImage"
                 name="avatar"
                 accept="image/*"
                 style="display:none;"
          >
        </div>

        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text"
               id="username"
               name="username"
               value="<?= htmlspecialchars($user['username'] ?? '') ?>"
        >

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email"
               id="email"
               name="email"
               value="<?= htmlspecialchars($user['email'] ?? '') ?>"
        >

        <!-- Phone -->
        <label for="phone">Phone Number:</label>
        <input type="text"
               id="phone"
               name="phone"
               value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
               placeholder="Enter your phone number"
        >

        <!-- Company (admins only) -->
        <?php if ($role === 'admin'): ?>
          <label for="company">Company Name:</label>
          <input type="text"
                 id="company"
                 name="company"
                 value="<?= htmlspecialchars($companyName) ?>"
                 placeholder="Enter your company name"
          >
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