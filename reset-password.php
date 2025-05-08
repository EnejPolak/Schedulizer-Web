<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Schedulizer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7ff, #f0f9ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .reset-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reset-container h2 {
            color: #3A82F7;
            margin-bottom: 25px;
            font-size: 28px;
            text-align: center;
        }

        .reset-container input[type="email"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .reset-container input[type="email"]:focus {
            border-color: #00C2FF;
            outline: none;
        }

        .reset-container button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #00C2FF, #3A82F7);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reset-container button:hover {
            background: linear-gradient(135deg, #009dd9, #2a6bd1);
            transform: scale(1.03);
        }

        .reset-container button i {
            margin-right: 8px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(to right, #00C2FF, #3A82F7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            text-align: center;
        }

        .background-bubbles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: floatBubble 20s ease-in-out infinite;
        }

        .blue {
            background: #3A82F7;
            width: 300px;
            height: 300px;
            top: -50px;
            left: -80px;
        }

        .lightblue {
            background: #00C2FF;
            width: 250px;
            height: 250px;
            bottom: -60px;
            right: -60px;
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* ✅ POPUP STYLES */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .popup-box {
            background: #fff;
            padding: 30px 25px;
            border-radius: 16px;
            max-width: 360px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease forwards;
            position: relative;
        }

        .popup-box p {
            margin-top: 15px;
            font-size: 16px;
            color: #2b8a60;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: #999;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <!-- background bubbles -->
    <div class="background-bubbles">
        <div class="bubble blue"></div>
        <div class="bubble lightblue"></div>
    </div>

    <!-- main form -->
    <div class="reset-container">
        <div class="logo">SCHEDULIZER</div>
        <h2>Reset Password</h2>

        <form id="resetForm">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit"><i class="fas fa-envelope"></i>Send Reset Link</button>
        </form>
    </div>

    <!-- ✅ popup -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <i class="fas fa-check-circle" style="color: #2b8a60; font-size: 40px;"></i>
            <p>Reset link sent successfully.<br>Please check your inbox. </p>
        </div>
    </div>

    <script>
        const form = document.getElementById('resetForm');
        const popup = document.getElementById('popupOverlay');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // prevent actual submission
            popup.style.display = 'flex';
        });

        function closePopup() {
            popup.style.display = 'none';
        }
    </script>

</body>

</html>