<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href='assets/css/main.css'>
    <link rel="stylesheet" href='assets/css/contact.css'>
    <script type="module" defer src='assets/js/modules/contact.js'></script>

    <style>
        @import '../assets/css/main.css';

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #e67e22;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #d35400;
        }

        .error-msg {
            color: red;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>

    <?php include "../header.php"; ?>
    <?php include '../config/db_connect.php'; ?>

    <?php
    session_start();
    $error = "";

    if (isset($_POST['login_btn'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        // $password = $_POST['password']; // Don't escape password, we need the raw string to verify
    
        // 1. Check if username exists
        $sql = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            // 2. Verify the Password (Hash check)
            // password_verify checks if "password123" matches the hash in DB
            if ($password == $row['password']) {

                // 3. SUCCESS: Set Session Variables
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['username'];

                // Redirect to Dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Incorrect Password!";
            }
        } else {
            $error = "User not found!";
        }
    }
    ?>

    <main class="login-box">
        <h2>Admin Panel</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login_btn">Login</button>
        </form>
    </main>

    </body>

</html>