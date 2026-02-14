<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dikshant Travel</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/packages.css'>
    <script type="module" defer src='assets/js/modules/packages.js'></script>
    <?php include "header.php"; ?>
    <?php include 'config/db_connect.php'; ?>

    <?php
    // ==========================================
    // 1. BACKEND LOGIC: SEARCH & FILTER
    // ==========================================
    
    // Initialize the "WHERE" clause of our SQL query
    // By default, 1=1 is a trick that means "True" (selects everything)
    $whereClause = "WHERE 1=1";

    // Check if User searched for a DESTINATION
    if (isset($_GET['destination']) && !empty($_GET['destination'])) {
        $dest = mysqli_real_escape_string($conn, $_GET['destination']);
        // We use LIKE %...% so "Goa" finds "Trip to Goa"
        $whereClause .= " AND package_title LIKE '%$dest%'";
    }

    // Check if User filtered by DATE (Optional - assumes you have a date column or just ignores it for now)
    if (isset($_GET['date']) && !empty($_GET['date'])) {
        $date = mysqli_real_escape_string($conn, $_GET['date']);
        // Example logic: Filter if package creation or start date matches
        // $whereClause .= " AND start_date >= '$date'"; 
    }

    // Check if User filtered by PRICE (Optional sidebar filter)
    if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $maxPrice = (int) $_GET['max_price'];
        $whereClause .= " AND package_price <= $maxPrice";
    }

    // FINAL SQL QUERY
// We combine the base query with our dynamic filters
    $sql = "SELECT * FROM packages $whereClause ORDER BY id DESC";
    $result = $conn->query($sql);
    ?>

    <main>
        <section class="page-header">
            <div class="container">
                <h1 class="heading">Our Tour Packages</h1>
                <p class="description">Find the perfect escape from the everyday.</p>
            </div>
        </section>

        <section class="packages-section">
            <div class="container">

                <?php if (isset($_GET['destination']) && !empty($_GET['destination'])): ?>
                    <div class="search-result-msg">
                        <h3>Showing results for "<?php echo htmlspecialchars($_GET['destination']); ?>"</h3>
                        <a href="packages.php" class="clear-filter">Clear Filters</a>
                    </div>
                <?php endif; ?>

                <div class="packages-grid">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>

                            <div class="package-card">
                                <div class="card-img">
                                    <img src="assets/images/<?php echo $row['package_image']; ?>"
                                        alt="<?php echo $row['package_title']; ?>">
                                    <span class="price-tag">$<?php echo $row['package_price']; ?></span>
                                </div>

                                <div class="card-content">
                                    <div class="meta-tags">
                                        <span><i class="ri-time-line"></i> <?php echo $row['package_duration']; ?></span>
                                    </div>
                                    <h3><?php echo $row['package_title']; ?></h3>
                                    <p><?php echo substr($row['package_description'], 0, 100); ?>...</p>

                                    <a href="contact.php?package=<?php echo urlencode($row['package_title']); ?>"
                                        class="btn-book">
                                        Book Now
                                    </a>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <h3>No packages found matching your criteria.</h3>
                            <p>Try adjusting your search or view all packages.</p>
                            <a href="packages.php" class="btn-primary">View All Packages</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>

    <?php include "footer.php"; ?>