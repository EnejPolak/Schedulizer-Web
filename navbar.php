<header id="navbar">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Resetiranje osnovnih stilov */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #121212;
            color: #ffffff;
            padding-top: 80px;
            /* Prostor za fiksni navbar */
        }

        /* Glavni navbar container */
        #navbar {
            font-family: 'Montserrat', sans-serif;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        /* Logo stilizacija */
        .nav-left {
            display: flex;
            align-items: center;
        }

        .nav-left a {
            text-decoration: none;
            display: flex;
            align-items: center;
            position: relative;
            padding-right: 10px;
        }

        .logo-container {
            position: relative;
            display: inline-block;
            margin-left: 20px;
        }

        .logo-container::before {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            background: radial-gradient(circle at center, rgba(58, 130, 247, 0.1) 0%, rgba(0, 194, 255, 0) 70%);
            opacity: 0;
            border-radius: 50%;
            transition: opacity 0.5s ease;
        }

        .nav-left a:hover .logo-container::before {
            opacity: 1;
        }

        .schedulizer-blue {
            font-weight: 700;
            font-size: 28px;
            color: #ffffff;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
        }

        .schedulizer-lightblue {
            font-weight: 700;
            font-size: 28px;
            color: #3A82F7;
            letter-spacing: 1px;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Animated glow effect */
        .nav-left a:hover .schedulizer-blue {
            color: #ffffff;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        .nav-left a:hover .schedulizer-lightblue {
            color: #00C2FF;
            text-shadow: 0 0 20px rgba(0, 194, 255, 0.7);
        }

        /* Navigacijski meni */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-right: 30px;
        }

        .nav-right a {
            text-decoration: none;
            color: #3A82F7;
            /* Modra barva za boljšo vidnost */
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 8px 4px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* Underline hover efekt */
        .nav-right a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            transform: translateX(-101%);
            transition: transform 0.3s cubic-bezier(0.65, 0.05, 0.36, 1);
            border-radius: 5px;
        }

        .nav-right a:hover {
            color: #00C2FF;
        }

        .nav-right a:hover::after {
            transform: translateX(0);
        }

        /* Active link style */
        .nav-right a.active {
            color: #00C2FF;
            font-weight: 600;
        }

        .nav-right a.active::after {
            transform: translateX(0);
            background: linear-gradient(90deg, #3A82F7, #00C2FF);
            box-shadow: 0 0 10px rgba(0, 194, 255, 0.5);
        }

        /* Login button special styling */
        .nav-right a:last-child {
            margin-left: 15px;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            color: #fff;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            position: relative;
            overflow: visible;
        }

        .nav-right a:last-child::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(135deg, #3A82F7, #00C2FF);
            border-radius: 33px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-right a:last-child:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.45);
        }

        .nav-right a:last-child:hover::before {
            opacity: 0.4;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.4;
            }

            70% {
                transform: scale(1.05);
                opacity: 0;
            }

            100% {
                transform: scale(1.1);
                opacity: 0;
            }
        }

        .nav-right a:last-child::after {
            display: none;
        }

        /* Scrolled navbar style */
        #navbar.scrolled {
            height: 70px;
            background: rgba(24, 24, 24, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(58, 130, 247, 0.1);
        }

        #navbar.scrolled .schedulizer-blue {
            font-size: 26px;
        }

        #navbar.scrolled .schedulizer-lightblue {
            font-size: 26px;
        }
    </style>

    <?php
    // Določanje imena trenutne strani (brez .php)
    $currentPage = basename($_SERVER['PHP_SELF'], ".php");
    ?>

    <div class="nav-left">
        <a href="index.php">
            <div class="logo-container">
                <span class="schedulizer-blue">SHEDU</span><span class="schedulizer-lightblue">LIZER</span>
            </div>
        </a>
    </div>



    <nav class="nav-right">
        <a href="index.php" class="<?= ($currentPage == 'index') ? 'active' : '' ?>">HOME</a>
        <a href="about_us.php" class="<?= ($currentPage == 'about_us') ? 'active' : '' ?>">ABOUT US</a>
        <a href="contact.php" class="<?= ($currentPage == 'contact') ? 'active' : '' ?>">CONTACT</a>
        <a href="store.php" class="<?= ($currentPage == 'store') ? 'active' : '' ?>">STORE</a>
        <a href="login.php" class="<?= ($currentPage == 'login') ? 'active' : '' ?>">LOGIN</a>
    </nav>
</header>

<script>
    // Scroll efekt za navbar
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobilni meni
    const menuToggle = document.querySelector('.menu-toggle');
    const navRight = document.querySelector('.nav-right');

    menuToggle.addEventListener('click', () => {
        navRight.classList.toggle('active');
    });

    // Zapri meni ob kliku na povezavo
    document.querySelectorAll('.nav-right a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                navRight.classList.remove('active');
            }
        });
    });

    // Dodaj sledenje ob premikanju miške za logo
    const logo = document.querySelector('.logo-container');

    document.addEventListener('mousemove', (e) => {
        if (window.innerWidth > 992) {
            const mouseX = e.clientX / window.innerWidth - 0.5;
            const mouseY = e.clientY / window.innerHeight - 0.5;

            if (logo) {
                logo.style.transform = `translateX(${mouseX * 10}px) translateY(${mouseY * 10}px)`;
            }
        }
    });
</script>