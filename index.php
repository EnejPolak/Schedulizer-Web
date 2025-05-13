<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
            width: 90%;
            height: auto;
            margin-left: 40px;
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
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: nowrap;
            gap: 40px;
            width: 100%;
            max-width: 1600px;
            margin: 100px auto;
            padding: 60px 40px;
            background: radial-gradient(circle at top left, rgba(58, 130, 247, 0.15), rgba(0, 194, 255, 0.1), rgba(237, 239, 239, 0.3));
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .hero-content {
            flex: 1 1 50%;
            max-width: 50%;
        }

        .hero-section h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            color: #222831;
            margin-bottom: 30px;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .hero-section p {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: #555;
            line-height: 1.7;
            font-weight: 400;
            margin-bottom: 20px;
        }

        .hero-content::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background-color: #3A82F7;
            margin-top: 30px;
            border-radius: 2px;
        }

        .demo-calendar {
            position: relative;
            flex: 1 1 45%;
            max-width: 45%;
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins',
                sans-serif;
            margin-left: auto;
            margin-right: auto;
            align-self: center;
        }

        .demo-calendar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #222;
        }

        .calendar-table {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            gap: 10px;
        }

        .day-header {
            text-align: center;
            font-weight: bold;
            padding: 8px;
            background: #e3e3e3;
            border-radius: 6px;
        }

        .time-label {
            font-weight: bold;
            text-align: left;
            padding-left: 6px;
            font-size: 14px;
        }

        .calendar-cell {
            height: 50px;
            background: #f0f0f0;
            border: 2px solid #ccc;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calendar-radio-options {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        input[type="radio"][value="can"]:checked {
            accent-color: green;
        }

        input[type="radio"][value="cant"]:checked {
            accent-color: red;
        }

        input[type="radio"][value="swap"]:checked {
            accent-color: orange;
        }

        .popup-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ffe0e0;
            color: #b30000;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 10;
            display: none;
        }

        /* Universal fade-in on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Typewriter-style heading */
        .typewrite {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            color: #222831;
            letter-spacing: 1px;
            line-height: 1.2;
            border-right: none;
            white-space: nowrap;
            overflow: hidden;
            animation: typing 3.2s steps(40, end) forwards, blink-caret 0.75s step-end infinite;
            margin-bottom: 30px;
        }

        /* Caret blinking */
        @keyframes blink-caret {

            from,
            to {
                border-color: transparent;
            }

            50% {
                border-color: #3A82F7;
            }
        }

        /* Typewriter paragraph fade-in */
        .type-paragraph {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: #555;
            line-height: 1.7;
            font-weight: 400;
            white-space: pre-wrap;
            overflow: hidden;
            min-height: 60px;
            opacity: 0;
            /* začne kot neviden */
            transition: opacity 0.3s ease-in;
        }


        /* Fade-in animation for paragraph */
        @keyframes fadeInText {
            to {
                opacity: 1;
            }
        }

        /* Slide-in from right */
        .slide-in-right {
            transform: translateX(100px);
            opacity: 0;
            transition: all 1s ease-out;
        }

        .reveal.active .slide-in-right {
            transform: translateX(0);
            opacity: 1;
        }



        .features-section {
            max-width: 1300px;
            margin: 100px auto 80px auto;
            padding: 40px 20px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        .features-section h2 {
            font-size: 36px;
            margin-bottom: 50px;
            color: #222831;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, opacity 0.8s ease, translate 0.8s ease;
            text-align: left;
            opacity: 0;
            transform: translateX(60px) scale(0.95);
            will-change: opacity, transform;
        }

        /* Reveal efekt, ko postane viden */
        .reveal.active .feature-card {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        /* Zaporedni delay za kaskadni prihod */
        .feature-card:nth-child(1) {
            transition-delay: 0.1s;
        }

        .feature-card:nth-child(2) {
            transition-delay: 0.25s;
        }

        .feature-card:nth-child(3) {
            transition-delay: 0.4s;
        }

        .feature-card:nth-child(4) {
            transition-delay: 0.55s;
        }

        .feature-card:nth-child(5) {
            transition-delay: 0.7s;
        }

        .feature-card:nth-child(6) {
            transition-delay: 0.85s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #3A82F7;
        }

        .feature-card p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }


        .pricing-section {
            max-width: 1300px;
            margin: 100px auto;
            padding: 40px 20px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        .pricing-section h2 {
            font-size: 36px;
            margin-bottom: 50px;
            color: #222831;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }

        .pricing-card {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            transition: transform 0.2s ease;
            text-align: left;
            height: 100%;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
        }

        .pricing-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #3A82F7;
            text-align: center;
        }

        .pricing-card .price {
            font-size: 28px;
            color: #00C2FF;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
        }

        .pricing-card ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .pricing-card ul li {
            margin-bottom: 12px;
            font-size: 16px;
            color: #444;
        }

        .pricing-btn {
            display: block;
            text-align: center;
            background: #00C2FF;
            color: white;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            margin-top: auto;
        }


        .pricing-btn:hover {
            background: #009ad6;
        }

        .popular {
            border: 2px solid #3A82F7;
        }

        .pricing-card-wrapper {
            position: relative;
            display: inline-block;
        }

        .pricing-card-wrapper .badge {
            position: absolute;
            top: -12px;
            right: 20px;
            /* premaknjeno z levo na desno */
            transform: none;
            z-index: 10;
            background: #3A82F7;
            color: white;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }



        .success-icon {
            color: #2ecc71;
            margin-right: 10px;
            font-size: 18px;
        }

        .error-icon {
            color: #e74c3c;
            margin-right: 10px;
            font-size: 18px;
        }

        .pricing-card ul li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            margin-bottom: 12px;
            color: #444;
        }

        .buy-button-wrapper {
            text-align: center;
            margin-top: auto;
            margin-bottom: 10px;
        }

        .buy-plan-btn {
            position: relative;
            overflow: hidden;
            padding: 12px 30px;
            font-size: 18px;
            border: none;
            border-radius: 12px;
            color: white;
            background: linear-gradient(45deg, #00C2FF, #3A82F7);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            animation: pulseBtn 2.5s infinite;
            width: 100%;
            max-width: 240px;
        }

        .buy-plan-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 194, 255, 0.7);
        }

        .buy-plan-btn::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 100%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            transition: width 0.4s ease, height 0.4s ease;
        }

        .buy-plan-btn:active::after {
            width: 200px;
            height: 200px;
            transition: 0s;
        }


        /* Valovanje za Pro gumb */
        .highlight-pro {
            position: relative;
            animation: pulseOutline 2s infinite ease-in-out;
            z-index: 1;
        }

        @keyframes pulseOutline {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 194, 255, 0.6);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(0, 194, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 194, 255, 0);
            }
        }


        .pricing-card {
            position: relative;
            overflow: hidden;
            padding: 40px 30px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            z-index: 1;
        }

        /* Animirano ozadje, ki "zalije" kartico */
        .pricing-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e6f4ff, #d2eeff);
            transition: left 0.4s ease;
            z-index: 0;
            border-radius: 16px;
        }

        .pricing-card:hover::before {
            left: 0;
        }

        .pricing-card * {
            position: relative;
            z-index: 1;
            transition: color 0.3s ease;
        }

        /* Hover efekti (kot si jih imel) */
        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 194, 255, 0.3);
        }

        .pricing-card:hover h3,
        .pricing-card:hover li {
            color: #222831;
        }

        .pricing-card:hover .buy-plan-btn {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 194, 255, 0.4);
        }



        .cta-section {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, #00c2ff0a, #3a82f712);
            border-radius: 20px;
            margin-top: 80px;
        }

        .cta-section h2 {
            font-size: 32px;
            color: #222831;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ffffff, #d9f3ff);
            color: #007aad;
            border: none;
            padding: 16px 36px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 14px;
            cursor: pointer;
            margin-top: 20px;
            box-shadow: 0 4px 16px rgba(0, 194, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .cta-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00c2ff, #3a82f7);
            transition: left 0.4s ease;
            z-index: -1;
        }

        .cta-btn:hover::before {
            left: 0;
        }

        .cta-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 6px 24px rgba(0, 194, 255, 0.6);
        }




        .faq-section {
            max-width: 800px;
            margin: 100px auto;
            padding: 0 20px;
            text-align: center;
        }

        .faq-section h2 {
            font-size: 36px;
            margin-bottom: 40px;
        }

        .faq-item {
            text-align: left;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            width: 100%;
            background: #f3faff;
            padding: 18px 20px;
            border: none;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            text-align: left;
            position: relative;
        }

        .faq-question::after {
            content: "+";
            position: absolute;
            right: 20px;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question::after {
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            background: #ffffff;
            padding: 0 20px;
            transition: max-height 0.4s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
            padding: 20px;
        }

        /* Animacija za FAQ naslov */
        .faq-title {
            font-size: 36px;
            color: #222831;
            opacity: 0;
            transform: scale(0.9) translateY(20px);
            transition: all 0.6s ease-out;
        }

        .faq-title.animate {
            opacity: 1;
            transform: scale(1) translateY(0);
        }





        .pricing-card {
            opacity: 0;
            transform: scale(0.8) rotateZ(5deg);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            will-change: transform, opacity;
        }

        /* Ko reveal + animacija */
        .reveal.active .pricing-card {
            opacity: 1;
            transform: scale(1) rotateZ(0deg);
        }

        .pricing-card.animate {
            opacity: 1;
            transform: scale(1) rotateZ(0deg);
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
            <img src="https://i.ibb.co/qLFRhZ5m/shedulizer.png" alt="Novi način urnika">
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
    <div class="hero-section reveal">
        <div class="hero-content">
            <h1 id="hero-title" class="typewrite"></h1>

            <p class="type-paragraph" data-text="In today's rapidly evolving world, where every second counts, effective organization isn't just a benefit — it's a necessity. At Schedulizer, we empower you to manage your schedules effortlessly, streamline team communication, and eliminate the chaos of last-minute changes."></p>

            <p class="type-paragraph" data-text="Our intuitive platform helps you coordinate tasks, align your team's availability, and ensure that everyone is always on the same page. No more confusion, missed deadlines, or wasted time."></p>

            <p class="type-paragraph" data-text="Focus on what truly matters — growing your business, supporting your team, and delivering results. With Schedulizer, you don't just organize; you optimize success. Work smarter. Achieve more."></p>
        </div>


        <div class="demo-calendar slide-in-right">
            <h2>Try Our Schedule Demo</h2>
            <div class="calendar-table">
                <div></div>
                <?php
                $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                foreach ($days as $i => $d) echo "<div class='day-header' id='day$i'>$d</div>";
                ?>

                <div class="time-label">7:00 – 15:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
                <div class="time-label">11:00 – 18:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
                <div class="time-label">15:00 – 22:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
            </div>

            <div class="calendar-radio-options">
                <label><input type="radio" name="availability" value="can"> Can</label>
                <label><input type="radio" name="availability" value="cant"> I can't</label>
                <label><input type="radio" name="availability" value="swap"> Swap</label>
            </div>

            <div id="swapErrorPopup" class="popup-error">
                ❌ You can only swap shifts you marked as "I can".
            </div>
        </div>
    </div>

    <section class="features-section reveal">
        <h2 id="features-title" class="typewrite"></h2>

        <div class="features-grid">
            <div class="feature-card">
                <h3>✅ Simple Shift Selection</h3>
                <p>Mark your availability with a single click – no spreadsheets, no confusion.</p>
            </div>
            <div class="feature-card">
                <h3>🔁 Smart Swap Requests</h3>
                <p>Request a shift swap by explaining why you can’t attend – clear, quick, and direct.</p>
            </div>
            <div class="feature-card">
                <h3>📢 Notify All Instantly</h3>
                <p>Everyone involved gets notified right away about swap requests and availability changes.</p>
            </div>
            <div class="feature-card">
                <h3>🛠️ Full Admin Control</h3>
                <p>Admins see who marked what and can remove or adjust entries with ease.</p>
            </div>
            <div class="feature-card">
                <h3>📱 Mobile-Friendly Interface</h3>
                <p>Designed to work beautifully on phones and tablets, wherever your team is.</p>
            </div>
            <div class="feature-card">
                <h3>⏱️ Save Time & Avoid Chaos</h3>
                <p>No more back-and-forth messages – just organized, visible shifts and decisions.</p>
            </div>
        </div>
    </section>


    <section class="pricing-section reveal">
        <h2 id="pricing-title" class="typewrite"></h2>
        <div class="pricing-grid">

            <!-- NORMAL PLAN -->
            <div class="pricing-card-wrapper">
                <div class="badge small">14-day free</div>
                <div class="pricing-card">
                    <h3>Normal</h3>
                    <ul>
                        <li><i class="fas fa-check-circle success-icon"></i> Mark "I can" / "I can't"</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Request swaps</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Vacation scheduling</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Group chat</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Earnings tracking</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> PDF/Excel export</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Integrations</li>
                    </ul>
                    <div class="buy-button-wrapper">
                        <a href="login.php" class="buy-plan-btn">Start Free Trial</a>
                    </div>
                </div>
            </div>

            <!-- PRO PLAN -->
            <div class="pricing-card-wrapper">
                <div class="badge">14-day free</div>
                <div class="pricing-card popular">
                    <h3>Pro</h3>
                    <ul>
                        <li><i class="fas fa-check-circle success-icon"></i> Everything in Normal</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Vacation scheduling</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Group chat</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Earnings tracking</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> PDF/Excel export</li>
                        <li><i class="fas fa-xmark-circle error-icon"></i> Integrations</li>
                    </ul>
                    <div class="buy-button-wrapper">
                        <a href="login.php" class="buy-plan-btn highlight-pro">Try Pro Free</a>
                    </div>
                </div>
            </div>

            <!-- MAX PLAN -->
            <div class="pricing-card-wrapper">
                <div class="pricing-card">
                    <h3>Max</h3>
                    <ul>
                        <li><i class="fas fa-check-circle success-icon"></i> Everything in Pro</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Real-time earnings tracking</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Admin insights & statistics</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Smart shift auto-suggestions</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Export to PDF & Excel</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Unlimited teams & roles</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Integrations (Google Calendar, Slack...)</li>
                        <li><i class="fas fa-check-circle success-icon"></i> Priority support</li>
                    </ul>
                    <div class="buy-button-wrapper">
                        <a href="login.php" class="buy-plan-btn">Contact Sales</a>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <section class="cta-section reveal">
        <h2 id="cta-title" class="typewrite" data-text="Start optimizing your team's schedule today"></h2>
        <p id="cta-subtitle" class="type-paragraph" data-text="No credit card required. Cancel anytime. Join hundreds of teams who save hours every week."></p>
        <a href="store.php" class="cta-btn">Get Started Now</a>
    </section>


    <section class="faq-section reveal" id="faq">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <div class="faq-item">
            <button class="faq-question">How does the 14-day trial work?</button>
            <div class="faq-answer">
                <p>You get full access to the plan’s features for 14 days. No credit card required. Cancel anytime.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question">Can I upgrade or downgrade later?</button>
            <div class="faq-answer">
                <p>Yes, you can change your plan anytime directly from your account dashboard.</p>
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question">Do I need to install anything?</button>
            <div class="faq-answer">
                <p>No installation required. Schedulizer runs directly in your browser, on any device.</p>
            </div>
        </div>
    </section>




    <script>
        let selectedAvailability = null;

        function getMonday(d) {
            const date = new Date(d);
            const day = date.getDay();
            const diff = date.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(date.setDate(diff));
        }

        function updateDemoWeek() {
            const currentDate = new Date();
            const monday = getMonday(currentDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let i = 0; i < 7; i++) {
                const day = new Date(monday);
                day.setDate(monday.getDate() + i);
                day.setHours(0, 0, 0, 0);

                const cell = document.getElementById('day' + i);
                if (!cell) continue;
                cell.style.background = (day.getTime() === today.getTime()) ? '#00C2FF' : '#e3e3e3';
                cell.style.color = (day.getTime() === today.getTime()) ? 'white' : 'black';
            }
        }

        document.querySelectorAll('input[name="availability"]').forEach(radio => {
            radio.addEventListener('change', function() {
                selectedAvailability = this.value;
            });
        });

        document.querySelectorAll('.calendar-cell').forEach(cell => {
            cell.addEventListener('click', function() {
                if (!selectedAvailability) return;

                const currentBg = window.getComputedStyle(this).backgroundColor;

                if (selectedAvailability === 'swap') {
                    if (currentBg !== 'rgb(195, 247, 195)') {
                        showSwapError();
                        return;
                    }
                }

                this.style.backgroundColor = '';
                this.style.borderColor = '#ccc';

                if (selectedAvailability === 'can') {
                    this.style.backgroundColor = '#c3f7c3';
                    this.style.borderColor = 'green';
                } else if (selectedAvailability === 'cant') {
                    this.style.backgroundColor = '#f7c3c3';
                    this.style.borderColor = 'red';
                } else if (selectedAvailability === 'swap') {
                    this.style.backgroundColor = '#fff3c3';
                    this.style.borderColor = 'orange';
                }
            });
        });


        function showSwapError() {
            const popup = document.getElementById('swapErrorPopup');
            popup.style.display = 'block';

            setTimeout(() => {
                popup.style.display = 'none';
            }, 2500);
        }


        updateDemoWeek();



        // FAQ toggle
        document.querySelectorAll('.faq-question').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.parentElement;
                item.classList.toggle('active');
            });
        });


        // Scroll reveal z enotnim observerjem
        (() => {
            const elements = document.querySelectorAll('.reveal');

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');

                        if (entry.target.querySelector('#hero-title')) {
                            startTypingAnimation();
                        }

                        if (entry.target.querySelector('#features-title')) {
                            startFeatureTyping();
                        }

                        if (entry.target.querySelector('#pricing-title')) {
                            startPricingTyping();
                        }

                        if (entry.target.querySelector('#cta-title')) {
                            startCtaTyping();
                        }

                        if (entry.target.querySelector('.faq-title')) {
                            entry.target.querySelector('.faq-title').classList.add('animate');
                        }


                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });

            elements.forEach(el => observer.observe(el));
        })();

        // HERO tipkanje
        function startTypingAnimation() {
            const titleText = "GET MORE ORGANIZED — MAKE YOUR WORK EASIER";
            const titleEl = document.getElementById("hero-title");
            const paragraphs = document.querySelectorAll(".type-paragraph");
            const typingSpeed = 35;
            let titleIndex = 0;

            if (titleEl.dataset.typed) return;

            titleEl.dataset.typed = "true";
            titleEl.textContent = "";

            function typeTitle() {
                if (titleIndex < titleText.length) {
                    titleEl.textContent += titleText.charAt(titleIndex);
                    titleIndex++;
                    setTimeout(typeTitle, typingSpeed);
                } else {
                    setTimeout(() => typeParagraphs(0), 500);
                }
            }

            function typeParagraphs(index) {
                if (index >= paragraphs.length) return;

                const p = paragraphs[index];
                const text = p.getAttribute("data-text");
                let charIndex = 0;
                p.textContent = "";
                p.style.opacity = 1;

                function typeChar() {
                    if (charIndex < text.length) {
                        p.textContent += text.charAt(charIndex);
                        charIndex++;
                        setTimeout(typeChar, typingSpeed);
                    } else {
                        setTimeout(() => typeParagraphs(index + 1), 400);
                    }
                }

                typeChar();
            }

            typeTitle();
        }

        // FEATURES tipkanje
        function startFeatureTyping() {
            const featureText = "Features That Make Scheduling Effortless";
            const featureEl = document.getElementById("features-title");
            const speed = 25;
            let i = 0;

            if (featureEl.dataset.typed) return;

            featureEl.dataset.typed = "true";
            featureEl.textContent = "";

            function typeChar() {
                if (i < featureText.length) {
                    featureEl.textContent += featureText.charAt(i);
                    i++;
                    setTimeout(typeChar, speed);
                }
            }

            typeChar();
        }

        function startPricingTyping() {
            const pricingText = "Choose Your Plan";
            const pricingEl = document.getElementById("pricing-title");
            const speed = 35;
            let i = 0;

            if (pricingEl.dataset.typed) return;

            pricingEl.dataset.typed = "true";
            pricingEl.textContent = "";

            function typeChar() {
                if (i < pricingText.length) {
                    pricingEl.textContent += pricingText.charAt(i);
                    i++;
                    setTimeout(typeChar, speed);
                }
            }

            typeChar();
        }


        function startCtaTyping() {
            const titleEl = document.getElementById("cta-title");
            const subtitleEl = document.getElementById("cta-subtitle");
            const titleText = titleEl.dataset.text;
            const subtitleText = subtitleEl.dataset.text;
            const speed = 35;
            let i = 0;

            if (titleEl.dataset.typed) return;
            titleEl.dataset.typed = "true";
            titleEl.textContent = "";

            function typeTitle() {
                if (i < titleText.length) {
                    titleEl.textContent += titleText.charAt(i);
                    i++;
                    setTimeout(typeTitle, speed);
                } else {
                    typeSubtitle();
                }
            }

            function typeSubtitle() {
                let j = 0;
                subtitleEl.textContent = "";
                subtitleEl.style.opacity = 1;

                function typeSubChar() {
                    if (j < subtitleText.length) {
                        subtitleEl.textContent += subtitleText.charAt(j);
                        j++;
                        setTimeout(typeSubChar, speed);
                    }
                }

                typeSubChar();
            }

            typeTitle();
        }




        if (entry.target.querySelector('#pricing-title')) {
            startPricingTyping();

            // Animacija za kartice (z zamikom)
            const cards = entry.target.querySelectorAll('.pricing-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.transitionDelay = `${index * 250}ms`;
                    card.classList.add('animate');
                }, 100); // rahla zakasnitev po revealu
            });
        }
    </script>



    <?php include 'footer.php'; ?>

</body>

</html>