<?php
function get_db_connection() {
    $servername = "localhost";
    $username = "root";
    $password = "Irfan_78667821";
    $database = "testing";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
