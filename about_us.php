<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us - Schedulizer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f2f4f8;
            color: #222;
        }

        .about-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
            text-align: center;
        }

        .about-section h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-family: 'Bebas Neue', sans-serif;
            animation: fadeIn 1s ease-in;
        }

        .about-section p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto 40px;
            line-height: 1.6;
            color: #555;
            animation: fadeIn 1.3s ease-in;
        }

        .team-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 30px 20px;
            width: 260px;
            text-align: center;
            transition: transform 0.3s ease;
            animation: slideUp 1s ease forwards;
            opacity: 0;
        }

        .team-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .team-card h3 {
            margin: 10px 0 5px;
            color: #3A82F7;
        }

        .team-card p {
            font-size: 14px;
            color: #777;
        }

        .team-card:hover {
            transform: translateY(-8px);
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .about-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.4;
            animation: pulseBlob 12s ease-in-out infinite;
            mix-blend-mode: multiply;
        }

        .blob1 {
            background: #3A82F7;
            top: -200px;
            left: -200px;
        }

        .blob2 {
            background: #00C2FF;
            bottom: -200px;
            right: -200px;
        }

        @keyframes pulseBlob {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .floating-circle {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            animation: float 10s ease-in-out infinite;
        }

        .circle1 {
            top: 20%;
            left: 15%;
            animation-delay: 0s;
        }

        .circle2 {
            top: 70%;
            left: 60%;
            animation-delay: 3s;
        }

        .circle3 {
            top: 40%;
            left: 80%;
            animation-delay: 5s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-30px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body>

    <!-- Insane ozadje -->
    <div class="about-background">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
        <div class="floating-circle circle1"></div>
        <div class="floating-circle circle2"></div>
        <div class="floating-circle circle3"></div>
    </div>

    <section class="about-section">
        <h1>Meet the Team Behind Schedulizer</h1>
        <p>We’re a small but passionate team focused on making scheduling smoother, smarter, and stress-free for businesses and individuals. From intuitive features to elegant design — we do it all.</p>

        <div class="team-container">
            <div class="team-card" style="animation-delay: 0.2s;">
                <img src="https://i.ibb.co/T0ZLj2j/avatar1.png" alt="Team Member 1">
                <h3>Anna Novak</h3>
                <p>Product Designer</p>
            </div>

            <div class="team-card" style="animation-delay: 0.4s;">
                <img src="https://i.ibb.co/hRSVpJj/avatar2.png" alt="Team Member 2">
                <h3>Luka Kranjc</h3>
                <p>Frontend Developer</p>
            </div>

            <div class="team-card" style="animation-delay: 0.6s;">
                <img src="https://i.ibb.co/Y2F7dk2/avatar3.png" alt="Team Member 3">
                <h3>Eva Šubic</h3>
                <p>Backend Engineer</p>
            </div>

            <div class="team-card" style="animation-delay: 0.8s;">
                <img src="https://i.ibb.co/PMsKcN4/avatar4.png" alt="Team Member 4">
                <h3>Nejc Potočnik</h3>
                <p>UX Researcher</p>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>