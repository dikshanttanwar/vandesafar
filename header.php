<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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

    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">
                Vande<span>Safar</span>
            </a>

            <nav class="navbar">
                <ul class="nav-links">
                    <li><a href="index.php" class="<?php isActive('index.php'); ?>">Home</a></li>
                    <li><a href="packages.php" class="<?php isActive('packages.php'); ?>">Packages</a></li>
                    <li><a href="about-us.php" class="<?php isActive('about-us.php'); ?>">About Us</a></li>
                    <li><a href="contact.php" class="btn-contact">Contact Us</a></li>
                </ul>
            </nav>

            <div class="hamburger" id="hamburger-icon">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </header>