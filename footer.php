<head>
    <!-- Font Awesome for social icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<footer class="site-footer">
    <style>
        .site-footer {
            background-color: #0b0f19;
            color: #d8e1f5;
            padding: 60px 20px 20px;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
            z-index: 10;
        }

        /* Ozadje z bubble efekti */
        .footer-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .footer-bubbles .bubble {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.25;
            animation: floatBubble 18s ease-in-out infinite;
        }

        .footer-bubbles .blue {
            background: #3A82F7;
            width: 400px;
            height: 400px;
            bottom: -80px;
            left: -150px;
        }

        .footer-bubbles .lightblue {
            background: #00C2FF;
            width: 300px;
            height: 300px;
            top: -60px;
            right: -100px;
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.05);
            }

            100% {
                transform: translateY(0) scale(1);
            }
        }

        footer {
            position: relative;
            z-index: 10;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            max-width: 1200px;
            margin: auto;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .footer-col h3,
        .footer-col h4 {
            color: #ffffff;
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #d8e1f5;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col ul li a:hover {
            color: #00c2ff;
        }

        .footer-col p {
            margin-bottom: 10px;
        }

        .social-icons a {
            color: #00c2ff;
            font-size: 20px;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: scale(1.2);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #aaa;
            border-top: 1px solid #1d2230;
            padding-top: 20px;
            position: relative;
            z-index: 1;
        }

        .footer-logo-gradient {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, #00C2FF, #3A82F7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @import url('https://fonts.googleapis.com/css?family=Open+Sans');

        a {
            text-decoration: none;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
            padding: 0 0;
            align-items: center;
            justify-content: flex-start;
        }

        .fab {
            margin: auto;
        }

        .social {
            color: #FFF;
            transition: all 0.35s;
            transition-timing-function: cubic-bezier(0.31, -0.105, 0.43, 1.59);
        }

        .social:hover {
            text-shadow: 0px 5px 5px rgba(0, 0, 0, 0.3);
            transition: all ease 0.5s;
        }

        .facebook {
            color: #4267B2;
        }

        .instagram {
            color: transparent;
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-ico {
            display: flex;
            background-color: #FFF;
            width: 60px;
            height: 60px;
            line-height: 60px;
            margin: 0 5px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-radius: 20%;
            box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.1);
            opacity: 0.99;
            transition: background-color 2s ease-out;
        }

        .bg-ico:hover {
            box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.8);
        }

        #facebook:hover {
            background-color: #4267B2;
        }

        #instagram:hover {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        }

        .facebook:hover,
        .instagram:hover {
            color: #fff !important;
            -webkit-text-fill-color: #fff !important;
            transform: scale(1.3);
        }
    </style>

    <!-- Bubbles ozadje -->
    <div class="footer-bubbles">
        <div class="bubble blue"></div>
        <div class="bubble lightblue"></div>
    </div>

    <!-- Vsebina footra -->
    <div class="footer-container">
        <div class="footer-col">
            <h3 class="footer-logo-gradient">SCHEDULIZER</h3>
            <p>Smarter scheduling for modern teams.<br>Work simpler, grow faster.</p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <p>Email: support@schedulizer.com</p>
            <p>Phone: +1 (555) 123-4567</p>
        </div>

        <div class="footer-col">
            <h4>Follow Us</h4>
            <div class="wrapper">
                <a href="https://facebook.com" target="_blank">
                    <div class="bg-ico" id="facebook">
                        <i class="fab fa-facebook social facebook fa-2x"></i>
                    </div>
                </a>
                <a href="https://instagram.com" target="_blank">
                    <div class="bg-ico" id="instagram">
                        <i class="fab fa-instagram social instagram fa-2x"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2025 Schedulizer. All rights reserved.</p>
    </div>
</footer>