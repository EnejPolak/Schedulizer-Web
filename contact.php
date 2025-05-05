<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <title>Contact Us - Schedulizer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #edefef;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .contact-background-wrapper {
            position: relative;
            z-index: 0;
            overflow: hidden;
        }


        .background-blobs {
            position: fixed;
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
            opacity: 0.4;
            mix-blend-mode: multiply;
            animation: blobPulse 10s ease-in-out infinite;
        }

        .blob.blue {
            width: 1000px;
            height: 1000px;
            background: #3A82F7;
            top: -200px;
            left: -200px;
        }

        .blob.lightblue {
            width: 1000px;
            height: 1000px;
            background: #00C2FF;
            bottom: -150px;
            right: -150px;
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

        .contact-section {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 160px auto;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 194, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .contact-section h2 {
            font-size: 36px;
            text-align: center;
            color: #222831;
            margin-bottom: 30px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 14px 16px;
            border: 2px solid #cce8ff;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            resize: vertical;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #3A82F7;
            box-shadow: 0 0 8px rgba(0, 194, 255, 0.4);
            outline: none;
        }

        .contact-form textarea {
            min-height: 120px;
        }

        .contact-btn {
            align-self: center;
            padding: 16px 48px;
            font-size: 20px;
            font-weight: bold;
            color: #007aad;
            background: linear-gradient(135deg, #ffffff, #d9f3ff);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .contact-btn::before {
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

        .contact-btn:hover::before {
            left: 0;
        }

        .contact-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 194, 255, 0.6);
        }

        @media (max-width: 600px) {
            .contact-section {
                margin: 120px 20px;
                padding: 30px 20px;
            }

            .contact-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="contact-background-wrapper">
        <!-- Animirano ozadje -->
        <div class="background-blobs">
            <div class="blob blue"></div>
            <div class="blob lightblue"></div>
        </div>

        <!-- Kontaktni obrazec -->
        <section class="contact-section">
            <h2>Contact Us</h2>
            <form class="contact-form" method="post" action="#">
                <input type="text" name="name" placeholder="Your Name" required />
                <input type="email" name="email" placeholder="Your Email" required />
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit" class="contact-btn">Send Message</button>
            </form>
        </section>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>