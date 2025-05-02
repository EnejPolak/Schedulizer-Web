<?php
include 'toolbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

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

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .reset-password-btn {
            background: linear-gradient(135deg, #ffffff, #d9f3ff);
            color: #007aad;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 4px 14px rgba(0, 194, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .reset-password-btn::before {
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

        .reset-password-btn:hover::before {
            left: 0;
        }

        .reset-password-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 194, 255, 0.6);
        }


        .input-wrapper {
            margin-left: 300px;
            /* Prestavi vse vnose desno, stran od sidebarja */
            max-width: 500px;
        }

        .input-wrapper label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            font-size: 16px;
            color: white;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
        }

        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>

<body>

    <div id="main-content">
        <div class="input-wrapper">
            <h2 style="color: white;">Full Name</h2>

            <label for="email">Email:</label>
            <input type="text" id="email" placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" id="password" placeholder="Enter your password">

            <button class="reset-password-btn">Reset Password</button>


            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" placeholder="Enter your phone number">
        </div>
    </div>


</body>

</html>