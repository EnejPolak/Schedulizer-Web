<?php
// toolbar.php
?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toolbar</title>

    <!-- Povezava do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

    <style>
        /* Nastavitve za celo stran */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Bebas Neue', sans-serif;
            background: #f4f4f4;
        }

        /* Stranski Toolbar */
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
            padding-top: 30px;
            z-index: 1000;
        }

        /* Naslov na vrhu Toolbara */
        .sidebar-title {
            text-align: center;
            color: #00C2FF;
            font-size: 30px;
            margin-bottom: 40px;
            font-family: 'Anton', sans-serif;
        }

        /* Povezave v Toolbaru */
        #sidebar a {
            text-decoration: none;
            color: #FFF1D0;
            padding: 15px 30px;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        /* Hover efekt */
        #sidebar a:hover {
            background-color: #3A82F7;
            color: white;
            border-radius: 10px;
            margin-left: 10px;
        }

        /* Aktivna povezava */
        #sidebar a.active {
            background-color: #00C2FF;
            color: #1e1e1e;
            border-radius: 10px;
            margin-left: 10px;
        }

        /* Glavna vsebina */
        #main-content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Primer sekcij */
        section {
            padding: 50px 0;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <!-- Stranski Toolbar -->
    <div id="sidebar">
        <div class="sidebar-title">SHEDULIZER</div>
        <a href="aboutme.php" id="link-o-meni">ABOUT ME</a>
        <a href="calendar.php" id="link-koledar">CALENDAR</a>
        <a href="settings.php" id="link-nastavitve">SETTINGS</a>
        <a href="group.php" id="link-skupina">GROUP</a>
    </div>




    <!-- JavaScript za aktivni link -->
    <script>
        // Ko klikneš na link, se označi kot aktiven
        const links = document.querySelectorAll('#sidebar a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

</body>

</html>