<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="sl">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Schedulizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fbff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            color: #333;
        }

        .contact-background-wrapper {
            position: relative;
            z-index: 0;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
            filter: blur(120px);
            opacity: 0.3;
            mix-blend-mode: multiply;
            transition: all 0.5s ease;
        }

        .blob.blue {
            width: 800px;
            height: 800px;
            background: #3A82F7;
            top: -200px;
            left: -200px;
            animation: blobFloat 20s ease-in-out infinite alternate;
        }

        .blob.lightblue {
            width: 800px;
            height: 800px;
            background: #00C2FF;
            bottom: -200px;
            right: -200px;
            animation: blobFloat 15s ease-in-out infinite alternate-reverse;
        }

        .blob.accent {
            width: 400px;
            height: 400px;
            background: #9564FF;
            top: 30%;
            right: 10%;
            opacity: 0.15;
            animation: blobPulse 12s ease-in-out infinite;
        }

        @keyframes blobFloat {
            0% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(50px, 30px) scale(1.05);
            }

            100% {
                transform: translate(-50px, -30px) scale(0.95);
            }
        }

        @keyframes blobPulse {
            0% {
                transform: scale(1);
                opacity: 0.15;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.25;
            }

            100% {
                transform: scale(1);
                opacity: 0.15;
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            align-items: stretch;
        }

        .contact-info {
            flex: 1;
            min-width: 300px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transform: translateY(0);
            transition: all 0.3s ease;
            animation: fadeInLeft 1s ease-out forwards;
        }

        .contact-info:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(58, 130, 247, 0.1);
        }

        .contact-info h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #3A82F7;
            position: relative;
            display: inline-block;
        }

        .contact-info h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            border-radius: 3px;
        }

        .contact-info p {
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .contact-methods {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .contact-method {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        }

        .contact-method:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(58, 130, 247, 0.1);
        }

        .contact-method .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .contact-method .details {
            flex: 1;
        }

        .contact-method .details h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .contact-method .details p {
            font-size: 14px;
            margin: 0;
            color: #666;
        }

        .contact-form-container {
            flex: 1.2;
            min-width: 350px;
            padding: 40px;
            border-radius: 20px;
            background: white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            animation: fadeInRight 1s ease-out forwards;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .contact-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #e6f3ff, #ffffff);
            border-radius: 0 0 0 100%;
            z-index: 0;
        }

        .contact-form-container h2 {
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            z-index: 1;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .input-group {
            position: relative;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e6f3ff;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fcff;
            color: #444;
            font-family: 'Poppins', sans-serif;
        }

        .input-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            border-color: #3A82F7;
            box-shadow: 0 0 0 4px rgba(58, 130, 247, 0.1);
            outline: none;
            background: white;
        }

        .input-group label {
            position: absolute;
            left: 20px;
            top: 16px;
            color: #777;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus~label,
        .input-group textarea:focus~label,
        .input-group input:not(:placeholder-shown)~label,
        .input-group textarea:not(:placeholder-shown)~label {
            top: -10px;
            left: 15px;
            font-size: 12px;
            padding: 0 5px;
            background: white;
            border-radius: 4px;
            color: #3A82F7;
            font-weight: 500;
        }

        .contact-btn {
            align-self: center;
            margin-top: 20px;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(58, 130, 247, 0.3);
            position: relative;
            overflow: hidden;
        }

        .contact-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(58, 130, 247, 0.4);
        }

        .contact-btn:hover::after {
            left: -10%;
            opacity: 1;
        }

        .form-footer {
            margin-top: 40px;
            text-align: center;
            color: #777;
            font-size: 14px;
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
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        }

        .social-links a:hover {
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(58, 130, 247, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                flex-direction: column;
            }

            .contact-info,
            .contact-form-container {
                min-width: 100%;
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="contact-background-wrapper">
        <!-- Animated background -->
        <div class="background-blobs">
            <div class="blob blue"></div>
            <div class="blob lightblue"></div>
            <div class="blob accent"></div>
        </div>

        <div class="container">
            <!-- Contact info section -->
            <div class="contact-info">
                <h2>Get In Touch</h2>
                <p>Have questions about Schedulizer? Need help with your account? Our team is here to assist you with any inquiries you might have.</p>

                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="details">
                            <h3>Email Us</h3>
                            <p>support@schedulizer.com</p>
                        </div>
                    </div>

                    <div class="contact-method">
                        <div class="icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="details">
                            <h3>Call Us</h3>
                            <p>+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <div class="contact-method">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="details">
                            <h3>Visit Us</h3>
                            <p>1234 Schedulizer Way, Tech Park</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact form section -->
            <div class="contact-form-container">
                <h2>Send Us a Message</h2>
                <form class="contact-form" method="post" action="#">
                    <div class="input-group">
                        <input type="text" id="name" required placeholder=" ">
                        <label for="name">Your Name</label>
                    </div>

                    <div class="input-group">
                        <input type="email" id="email" required placeholder=" ">
                        <label for="email">Your Email</label>
                    </div>

                    <div class="input-group">
                        <input type="text" id="subject" required placeholder=" ">
                        <label for="subject">Subject</label>
                    </div>

                    <div class="input-group">
                        <textarea id="message" required placeholder=" "></textarea>
                        <label for="message">Your Message</label>
                    </div>

                    <button type="submit" class="contact-btn">
                        Send Message <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Or connect with us on social media</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add animation to form fields when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const inputGroups = document.querySelectorAll('.input-group');

            inputGroups.forEach((group, index) => {
                setTimeout(() => {
                    group.style.opacity = '0';
                    group.style.transform = 'translateY(20px)';
                    group.style.transition = 'all 0.3s ease';

                    setTimeout(() => {
                        group.style.opacity = '1';
                        group.style.transform = 'translateY(0)';
                    }, index * 100);
                }, 500);
            });

            // Interactive background blobs that follow mouse movement slightly
            const blobs = document.querySelectorAll('.blob');
            const wrapper = document.querySelector('.contact-background-wrapper');

            wrapper.addEventListener('mousemove', (e) => {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;

                blobs.forEach(blob => {
                    const speed = 0.05;
                    const blobX = (x - 0.5) * speed * window.innerWidth;
                    const blobY = (y - 0.5) * speed * window.innerHeight;

                    blob.style.transform = `translate(${blobX}px, ${blobY}px) scale(${1 + x * 0.1})`;
                });
            });
        });
    </script>

    <?php include 'footer.php'; ?>

</body>

</html>