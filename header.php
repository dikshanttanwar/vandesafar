<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<!-- <?php include_once "./config/db_connect.php" ?> -->
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
            <a href="/travelSite" class="logo">
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