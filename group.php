<?php
include 'toolbar.php';
?>
<!-- group.php -->
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
    </style>
</head>

<body>
    <div id="main-content">
        <div class="container">
            <h1>Člani skupine</h1>
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