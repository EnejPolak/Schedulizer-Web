<?php
session_start();
require 'db_connect.php';

// ————————————————————————————
// 1) Auth + role + company
// ————————————————————————————
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// fetch current user role
$stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

// fetch this user’s company
$c = $pdo->prepare("SELECT company_id FROM user_companies WHERE user_id = ? LIMIT 1");
$c->execute([$_SESSION['user_id']]);
$companyId = $c->fetchColumn();

// only admins/moderators/Premium may invite
if (! in_array($role, ['admin', 'moderator', 'Premium'], true)) {
    header('Location: calendar.php');
    exit;
}

// fetch company name
$c2 = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
$c2->execute([$companyId]);
$companyName = $c2->fetchColumn() ?: '';

// ————————————————————————————
// 2) Handle POST: insert into `invites` + send mail
// ————————————————————————————
$inviteLink = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email   = filter_var(trim($_POST['invite_email']), FILTER_VALIDATE_EMAIL);
    $selRole = $_POST['role'] ?? 'user';

    if (!$email) {
        $_SESSION['invite_error'] = "Prosimo, vnesite veljaven e-poštni naslov.";
    } else {
        // generate a 16-char token
        $token   = bin2hex(random_bytes(8));
        $expires = date('Y-m-d H:i:s', strtotime('+7 days'));

        // insert into invites
        $ins = $pdo->prepare("
            INSERT INTO invites
              (token, email, role, invited_by, company_id, expires_at, created_at)
            VALUES
              (?,     ?,     ?,    ?,          ?,          ?,          NOW())
        ");
        $ins->execute([
            $token,
            $email,
            $selRole,
            $_SESSION['user_id'],
            $companyId,
            $expires
        ]);

        // send exactly that token (no register link) plus login link
        $subject = "Vaš Schedulizer dostopni token";
        $message = "Pozdravljeni,\n\n"
            . "Vaš dostopni token je:\n\n"
            . "    $token\n\n"
            . "Ta token shranite, nato se prijavite tukaj:\n"
            . "https://schedulizer.eu/login.php\n\n"
            . "Lep pozdrav,\n"
            . "Ekipa Schedulizer";

        $headers =
            "From: no-reply@schedulizer.com\r\n" .
            "Reply-To: no-reply@schedulizer.com\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n";

        if (! mail($email, $subject, $message, $headers)) {
            $_SESSION['invite_error'] = "Vabilo ni bilo poslano po e-pošti, vendar je bil token ustvarjen.";
        } else {
            $_SESSION['invite_success'] = true;
        }

        // build a link only so you can show it on screen if you want
        $base       = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
            . $_SERVER['HTTP_HOST']
            . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $inviteLink = "$base/login.php";

        header("Location: invite.php?link=" . urlencode($inviteLink));
        exit;
    }
}

// pick up link from GET if present
if (!empty($_GET['link'])) {
    $inviteLink = urldecode($_GET['link']);
}

// ————————————————————————————
// 3) Fetch recent invitations
// ————————————————————————————
$invStmt = $pdo->prepare("
    SELECT i.email,
           i.role,
           i.created_at,
           u.username AS invited_by_username
      FROM invites i
      JOIN users   u ON u.id = i.invited_by
     WHERE i.company_id = ?
     ORDER BY i.created_at DESC
     LIMIT 10
");
$invStmt->execute([$companyId]);
$invitations = $invStmt->fetchAll(PDO::FETCH_ASSOC);

// ————————————————————————————
// 4) Display page (include toolbar before HTML)
// ————————————————————————————
include 'toolbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invite Team Members | Schedulizer</title>
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

        .invite-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Alert message styling */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            animation: fadeInDown 0.5s ease;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            border-left: 4px solid var(--warning-color);
            color: var(--warning-color);
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid var(--danger-color);
            color: var(--danger-color);
        }

        .alert-icon {
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card styling */
        .card {
            background-color: var(--bg-card);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            position: relative;
        }

        .card-header-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            vertical-align: middle;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            display: inline-block;
            vertical-align: middle;
        }

        .card-body {
            padding: 2rem;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: var(--border-radius-sm);
            border: 1px solid #e2e8f0;
            background-color: var(--bg-input);
            color: var(--text-dark);
            transition: all var(--transition-speed) ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        body.dark .form-control {
            border-color: #2d3748;
            color: var(--text-light);
        }

        body.dark .form-control:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        /* Role selector */
        .role-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .role-option {
            flex: 1;
            background-color: var(--bg-input);
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius-sm);
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        body.dark .role-option {
            border-color: #2d3748;
        }

        .role-option:hover {
            background-color: var(--bg-hover);
        }

        .role-option.active {
            border-color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.05);
        }

        body.dark .role-option.active {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .role-option.active .role-icon {
            color: var(--primary-color);
        }

        .role-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .role-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Buttons */
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

        /* Link box */
        .link-box {
            background-color: var(--bg-input);
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-top: 1.5rem;
            position: relative;
            border: 1px solid #e2e8f0;
        }

        body.dark .link-box {
            border-color: #2d3748;
        }

        .link-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
        }

        .link-content {
            font-size: 0.95rem;
            word-break: break-all;
            padding: 0.5rem;
            background-color: var(--bg-card);
            border-radius: var(--border-radius-sm);
            border: 1px dashed #e2e8f0;
            margin-bottom: 0.5rem;
            font-family: monospace;
        }

        body.dark .link-content {
            border-color: #2d3748;
        }

        .copy-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: color var(--transition-speed) ease;
        }

        .copy-btn:hover {
            color: var(--primary-color);
        }

        /* Recent invites section */
        .recent-invites {
            margin-top: 2rem;
        }

        .invite-list {
            margin-top: 1rem;
        }

        .invite-item {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        body.dark .invite-item {
            border-color: #2d3748;
        }

        .invite-item:last-child {
            border-bottom: none;
        }

        .invite-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }

        .invite-details {
            flex: 1;
        }

        .invite-email {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .invite-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .invite-role {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 100px;
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .invite-role.admin {
            background-color: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }

        .invite-role.moderator {
            background-color: rgba(8, 145, 178, 0.1);
            color: #0891b2;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .role-selector {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body class="dark">
    <div id="main-content">
        <div class="invite-container">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Invite Team Members</h1>
                <p class="page-subtitle">
                    Grow your team by inviting colleagues to join <strong><?= htmlspecialchars($companyName) ?></strong>
                </p>
            </div>

            <!-- Alerts -->
            <?php if (!empty($_SESSION['invite_success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    Invite sended succesful!
                </div>
                <?php unset($_SESSION['invite_success']); ?>
            <?php elseif (!empty($_SESSION['invite_error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <?= htmlspecialchars($_SESSION['invite_error']) ?>
                </div>
                <?php unset($_SESSION['invite_error']); ?>
            <?php endif; ?>

            <!-- Invitation Form -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-plus card-header-icon"></i>
                    <span class="card-title">Send an Invitation</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="invite.php">
                        <div class="form-group">
                            <label for="invite_email" class="form-label">E-poštni naslov kolega</label>
                            <input type="email"
                                id="invite_email"
                                name="invite_email"
                                class="form-control"
                                placeholder="Vnesite email"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Izberi vlogo</label>
                            <div class="role-selector">
                                <div class="role-option active" data-role="user">
                                    <div class="role-icon"><i class="fas fa-user"></i></div>
                                    <div class="role-title">User</div>
                                    <div class="role-desc">Samo svoj urnik</div>
                                </div>
                                <div class="role-option" data-role="moderator">
                                    <div class="role-icon"><i class="fas fa-user-cog"></i></div>
                                    <div class="role-title">Moderator</div>
                                    <div class="role-desc">Upravljanje urnikov ekipe</div>
                                </div>
                                <?php if ($role === 'admin'): ?>
                                    <div class="role-option" data-role="admin">
                                        <div class="role-icon"><i class="fas fa-user-shield"></i></div>
                                        <div class="role-title">Admin</div>
                                        <div class="role-desc">Poln dostop</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="role" id="selected_role" value="user">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Pošlji vabilo
                        </button>
                    </form>

                    <!-- Show invite link if generated -->
                    <?php if ($inviteLink): ?>
                        <div class="link-box">
                            <div class="link-title"><i class="fas fa-link"></i> Povezava za registracijo</div>
                            <div class="link-content" id="invite-link"><?= htmlspecialchars($inviteLink) ?></div>
                            <button class="copy-btn" onclick="copyLink()" title="Kopiraj"><i class="fas fa-copy"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Invitations -->
            <div class="card recent-invites">
                <div class="card-header">
                    <i class="fas fa-history card-header-icon"></i>
                    <span class="card-title">Zadnja vabila</span>
                </div>
                <div class="card-body">
                    <?php if ($invitations): ?>
                        <div class="invite-list">
                            <?php foreach ($invitations as $inv):
                                $parts = explode('.', $inv['invited_by_username']);
                                $inviter = ucfirst($parts[0]) . (isset($parts[1]) ? ' ' . ucfirst($parts[1]) : '');
                                $dt = (new DateTime($inv['created_at']))->format('M j, Y');
                            ?>
                                <div class="invite-item">
                                    <div class="invite-icon"><i class="fas fa-envelope"></i></div>
                                    <div class="invite-details">
                                        <div class="invite-email"><?= htmlspecialchars($inv['email']) ?></div>
                                        <div class="invite-meta">
                                            Poslal: <?= htmlspecialchars($inviter) ?>, <?= $dt ?>
                                        </div>
                                    </div>
                                    <div class="invite-role <?= strtolower($inv['role']) ?>">
                                        <?= ucfirst(htmlspecialchars($inv['role'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox empty-icon"></i>
                            <p>Še ni poslanih vabil.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle role-option active
        document.querySelectorAll('.role-option').forEach(opt => {
            opt.addEventListener('click', () => {
                document.querySelectorAll('.role-option').forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                document.getElementById('selected_role').value = opt.dataset.role;
            });
        });

        function copyLink() {
            const txt = document.getElementById('invite-link').textContent.trim();
            navigator.clipboard.writeText(txt);
        }
    </script>
</body>

</html>