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
            <div class="hero-overlay"></div>

            <div class="hero-content container">
                <h1 class="hero-title">
                    Discover Your Next <br>
                    <span>Great Adventure</span>
                </h1>
                <p class="hero-subtitle">
                    Explore top-rated tours, hotels, and destinations tailored just for you.
                </p>

                <form action="packages.php" method="GET" class="hero-search-box">
                    <div class="input-group">
                        <label>Location</label>
                        <input type="text" name="destination" placeholder="Where do you want to go?">
                    </div>
                    <div class="input-group">
                        <label>Date</label>
                        <input type="date" name="date">
                    </div>
                    <button type="submit" class="btn-search">Search Packages</button>
                </form>
            </div>
        </section>

        <section class="features-section">
            <div class="container">
                <div class="section-header text-center">
                    <h2>Travel with Confidence</h2>
                    <p>We ensure your journey is seamless from start to finish.</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="icon-box">
                            <i class="ri-earth-line"></i>
                        </div>
                        <h3>Global Destinations</h3>
                        <p>Access to over 500+ unique destinations across the globe.</p>
                    </div>

                    <div class="feature-card">
                        <div class="icon-box">
                            <i class="ri-wallet-3-line"></i>
                        </div>
                        <h3>Best Price Guarantee</h3>
                        <p>We match any competitor's price to ensure you get the best deal.</p>
                    </div>

                    <div class="feature-card">
                        <div class="icon-box">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Our team is available round the clock to assist you anywhere.</p>
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
                <div class="section-header">
                    <h2>Popular Destinations</h2>
                    <a href="packages.php" class="view-all-link">View All Tours <i class="ri-arrow-right-line"></i></a>
                </div>

                <div class="packages-grid">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="package-card">
                                <div class="card-img">
                                    <img src="assets/images/<?php echo $row['package_image']; ?>" alt="<?php echo $row['package_title']; ?>">
                                    <span class="price-tag">$<?php echo $row['package_price']; ?></span>
                                </div>
                                <div class="card-content">
                                    <div class="meta-tags">
                                        <span><i class="ri-time-line"></i> <?php echo $row['package_duration']; ?></span>
                                        <span><i class="ri-star-fill"></i> 4.8</span>
                                    </div>
                                    <h3><?php echo $row['package_title']; ?></h3>
                                    <p><?php echo substr($row['package_description'], 0, 80); ?>...</p>
                                    <a href="contact.php?package=<?php echo urlencode($row['package_title']); ?>" class="btn-book">Book
                                        Now</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No packages found. Please add some in the database!</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include "footer.php"; ?>