<?php
session_start();
require 'db_connect.php';

$errors   = [];
$action   = $_REQUEST['action']   ?? '';
$email    = $_POST['email']       ?? '';
$password = $_POST['password']    ?? '';
$username = $_POST['username']    ?? '';

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    $email    = trim($email);
    $password = trim($password);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif ($password === '') {
        $errors[] = 'Please enter your password.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors[] = 'No account found with that email.';
        } elseif (!password_verify($password, $user['password_hash'])) {
            $errors[] = 'Incorrect password.';
        } else {
            $_SESSION['user_id'] = $user['id'];

            // 🔍 Zraven pridobi tudi username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE id = ?");
            $stmt2->execute([$user['id']]);
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);

            $_SESSION['username'] = $row['username']; // npr. enej.polak

            header('Location: calendar.php');
            exit;
        }
    }
}

// REGISTER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
    $email     = trim($email);
    $password  = trim($password);
    $username  = trim($username);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/\d/', $password)) {
        $errors[] = 'Password must contain at least one number.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'That email is already registered.';
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username']  = $username;
            header("Location: login.php?registered=1");
            exit;
        } catch (Exception $e) {
            $errors[] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup Form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* enak CSS kot prej, brez sprememb za register-business */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
        }

        .container {
            position: relative;
            width: 850px;
            height: 550px;
            background: #fff;
            margin: 20px;
            border-radius: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, .2);
            overflow: hidden;
        }

        .container h1 {
            font-size: 26px;
            margin: -10px 0;
        }

        .container p {
            font-size: 14.5px;
            margin: 15px 0;
        }

        form {
            width: 100%;
        }

        .form-box {
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background: #fff;
            display: flex;
            align-items: center;
            color: #333;
            text-align: center;
            padding: 40px;
            z-index: 1;
            transition: .6s ease-in-out 1.2s, visibility 0s 1s;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        .form-box.active {
            opacity: 1;
            transform: translateY(0);
        }

        .container.active .form-box {
            right: 50%;
        }

        .form-box.register {
            visibility: hidden;
        }

        .container.active .form-box.register {
            visibility: visible;
        }

        .input-box {
            position: relative;
            margin: 30px 0;
        }

        .input-box input {
            width: 100%;
            padding: 13px 50px 13px 20px;
            background: #eee;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .input-box input::placeholder {
            color: #888;
            font-weight: 400;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .forgot-link {
            margin: -15px 0 15px;
        }

        .forgot-link a {
            font-size: 14.5px;
            color: #333;
        }

        .btn {
            width: 100%;
            height: 48px;
            background: #7494ec;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
        }

        .social-icons {
            display: flex;
            justify-content: center;
        }

        .social-icons a {
            display: inline-flex;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 24px;
            color: #333;
            margin: 0 8px;
        }

        .toggle-box {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .toggle-box::before {
            content: '';
            position: absolute;
            left: -250%;
            width: 300%;
            height: 100%;
            background: #7494ec;
            border-radius: 150px;
            z-index: 2;
            transition: 1.8s ease-in-out;
        }

        .container.active .toggle-box::before {
            left: 50%;
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2;
            transition: .6s ease-in-out;
        }

        .toggle-panel.toggle-left {
            left: 0;
            transition-delay: 1.2s;
        }

        .container.active .toggle-panel.toggle-left {
            left: -50%;
            transition-delay: .6s;
        }

        .toggle-panel.toggle-right {
            right: -50%;
            transition-delay: .6s;
        }

        .container.active .toggle-panel.toggle-right {
            right: 0;
            transition-delay: 1.2s;
        }

        .toggle-panel p {
            margin-bottom: 20px;
        }

        .toggle-panel .btn {
            width: 160px;
            height: 46px;
            background: transparent;
            border: 2px solid #fff;
            box-shadow: none;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- LOGIN -->
        <div class="form-box login">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <?php if (isset($_GET['registered'])): ?>
                    <div class="success-box" style="color:green; text-align:center; margin-bottom:1em;">
                        You’ve successfully registered! Please log in.
                    </div>
                <?php endif; ?>
                <input type="hidden" name="action" value="login">
                <h1>Login</h1>
                <?php if (isset($_GET['reset'])): ?>
                    <div class="success-box" style="color:green; text-align:center; margin-bottom:1em;">
                        Your password has been changed! Please log in.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors) && ($_POST['action'] ?? '') === 'login'): ?>
                    <div class="error-box" style="color:red;…">
                        <?= htmlspecialchars($errors[0]) ?>
                    </div>
                <?php endif; ?>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required
                        value="<?= htmlspecialchars($email ?? '') ?>">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="forgot-link">
                    <a href="reset-password.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <!-- REGISTER -->
        <div class="form-box register">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <h1 style="margin-bottom: 12px;">Registration</h1>
                <input type="hidden" name="action" value="register">

                <?php if (!empty($errors) && $action === 'register'): ?>
                    <div class="error-box" style="color:red; font-size:14px; margin-bottom:20px; text-align:center;">
                        <?= htmlspecialchars($errors[0]) ?>
                    </div>
                <?php endif; ?>

                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username ?? '') ?>">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email ?? '') ?>">
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <!-- TOGGLE PANELS -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script>
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.register-btn');
        const loginBtn = document.querySelector('.login-btn');
        const formBoxLogin = document.querySelector('.form-box.login');
        const formBoxRegister = document.querySelector('.form-box.register');

        function hideAllForms() {
            formBoxLogin.classList.remove('active');
            formBoxRegister.classList.remove('active');
        }

        // ↔️ Toggle to Register panel
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
            hideAllForms();
            formBoxRegister.style.visibility = 'visible';
            formBoxRegister.classList.add('active');
        });

        // ↔️ Toggle to Login panel
        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
            hideAllForms();
            formBoxRegister.style.visibility = 'hidden';
            formBoxLogin.classList.add('active');
        });

        // 📌 Default to Login panel on first load
        formBoxLogin.classList.add('active');

        document.addEventListener('DOMContentLoaded', () => {
            <?php if (!empty($errors)): ?>
                // If there were any errors, show the matching panel:
                hideAllForms();
                <?php if ($action === 'login'): ?>
                    // Login error → show Login
                    container.classList.remove('active');
                    formBoxRegister.style.visibility = 'hidden';
                    formBoxLogin.classList.add('active');
                <?php else: /* register */ ?>
                    // Register error → show Register
                    container.classList.add('active');
                    formBoxRegister.style.visibility = 'visible';
                    formBoxRegister.classList.add('active');
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>

</body>

</html>