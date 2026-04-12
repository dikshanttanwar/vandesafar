<?php
// package-details.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config/db_connect.php';
include 'config/mail_config.php';

$package_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM packages WHERE id = $package_id";
$result = mysqli_query($conn, $query);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    echo "<script>window.location.href='404.php';</script>";
    exit();
}

// Fallbacks
$location = $package['location'] ?? 'Rajasthan, India';
$duration = $package['package_duration'];
$price = number_format($package['package_price'], 2);
$title = $package['package_title'];
$image = $package['package_image'];
$description = $package['package_description'];

// Booking Form Processing
$alert_msg = "";
$alert_class = "";

if (isset($_POST['book_btn']) || isset($_POST['ajax'])) {
    $b_name = $_POST['name'] ?? '';
    $b_phone = $_POST['phone'] ?? '';
    $b_email = $_POST['email'] ?? '';
    $b_destination = $_POST['destination'] ?? '';
    $b_arrival = $_POST['arrival_date'] ?? '';
    $b_departure = $_POST['departure_date'] ?? '';
    $b_adults = $_POST['adults'] ?? 0;
    $b_children = $_POST['children'] ?? 0;
    $b_vehicle = $_POST['vehicle_option'] ?? 'Standard Package';

    if (empty($b_name) || empty($b_email) || empty($b_phone)) {
        $alert_msg = "Please fill in all required fields.";
        $alert_class = "error";
    }
    else {
        $to = "admin@vandesafar.in";
        $email_subject = "New Booking Request: $b_destination";
        $email_body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #e67e22;'>New Package Booking Request</h2>
                <p>You have received a new package booking request. Here are the details:</p>
                <table style='width: 100%; max-width: 600px; border-collapse: collapse; margin-top: 15px;'>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9; width: 150px;'><strong>Destination:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_destination</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Package ID:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$package_id</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Name:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_name</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Phone:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_phone</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Email:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_email</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Arrival Date:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_arrival</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Departure Date:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_departure</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Guests:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_adults Adults, $b_children Children</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Vehicle Option:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$b_vehicle</td>
                    </tr>
                </table>
            </div>";

        $mail_error = "";
        if (send_site_mail($to, $email_subject, $email_body, $b_email, $mail_error)) {
            $alert_msg = "Booking request sent successfully! We will contact you shortly.";
            $alert_class = "success";
        }
        else {
            $alert_msg = "Failed to send booking request. Error: " . $mail_error;
            $alert_class = "error";
        }

        // If AJAX request, return JSON securely
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $alert_class, 'message' => $alert_msg]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Details | VandeSafar</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/packageDetail.css'>
    <!-- Ensure FontAwesome is loaded if header doesn't have it -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include "header.php"; ?>

    <main class="package-details-main">
        <!-- HERO SECTION -->
        <section class="package-hero"
            style="background-image: linear-gradient(to bottom, rgba(15, 15, 26, 0.4), rgba(15, 15, 26, 0.9)), url('assets/images/<?php echo $image; ?>');">
            <div class="hero-container container">
                <div class="hero-content-wrapper">
                    <div class="tags-row">
                        <span class="location-badge glass-badge"><i class="fas fa-map-marker-alt"></i>
                            <?php echo $location; ?></span>
                        <span class="rating-badge glass-badge"><i class="fas fa-star"></i> 4.9 (120 Reviews)</span>
                    </div>
                    <h1 class="hero-title"><?php echo $title; ?></h1>

                    <div class="quick-stats-glass">
                        <div class="stat-item">
                            <div class="stat-icon"><i class="far fa-clock"></i></div>
                            <div class="stat-text">
                                <span class="stat-label">Duration</span>
                                <span class="stat-value"><?php echo htmlspecialchars($duration); ?></span>
                            </div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                            <div class="stat-text">
                                <span class="stat-label">Group Size</span>
                                <span class="stat-value">2-10 People</span>
                            </div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-hiking"></i></div>
                            <div class="stat-text">
                                <span class="stat-label">Tour Type</span>
                                <span class="stat-value">Guided Tour</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT AREA -->
        <div class="container content-layout">
            <div class="left-content">

                <!-- OVERVIEW -->
                <section class="detail-block" id="overview">
                    <h2 class="block-title">Overview</h2>
                    <div class="overview-text">
                        <?php
// Split description into paragraphs automatically
$paragraphs = explode("\n", $description);
foreach ($paragraphs as $p) {
    if (trim($p) !== "") {
        echo "<p>" . htmlspecialchars(trim($p)) . "</p>";
    }
}
?>
                    </div>
                </section>

                <!-- HIGHLIGHTS -->
                <section class="detail-block" id="highlights">
                    <h2 class="block-title">Highlights</h2>
                    <div class="highlights-grid">
                        <div class="highlight-card">
                            <div class="hc-icon"><i class="fas fa-camera"></i></div>
                            <div class="hc-text">Iconic Landmarks</div>
                        </div>
                        <div class="highlight-card">
                            <div class="hc-icon"><i class="fas fa-utensils"></i></div>
                            <div class="hc-text">Local Cuisine</div>
                        </div>
                        <div class="highlight-card">
                            <div class="hc-icon"><i class="fas fa-bus"></i></div>
                            <div class="hc-text">Luxury Transport</div>
                        </div>
                        <div class="highlight-card">
                            <div class="hc-icon"><i class="fas fa-bed"></i></div>
                            <div class="hc-text">Premium Stay</div>
                        </div>
                    </div>
                </section>

                <!-- ITINERARY -->
                <section class="detail-block" id="itinerary">
                    <h2 class="block-title">Itinerary</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h3 class="day-title">Day 1: Arrival & Local Exploration</h3>
                                <p>Pick up from the airport or station and check-in to your premium hotel. Enjoy an
                                    evening visit to local historic markets and sample cultural cuisines.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h3 class="day-title">Day 2: City Sightseeing</h3>
                                <p>Experience a full-day guided tour across major historical monuments, majestic forts,
                                    and museums. Traditional lunch is served at a heritage site.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot highlight-dot"></div>
                            <div class="timeline-content">
                                <h3 class="day-title">Day 3: Departure</h3>
                                <p>Morning at leisure for local souvenir shopping. Smooth transfer to your departure
                                    point in the late afternoon.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- INCLUSIONS & EXCLUSIONS -->
                <section class="detail-block two-col-block" id="included">
                    <div class="inclusions">
                        <h2 class="block-title">What's Included</h2>
                        <ul class="check-list include-list">
                            <li><i class="fas fa-check-circle"></i> Private AC Vehicle</li>
                            <li><i class="fas fa-check-circle"></i> Government Approved Guide</li>
                            <li><i class="fas fa-check-circle"></i> Hotel Accommodation (4-Star)</li>
                            <li><i class="fas fa-check-circle"></i> Daily Breakfast & Mineral Water</li>
                        </ul>
                    </div>
                    <div class="exclusions">
                        <h2 class="block-title">What's Excluded</h2>
                        <ul class="check-list exclude-list">
                            <li><i class="fas fa-times-circle"></i> Flights / Train Tickets</li>
                            <li><i class="fas fa-times-circle"></i> Personal Expenses (Shopping, etc.)</li>
                            <li><i class="fas fa-times-circle"></i> Additional Activity Fees</li>
                        </ul>
                    </div>
                </section>

                <!-- CONTACT / INQUIRY -->
                <section class="detail-block inquiry-block">
                    <div class="inquiry-content">
                        <h2>Have specific requirements?</h2>
                        <p>Our travel experts can customize this package exactly to your needs.</p>
                        <form action="contact_process.php" method="POST" class="inline-inquiry-form">
                            <input type="text" name="name" placeholder="Your Name" required>
                            <input type="email" name="email" placeholder="Email Address" required>
                            <button type="submit" class="btn-theme">Request Callback</button>
                        </form>
                    </div>
                </section>

            </div>

            <div class="right-sidebar">
                <?php
                // Fetch Vehicle Options safely
                $vehicle_options = [];
                if (!empty($package['price_cab']) && $package['price_cab'] > 0) $vehicle_options['Cab'] = $package['price_cab'];
                if (!empty($package['price_tempo']) && $package['price_tempo'] > 0) $vehicle_options['Traveller Tempo'] = $package['price_tempo'];
                if (!empty($package['price_minibus']) && $package['price_minibus'] > 0) $vehicle_options['Mini Bus'] = $package['price_minibus'];
                if (!empty($package['price_bus']) && $package['price_bus'] > 0) $vehicle_options['Bus'] = $package['price_bus'];

                $lowest_vehicle_price = !empty($vehicle_options) ? min($vehicle_options) : $package['package_price'];
                ?>
                <div class="booking-widget sticky-widget">
                    <div class="widget-header">
                        <div class="price-box">
                            <span class="currency">₹</span>
                            <span class="amount"><?php echo number_format($lowest_vehicle_price, 2); ?></span>
                            <span class="per-person" style="font-size:0.85rem; margin-left:5px;">Starting Price</span>
                        </div>
                    </div>
                    <div class="widget-body">

                        <form action="" method="POST" class="widget-form">
                            <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">

                            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($title); ?>">

                            <div class="input-group">
                                <label><i class="fas fa-user"></i> Full Name *</label>
                                <input type="text" name="name" placeholder="e.g. John Doe" required>
                            </div>

                            <div class="flex-row">
                                <div class="input-group">
                                    <label><i class="fas fa-phone"></i> Phone *</label>
                                    <input type="text" name="phone" placeholder="+91..." required>
                                </div>

                                <div class="input-group">
                                    <label><i class="fas fa-envelope"></i> Email *</label>
                                    <input type="email" name="email" placeholder="abc@xyz.com" required>
                                </div>
                            </div>

                            <div class="flex-row">
                                <div class="input-group">
                                    <label><i class="far fa-calendar-alt"></i> Arrival *</label>
                                    <input type="date" name="arrival_date" required>
                                </div>

                                <div class="input-group">
                                    <label><i class="far fa-calendar-alt"></i> Departure *</label>
                                    <input type="date" name="departure_date" required>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom: 15px;">
                                <label><i class="fas fa-car-side"></i> Vehicle Option *</label>
                                <select name="vehicle_option" id="vehicleOption" required onchange="updateTotal()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                    <?php if(empty($vehicle_options)): ?>
                                        <option value="Standard Package" data-price="<?php echo $lowest_vehicle_price; ?>">Standard Package (₹<?php echo number_format($lowest_vehicle_price, 2); ?>)</option>
                                    <?php else: ?>
                                        <?php foreach($vehicle_options as $name => $v_price): ?>
                                            <option value="<?php echo $name; ?>" data-price="<?php echo $v_price; ?>"><?php echo $name; ?> (₹<?php echo number_format($v_price, 2); ?>)</option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="flex-row">
                                <div class="input-group">
                                    <label><i class="fas fa-user"></i> Adults</label>
                                    <div class="guest-counter">
                                        <button type="button" class="btn-count minus"
                                            onclick="document.getElementById('adults').stepDown(); updateTotal();">-</button>
                                        <input type="number" id="adults" name="adults" min="1" value="2" required
                                            readonly>
                                        <button type="button" class="btn-count plus"
                                            onclick="document.getElementById('adults').stepUp(); updateTotal();">+</button>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <label><i class="fas fa-child"></i> Children</label>
                                    <div class="guest-counter">
                                        <button type="button" class="btn-count minus"
                                            onclick="document.getElementById('children').stepDown(); updateTotal();">-</button>
                                        <input type="number" id="children" name="children" min="0" value="0" required
                                            readonly>
                                        <button type="button" class="btn-count plus"
                                            onclick="document.getElementById('children').stepUp(); updateTotal();">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-row">
                                <span>Base Price</span>
                                <span>$<?php echo $price; ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Taxes & Fees</span>
                                <span>Included</span>
                            </div>
                            <hr class="summary-divider">
                            <div class="summary-row total-row">
                                <span>Total Estimated</span>
                                <span class="total-est"
                                    id="total-val">₹<?php echo number_format($lowest_vehicle_price, 2); ?></span>
                            </div>

                            <button type="submit" name="book_btn" class="btn-book-now">Request to Book</button>
                        </form>
                        <div class="secure-checkout">
                            <i class="fas fa-lock"></i> Secure & encrypted checkout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Script to dynamically update total estimate in sidebar and Handle AJAX Forms -->
    <script>
        // --- 1. Vehicle Option Pricing Logic ---
        const vehicleSelect = document.getElementById('vehicleOption');
        const totalVal = document.getElementById('total-val');

        function updateTotal() {
            if (vehicleSelect) {
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                totalVal.textContent = '₹' + price.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        }

        if (vehicleSelect) {
            vehicleSelect.addEventListener('change', updateTotal);
        }

        // --- 2. AJAX Form Submissions & Toasts ---
        const handleAjaxSubmit = (formSelector, btnSelector, actionUrl) => {
            const form = document.querySelector(formSelector);
            if (!form) return;

            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const submitBtn = form.querySelector(btnSelector);
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Sending...</span><i class="ri-loader-4-line ri-spin"></i>';
                submitBtn.disabled = true;

                const formData = new FormData(form);
                formData.append("ajax", "1");

                // Target URL is either actionUrl passed, or form's action, or current page.
                const targetAction = actionUrl || form.getAttribute("action") || window.location.href;

                fetch(targetAction, {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                    body: formData,
                })
                .then(async (response) => {
                    const clone = response.clone();
                    try {
                        return await response.json();
                    } catch(err) {
                        const rawText = await clone.text();
                        console.error("RAW PHP ERROR TEXT:", rawText);
                        throw new Error("Server returned non-JSON. View console.");
                    }
                })
                .then((data) => {
                    showToast(data.message, data.status);
                    if (data.status === "success") {
                        form.reset();
                        updateTotal(); // reset price dynamically to default vehicle inside dropdown
                    }
                })
                .catch((error) => {
                    console.error("Fetch Error: ", error);
                    showToast("Something went wrong. Please check console.", "error");
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        };

        // Attach AJAX handlers
        handleAjaxSubmit(".widget-form", ".btn-book-now");
        handleAjaxSubmit(".inline-inquiry-form", ".btn-theme", "contact_process.php");

        // --- 3. Toast UI Notification System ---
        function showToast(message, type) {
            let toastContainer = document.getElementById("toast-container");
            if (!toastContainer) {
                toastContainer = document.createElement("div");
                toastContainer.id = "toast-container";
                toastContainer.className = "toast-container";
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement("div");
            toast.className = `toast toast-${type}`;
            const icon = type === "success" ? "ri-checkbox-circle-fill" : "ri-error-warning-fill";
            toast.innerHTML = `<i class="${icon}"></i> <span>${message}</span>`;
            
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.classList.add("show");
            }, 10);

            setTimeout(() => {
                toast.classList.remove("show");
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>

    <?php include 'footer.php'; ?>
    </body>

</html>