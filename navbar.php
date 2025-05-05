<header id="navbar">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anton&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Fiksna navigacijska vrstica, izolirana s selektorjem #navbar */
        #navbar {
            font-family: 'Montserrat', sans-serif;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            z-index: 1000;
        }

        #navbar {
            backdrop-filter: blur(30px);
            box-shadow: 0px 0px 30px rgba(227, 228, 238, 0.37);
            border: 2px solid rgba(255, 255, 255, 0.18);
        }

        #navbar .nav-left {
            font-size: 30px;
            font-weight: bold;
            margin-left: 100px;
            letter-spacing: 1px;
        }


        #navbar.scrolled {
            background: rgba(0, 123, 255, 0.25);
            border: 1px solid rgba(0, 123, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 123, 255, 0.3);
            backdrop-filter: blur(20px);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }


        #navbar .nav-left a {
            text-decoration: none;
        }

        #navbar .nav-left a span {
            color: #f0ebe3;
            transition: color 0.3s ease;
        }

        #navbar .nav-left a:hover .schedulizer-blue {
            color: #3A82F7;
        }

        #navbar .nav-left a:hover .schedulizer-lightblue {
            color: #00C2FF;
        }

        #navbar .nav-right {
            margin-right: 200px;
        }

        #navbar .nav-right a {
            margin-left: 20px;
            text-decoration: none;
            color: #f0ebe3;
            font-size: 25px;
            transition: color 0.3s ease;
        }

        /* Hover efekt za navigacijske povezave */
        #navbar .nav-right a:hover {
            color: #3A82F7 !important;
        }

        /* Aktivna povezava */
        #navbar .nav-right a.active {
            color: #00C2FF !important;
        }

        /* Uvoz pisave Bebas Neue */
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

        #navbar,
        #navbar * {
            font-family: 'Bebas Neue', sans-serif;
        }
    </style>

    <?php
    // Določanje imena trenutne strani (brez .php)
    $currentPage = basename($_SERVER['PHP_SELF'], ".php");
    ?>

    <div class="nav-left">
        <a href="index.php">
            <span class="schedulizer-blue">SHEDU</span><span class="schedulizer-lightblue">LIZER</span>
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
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>