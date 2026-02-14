<?php
session_start();
include '../config/db_connect.php';

// 1. SECURITY GATE
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// 2. DELETE INQUIRY
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete']; 
    $deleteSql = "DELETE FROM inquiries WHERE id = $id";
    if (mysqli_query($conn, $deleteSql)) {
        header('Location: dashboard.php?msg=deleted');
    }
}

// 3. FETCH INQUIRIES
$sql = "SELECT * FROM inquiries ORDER BY created_at DESC"; // Changed to DESC to see newest first
$result = mysqli_query($conn, $sql);

// 4. DELETE PACKAGE
if (isset($_GET['delete_package'])) {
    $id = (int) $_GET['delete_package'];

    // Get Image Name
    $query = "SELECT package_image FROM packages WHERE id = $id";
    $pkg_res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($pkg_res);

    // Delete Record
    $deleteSql = "DELETE FROM packages WHERE id = $id";
    if (mysqli_query($conn, $deleteSql)) {
        // Delete File
        if ($row) {
            $image_path = "../assets/images/" . $row['package_image'];
            if (file_exists($image_path)) {
                unlink($image_path); 
            }
        }
        header('Location: dashboard.php?msg=pkg_deleted');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* --- RESET & BASICS --- */
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; margin: 0; padding-bottom: 50px; }

        /* --- HEADER --- */
        .admin-header {
            background: #2c3e50;
            color: white;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .logout-btn:hover { background: #c0392b; }

        /* --- LAYOUT CONTAINER --- */
        .container {
            padding: 2rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- ACTION BAR (Title + Button) --- */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Allows wrapping on mobile */
            gap: 15px;
        }

        .btn-add {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .btn-add:hover { background: #219150; }

        /* --- ALERTS --- */
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* --- RESPONSIVE TABLES --- */
        /* This wrapper allows the table to scroll sideways on mobile */
        .table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Forces table to keep shape even on small screens */
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }

        th { background: #34495e; color: white; white-space: nowrap; }
        tr:hover { background: #f8f9fa; }

        .action-btn { color: #e74c3c; text-decoration: none; font-weight: bold; }
        .action-btn:hover { text-decoration: underline; }

        .badge {
            background: #27ae60;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-right: 5px;
        }

        .img-thumb {
            width: 50px; height: 50px; 
            object-fit: cover; 
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        /* --- MOBILE MEDIA QUERIES --- */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .action-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn-add {
                width: 100%; /* Full width button on mobile */
                text-align: center;
            }

            .container { padding: 1rem; }
        }
    </style>
</head>

<body>

    <header class="admin-header">
        <h3>TravelGo Admin</h3>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
            <a href="logout.php" class="logout-btn" style="margin-left: 10px;">Logout</a>
        </div>
    </header>

    <div class="container">

        <div class="action-bar">
            <div>
                <h1 style="margin:0;">Inquiries</h1>
                <p style="margin:5px 0 0; color:#666;">Manage messages from customers.</p>
            </div>
            <a href="add_package.php" class="btn-add">+ Add New Package</a>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert">Message deleted successfully.</div>
        <?php endif; ?>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Date</th>
                        <th width="15%">Name</th>
                        <th width="20%">Contact</th> <th width="15%">Subject</th>
                        <th width="20%">Message</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td style="color: #666; font-size: 0.85rem;">
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                    <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($row['email']); ?><br>
                                    <span style="color: #27ae60; font-size: 0.9rem;">
                                        <?php echo isset($row['phone']) ? htmlspecialchars($row['phone']) : ''; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (stripos($row['subject'], 'package') !== false): ?>
                                        <span class="badge">Booking</span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($row['subject']); ?>
                                </td>
                                <td title="<?php echo htmlspecialchars($row['message']); ?>">
                                    <?php echo substr(htmlspecialchars($row['message']), 0, 40); ?>...
                                </td>
                                <td>
                                    <a href="dashboard.php?delete=<?php echo $row['id']; ?>" class="action-btn"
                                       onclick="return confirm('Delete this message?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center;">No inquiries found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'pkg_deleted'): ?>
            <div class="alert">Package deleted successfully.</div>
        <?php endif; ?>

        <h2 style="margin-top: 40px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Current Packages</h2>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th width="10%">Image</th>
                        <th width="25%">Title</th>
                        <th width="15%">Price</th>
                        <th width="20%">Duration</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pkg_sql = "SELECT * FROM packages ORDER BY id DESC";
                    $pkg_result = mysqli_query($conn, $pkg_sql);

                    if (mysqli_num_rows($pkg_result) > 0):
                        while ($pkg = mysqli_fetch_assoc($pkg_result)):
                    ?>
                        <tr>
                            <td>
                                <img src="../assets/images/<?php echo $pkg['package_image']; ?>" class="img-thumb">
                            </td>
                            <td><strong><?php echo htmlspecialchars($pkg['package_title']); ?></strong></td>
                            <td style="color: #27ae60; font-weight: bold;">$<?php echo $pkg['package_price']; ?></td>
                            <td><?php echo $pkg['package_duration']; ?></td>
                            <td>
                                <a href="dashboard.php?delete_package=<?php echo $pkg['id']; ?>" class="action-btn"
                                   onclick="return confirm('Delete this package? It will be removed from the website.');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="5" style="text-align: center;">No packages added yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>