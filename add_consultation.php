<?php
require_once(dirname(__FILE__) . "/db_connection.inc.php");
require_once(dirname(__FILE__) . "/helpers.inc.php");
// Patients
$sql_patients = "SELECT * FROM patients";
$patients = $conn->query($sql_patients);
// Symptomps
$sql_symptoms = "SELECT * FROM symptoms";
$sel_symptoms = $conn->query($sql_symptoms);

// define variables and set to empty values
$patient_id = $chief_complaint = $nurse_on_duty = "";
$symptoms = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = test_input($_POST["patient_id"]);
    $chief_complaint = test_input($_POST["chief_complaint"]);

    $sql = "INSERT INTO consultations (patient_id, chief_complaint,  date_created) 
    VALUES ('" . $patient_id . "', '" . $chief_complaint . "', NOW())";

    if ($conn->query($sql) === TRUE) {
        $consultation_id = $conn->insert_id;
        // Symptoms
        foreach ($_POST['symptoms'] as $key => $symptom_id) {
            $sql = "INSERT INTO consultation_symptoms (consultation_id, symptom_id,  date_created) 
    VALUES ('" . $consultation_id . "', '" . $symptom_id . "', NOW())";
            if ($conn->query($sql) === TRUE) {
                // Do nothing
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "manage_consultation.php?id=" . $consultation_id;
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

    <h2>Add Consultation</h2>
    <a class="button-link" href="index.php" style="float:right;">BACK</a>

    <form action="add_consultation.php" method="POST">
        <label for="patient_id">Patient:</label><br>
        <select id="patient_id" name="patient_id">
            <?php
            if ($patients->num_rows > 0) {
                // output data of each row
                while ($pat = $patients->fetch_assoc()) {
                    echo "<option value='" . $pat["id"] . "'>" . $pat["last_name"] . ", " . $pat["first_name"] . ", " . $pat["middle_name"] . " " . $pat["suffix_name"] . " | " . $pat["birthdate"] . " | " . $pat["gender"] . "</option>";
                }
            } ?>
        </select><br>
        <label for="chief_complaint">Chief Complaint:</label><br>
        <textarea id="chief_complaint" name="chief_complaint" rows="4" cols="50"></textarea><br>
        <label>Symptoms:</label><br>
        <?php
        if ($sel_symptoms->num_rows > 0) {
            // output data of each row
            while ($symp = $sel_symptoms->fetch_assoc()) { ?>
                <input type="checkbox" id="symp<?php echo $symp['id']; ?>" name="symptoms[]" value="<?php echo $symp['id']; ?>">
                <label for="symp<?php echo $symp['id']; ?>"><?php echo $symp['name']; ?></label><br>
        <?php
            }
        }
        ?>

        <br>
        <input type="submit" value="Submit">
    </form>


</body>

</html>
<?php

$conn->close();
