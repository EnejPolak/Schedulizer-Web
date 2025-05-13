<?php
session_start();
include 'toolbar.php';
require 'db_connect.php';

// Pridobi podatke prijavljenega uporabnika
$user = null;
$role = null;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT username, email, password_hash, user_role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $role = $user['user_role'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        <div class="input-wrapper">
            <h2>Account Info</h2>

            <div class="profile-section">
                <img id="profileImage" src="" alt="Profile Picture" class="profile-image">
                <br>
                <label for="uploadImage" class="upload-label">Change your picture</label>
                <input type="file" id="uploadImage" accept="image/*" style="display: none;">
            </div>

            <label for="username">Username:</label>
            <input type="text" id="username" placeholder="Enter your username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" placeholder="Enter your email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">

            <label for="password">Password:</label>
            <input type="password" id="password" value="<?= str_repeat('*', 12) ?>" disabled>

            <button class="reset-password-btn" onclick="window.location.href='reset-password.php'">Reset Password</button>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" placeholder="Enter your phone number">

            <?php if ($role === 'admin'): ?>
                <label for="company">Company Name:</label>
                <input type="text" id="company" placeholder="Enter your company name">
            <?php endif; ?>

            <button class="save-changes-btn">Save Changes</button>
        </div>
    </div>

    <script>
        document.getElementById('uploadImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profileImage').src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        window.addEventListener('DOMContentLoaded', () => {
            const image = document.getElementById('profileImage');
            if (!image.src || image.src.includes('blank')) {
                image.src = 'https://via.placeholder.com/90x90/ffffff/cccccc?text=👤';
            }
        });
    </script>

</body>

</html>