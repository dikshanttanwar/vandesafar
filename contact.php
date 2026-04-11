<?php
// ==========================
// 1. BACKEND LOGIC SECTION
// ==========================

include 'config/mail_config.php';

// Initialize variables to hold form data (Sticky Form) and messages
$name = $email = $phone = $subject = $message = "";
$alert_msg = "";
$alert_class = "";

// A. Handle "Book Now" Link Logic
// If user comes from a package page (e.g., contact.php?package=Goa)
if (isset($_GET['package'])) {
    $subject = "Inquiry for Package: " . htmlspecialchars($_GET['package']);
}

// B. Handle Form Submission Logic
if (isset($_POST['submit_btn']) || isset($_POST['ajax'])) {

    // 1. Capture & Sanitize Data
    $name = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'] ?? '')));
    $phone = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
    $subject = htmlspecialchars(strip_tags(trim($_POST['subject'] ?? '')));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

    // 2. Server-Side Validation
    if (empty($name) || empty($email) || empty($message)) {
        $alert_msg = "Please fill in all required fields.";
        $alert_class = "error";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert_msg = "Invalid email format.";
        $alert_class = "error";
    }
    else {
        // 3. Send Email
        $to = "admin@vandesafar.in";
        $email_subject = "New Contact Us Form Submission: $subject";
        $email_body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #e67e22;'>New Contact Us Form Submission</h2>
                <p>You have received a new message from your website contact form. Here are the details:</p>
                <table style='width: 100%; max-width: 600px; border-collapse: collapse; margin-top: 15px;'>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9; width: 120px;'><strong>Name:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$name</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Email:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$email</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Phone:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$phone</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Subject:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>$subject</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Message:</strong></td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>" . nl2br($message) . "</td>
                    </tr>
                </table>
            </div>";

        $mail_error = "";
        if (send_site_mail($to, $email_subject, $email_body, $email, $mail_error)) {
            $alert_msg = "Message sent successfully! We will contact you shortly.";
            $alert_class = "success";

            // Clear the form fields after success
            $name = $email = $phone = $subject = $message = "";
        }
        else {
            $alert_msg = "Failed to send message. Error: " . $mail_error;
            $alert_class = "error";
        }

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
    <title>VandeSafar</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/contact.css'>
    <script type="module" defer src='assets/js/modules/contact.js'></script>
    <?php include "header.php"; ?>
    <?php include 'config/db_connect.php'; ?>

    <main class="contact-page-main">
        <!-- Hero Banner with gradient -->
        <section class="contact-hero">
            <div class="container text-center">
                <h1 class="hero-title reveal-text">Get in Touch</h1>
                <p class="hero-subtitle reveal-fade">We'd love to hear from you. Reach out for any inquiries or to plan your next great adventure.</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-glass-wrapper smooth-reveal">
                    
                    <!-- Contact Form Column -->
                    <div class="contact-form-col">
                        <h2>Send us a Message!</h2>
                        <p class="form-subtext">Fill out the form below and our team will get back to you within 24 hours.</p>



                        <form action="contact.php" method="POST" class="modern-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <div class="input-wrapper">
                                        <i class="ri-user-smile-line"></i>
                                        <input type="text" name="name" placeholder="John Doe" value="<?php echo htmlspecialchars($name); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Email Address *</label>
                                    <div class="input-wrapper">
                                        <i class="ri-mail-line"></i>
                                        <input type="email" name="email" placeholder="john@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone Number *</label>
                                    <div class="input-wrapper">
                                        <i class="ri-phone-line"></i>
                                        <input type="text" name="phone" placeholder="+1 234 567 890" value="<?php echo htmlspecialchars($phone); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Subject</label>
                                    <div class="input-wrapper">
                                        <i class="ri-chat-1-line"></i>
                                        <input type="text" name="subject" placeholder="Inquiry Type" value="<?php echo htmlspecialchars($subject); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label>Message *</label>
                                <div class="input-wrapper textarea-wrapper">
                                    <i class="ri-pencil-line"></i>
                                    <textarea name="message" rows="4" placeholder="How can we help you?" required><?php echo htmlspecialchars($message); ?></textarea>
                                </div>
                            </div>

                            <button type="submit" name="submit_btn" class="btn-submit">
                                <span>Send Message</span>
                                <i class="ri-send-plane-fill"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Contact Info Column -->
                    <div class="contact-info-col">
                        <div class="info-card">
                            <h3>Contact Information</h3>
                            <p>We are always open to discuss your needs and provide beautiful travel solutions.</p>
                            
                            <div class="info-items">
                                <div class="info-item">
                                    <div class="icon-box"><i class="ri-map-pin-2-fill"></i></div>
                                    <div class="info-content">
                                        <h4>Our Location</h4>
                                        <p>Jaipur, Rajasthan, India</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon-box"><i class="ri-mail-fill"></i></div>
                                    <div class="info-content">
                                        <h4>Email Us</h4>
                                        <p><a href="mailto:admin@vandesafar.in">admin@vandesafar.in</a></p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon-box"><i class="ri-phone-fill"></i></div>
                                    <div class="info-content">
                                        <h4>Call Us</h4>
                                        <p><a href="tel:+919887700193">+91 9887700193</a></p>
                                    </div>
                                </div>
                            </div>

                            <div class="social-links">
                                <h4>Follow Us</h4>
                                <div class="social-icons">
                                    <a href="#"><i class="ri-facebook-circle-fill"></i></a>
                                    <a href="#"><i class="ri-instagram-fill"></i></a>
                                    <a href="#"><i class="ri-twitter-x-line"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php include "footer.php"; ?>