<?php
session_start();
require 'db_connect.php';
include 'toolbar.php';

// Preveri vlogo
$role = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $role = $stmt->fetchColumn();
}

if (!in_array($role, ['admin', 'moderator'])) {
    header("Location: calendar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Invite Teammates</title>

    <!-- Google Fonts for consistent typography -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            font-family: 'Mukta', sans-serif;
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
            font-family: 'Anton', sans-serif;
            font-size: 30px;
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
            font-family: 'Mukta', sans-serif;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn {
            margin-top: 20px;
            padding: 12px 24px;
            background: linear-gradient(to right, #00c2ff, #3a82f7);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: white;
            transition: 0.3s;
            font-family: 'Mukta', sans-serif;
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
            font-family: 'Mukta', sans-serif;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['invite_email'])) {
            $email = htmlspecialchars(trim($_POST['invite_email']));
            $fakeToken = bin2hex(random_bytes(8));
            $inviteLink = "https://schedulizer.eu/register.php?invite=$fakeToken";
            echo "
            <div class='link-box'>
                🔗 Invite Link:<br><strong>$inviteLink</strong>
            </div>";
        }
        ?>
    </div>

</body>

</html>