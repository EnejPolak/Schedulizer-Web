<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedulizer - Domov</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #edefef;
            overflow-x: hidden;
            font-family: 'Bebas Neue', sans-serif;
            height: 100vh;
            position: relative;
        }

        .background-blobs {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.5;
            mix-blend-mode: multiply;
        }

        .blob.blue {
            width: 1200px;
            height: 1200px;
            background: #3A82F7;
            top: -200px;
            left: -200px;
        }

        .blob.lightblue {
            width: 1200px;
            height: 1200px;
            background: #00C2FF;
            bottom: -200px;
            right: -200px;
        }

        .container {
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .circle {
            border-radius: 50%;
            width: 500px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: absolute;
            z-index: 2;
        }

        .blue {
            background-color: #3A82F7;
            top: 100px;
            left: 100px;
        }

        .lightblue {
            background-color: #00C2FF;
            bottom: 100px;
            right: 100px;
        }

        .circle.blue img {
            width: 80%;
            height: auto;
        }

        .circle.lightblue img {
            width: 105%;
            height: auto;
        }

        .arrow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .arrow svg {
            width: 100%;
            height: 100%;
        }

        .arrow path {
            stroke: #E63946;
            stroke-width: 6;
            fill: none;
            stroke-dasharray: 3000;
            stroke-dashoffset: 3000;
            animation: drawArrow 4s ease forwards, waveArrow 6s ease-in-out infinite;
            transform-origin: center;
        }

        @keyframes drawArrow {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes waveArrow {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes blobPulse {
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

        .blob {
            animation: blobPulse 10s ease-in-out infinite;
        }

        .hero-section {
            width: 100%;
            max-width: 1200px;
            margin: 100px auto 80px auto;
            padding: 60px 40px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            position: relative;
            z-index: 3;
            background: radial-gradient(circle at top left, rgba(58, 130, 247, 0.15) 0%, rgba(0, 194, 255, 0.1) 40%, rgba(237, 239, 239, 0.3) 100%);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }



        .hero-content {
            max-width: 700px;
        }

        .hero-section h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 64px;
            color: #222831;
            margin-bottom: 30px;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .hero-section p {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            color: #555;
            line-height: 1.7;
            font-weight: 400;
            margin-bottom: 20px;
        }


        /* Premium modra črta spodaj */
        .hero-content::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background-color: #3A82F7;
            margin-top: 30px;
            border-radius: 2px;
        }
    </style>
</head>

<body>

    <!-- Zamegljeni veliki krogi v ozadju -->
    <div class="background-blobs">
        <div class="blob blue"></div>
        <div class="blob lightblue"></div>
    </div>

    <!-- Glavni elementi -->
    <div class="container">
        <div class="circle blue">
            <img src="https://i.ibb.co/DgLLL1X5/planning-management-1-1.png" alt="Stari način urnika">
        </div>

        <div class="circle lightblue">
            <img src="https://i.ibb.co/HDbfNHVS/6b93585a-bd10-4bc8-a3ac-19b8ddd00382-1.png" alt="Novi način urnika">
        </div>

        <!-- SVG puščica, KI SE ZAČNE PRI LEVEM KROGU in zaključi pri DESNEM -->
        <div class="arrow">
            <svg viewBox="0 0 1400 600" xmlns="http://www.w3.org/2000/svg">
                <path d="
M 300 200  
q 30 -100, 80 0
q 50 100, 120 20
l 0 -100
l 40 0 
l -40 0
l 0 40
l 30 0
l -30 0
l 0 60
l 40 0
l 0 -50
l 0 20
a 10 10 0 0 1 20 0
a 10 10 0 0 0 -20 -5
l 0 35
l 40 0
m -10 -20
a 15 15 0 1 0 30 0
a 15 15 0 1 0 -30 0
l 0 20
l 40 0
l 0 -40
l 0 20
a 15 15 0 0 1 20 10
l 0 13
l 0 -13
a 20 100 0 0 1 20 10
l 100 0
a 50 50 0 0 1 0 -100
a 50 50 0 0 0 0 100
l 25 0
l 0 -35
l 0 10
a 10 10 0 0 0 -10 25
l 30 0
m -5 -20
a 15 15 0 1 0 30 0
a 15 15 0 1 0 -30 0
l 0 20
l 50 0
a 10 10 0 1 0 0 -20
a 10 10 0 1 1 0 -20
a 10 10 0 1 0 0 20
a 10 10 0 1 1 0 20
l 20 0
a 20 20 0 1 1 0 40
l -400 0
a 20 20 0 0 0 -20 20
l 0 100
a 20 20 0 0 0 20 20
l 50 0
l 0 -100
l -40 0
l 40 0
l 40 0
l -40 0
l 0 100
l 30 0
m -10 -20
a 15 15 0 1 0 30 0
a 15 15 0 1 0 -30 0
l 0 20
l 60 0
l 100 0
a 50 50 0 0 1 0 -100
a 50 50 0 0 0 0 100
l 10 0
l 0 -60
l 0 60
l 30 0
l 0 -35
l 0 10
a 10 10 0 0 0 -10 25
l 30 0
l 0 -40
l 0 20
a 10 10 0 0 1 20 0
a 10 10 0 0 0 -20 -5
l 0 25
l 30 0 
l 0 -35
l 0 35
l 20 0
l 0 -50
l 0 10
l 15 0
l -15 0
l -15 0
l 15 0
l 0 40
l 30 0
l -20 -20
l 20 20
l 20 -20
l -20 20
l -30 30
l 30 -30
l 150 0
                " />
            </svg>
        </div>

    </div>
    <!-- Glavni napis in opis -->
    <div class="hero-section">
        <div class="hero-content">
            <h1>Get More Organized — Make Your Work Easier with Us!</h1>
            <p>In today's rapidly evolving world, where every second counts, effective organization isn't just a benefit — it's a necessity. At Schedulizer, we empower you to manage your schedules effortlessly, streamline team communication, and eliminate the chaos of last-minute changes.</p>

            <p>Our intuitive platform helps you coordinate tasks, align your team's availability, and ensure that everyone is always on the same page. No more confusion, missed deadlines, or wasted time.</p>

            <p>Focus on what truly matters — growing your business, supporting your team, and delivering results. With Schedulizer, you don't just organize; you optimize success. Work smarter. Achieve more.</p>
        </div>
    </div>


</body>

</html>