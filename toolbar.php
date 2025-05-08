<!-- Google Fonts (lahko daš v glavo glavne strani!) -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet" />

<!-- Intro.js -->
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<style>
    /* Style iz tvoje glave – ohranjeno enako */
    body {
        font-family: 'Bebas Neue', sans-serif;
    }

    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 240px;
        background: #002B5B;
        backdrop-filter: blur(15px);
        box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 30px 0;
        z-index: 1000;
    }

    .sidebar-top {
        display: flex;
        flex-direction: column;
    }

    .sidebar-title {
        text-align: center;
        color: #00C2FF;
        font-size: 30px;
        margin-bottom: 40px;
        font-family: 'Anton', sans-serif;
    }

    #sidebar a {
        text-decoration: none;
        color: #FFF1D0;
        padding: 15px 30px;
        font-size: 24px;
        transition: all 0.3s ease;
    }

    #sidebar a:hover {
        background-color: #3A82F7;
        color: white;
        border-radius: 10px;
        margin-left: 10px;
    }

    #sidebar a.active {
        background-color: #00C2FF;
        color: #1e1e1e;
        border-radius: 10px;
        margin-left: 10px;
    }

    .tutorial-button-bottom {
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

    .tutorial-button-bottom:hover {
        background-color: #3A82F7;
        color: white;
    }

    .tutorial-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }

    .tutorial-modal-content {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        max-width: 400px;
        font-family: 'Mukta', sans-serif;
    }

    .tutorial-modal-content h2 {
        margin-bottom: 20px;
        font-size: 22px;
        color: #002B5B;
    }

    .tutorial-modal-content button {
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 10px;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        background: #00C2FF;
        color: #fff;
        transition: background 0.3s;
    }

    .tutorial-modal-content button:hover {
        background: #3A82F7;
    }
</style>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-title" data-intro="Welcome to Schedulizer – your smart scheduling assistant!" data-step="1">SHEDULIZER</div>
        <a href="aboutme.php" id="link-o-meni" data-intro="Learn more about the app or its creator here." data-step="2">ABOUT ME</a>
        <a href="calendar.php" id="link-koledar" data-intro="Manage your availability and shifts in the weekly calendar." data-step="3">CALENDAR</a>
        <a href="settings.php" id="link-nastavitve" data-intro="Customize your account preferences and settings here." data-step="4">SETTINGS</a>
        <a href="group.php" id="link-skupina" data-intro="Check who’s in your team or group." data-step="5">GROUP</a>
    </div>

    <button class="tutorial-button-bottom" onclick="openTutorialModal()">📘 How it works?</button>
</div>

<!-- Tutorial Modal -->
<div class="tutorial-modal" id="tutorialModal">
    <div class="tutorial-modal-content">
        <h2>What would you like to see?</h2>
        <button onclick="startToolbarTour()">🧭 Just the Toolbar</button>
        <button onclick="goToCalendar()">📆 Calendar Tutorial</button>
    </div>
</div>

<script>
    const links = document.querySelectorAll('#sidebar a');
    links.forEach(link => {
        link.addEventListener('click', function() {
            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function openTutorialModal() {
        document.getElementById("tutorialModal").style.display = "flex";
    }

    function startToolbarTour() {
        document.getElementById("tutorialModal").style.display = "none";
        introJs().setOptions({
            steps: [{
                    element: document.querySelector('.sidebar-title'),
                    intro: "👋 Welcome to Schedulizer – your smart scheduling assistant!"
                },
                {
                    element: document.querySelector('#link-o-meni'),
                    intro: "📄 Personal info and password settings."
                },
                {
                    element: document.querySelector('#link-koledar'),
                    intro: "📆 Your weekly calendar and shift editor."
                },
                {
                    element: document.querySelector('#link-nastavitve'),
                    intro: "⚙️ Change your app language or theme."
                },
                {
                    element: document.querySelector('#link-skupina'),
                    intro: "👥 View your group or team members."
                }
            ]
        }).start();
    }

    function goToCalendar() {
        window.location.href = "calendar.php?tutorial=true";
    }

    function startFullTour() {
        document.getElementById("tutorialModal").style.display = "none";
        introJs().start();
    }
</script>