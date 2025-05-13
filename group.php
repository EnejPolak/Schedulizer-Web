<?php
session_start();
require 'db_connect.php';

// 1) Redirect to login if not authenticated
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2) Figure out this user’s company
$stmt = $pdo->prepare("
    SELECT company_id
      FROM user_companies
     WHERE user_id = ?
     LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$companyId = $stmt->fetchColumn();

// if no company, bounce back
if (! $companyId) {
    header('Location: no_group.php');
    exit;
}

// 2a) Fetch the company’s name
$cstmt = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
$cstmt->execute([$companyId]);
$companyName = $cstmt->fetchColumn();

include 'toolbar.php';

// 3) Fetch all members of that company
$membersStmt = $pdo->prepare("
    SELECT u.username, u.user_role, u.phone
      FROM users u
      JOIN user_companies uc ON uc.user_id = u.id
     WHERE uc.company_id = ?
");
$membersStmt->execute([$companyId]);
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
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
.label {
  font-weight: 600;
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
       <h1>
        <?= htmlspecialchars($companyName ?? 'Your Company') ?> Team Members
      </h1>
      <div class="user-list">
        <?php foreach($members as $m): 
            // split username "first.last" into "First Last"
            $parts = explode('.', $m['username']);
            $displayName = ucfirst($parts[0]) . (isset($parts[1]) ? ' '.ucfirst($parts[1]) : '');
            $phone = !empty($m['phone']) ? htmlspecialchars($m['phone']) : '—';
        ?>
          <div class="user-card">
            <h3><?= htmlspecialchars($displayName) ?></h3>
            <p><span class="label">Role:</span> <?= htmlspecialchars($m['user_role']) ?></p>
            <p><span class="label">Phone:</span> <?= $phone ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>
</html>