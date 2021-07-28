<?php
require_once(dirname(__FILE__) . "/db_connection.inc.php");
require_once(dirname(__FILE__) . "/helpers.inc.php");
// Doctor Types
$sql_doc_types = "SELECT * FROM doctor_types";
$doc_types = $conn->query($sql_doc_types);

// define variables and set to empty values
$name = $contact_no = $license_no = $doctor_type_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = test_input($_POST["name"]);
    $contact_no = test_input($_POST["contact_no"]);
    $license_no = test_input($_POST["license_no"]);
    $doctor_type_id = $_POST["doctor_type_id"];

    $sql = "INSERT INTO doctors (name, contact_no, license_no, doctor_type_id, date_created) 
    VALUES ('" . $name . "', '" . $contact_no . "', '" . $license_no . "', '" . $doctor_type_id . "', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New doctor created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<body>

    <h2>Add Doctor</h2>

    <form action="add_doctor.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="contact_no">Contact No.</label><br>
        <input type="text" id="contact_no" name="contact_no"><br>
        <label for="license_no">License No.</label><br>
        <input type="text" id="license_no" name="license_no"><br>
        <label for="doctor_type_id">Type:</label><br>
        <select id="doctor_type_id" name="doctor_type_id">
            <?php
            if ($doc_types->num_rows > 0) {
                // output data of each row
                while ($doc_type = $doc_types->fetch_assoc()) {
                    echo "<option value='" . $doc_type["id"] . "'>" . $doc_type["type"] . "</option>";
                }
            } ?>
        </select><br>
        <br>
        <input type="submit" value="Submit">
    </form>


</body>

</html>
<?php

$conn->close();
