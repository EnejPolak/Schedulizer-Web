<?php
include 'theme.php';
// Začnemo sejo pred branjem
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Zagotovimo povezavo z bazo
if (!isset($pdo)) {
    require_once 'db_connect.php';
}

// Privzete vrednosti
$role      = null;
$username  = '';
$avatarUrl = 'assets/images/default_avatar.png';

// Pridobimo podatke uporabnika iz seje
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("
        SELECT username, user_role, avatar
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        $username = $u['username'] ?? '';
        $role     = $u['user_role'] ?? null;
        if (!empty($u['avatar'])) {
            $avatarUrl = $u['avatar'];
        }
    }
}

// Določimo aktivno stran
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Zunanje knjižnice -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<style>
    :root {
        --sidebar-width: 260px;
        --primary-color: #2563eb;
        --secondary-color: #0284c7;
        --accent-color: #38bdf8;
        --text-light: #f8fafc;
        --text-dark: #1e293b;
        --bg-light: #1e293b;
        --bg-dark: #0f172a;
        --bg-dark-secondary: #1e293b;
        --transition-speed: 0.3s;
        --shadow-subtle: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-strong: 0 10px 25px rgba(0, 0, 0, 0.2);
        --border-radius: 12px;
    }

    body.dark {
        --primary-color: #6366f1;
        --secondary-color: #4f46e5;
        --accent-color: #818cf8;
    }

    #sidebar-container {
        font-family: 'Montserrat', sans-serif;
    }

    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--bg-dark) 0%, var(--bg-dark-secondary) 100%);
        box-shadow: var(--shadow-strong);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 1000;
        transition: all var(--transition-speed) ease;
    }

    body.light #sidebar,
    body.dark #sidebar {
        background: linear-gradient(180deg, var(--bg-dark) 0%, var(--bg-dark-secondary) 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    body.light .nav-link,
    body.light .nav-link i {
        color: #ffffff !important;
    }

    /* ohrani tudi aktivno stanje */
    body.light .nav-link.active {
        color: #ffffff !important;
    }

    /* LOGO */
    .sidebar-header {
        padding: 2rem 1.5rem 1.5rem;
        text-align: center;
    }

    .sidebar-logo {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.8rem;
        letter-spacing: 1px;
        background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        margin: 0;
    }

    body.light .sidebar-logo {
        text-shadow: none;
    }

    /* NAVIGACIJA */
    .nav-links {
        list-style: none;
        padding: 0 1rem;
        margin: 1rem 0;
    }

    .nav-item {
        margin-bottom: 0.5rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--text-light);
        padding: 0.85rem 1.25rem;
        border-radius: var(--border-radius);
        transition: all var(--transition-speed) ease;
        font-weight: 500;
        font-size: 0.95rem;
    }

    body.light .nav-link {
        color: var(--text-dark);
    }

    .nav-link i {
        margin-right: 1rem;
        font-size: 1.1rem;
        width: 1.5rem;
        text-align: center;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    body.light .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .nav-link.active {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        font-weight: 600;
        box-shadow: var(--shadow-subtle);
    }

    body.light .nav-link.active {
        color: white;
    }

    .nav-link.active i {
        transform: scale(1.2);
    }

    /* TUTORIAL GUMB */
    .tutorial-button {
        margin: 1rem 1.5rem;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 0.85rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-subtle);
    }

    .tutorial-button i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .tutorial-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-strong);
    }

    /* USER PANEL */
    .user-panel {
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .user-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-subtle);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all var(--transition-speed) ease;
    }

    body.light .user-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
    }

    .user-info {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-bottom: 0.5rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: var(--shadow-subtle);
    }

    .user-details {
        margin-left: 0.75rem;
    }

    .user-name {
        color: var(--text-light);
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .user-role {
        color: var(--accent-color);
        font-size: 0.75rem;
        margin: 0;
    }

    body.light .user-name,
    body.light .user-role {
        color: white;
    }

    /* ---- NOVO: skrij logout gumb ---- */
    .user-card .logout-button {
        display: none;
    }

    .user-card.clicked .logout-button {
        display: flex;
    }

    .logout-button {
        align-items: center;
        margin-top: 0.75rem;
        padding: 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        width: 100%;
        justify-content: center;
        color: var(--text-light);
    }

    .logout-button i {
        margin-right: 0.5rem;
    }

    /* Light mode: modro ozadje in bela barva teksta */
    body.light .user-card .logout-button {
        background-color: var(--secondary-color) !important;
        /* #0284c7 */
        color: white !important;
    }

    body.light .user-card .logout-button i {
        color: white !important;
    }

    body.light .user-card .logout-button:hover {
        background-color: var(--primary-color) !important;
        /* #2563eb */
        color: white !important;
    }


    body.dark .logout-button {
        /* namesto 0.1 opacity lahko uporabiš 0.2 ali celo trdno barvo */
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-dark);
    }

    /* Dark mode: hover efekt */
    body.dark .logout-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }


    /* Tutorial Modal */
    .tutorial-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        animation: fadeIn 0.4s ease;
    }

    .tutorial-modal-content {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        text-align: center;
        width: 90%;
        max-width: 400px;
        color: var(--text-dark);
        box-shadow: var(--shadow-strong);
        position: relative;
    }

    body.dark .tutorial-modal-content {
        background: var(--bg-dark-secondary);
        color: var(--text-light);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tutorial-modal-content h2 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .tutorial-modal-content button {
        display: block;
        width: 100%;
        margin: 0.75rem 0;
        padding: 0.85rem 1.25rem;
        border: none;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        box-shadow: var(--shadow-subtle);
    }

    .tutorial-modal-content button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-strong);
    }

    .tutorial-close {
        position: absolute;
        top: 1rem;
        right: 1.2rem;
        font-size: 1.5rem;
        color: var(--text-dark);
        cursor: pointer;
        transition: all var(--transition-speed) ease;
    }

    body.dark .tutorial-close {
        color: var(--text-light);
    }

    .tutorial-close:hover {
        transform: scale(1.1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Odzivnost za mobilne naprave */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.active {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: block;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            padding: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
    }

    .introjs-tooltip {
        background-color: #ffffff !important;
    }

    /* Črno besedilo za naslov in telo tooltipa */
    .introjs-tooltip .introjs-tooltipheader,
    .introjs-tooltip .introjs-tooltiptext {
        color: #000000 !important;
    }

    /* Tudi gumbi (Back/Next/Done) naj bodo črni s črno obrobo */
    .introjs-prevbutton,
    .introjs-nextbutton,
    .introjs-donebutton,
    .introjs-skipbutton {
        color: #000000 !important;
        border: 1px solid #000000 !important;
    }
</style>

<body class="<?= $lightMode ? 'light' : 'dark' ?>">

    <!-- Mobilni toggle -->
    <button class="sidebar-toggle d-md-none" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div id="sidebar-container">
        <div id="sidebar">
            <div>
                <!-- LOGO -->
                <div class="sidebar-header">
                    <h1 class="sidebar-logo" id="sidebar-logo">SCHEDULIZER</h1>
                </div>

                <!-- NAVIGACIJA -->
                <ul class="nav-links">
                    <li class="nav-item">
                        <a id="link-aboutme"
                            href="aboutme.php"
                            class="nav-link <?= $currentPage == 'aboutme.php' ? 'active' : '' ?>">
                            <i class="fas fa-user-circle"></i>
                            <span>About Me</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="link-calendar"
                            href="calendar.php"
                            class="nav-link <?= $currentPage == 'calendar.php' ? 'active' : '' ?>">
                            <i class="fas fa-calendar"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="link-settings"
                            href="settings.php"
                            class="nav-link <?= $currentPage == 'settings.php' ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="link-group"
                            href="group.php"
                            class="nav-link <?= $currentPage == 'group.php' ? 'active' : '' ?>">
                            <i class="fas fa-users"></i>
                            <span>Group</span>
                        </a>
                    </li>
                    <?php if ($role === 'admin' || $role === 'moderator'): ?>
                        <li class="nav-item">
                            <a id="link-invite"
                                href="invite.php"
                                class="nav-link <?= $currentPage == 'invite.php' ? 'active' : '' ?>">
                                <i class="fas fa-user-plus"></i>
                                <span>Invite Users</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <button class="tutorial-button" id="start-tutorial">
                    <i class="fas fa-book"></i>
                    <span>How it works?</span>
                </button>

                <div class="user-panel">
                    <div class="user-card">
                        <div class="user-info" id="userCardToggle">
                            <img src="<?= htmlspecialchars($avatarUrl) ?>"
                                alt="User Avatar"
                                class="user-avatar">
                            <div class="user-details">
                                <p class="user-name">
                                    <?php
                                    if ($username !== '') {
                                        $parts = explode('.', $username);
                                        echo ucfirst($parts[0])
                                            . (isset($parts[1]) ? ' ' . ucfirst($parts[1]) : '');
                                    } else {
                                        echo "Guest";
                                    }
                                    ?>
                                </p>
                                <?php if ($role): ?>
                                    <p class="user-role"><?= ucfirst($role) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button class="logout-button" id="logoutButton">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log out</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tutorial Modal -->
    <div class="tutorial-modal" id="tutorialModal">
        <div class="tutorial-modal-content">
            <span class="tutorial-close" id="close-tutorial">&times;</span>
            <h2>What would you like to see?</h2>
            <button id="tutorial-step-interface">
                <i class="fas fa-compass"></i> Interface Tutorial
            </button>
            <button id="tutorial-step-calendar">
                <i class="fas fa-calendar-alt"></i> Calendar Tutorial
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const userCard = document.querySelector(".user-card");
            const userInfo = document.getElementById("userCardToggle");
            const logoutBtn = document.getElementById("logoutButton");
            const tutorialBtn = document.getElementById("start-tutorial");
            const tutorialModal = document.getElementById("tutorialModal");
            const closeTutorial = document.getElementById("close-tutorial");
            const stepInterface = document.getElementById("tutorial-step-interface");
            const stepCalendar = document.getElementById("tutorial-step-calendar");

            // Toggle user dropdown
            userInfo.addEventListener("click", e => {
                e.stopPropagation();
                userCard.classList.toggle("clicked");
            });
            document.addEventListener("click", e => {
                if (!e.target.closest(".user-card")) {
                    userCard.classList.remove("clicked");
                }
            });
            logoutBtn.addEventListener("click", () => {
                window.location.href = "logout.php";
            });

            // Open / close tutorial modal
            tutorialBtn.addEventListener("click", () => {
                tutorialModal.style.display = "flex";
            });
            closeTutorial.addEventListener("click", () => {
                tutorialModal.style.display = "none";
            });

            // Start full toolbar tour
            stepInterface.addEventListener("click", () => {
                tutorialModal.style.display = "none";
                introJs().setOptions({
                    steps: [{
                            element: '#sidebar-logo',
                            intro: "👋 Welcome to Schedulizer – your smart scheduling assistant!"
                        },
                        {
                            element: '#link-aboutme',
                            intro: "🗂️ Your personal info and password settings."
                        },
                        {
                            element: '#link-calendar',
                            intro: "📅 Your weekly calendar and shift editor."
                        },
                        {
                            element: '#link-settings',
                            intro: "⚙️ Change app language or theme here."
                        },
                        {
                            element: '#link-group',
                            intro: "👥 View your group or team members."
                        }
                    ],
                    showProgress: true,
                    showBullets: true,
                    nextLabel: "Next",
                    prevLabel: "Back",
                    doneLabel: "Finish"
                }).start();
            });

            // Shortcut: go directly to calendar tutorial
            stepCalendar.addEventListener("click", () => {
                tutorialModal.style.display = "none";
                introJs().setOptions({
                    steps: [{
                        element: '#link-calendar',
                        intro: "📅 This is your calendar — click here to edit shifts."
                    }],
                    showBullets: false,
                    doneLabel: "Got it"
                }).start();
            });
        });
    </script>