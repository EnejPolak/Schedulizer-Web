<?php
include 'theme.php';
// Always start the session before reading it
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Make sure we have our DB connection
if (!isset($pdo)) {
    require_once 'db_connect.php';
}

// Defaults
$role      = null;
$username  = '';
$avatarUrl = 'assets/images/default_avatar.png';

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


?>


<!-- STYLES -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<style>
    #toolbar-container #sidebar {
        font-family: 'Bebas Neue', sans-serif !important;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 240px;
        background: #002B5B;
        backdrop-filter: blur(15px);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 1000;
    }

    #toolbar-container #sidebar .sidebar-top {
        display: flex;
        flex-direction: column;
        padding-top: 30px;
    }

    #toolbar-container #sidebar .sidebar-title {
        text-align: center;
        color: #00C2FF;
        font-size: 30px;
        margin-bottom: 40px;
        font-family: 'Anton', sans-serif;
    }

    #toolbar-container #sidebar a {
        text-decoration: none;
        color: #FFF1D0;
        padding: 15px 30px;
        font-size: 24px;
        transition: all 0.3s ease;
    }

    #toolbar-container #sidebar a:hover {
        background-color: #3A82F7;
        color: white;
        border-radius: 10px;
        margin-left: 10px;
    }

    #toolbar-container #sidebar a.active {
        background-color: #00C2FF;
        color: #1e1e1e;
        border-radius: 10px;
        margin-left: 10px;
    }

    #toolbar-container #sidebar .tutorial-button-bottom {
        margin: auto 20px 50px 20px;
        font-size: 16px;
        background-color: #00C2FF;
        color: #002B5B;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #toolbar-container #sidebar .tutorial-button-bottom:hover {
        background-color: #3A82F7;
        color: white;
    }

    #toolbar-container #sidebar .sidebar-bottom {
        padding: 0 20px 20px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #toolbar-container #sidebar .user-card {
        background: linear-gradient(135deg, #00C2FF, #3A82F7);
        border-radius: 12px;
        padding: 10px;
        width: 100%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
    }

    #toolbar-container #sidebar .user-info-row {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    #toolbar-container #sidebar .user-card img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    #toolbar-container #sidebar .user-name {
        font-family: 'Mukta', sans-serif;
        font-weight: 600;
        color: white;
        font-size: 14px;
    }

    #toolbar-container #sidebar .logout-text {
        display: none;
        font-family: 'Mukta', sans-serif;
        font-size: 14px;
        font-weight: bold;
        color: white;
        padding-left: 2px;
        animation: fadeIn 0.3s ease;
        align-self: flex-start;
        cursor: pointer;
    }

    #toolbar-container #sidebar .user-card.clicked .logout-text {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tutorial-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        animation: fadeIn 0.4s ease;
    }

    .tutorial-modal-content {
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        width: 90%;
        max-width: 400px;
        color: #002B5B;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        position: relative;
    }

    .tutorial-modal-content h2 {
        font-size: 22px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .tutorial-modal-content button {
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 12px 20px;
        border: none;
        background: linear-gradient(to right, #3A82F7, #00C2FF);
        color: white;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s;
    }

    .tutorial-modal-content button:hover {
        background: linear-gradient(to right, #00C2FF, #3A82F7);
        transform: scale(1.03);
    }

    .tutorial-close {
        position: absolute;
        top: 12px;
        right: 15px;
        font-size: 22px;
        font-weight: bold;
        color: #555;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    body.dark #toolbar-container #sidebar {
        background: radial-gradient(circle at top left, #1E1B2E, #140B2D, #0F0C1D);
        border-right: 3px solid #7C3AED;
    }

    body.dark #toolbar-container #sidebar .sidebar-title {
        color: #6D28D9;
        /* temnejša vijolična */
        font-weight: 700;
        letter-spacing: 1px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        /* senčenje teksta */
    }

    body.dark #toolbar-container #sidebar a {
        color: #C4B5FD;
        transition: background 0.3s ease, color 0.3s ease;
    }

    body.dark #toolbar-container #sidebar a:hover {
        background-color: #2E1065;
        color: #D8B4FE;
        border-radius: 8px;
        margin-left: 10px;
    }

    body.dark #toolbar-container #sidebar a.active {
        background-color: #7C3AED;
        color: #1E1B2E;
    }

    body.dark #toolbar-container #sidebar .tutorial-button-bottom {
        background: linear-gradient(to right, #7C3AED, #5B21B6, #2E1065);
        color: #D1C4E9;
        font-weight: 600;
        border: none;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.25);
    }

    body.dark #toolbar-container #sidebar .tutorial-button-bottom:hover {
        background: linear-gradient(to right, #2E1065, #5B21B6, #7C3AED);
        transform: scale(1.04);
        color: #EDE9FE;
    }

    body.dark #toolbar-container #sidebar .user-card {
        background: linear-gradient(135deg, #2A1A4F, #4C1D95, #1C1B29);
        color: #C4B5FD;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(124, 58, 237, 0.15);
    }

    body.dark #toolbar-container #sidebar .user-name,
    body.dark #toolbar-container #sidebar .logout-text {
        color: #C4B5FD;
        font-weight: 500;
    }
</style>

<body class="<?= $lightMode ? 'light' : 'dark' ?>">

    <div id="toolbar-container">
        <div id="sidebar">
            <div class="sidebar-top">
                <div class="sidebar-title">SHEDULIZER</div>
                <a href="aboutme.php" id="link-o-meni">ABOUT ME</a>
                <a href="calendar.php" id="link-koledar">CALENDAR</a>
                <a href="settings.php" id="link-nastavitve">SETTINGS</a>
                <a href="group.php" id="link-skupina">GROUP</a>
                <?php if (in_array($role, ['admin','moderator','Premium'], true)): ?>
  <a href="invite.php" id="link-invite">INVITE USERS</a>
<?php endif; ?>

            </div>

            <button class="tutorial-button-bottom" onclick="openTutorialModal()">📘 How it works?</button>

            <div class="sidebar-bottom">
                <div class="user-card" id="userCard">
                    <div class="user-info-row" id="userInfo">
                        <img
                            src="<?= htmlspecialchars($avatarUrl) ?>"
                            alt="User Avatar"
                            style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
                        <div class="user-name">
                            <?php
                            if ($username !== '') {
                                $parts = explode('.', $username);
                                echo ucfirst($parts[0])
                                    . (isset($parts[1]) ? ' ' . ucfirst($parts[1]) : '');
                            } else {
                                echo "Guest";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="logout-text" id="logoutText">
                        <i class="fas fa-right-from-bracket"></i> <span>Log out</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal + JS untouched -->


    <!-- Modal -->
    <!-- Modal (OUTSIDE SIDEBAR!) -->
    <div class="tutorial-modal" id="tutorialModal">
        <div class="tutorial-modal-content">
            <span class="tutorial-close" onclick="closeTutorialModal()">×</span>
            <h2>What would you like to see?</h2>
            <button onclick="startToolbarTour()">⏱️ Just the Toolbar</button>
            <button onclick="goToCalendar()">📅 Calendar Tutorial</button>
        </div>
    </div>


    <!-- JS Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userCard = document.getElementById("userCard");
            const userInfo = document.getElementById("userInfo");
            const logoutText = document.getElementById("logoutText");

            userInfo.addEventListener("click", () => {
                userCard.classList.toggle("clicked");
            });

            logoutText.addEventListener("click", e => {
                e.stopPropagation();
                window.location.href = "logout.php";
            });

            document.querySelectorAll('#sidebar a').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('#sidebar a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });

        // ✅ ENA IZVEDBA FUNKCIJ – brez duplikatov
        function openTutorialModal() {
            document.getElementById("tutorialModal").style.display = "flex";
        }

        function closeTutorialModal() {
            document.getElementById("tutorialModal").style.display = "none";
        }

        function startToolbarTour() {
            closeTutorialModal();

            introJs().setOptions({
                steps: [{
                        element: document.querySelector('.sidebar-title'),
                        intro: "👋 Welcome to Schedulizer – your smart scheduling assistant!"
                    },
                    {
                        element: document.querySelector('#link-o-meni'),
                        intro: "🗂️ Personal info and password settings."
                    },
                    {
                        element: document.querySelector('#link-koledar'),
                        intro: "📅 Your weekly calendar and shift editor."
                    },
                    {
                        element: document.querySelector('#link-nastavitve'),
                        intro: "⚙️ Change your app language or theme."
                    },
                    {
                        element: document.querySelector('#link-skupina'),
                        intro: "👥 View your group or team members."
                    }
                ],
                showProgress: true,
                showBullets: true,
                nextLabel: "Next",
                prevLabel: "Back",
                doneLabel: "Finish"
            }).start();
        }

        function goToCalendar() {
            window.location.href = "calendar.php?tutorial=true";
        }
    </script>
    <?php ob_end_flush(); ?>