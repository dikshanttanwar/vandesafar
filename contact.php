<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VandeSafar</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/contact.css'>
    <script type="module" defer src='assets/js/modules/contact.js'></script>
    <?php include "header.php"; ?>
    <?php include 'config/db_connect.php'; ?>

    <?php
    // ==========================
    // 1. BACKEND LOGIC SECTION
    // ==========================
    
    // Connect to the Database
    include 'config/db_connect.php';

    // Initialize variables to hold form data (Sticky Form) and messages
    $name = $email = $subject = $message = "";
    $alert_msg = "";
    $alert_class = "";

    // A. Handle "Book Now" Link Logic
    // If user comes from a package page (e.g., contact.php?package=Goa)
    if (isset($_GET['package'])) {
        $subject = "Inquiry for Package: " . htmlspecialchars($_GET['package']);
    }

    // B. Handle Form Submission Logic
    if (isset($_POST['submit_btn'])) {

        // 1. Capture & Sanitize Data (Security Warning: ALWAYS do this!)
        // mysqli_real_escape_string prevents SQL Injection attacks
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // 2. Server-Side Validation
        if (empty($name) || empty($email) || empty($message)) {
            $alert_msg = "Please fill in all required fields.";
            $alert_class = "error";
        } else {
            // 3. The SQL Query (The functionality part)
            $sql = "INSERT INTO inquiries (name, email, phone, subject, message) VALUES ('$name', '$email', '$phone', '$subject', '$message')";

            // 4. Execute Query
            if (mysqli_query($conn, $sql)) {
                $alert_msg = "Message sent successfully! We will contact you shortly.";
                $alert_class = "success";

                // Clear the form fields after success
                $name = $email = $phone = $subject = $message = "";
            } else {
                $alert_msg = "Database Error: " . mysqli_error($conn);
                $alert_class = "error";
            }
        }
    } ?>

    <main>
        <section class="contact-header">
            <div class="container">
                <h1>Contact Us</h1>
                <p>Get in touch for your next adventure.</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-wrapper">

                    <div class="contact-form">

                        <?php if ($alert_msg != ''): ?>
                            <div class="alert <?php echo $alert_class; ?>">
                                <?php echo $alert_msg; ?>
                            </div>
                        <?php endif; ?>

                        <form action="contact.php" method="POST">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="num" name="phone" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
                            </div>

                            <div class="form-group">
                                <label>Message *</label>
                                <textarea name="message" rows="5"
                                    required><?php echo htmlspecialchars($message); ?></textarea>
                            </div>

                            <button type="submit" name="submit_btn" class="btn-submit">Send Message</button>
                        </form>
                    </div>

                    <div class="contact-info">
                        <h3>Office Info</h3>
                        <p><strong>Address:</strong> Jaipur, Rajasthan, India</p>
                        <p><strong>Email:</strong> <a href="vandesafartourandtravels@gmail.com">vandesafartourandtravels@gmail.com</a> </p>
                        <p><strong>Phone:</strong> <a href="tel:+916378138368">+91 6378138368</a></p>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php include "footer.php"; ?>