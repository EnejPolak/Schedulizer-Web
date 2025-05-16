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
    SELECT 
      u.username,
      u.user_role,
      u.phone,
      u.email,         -- dodano
      u.avatar         -- dodano
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Members | Schedulizer</title>
    <!-- Font imports -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #0284c7;
            --accent-color: #38bdf8;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #94a3b8;
            --bg-light: #ffffff;
            --bg-dark: #0f172a;
            --bg-card: #ffffff;
            --bg-input: #f1f5f9;
            --bg-hover: #f8fafc;
            --transition-speed: 0.3s;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius-sm: 6px;
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --sidebar-width: 260px;
        }

        body.dark {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --accent-color: #818cf8;
            --bg-card: #1e293b;
            --bg-input: #0f172a;
            --bg-hover: #1e293b;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
            transition: all var(--transition-speed) ease;
        }

        body.dark {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        #main-content {
            padding: 2rem;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .team-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Team stats */
        .team-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 150px;
            box-shadow: var(--shadow-md);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Members grid */
        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .member-card {
            background-color: var(--bg-card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all var(--transition-speed) ease;
            position: relative;
        }

        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card-banner {
            height: 80px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            position: relative;
        }

        .card-content {
            padding: 3rem 1.5rem 1.5rem;
        }

        .member-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid var(--bg-card);
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--bg-card);
            object-fit: cover;
        }

        .member-name {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .member-role {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            margin-bottom: 1rem;
            text-align: center;
            width: 100%;
        }

        .member-details {
            margin-top: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .detail-icon {
            width: 24px;
            margin-right: 0.75rem;
            color: var(--text-muted);
        }

        .detail-text {
            color: var(--text-dark);
        }

        body.dark .detail-text {
            color: var(--text-light);
        }

        .role-admin {
            background-color: #7c3aed;
        }

        .role-moderator {
            background-color: #0891b2;
        }

        .role-user {
            background-color: #2563eb;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: var(--bg-card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-desc {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .team-stats {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .stat-card {
                width: 100%;
                max-width: 300px;
            }

            .members-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark mode overrides */
        body.dark .member-card {
            background-color: var(--bg-card);
        }

        body.dark .member-avatar {
            border-color: var(--bg-card);
        }

        body.dark .empty-state {
            background-color: var(--bg-card);
        }
    </style>
</head>

<body class="<?php echo $lightMode ? 'light' : 'dark'; ?>">
    <div id="main-content">
        <div class="team-container">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title"><?= htmlspecialchars($companyName) ?> Team</h1>
                <p class="page-subtitle">Meet your colleagues and team members</p>
            </div>

            <!-- Team Stats or Empty State -->
            <?php if (!empty($members)): ?>
                <div class="team-stats">
                    <div class="stat-card">
                        <div class="stat-value"><?= count($members) ?></div>
                        <div class="stat-label">Team Members</div>
                    </div>
                    <?php
                    $counts = array_count_values(array_column($members, 'user_role'));
                    $admins = $counts['admin'] ?? 0;
                    $mods   = $counts['moderator'] ?? 0;
                    ?>
                    <div class="stat-card">
                        <div class="stat-value"><?= $admins ?></div>
                        <div class="stat-label">Administrators</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $mods ?></div>
                        <div class="stat-label">Moderators</div>
                    </div>
                </div>

                <!-- Members Grid -->
                <div class="members-grid">
                    <?php foreach ($members as $m):
                        $parts = explode('.', $m['username']);
                        $name  = ucfirst($parts[0]) . (isset($parts[1]) ? ' ' . ucfirst($parts[1]) : '');
                        $phone = $m['phone'] ?: 'Not provided';
                        $cls   = 'role-' . strtolower($m['user_role']);
                    ?>
                        <div class="member-card">
                            <div class="card-banner"></div>
                            <img src="<?= htmlspecialchars($m['avatar'] ?? 'assets/images/default_avatar.png') ?>"
                                alt="<?= htmlspecialchars($name) ?>"
                                class="member-avatar">
                            <div class="card-content">
                                <h3 class="member-name"><?= htmlspecialchars($name) ?></h3>
                                <span class="member-role <?= $cls ?>">
                                    <?= ucfirst(htmlspecialchars($m['user_role'])) ?>
                                </span>
                                <div class="member-details">
                                    <div class="detail-item">
                                        <i class="fas fa-envelope detail-icon"></i>
                                        <span class="detail-text"><?= htmlspecialchars($m['email']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-phone detail-icon"></i>
                                        <span class="detail-text"><?= htmlspecialchars($phone) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users empty-icon"></i>
                    <h3 class="empty-title">No team members found</h3>
                    <p class="empty-desc">Your company doesn’t have any members yet.</p>
                    <?php if (in_array($role, ['admin', 'moderator'], true)): ?>
                        <a href="invite.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Invite Team Members
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>