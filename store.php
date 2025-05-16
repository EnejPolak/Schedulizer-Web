<?php
session_start();

// 0) If they just clicked “upgrade”…
if (!empty($_SESSION['user_id']) && isset($_GET['upgrade'])) {
    // sanitize & whitelist:
    $newRole = $_GET['upgrade'] === 'Premium' ? 'Premium' : null;
    if ($newRole) {
        require_once 'db_connect.php';
        $stmt = $pdo->prepare("UPDATE users SET user_role = ? WHERE id = ?");
        $stmt->execute([$newRole, $_SESSION['user_id']]);
        // also update the session so toolbar.php sees it right away:
        $_SESSION['user_role'] = $newRole;
    }
    // send them into the app:
    header('Location: calendar.php');
    exit;
}

// 1) Now the normal “must be logged in” guard:
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2) Only now include your navbar and start outputting HTML…
include 'navbar.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedulizer Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #e9f5ff, #f5faff);
            color: #222;
            overflow-x: hidden;
        }

        main {
            flex: 1;
            padding-top: 30px;
            padding-bottom: 60px;
        }

        .store-container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 80px auto;
            padding: 40px;
        }

        .store-header {
            text-align: center;
            margin-bottom: 80px;
            animation: fadeInUp 0.8s ease-out;
        }

        .store-header h1 {
            font-size: 48px;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            font-weight: 700;
        }

        .store-header h1::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 4px;
        }

        .store-header p {
            font-size: 18px;
            color: #555;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
            perspective: 1000px;
        }

        .plan-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            animation-delay: calc(var(--order) * 0.2s);
        }

        .plan-card:nth-child(1) {
            --order: 1;
        }

        .plan-card:nth-child(2) {
            --order: 2;
        }

        .plan-card:nth-child(3) {
            --order: 3;
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .plan-card:hover {
            transform: translateY(-10px) rotateX(3deg) rotateY(3deg);
            box-shadow: 0 20px 40px rgba(58, 130, 247, 0.15);
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .plan-card:hover::before {
            opacity: 1;
        }

        .plan-card h3 {
            font-size: 28px;
            margin-bottom: 16px;
            position: relative;
            display: inline-block;
            font-weight: 600;
        }

        .plan-card h3::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: #f0f0f0;
            bottom: -8px;
            left: 0;
            border-radius: 2px;
        }

        .basic h3 {
            color: #3A82F7;
        }

        .pro h3 {
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .max h3 {
            background: linear-gradient(135deg, #00C2FF, #00d4ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .plan-price {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
        }

        .price-period {
            font-size: 16px;
            color: #777;
            font-weight: 400;
        }

        .basic .plan-price {
            color: #3A82F7;
        }

        .pro .plan-price {
            color: #00C2FF;
        }

        .max .plan-price {
            color: #00d4ff;
        }

        .plan-features {
            list-style: none;
            padding-left: 0;
            margin-bottom: 40px;
        }

        .plan-features li {
            margin-bottom: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #444;
        }

        .plan-features li i.fa-check {
            color: #4CAF50;
            font-size: 18px;
        }

        .plan-features li i.fa-times {
            color: #ff5252;
            font-size: 18px;
        }

        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: rgba(76, 175, 80, 0.1);
            flex-shrink: 0;
        }

        .feature-icon.negative {
            background-color: rgba(255, 82, 82, 0.1);
        }

        .plan-btn {
            display: block;
            width: 100%;
            padding: 15px 0;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            color: white;
        }

        .plan-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(58, 130, 247, 0.9), rgba(0, 194, 255, 0.9));
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 1;
        }

        .plan-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 194, 255, 0.9), rgba(58, 130, 247, 0.9));
            z-index: -2;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .plan-btn:hover::before {
            opacity: 0;
        }

        .plan-btn:hover::after {
            opacity: 1;
        }

        .plan-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(58, 130, 247, 0.3);
        }

        .badge {
            position: absolute;
            top: -25px;
            /* Še bolj povečan odmik od vrha */
            right: 30px;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: white;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(58, 130, 247, 0.3);
            animation: pulse 2s infinite;
            z-index: 100;
            /* Še višji z-index */
        }

        /* Dodatno: poskrbite, da ima kartica relativno pozicijo za pravilno prikaz značke */
        .plan-card {
            /* Obstoječe lastnosti */
            position: relative;
            z-index: 1;
            overflow: visible;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 5px 15px rgba(58, 130, 247, 0.3);
            }

            50% {
                box-shadow: 0 5px 25px rgba(58, 130, 247, 0.5);
            }

            100% {
                box-shadow: 0 5px 15px rgba(58, 130, 247, 0.3);
            }
        }

        .highlight-plan {
            z-index: 2;
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 123, 255, 0.15);
            border: 2px solid rgba(0, 194, 255, 0.2);
        }

        .highlight-plan:hover {
            transform: translateY(-10px) scale(1.05) rotateX(3deg) rotateY(3deg);
        }

        .store-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(0, 194, 255, 0.1), transparent 800px),
                radial-gradient(circle at 80% 70%, rgba(58, 130, 247, 0.1), transparent 800px);
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .store-bubble {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            animation: floatBubble 20s infinite ease-in-out;
            box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.3);
            opacity: 0.15;
            backdrop-filter: blur(2px);
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(100vh) translateX(0) scale(0.5);
            }

            50% {
                transform: translateY(50vh) translateX(30px) scale(0.8);
            }

            100% {
                transform: translateY(-10vh) translateX(0) scale(1);
            }
        }

        /* Most popular tag animation */
        .ribbon {
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            overflow: hidden;
            pointer-events: none;
        }

        .ribbon::before,
        .ribbon::after {
            position: absolute;
            z-index: -1;
            content: '';
            display: block;
            border: 5px solid #00C2FF;
            border-top-color: transparent;
            border-right-color: transparent;
        }

        .ribbon::before {
            top: 0;
            left: 0;
        }

        .ribbon::after {
            bottom: 0;
            right: 0;
        }

        .ribbon span {
            position: absolute;
            display: block;
            width: 225px;
            padding: 8px 0;
            background-color: #00C2FF;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            font-size: 13px;
            text-transform: uppercase;
            text-align: center;
            right: -25px;
            top: 30px;
            transform: rotate(45deg);
        }

        @media (max-width: 768px) {
            .store-container {
                padding: 20px;
            }

            .plans-grid {
                gap: 30px;
            }

            .plan-card {
                padding: 30px 20px;
            }

            .store-header h1 {
                font-size: 36px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="store-background">
            <div class="store-bubble" style="width: 120px; height: 120px; left: 10%; animation-delay: 0s;"></div>
            <div class="store-bubble" style="width: 60px; height: 60px; left: 30%; animation-delay: 3s;"></div>
            <div class="store-bubble" style="width: 80px; height: 80px; left: 50%; animation-delay: 6s;"></div>
            <div class="store-bubble" style="width: 100px; height: 100px; left: 70%; animation-delay: 9s;"></div>
            <div class="store-bubble" style="width: 90px; height: 90px; left: 85%; animation-delay: 12s;"></div>
        </div>

        <div class="store-container">
            <div class="store-header">
                <h1>Choose the Right Plan for Your Team</h1>
                <p>Compare features and find the perfect fit for your needs.</p>
            </div>

            <div class="plans-grid">
                <!-- Basic Plan -->
                <div class="plan-card">
                    <h3>Basic</h3>
                    <div class="plan-price">Free</div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> Mark availability</li>
                        <li><i class="fas fa-check"></i> Swap shifts</li>
                        <li><i class="fas fa-times" style="color: red;"></i> Admin view</li>
                        <li><i class="fas fa-times" style="color: red;"></i> Notifications</li>
                    </ul>
                    <a href="store.php?upgrade=Premium" class="plan-btn">Start Free</a>
                </div>

                <!-- Pro Plan -->
                <div class="plan-card highlight-plan">
                    <span class="badge">Popular</span>
                    <h3>Pro</h3>
                    <div class="plan-price">€12/month</div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> Everything in Basic</li>
                        <li><i class="fas fa-check"></i> Admin tools</li>
                        <li><i class="fas fa-check"></i> Weekly reports</li>
                        <li><i class="fas fa-check"></i> Email notifications</li>
                    </ul>
                    <a href="store.php?upgrade=Premium" class="plan-btn">Upgrade to Pro</a>
                </div>

                <!-- Max Plan -->
                <div class="plan-card">
                    <h3>Max</h3>
                    <div class="plan-price">€25/month</div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> All Pro features</li>
                        <li><i class="fas fa-check"></i> Team analytics</li>
                        <li><i class="fas fa-check"></i> Priority support</li>
                        <li><i class="fas fa-check"></i> Full integration</li>
                    </ul>
                    <a href="store.php?upgrade=Premium" class="plan-btn">Go Max</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>