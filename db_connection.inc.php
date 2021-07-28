 <?php
    // Database Connection
    $conn = new
        mysqli("localhost", "admin", "password", "djjdkasdcdc_db");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?> 