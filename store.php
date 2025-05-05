<?php include 'navbar.php'; ?>

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

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7ff, #f0f9ff);
            color: #222;
        }

        .store-container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 100px auto;
            padding: 40px 20px;
        }

        .store-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .store-header h1 {
            font-size: 42px;
            color: #3A82F7;
            margin-bottom: 10px;
        }

        .store-header p {
            font-size: 18px;
            color: #555;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .plan-card {
            background: white;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
            position: relative;
        }

        .plan-card:hover {
            transform: translateY(-5px);
        }

        .plan-card h3 {
            font-size: 24px;
            color: #3A82F7;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 28px;
            font-weight: bold;
            color: #00C2FF;
            margin-bottom: 20px;
        }

        .plan-features {
            list-style: none;
            padding-left: 0;
            margin-bottom: 30px;
        }

        .plan-features li {
            margin-bottom: 10px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .plan-features li i {
            color: #3A82F7;
        }

        .plan-btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #00C2FF, #3A82F7);
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 12px;
            transition: background 0.3s ease;
        }

        .plan-btn:hover {
            background: linear-gradient(135deg, #009dd9, #2a6bd1);
        }

        .badge {
            position: absolute;
            top: -15px;
            right: 20px;
            background: #3A82F7;
            color: white;
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 20px;
        }

        .highlight-plan {
            border: 2px solid #00C2FF;
        }


        .store-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, #00C2FF40, transparent),
                radial-gradient(circle at 80% 70%, #3A82F740, transparent);
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .store-bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            animation: floatBubble 20s infinite ease-in-out;
            opacity: 0.3;
            mix-blend-mode: screen;
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(100vh) scale(0.5);
            }

            100% {
                transform: translateY(-10vh) scale(1);
            }
        }
    </style>
</head>

<body>

    <div class="store-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">

        <!-- Mehurčki -->
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
                <a href="#" class="plan-btn">Start Free</a>
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
                <a href="#" class="plan-btn">Upgrade to Pro</a>
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
                <a href="#" class="plan-btn">Go Max</a>
            </div>
        </div>
    </div>



    <?php include 'footer.php'; ?>
</body>


</html>