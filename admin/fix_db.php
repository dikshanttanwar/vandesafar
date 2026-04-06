<?php
include '../config/db_connect.php';

$sql = "ALTER TABLE packages MODIFY package_description TEXT";
if (mysqli_query($conn, $sql)) {
    echo "SUCCESS";
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
