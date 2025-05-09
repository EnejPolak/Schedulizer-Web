<?php
// new_password.php
session_start();
require 'db_connect.php';

$errors   = [];
$token    = $_REQUEST['token'] ?? '';
$email    = null;

// Lookup & validate token on GET and POST
if ($token) {
    $stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $row) {
        $errors[] = 'Invalid reset link.';
    } elseif (strtotime($row['expires_at']) < time()) {
        $errors[] = 'This reset link has expired.';
    } else {
        $email = $row['email'];
    }
} else {
    $errors[] = 'No reset token provided.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $password        = $_POST['password']         ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Server-side validations
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if (! preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // 1) Update the user’s password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $upd->execute([$hash, $email]);

        // 2) Remove the token so it can’t be reused
        $del = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $del->execute([$token]);

        // 3) Redirect to login with a “reset=1” flag
        header("Location: login.php?reset=1");
        exit;
    }
}
$isGet  = $_SERVER['REQUEST_METHOD'] === 'GET';
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

// Show the form if...
$showForm = ($isGet   && empty($errors))  // first time load, token ok
         || ($isPost  && !empty($errors)); // form submitted but errors
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Schedulizer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7ff, #f0f9ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .reset-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reset-container h2 {
            color: #3A82F7;
            margin-bottom: 25px;
            font-size: 28px;
            text-align: center;
        }

        .reset-container .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .reset-container input[type="password"],
        .reset-container input[type="text"] {
            width: 100%;
            padding: 14px;
            padding-right: 44px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .reset-container input:focus {
            border-color: #00C2FF;
            outline: none;
        }

        .reset-container .eye-icon {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #3A82F7;
            font-size: 18px;
        }

        .reset-container button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #00C2FF, #3A82F7);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reset-container button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .reset-container button:hover:enabled {
            background: linear-gradient(135deg, #009dd9, #2a6bd1);
            transform: scale(1.03);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(to right, #00C2FF, #3A82F7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            text-align: center;
        }

        .background-bubbles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: floatBubble 20s ease-in-out infinite;
        }

        .blue {
            background: #3A82F7;
            width: 300px;
            height: 300px;
            top: -50px;
            left: -80px;
        }

        .lightblue {
            background: #00C2FF;
            width: 250px;
            height: 250px;
            bottom: -60px;
            right: -60px;
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .criteria-list {
            font-size: 14px;
            list-style: none;
            padding-left: 0;
            margin: 5px 0 10px 0;
        }

        .reset-container ul {
            padding-left: 0;
            margin: 5px 0 10px 0;
            padding-inline-start: 2px;
        }


        .criteria-list li {
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            color: #333;
        }

        .criteria-list.valid {
            color: #28a745;
            font-weight: 500;
        }

        .criteria-list i {
            margin-right: 6px;
            visibility: hidden;
        }

        .criteria-list.valid i {
            visibility: visible;
            color: #28a745;
        }

        #mismatchWarning {
            font-size: 14px;
            color: #dc3545;
            margin-top: -5px;
            margin-bottom: 10px;
            display: none;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- background bubbles -->
    <div class="background-bubbles">
        <div class="bubble blue"></div>
        <div class="bubble lightblue"></div>
    </div>

    <div class="reset-container">
    <div class="logo">SCHEDULIZER</div>
    <h2>Reset your password</h2>

    <?php if (!empty($errors)): ?>
      <div class="error-box" style="color:red; text-align:center; margin-bottom:1em;">
        <?= htmlspecialchars($errors[0]) ?>
      </div>
    <?php endif; ?>

    <?php if ($showForm): ?>
      <form id="passwordForm" action="" method="POST">
        <!-- carry the token through POST -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="input-wrapper">
          <input type="password" id="password" name="password"
                 placeholder="Enter your password" required
                 oninput="checkPasswords()">
          <i class="fas fa-eye-slash eye-icon" onclick="togglePassword('password', this)"></i>
        </div>

        <div class="input-wrapper">
          <input type="password" id="confirm_password" name="confirm_password"
                 placeholder="Repeat your password" required
                 oninput="checkPasswords()">
          <i class="fas fa-eye-slash eye-icon" onclick="togglePassword('confirm_password', this)"></i>
        </div>

        <p id="mismatchWarning">Passwords do not match</p>

        <ul>
          <li id="lengthCheck" class="criteria-list"><i class="fas fa-check-circle"></i> At least 8 characters</li>
          <li id="uppercaseCheck" class="criteria-list"><i class="fas fa-check-circle"></i> At least one uppercase letter</li>
        </ul>

        <button type="submit" id="submitBtn" disabled>Confirm</button>
      </form>
    <?php endif; ?>
  </div>

    <script>
    // your existing togglePassword and checkPasswords functions…
    function togglePassword(fieldId, icon) {
      const field = document.getElementById(fieldId);
      const isPwd  = field.type === "password";
      field.type = isPwd ? "text" : "password";
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    }

    function checkPasswords() {
      const pass    = document.getElementById("password").value;
      const confirm = document.getElementById("confirm_password").value;

      const lengthCheck    = document.getElementById("lengthCheck");
      const uppercaseCheck = document.getElementById("uppercaseCheck");
      const warning        = document.getElementById("mismatchWarning");
      const submitBtn      = document.getElementById("submitBtn");

      const validLength   = pass.length >= 8;
      const hasUppercase  = /[A-Z]/.test(pass);
      const matches       = pass === confirm && pass !== "";

      lengthCheck.classList.toggle("valid", validLength);
      uppercaseCheck.classList.toggle("valid", hasUppercase);

      if (confirm.length === 0) {
        warning.style.display = "none";
      } else {
        warning.style.display = matches ? "none" : "block";
      }

      submitBtn.disabled = !(validLength && hasUppercase && matches);
    }
  </script>
</body>

</html>