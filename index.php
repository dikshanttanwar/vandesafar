<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VandeSafar</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/index.css'>
    <script type="module" defer src='assets/js/modules/index.js'></script>
    <?php include "header.php"; ?>

    <main>
        <section class="hero-section">
            <div class="container hero-split">
                <!-- Left Content -->
                <div class="hero-content">
                    <h1 class="hero-title reveal-text">
                        Discover Your Next <br>
                        <span>Great Adventure</span>
                    </h1>
                    <p class="hero-subtitle reveal-fade">
                        Explore top-rated tours, hotels, and destinations tailored just for you. Escape the ordinary and
                        embrace the extraordinary.
                    </p>

                    <form action="packages.php" method="GET" class="hero-search-box reveal-fade"
                        style="animation-delay: 0.2s">
                        <div class="input-group">
                            <label><i class="ri-map-pin-line"></i> Location</label>
                            <input type="text" name="destination" placeholder="Where to?">
                        </div>
                        <div class="divider"></div>
                        <div class="input-group">
                            <label><i class="ri-calendar-line"></i> Date</label>
                            <input type="date" name="date">
                        </div>
                        <button type="submit" class="btn-search"><i class="ri-search-line"></i> Search</button>
                    </form>
                </div>

                <!-- Right Content (Image Collage) -->
                <div class="hero-collage reveal-slide">
                    <div class="collage-col col-down">
                        <div class="img-wrapper"><img src="assets/images/home-page-banner-image.webp" alt="Travel 1">
                        </div>
                        <div class="img-wrapper"><img
                                src="assets/images/city-sea-pattaya-thailand-260nw-2234604393.webp" alt="Travel 2">
                        </div>
                    </div>
                    <div class="collage-col col-up">
                        <div class="img-wrapper"><img src="assets/images/so-maldives-landing-page-carousel-new-2.jpg"
                                alt="Travel 3"></div>
                        <div class="img-wrapper"><img src="assets/images/home-page-banner-mobile-image.webp"
                                alt="Travel 4"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="container">
                <div class="section-header text-center smooth-reveal">
                    <h2>Travel with Confidence</h2>
                    <p>We ensure your journey is seamless from start to finish.</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card glass-card smooth-reveal" style="transition-delay: 0.1s">
                        <div class="icon-box">
                            <i class="ri-earth-line"></i>
                        </div>
                        <h3>Global Destinations</h3>
                        <p>Access to over 500+ unique destinations across the globe with exclusive, tailored
                            itineraries.</p>
                    </div>

                    <div class="feature-card glass-card smooth-reveal" style="transition-delay: 0.2s">
                        <div class="icon-box">
                            <i class="ri-wallet-3-line"></i>
                        </div>
                        <h3>Best Price Guarantee</h3>
                        <p>We match any competitor's price to ensure you get the absolute best deal, every single time.
                        </p>
                    </div>

                    <div class="feature-card glass-card smooth-reveal" style="transition-delay: 0.3s">
                        <div class="icon-box">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Our dedicated team is available round the clock to assist you wherever you are in the world.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // Fetch top 3 packages from Database
        $sql = "SELECT * FROM packages LIMIT 3";
        // $result = $conn->query($sql);
        $result = mysqli_query($conn, $sql);
        ?>

        <section class="popular-section">
            <div class="container">
                <div class="section-header smooth-reveal">
                    <h2>Popular Destinations</h2>
                    <a href="packages.php" class="view-all-link">Explore the World <i
                            class="ri-arrow-right-line"></i></a>
                </div>

                <div class="packages-grid">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $delay = 0.1;
                        while ($row = $result->fetch_assoc()): ?>
                            <div class="package-card smooth-reveal" style="transition-delay: <?php echo $delay; ?>s">
                                <div class="card-img">
                                    <img src="assets/images/<?php echo $row['package_image']; ?>"
                                        alt="<?php echo $row['package_title']; ?>">
                                    <span class="price-tag">₹<?php echo $row['package_price']; ?></span>
                                </div>
                                <div class="card-content">
                                    <div class="meta-tags">
                                        <span><i class="ri-time-line"></i> <?php echo $row['package_duration']; ?></span>
                                        <span><i class="ri-star-fill"></i> 4.8</span>
                                    </div>
                                    <h3><?php echo $row['package_title']; ?></h3>
                                    <p><?php echo substr($row['package_description'], 0, 80); ?>...</p>
                                    <a href="contact.php?package=<?php echo urlencode($row['package_title']); ?>"
                                        class="btn-book">Book
                                        Now</a>
                                </div>
                            </div>
                            <?php $delay += 0.1; endwhile; ?>
                    <?php else: ?>
                        <p class="smooth-reveal">No packages found. Please add some in the database!</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include "footer.php"; ?>