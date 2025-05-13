<?php
include 'toolbar.php';

// Fetch this user’s company name
$pdo; // from toolbar.php you have $pdo
$stmt = $pdo->prepare("
  SELECT c.name
    FROM companies c
    JOIN user_companies uc ON uc.company_id = c.id
   WHERE uc.user_id = ?
   LIMIT 1
");
$stmt->execute([ $_SESSION['user_id'] ]);
$companyName = $stmt->fetchColumn() ?: 'Your Group';
?>
<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <title>Skupina</title>
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .user-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 12px;
            transition: transform 0.2s ease;
        }

        .user-card:hover {
            transform: scale(1.02);
            background: rgba(255, 255, 255, 0.25);
        }

        body.dark #main-content {
            background: radial-gradient(circle at top left, #1E1B2E, #140B2D, #0F0C1D);
            color: #C4B5FD;
            padding-top: 80px;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        body.dark .container {
            max-width: 800px;
            margin: 0 auto;
            background: #1C1B29;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.15);
        }

        body.dark .container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #6D28D9;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }

        body.dark .user-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        body.dark .user-card {
            background: linear-gradient(135deg, #2E1065, #4C1D95, #1C1B29);
            padding: 20px;
            border-radius: 12px;
            transition: transform 0.2s ease;
            color: #D8B4FE;
            box-shadow: 0 4px 10px rgba(124, 58, 237, 0.1);
        }

        body.dark .user-card:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #4C1D95, #7C3AED, #2A1A4F);
        }
    </style>
</head>

<body>
    <div id="main-content">
        <div class="container">
            <h1><?= htmlspecialchars($companyName) ?></h1>
            <div class="user-list">
                <!-- Tu se bodo kasneje dodali člani skupine -->
                <div class="user-card">Uporabnik 1</div>
                <div class="user-card">Uporabnik 2</div>
                <div class="user-card">Uporabnik 3</div>
            </div>
        </div>
    </div>
</body>

</html>