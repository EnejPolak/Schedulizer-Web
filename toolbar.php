<?php
session_start();
ob_start();

if (!isset($pdo)) {
    require_once 'db_connect.php';
}

$role = null;
if (isset($_SESSION['user_id']) && $pdo instanceof PDO) {
    try {
        $stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $role = $stmt->fetchColumn();
    } catch (Exception $e) {
        $role = null;
    }
}
?>

<!-- STYLES -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css" />
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
        margin: 0 20px 50px 20px;
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
</style>

<!-- TOOLBAR HTML -->
<div id="toolbar-container">
    <div id="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-title">SHEDULIZER</div>
            <a href="aboutme.php" id="link-o-meni">ABOUT ME</a>
            <a href="calendar.php" id="link-koledar">CALENDAR</a>
            <a href="settings.php" id="link-nastavitve">SETTINGS</a>
            <a href="group.php" id="link-skupina">GROUP</a>
            <?php if ($role === 'admin' || $role === 'moderator'): ?>
                <a href="invite.php" id="link-invite">INVITE USERS</a>
            <?php endif; ?>
        </div>

        <button class="tutorial-button-bottom" onclick="openTutorialModal()">📘 How it works?</button>

        <div class="sidebar-bottom">
            <div class="user-card" id="userCard">
                <div class="user-info-row" id="userInfo">
                    <img src="380d2bf3-4656-40f6-bbfc-1f9d67c308ad.png" alt="User Avatar">
                    <div class="user-name">
                        <?php
                        if (isset($_SESSION['username'])) {
                            $parts = explode('.', $_SESSION['username']);
                            $ime = ucfirst($parts[0]);
                            $priimek = isset($parts[1]) ? ucfirst($parts[1]) : '';
                            echo "$ime $priimek";
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

<!-- Modal -->
<div class="tutorial-modal" id="tutorialModal" style="display:none;">
    <div class="tutorial-modal-content">
        <h2>What would you like to see?</h2>
        <button onclick="startToolbarTour()">🧭 Just the Toolbar</button>
        <button onclick="goToCalendar()">📆 Calendar Tutorial</button>
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

        logoutText.addEventListener("click", (e) => {
            e.stopPropagation();
            window.location.href = "logout.php";
        });

        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('#sidebar a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        window.openTutorialModal = function() {
            document.getElementById("tutorialModal").style.display = "flex";
        }

        window.startToolbarTour = function() {
            document.getElementById("tutorialModal").style.display = "none";
            introJs().start();
        }

        window.goToCalendar = function() {
            window.location.href = "calendar.php?tutorial=true";
        }
    });
</script>

<?php ob_end_flush(); ?>