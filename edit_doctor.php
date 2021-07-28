<?php
require_once(dirname(__FILE__) . "/db_connection.inc.php");
require_once(dirname(__FILE__) . "/helpers.inc.php");

// define variables and set to empty values
$name = $contact_no = $license_no = $doctor_type_id = "";
// Doctor
$sql_doc = "SELECT * FROM doctors WHERE id=" . $_GET['id'];
$doc_result = $conn->query($sql_doc);

if ($doc_result->num_rows == 1) {
    $row = $doc_result->fetch_assoc();
    // define variables and set to empty values
    $name = $row['name'];
    $contact_no = $row['contact_no'];
    $license_no = $row['license_no'];
    $doctor_type_id = $row['doctor_type_id'];
}
// Doctor Types
$sql_doc_types = "SELECT * FROM doctor_types";
$doc_types = $conn->query($sql_doc_types);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = test_input($_POST["name"]);
    $contact_no = test_input($_POST["contact_no"]);
    $license_no = test_input($_POST["license_no"]);
    $doctor_type_id = $_POST["doctor_type_id"];

    $sql = "UPDATE doctors ";
    $sql .= "SET name = '" . $name . "', contact_no = '" . $contact_no . "', license_no = '" . $license_no . "', doctor_type_id = '" . $doctor_type_id . "', date_updated=NOW() ";
    $sql .= "WHERE id=" . $_GET['id'];

    if ($conn->query($sql) === TRUE) {
        echo "Doctor updated successfully";
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "doctors.php";
        header("Location: http://$host$uri/$extra");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<style>
    .button-link {
        border: 1px solid #ddd;
        color: #000;
        text-align: center;
        padding: 8px;
        text-decoration: none;
        height: 14px;
        background-color: #ccc;
    }
</style>

<body>

    <h2>Edit Doctor</h2>
    <a class="button-link" href="index.php" style="float:right;">BACK</a>

    <form action="edit_doctor.php?id=<?php echo $_GET['id']; ?>" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name ?>"><br>
        <label for="contact_no">Contact No.</label><br>
        <input type="text" id="contact_no" name="contact_no" value="<?php echo $contact_no ?>"><br>
        <label for=" license_no">License No.</label><br>
        <input type="text" id="license_no" name="license_no" value="<?php echo $license_no ?>"><br>
        <label for="doctor_type_id">Type:</label><br>
        <select id="doctor_type_id" name="doctor_type_id">
            <?php
            if ($doc_types->num_rows > 0) {
                // output data of each row
                while ($doc_type = $doc_types->fetch_assoc()) {
                    echo "<option ";
                    echo $doctor_type_id == $doc_type["id"] ? 'selected="selected" ' : '';
                    echo "value='" . $doc_type["id"] . "'>" . $doc_type["type"] . "</option>";
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
