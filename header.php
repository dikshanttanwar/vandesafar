<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<!-- <?php include_once "./config/db_connect.php" ?> -->
<link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">

<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">

<link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">

<link rel="icon" type="image/png" sizes="192x192" href="assets/images/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="assets/images/android-chrome-512x512.jpg">
</head>

<body>

    <?php
    // Helper function to check active page
    function isActive($pageName)
    {
        $current = basename($_SERVER['PHP_SELF']);
        if ($current == $pageName) {
            echo 'active';
        }
    }
    ?>

    <!-- TOP CONTACT BAR -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-contact">
                <a href="mailto:admin@vandesafar.in"><i class="ri-mail-line"></i> admin@vandesafar.in</a>
                <a href="tel:+919887700193"><i class="ri-phone-line"></i> +91 9887700193</a>
            </div>
            <div class="top-bar-socials">
                <a href="#" aria-label="Facebook"><i class="ri-facebook-circle-fill"></i></a>
                <a href="https://www.instagram.com/vandesafartourandtravels/" aria-label="Instagram"><i
                        class="ri-instagram-line"></i></a>
            </div>
        </div>
    </div>

    <!-- MAIN NAVIGATION -->
    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">
                Vande<span>Safar</span>
            </a>

            <nav class="navbar" id="navbar">
                <ul class="nav-links">
                    <li><a href="index.php" class="<?php isActive('index.php'); ?>">Home</a></li>
                    <li><a href="packages.php" class="<?php isActive('packages.php'); ?>">Packages</a></li>
                    <li><a href="about-us.php" class="<?php isActive('about-us.php'); ?>">About Us</a></li>
                    <li><a href="contact.php" class="btn-contact"><i class="ri-send-plane-fill"></i> Book Now</a></li>
                </ul>
            </nav>

            <div class="hamburger" id="hamburger-icon" onclick="openMobileMenu()">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </header>

    <script>
        // Highly reliable global function for Mobile Menu
        function openMobileMenu() {
            var nav = document.getElementById("navbar");
            var icon = document.getElementById("hamburger-icon");
            nav.classList.toggle("open");
            icon.classList.toggle("open");
        }
    </script>