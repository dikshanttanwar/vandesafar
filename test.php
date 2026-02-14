<?php
include_once "./config/db_connect.php";
$sql = "SELECT * FROM packages";

$result = mysqli_query($conn, $sql);

print_r(mysqli_fetch_all($result, MYSQLI_ASSOC));

?>