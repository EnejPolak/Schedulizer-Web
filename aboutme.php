<?php
// aboutme.php
session_start();
include 'toolbar.php';

// Predpostavljamo, da imamo ID uporabnika v seji
if (!isset($_SESSION['user_id'])) {
    die("Nisi prijavljen.");
}

$user_id = $_SESSION['user_id'];

// Pridobimo podatke uporabnika iz baze
$query = "SELECT ime, priimek, email, telefon FROM uporabniki WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Uporabnik ni najden.");
}
?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

    <style>
        /* Dodamo osnovni stil za glavni content */
        #main-content {
            margin-left: 260px;
            padding: 20px;
            font-family: 'Mukta', sans-serif;
            background: linear-gradient(135deg, #3A82F7 0%, #00C2FF 100%);
            min-height: 100vh;
        }

        .user-info {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .user-info h2 {
            margin-top: 0;
        }

        .user-info p {
            font-size: 18px;
            margin: 10px 0;
        }

        .reset-password-btn {
            background-color: #00C2FF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .reset-password-btn:hover {
            background-color: #008bb5;
        }
    </style>
</head>

<body>

    <!-- Glavna vsebina About Me -->
    <div id="main-content">
        <div class="user-info">
            <h2><?php echo htmlspecialchars($user['ime'] . ' ' . $user['priimek']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Geslo:</strong> ****** <button class="reset-password-btn">Ponastavi geslo</button></p>
            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($user['telefon']); ?></p>
        </div>
    </div>

</body>

</html>