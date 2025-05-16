<?php
// ————————————————————————————
// DEBUG: show all PHP errors
// ————————————————————————————
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';

// 1) Must be logged in
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2) Get current user’s email
$userStmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$currentEmail = $userStmt->fetchColumn();

// 3) Handle form submit
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim whitespace around the token
    $code = trim($_POST['invite_code'] ?? '');

    if ($code === '') {
        $error = 'Please enter your invite code.';
    } else {
        // 4) Look up in invites table
        $inviteStmt = $pdo->prepare("
            SELECT id, company_id, email, role, expires_at
              FROM invites
             WHERE token = ?
               AND expires_at >= NOW()
             LIMIT 1
        ");
        $inviteStmt->execute([$code]);
        $invite = $inviteStmt->fetch(PDO::FETCH_ASSOC);

        if (! $invite) {
            $error = 'Invalid or expired code.';
        } elseif ($invite['email'] !== $currentEmail) {
            $error = 'This code was not issued to your account.';
        } else {
            // 5) Add user to that company, but ignore if already linked
            $pdo->prepare("
      INSERT IGNORE INTO user_companies (user_id, company_id)
      VALUES (?, ?)
    ")->execute([
                $_SESSION['user_id'],
                $invite['company_id']
            ]);

            // 6) Update both user_role and role in users
            $newRole = $invite['role'];
            $pdo->prepare("
      UPDATE users
         SET user_role = ?, role = ?
       WHERE id = ?
    ")->execute([
                $newRole,
                $newRole,
                $_SESSION['user_id']
            ]);

            // 7) Delete the invite so it can't be reused
            $pdo->prepare("DELETE FROM invites WHERE id = ?")
                ->execute([$invite['id']]);

            // 8) Redirect into the app
            header('Location: calendar.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Schedulizer – Plans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top left, #3A82F7, #00C2FF);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 30px 20px;
            overflow-x: hidden;
        }

        .top-notice {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        .top-notice h2 {
            font-size: 24px;
            font-weight: 500;
            color: #e8f4ff;
        }

        .top-notice h2 span {
            color: #ffffff;
            font-weight: 600;
        }

        .container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            max-width: 1300px;
            width: 100%;
            position: relative;
        }

        .box {
            background: rgba(255, 255, 255, 0.1);
            padding: 35px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 600px;
            text-align: center;
            min-height: 580px;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .box:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        }

        .clickable {
            cursor: pointer;
        }

        .clickable:hover {
            transform: scale(1.01) translateY(-6px);
        }

        .or-divider {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            animation: fadeInUp 1.5s ease;
        }

        h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 5px;
            background: linear-gradient(to right, #ffffff, #cce7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 0.7s ease;
        }

        h3 {
            font-size: 18px;
            font-weight: 400;
            color: #d0eaff;
            margin-bottom: 25px;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .plan-image {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.3s ease;
        }

        .plan-image:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .code-box h2 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .code-box p {
            font-size: 15px;
            color: #cde9ff;
            margin-bottom: 25px;
        }

        .code-box input[type="text"] {
            width: 80%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            outline: none;
            font-size: 16px;
            margin-bottom: 20px;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.2);
            background: white;
            color: #333;
        }

        .code-box button {
            background: linear-gradient(135deg, #ffffff, #dceeff);
            color: #3A82F7;
            padding: 12px 36px;
            font-size: 16px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            position: relative;
            transition: all 0.3s ease, transform 0.1s ease;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
        }

        .code-box button:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.6);
            background: linear-gradient(135deg, #ffffff, #b3d9ff);
        }

        .code-box button:active {
            transform: scale(0.96);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) inset;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            h1 {
                font-size: 28px;
            }

            h3 {
                font-size: 15px;
            }

            .code-box h2 {
                font-size: 20px;
            }

            .or-divider {
                position: static;
                transform: none;
                margin: 20px 0;
            }
        }
    </style>
</head>

<body>
    <!-- ✅ TOP NOTICE -->
    <div class="top-notice">
        <h2>Seems like you are not in any group – <span>start with Schedulizer!</span></h2>
    </div>

    <!-- ✅ MAIN WRAPPER -->
    <div class="container">

        <!-- LEFT: BUY PLAN BOX -->
        <div class="box clickable" onclick="window.location.href='store.php'">
            <h1>Buy a plan</h1>
            <h3>and start with Schedulizer today</h3>
            <img src="https://i.ibb.co/fGqjmchS/skupna-slika.png" alt="Schedulizer Plans" class="plan-image">
        </div>

        <!-- MIDDLE: OR -->
        <div class="or-divider">OR</div>

        <!-- RIGHT: CODE BOX -->
        <div class="box code-box">
            <h2>Enter a code you got</h2>
            <p>Enter the access code sent to your email by your boss or manager to unlock your schedule group.</p>

            <!-- show any error message -->
            <?php if ($error): ?>
                <div style="color: salmon; margin-bottom: 1rem; font-weight: bold;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input
                    type="text"
                    name="invite_code"
                    placeholder="Enter your code here"
                    class="form-control"
                    required>
                <br>
                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>

</html>