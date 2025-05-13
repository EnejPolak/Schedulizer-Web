<?php
session_start();
require 'db_connect.php';

// 1) Fetch the current user’s role & company
$role = $companyId = null;
if (!empty($_SESSION['user_id'])) {
    // role
    $stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $role = $stmt->fetchColumn();

    // company
    $c = $pdo->prepare("
      SELECT company_id 
        FROM user_companies 
       WHERE user_id = ?
       LIMIT 1
    ");
    $c->execute([ $_SESSION['user_id'] ]);
    $companyId = $c->fetchColumn();
}

// 2) Only admins/mods/Premium can invite
if (!in_array($role, ['admin','moderator','Premium'], true)) {
    header("Location: calendar.php");
    exit;
}

include 'toolbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Invite Teammates</title>
    <style>
        body {
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            font-family: 'Segoe UI', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-left: 300px;
            padding: 40px;
            max-width: 600px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
            margin-bottom: 16px;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn {
            padding: 12px 24px;
            background: linear-gradient(to right, #00c2ff, #3a82f7);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: white;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
        }

        .link-box {
            margin-top: 25px;
            background: rgba(255, 255, 255, 0.15);
            padding: 14px;
            border-radius: 10px;
            word-break: break-word;
            font-size: 15px;
        }

        body.dark {
            background: radial-gradient(circle at top left, #1E1B2E, #140B2D, #0F0C1D);
            font-family: 'Segoe UI', sans-serif;
            color: #D8B4FE;
            margin: 0;
            padding: 0;
        }

        body.dark .container {
            margin-left: 300px;
            padding: 40px;
            max-width: 600px;
        }

        body.dark h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #C4B5FD;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }

        body.dark form {
            display: flex;
            flex-direction: column;
        }

        body.dark input[type="email"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #EDE9FE;
            font-size: 16px;
            margin-bottom: 16px;
        }

        body.dark input::placeholder {
            color: rgba(232, 223, 255, 0.6);
        }

        body.dark .btn {
            padding: 12px 24px;
            background: linear-gradient(to right, #7C3AED, #5B21B6, #2E1065);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: #EDE9FE;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2);
        }

        body.dark .btn:hover {
            transform: scale(1.05);
            background: linear-gradient(to right, #2E1065, #5B21B6, #7C3AED);
            box-shadow: 0 6px 16px rgba(124, 58, 237, 0.3);
        }

        body.dark .link-box {
            margin-top: 25px;
            background: #2A1A4F;
            padding: 14px;
            border-radius: 10px;
            word-break: break-word;
            font-size: 15px;
            color: #D8B4FE;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Invite a Team Member</h2>

        <form method="POST">
            <input type="email" name="invite_email" placeholder="Enter colleague's email..." required>
            <button type="submit" class="btn">Send Invite</button>
        </form>

        <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && !empty($_POST['invite_email'])) 
    {
        $email = trim($_POST['invite_email']);

        
        $stmtU = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmtU->execute([$email]);
        $recipientId = $stmtU->fetchColumn();

        if (!$recipientId) {
            echo "<div class='link-box' style='color:salmon'>
                    ❌ No user found with email “".htmlspecialchars($email)."”
                  </div>";
        } else {
            
            $letters = substr(str_shuffle(
                'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
               .'abcdefghijklmnopqrstuvwxyz'
            ), 0, 8);
            $numbers = str_pad(
                (string) random_int(0, 99999999),
                8, '0', STR_PAD_LEFT
            );
            $token = $letters.$numbers;
            $ins = $pdo->prepare("
  INSERT INTO invite_tokens
    (token, sender_id, recipient_id, company_id)
  VALUES (?, ?, ?, ?)
");
$ins->execute([
  $token,
  $_SESSION['user_id'],
  $recipientId,
  $companyId
]);
$cstmt = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
        $cstmt->execute([$companyId]);
        $companyName = $cstmt->fetchColumn();

            $sender = urlencode($_SESSION['username']);
            $inviteLink = sprintf(
              "https://schedulizer.eu/register.php?invite=%s&from=%s&company=%s",
              $token, $sender, $companyId
            );

            
            $subject = "Your Schedulizer invite for “{$companyName}”";
        $body    = "Hello,\n\n"
                 . ucfirst($_SESSION['username'])
                 . " has invited you to join the “{$companyName}” team on Schedulizer.\n\n"
                 . "Your invite code is:\n\n"
                 . "$token\n\n"
                 . "Go to Schedulizer and paste it under “Enter a code you got” to join.\n\n"
                 . "— The Schedulizer Team";
        @mail($email, $subject, $body);


echo "<div class='link-box'>
        ✅ Invite token sent to “" . htmlspecialchars($email) . "”!
      </div>";

        }
    }
    ?>
    </div>

</body>

</html>