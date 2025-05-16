<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Schedulizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #f2f4f8;
            color: #222;
        }

        .about-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 30px;
            text-align: center;
        }

        .about-section h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #3A82F7;
            animation: fadeIn 1s ease-in;
            position: relative;
            display: inline-block;
        }

        .about-section h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .about-section p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto 60px;
            line-height: 1.7;
            color: #555;
            animation: fadeIn 1.3s ease-in;
        }

        .founders-title {
            font-size: 28px;
            margin: 0 0 40px;
            color: #333;
            font-weight: 600;
            animation: fadeIn 1.5s ease-in;
        }

        .team-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            max-width: 900px;
            margin: 0 auto;
        }

        .team-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 300px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideUp 1s ease forwards;
            opacity: 0;
            position: relative;
            overflow: hidden;
        }

        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .team-card:hover::before {
            transform: scaleX(1);
        }

        .team-card img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid white;
            box-shadow: 0 10px 25px rgba(58, 130, 247, 0.2);
            transition: all 0.3s ease;
        }

        .team-card:hover img {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(58, 130, 247, 0.3);
        }

        .team-card h3 {
            margin: 15px 0 8px;
            color: #3A82F7;
            font-size: 22px;
            font-weight: 600;
        }

        .team-card .role {
            font-size: 16px;
            color: #00C2FF;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .team-card .bio {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .team-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(58, 130, 247, 0.15);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f7ff;
            color: #3A82F7;
            font-size: 18px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-links a:hover {
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: white;
            transform: translateY(-3px);
        }

        .co-founder-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(58, 130, 247, 0.3);
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
            filter: blur(120px);
            opacity: 0.3;
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
            backdrop-filter: blur(5px);
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
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(5deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        @media (max-width: 768px) {
            .team-container {
                gap: 30px;
            }

            .team-card {
                width: 100%;
                max-width: 340px;
            }

            .about-section h1 {
                font-size: 36px;
            }

            .founders-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Background elements -->
    <div class="about-background">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
        <div class="floating-circle circle1"></div>
        <div class="floating-circle circle2"></div>
        <div class="floating-circle circle3"></div>
    </div>

    <section class="about-section">
        <h1>The Minds Behind Schedulizer</h1>
        <p>Schedulizer was born from a simple idea: scheduling shouldn't be complicated. Our small team is dedicated to creating an intuitive platform that makes managing time effortless for individuals and businesses alike.</p>

        <h2 class="founders-title">Our Founders</h2>

        <div class="team-container">
            <div class="team-card" style="animation-delay: 0.2s;">
                <span class="co-founder-badge">Co-Founder</span>
                <img src="https://i.ibb.co/T0ZLj2j/avatar1.png" alt="Enej Polak">
                <h3>Enej Polak</h3>
                <div class="role">Front-end & Back-end Developer</div>
                <p class="bio">Enej leads our development efforts with expertise in both front-end and back-end technologies. His passion for creating seamless user experiences drives Schedulizer's intuitive interface and robust functionality.</p>
                <div class="social-links">
                    <a href="https://www.linkedin.com/in/enej-polak-095655276/"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://github.com/EnejPolak"><i class="fab fa-github"></i></a>
                    <a href="https://www.instagram.com/enej.polak/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="team-card" style="animation-delay: 0.4s;">
                <span class="co-founder-badge">Co-Founder</span>
                <img src="https://i.ibb.co/hRSVpJj/avatar2.png" alt="Marcel Rošer">
                <h3>Marcel Rošer</h3>
                <div class="role">Back-end Developer</div>
                <p class="bio">Marcel is the backbone of our server architecture. His expertise in database design and API development ensures Schedulizer runs smoothly and securely while handling complex scheduling operations with ease.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>