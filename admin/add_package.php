<?php
session_start();
include '../config/db_connect.php';

// 1. SECURITY: Kick out if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$msg = "";
$msg_class = "";

// 2. HANDLE FORM SUBMISSION
if (isset($_POST['add_btn'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $price = (float) $_POST['price'];
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);

    // File Upload Logic
    $image_name = $_FILES['package_image']['name'];

    // Add a timestamp to the filename to prevent overwriting existing images
    // e.g., "beach.jpg" becomes "1738493_beach.jpg"
    $final_image_name = time() . '_' . basename($image_name);

    $target_dir = "../assets/images/";
    $target_file = $target_dir . $final_image_name;

    // Check if image is selected
    if (!empty($image_name)) {
        if (move_uploaded_file($_FILES['package_image']['tmp_name'], $target_file)) {

            // Insert into Database
            $sql = "INSERT INTO packages (package_title, package_description, package_price, package_duration, package_image) 
                    VALUES ('$title', '$desc', '$price', '$duration', '$final_image_name')";

            if (mysqli_query($conn, $sql)) {
                $msg = "Package added successfully!";
                $msg_class = "success";
            } else {
                $msg = "Database Error: " . mysqli_error($conn);
                $msg_class = "error";
            }
        } else {
            $msg = "Failed to upload image. Check folder permissions.";
            $msg_class = "error";
        }
    } else {
        $msg = "Please select an image.";
        $msg_class = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Package</title>
    <style>
        /* --- RESET & BASICS --- */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            /* Matches Dashboard */
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* --- CONTAINER --- */
        .admin-container {
            width: 100%;
            max-width: 600px;
            /* Stops it from getting too wide on PC */
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        /* --- HEADERS --- */
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.8rem;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .back-link:hover {
            color: #2c3e50;
            transform: translateX(-3px);
        }

        /* --- FORM ELEMENTS --- */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
            font-size: 0.95rem;
        }

        /* Inputs: Bigger padding for mobile tapping */
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            /* Prevents iOS zooming */
            background: #f9f9f9;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #27ae60;
            background: #fff;
            outline: none;
        }

        /* File Input Styling */
        input[type="file"] {
            background: white;
            padding: 10px;
        }

        /* --- BUTTON --- */
        button {
            width: 100%;
            padding: 15px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #219150;
        }

        /* --- ALERTS --- */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* --- MOBILE TWEAKS --- */
        @media (max-width: 480px) {
            .admin-container {
                padding: 1.5rem;
                /* Less padding on small screens */
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="admin-container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <h2>Add New Tour Package</h2>

        <?php if ($msg != ''): ?>
            <div class="alert <?php echo $msg_class; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="add_package.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Package Title</label>
                <input type="text" name="title" placeholder="e.g. Royal Rajasthan Tour" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="desc" rows="4" placeholder="Enter details about places, hotels, etc..."
                    required></textarea>
            </div>

            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" name="price" step="0.01" placeholder="e.g. 500.00" required>
            </div>

            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" placeholder="e.g. 5 Days / 4 Nights" required>
            </div>

            <div class="form-group">
                <label>Package Image</label>
                <input type="file" name="package_image" accept="image/*" required>
            </div>

            <button type="submit" name="add_btn">Create Package</button>
        </form>
    </div>

</body>

</html>