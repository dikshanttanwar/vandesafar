<?php
// contact_process.php
include 'config/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($name) || empty($email)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    $to = "admin@vandesafar.in";
    $subject = "New Callback Request (Inline Submission)";
    $message = "
    <div style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='color: #e67e22;'>New Callback Request</h2>
        <p>You have received a new callback request from an inline package form.</p>
        <table style='width: 100%; max-width: 500px; border-collapse: collapse; margin-top: 15px;'>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9; width: 100px;'><strong>Name:</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>$name</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; background: #f9f9f9;'><strong>Email:</strong></td>
                <td style='padding: 10px; border: 1px solid #ddd;'>$email</td>
            </tr>
        </table>
    </div>";

    if (send_site_mail($to, $subject, $message, $email)) {
        echo "<script>alert('Callback request sent successfully!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Failed to send request. Please try again later.'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>