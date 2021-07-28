<?php
require_once(dirname(__FILE__) . "/db_connection.inc.php");
require_once(dirname(__FILE__) . "/helpers.inc.php");

// define variables and set to empty values
$patient_id = $doctor_id = $doctor_type_id  = $doctor_type_name = $fee = $sc_fee = $selected_fee = "";
$chief_complaint  = "";
$first_name = $middle_name = $last_name = $suffix_name = "";
$birthdate = $gender = $age = "";
$symptoms = [];

// Consultation
$sql_cons = "SELECT * FROM consultations WHERE id=" . $_GET['id'];
$con_result = $conn->query($sql_cons);

if ($con_result->num_rows == 1) {
    $row = $con_result->fetch_assoc();
    // define variables and set to empty values
    $patient_id = $row["patient_id"];
    $chief_complaint = $row["chief_complaint"];
}

// Patient
$sql_pat = "SELECT * FROM patients WHERE id=" . $patient_id;
$pat_result = $conn->query($sql_pat);

if ($pat_result->num_rows == 1) {
    $row = $pat_result->fetch_assoc();
    // define variables and set to empty values
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $suffix_name = $row["suffix_name"];
    $birthdate = $row["birthdate"];
    $gender = $row["gender"];
}
// Symptomps
$sql_symptoms = "SELECT consultation_symptoms.*, symptoms.name FROM consultation_symptoms LEFT JOIN symptoms ON symptoms.id = consultation_symptoms.symptom_id WHERE consultation_symptoms.consultation_id=" . $_GET['id'];
$sel_symptoms = $conn->query($sql_symptoms);

// Patient
$sql_doc_type = "SELECT * FROM doctor_types WHERE symptoms_handled >=" . $sel_symptoms->num_rows . " ORDER BY symptoms_handled ASC LIMIT 1";
$doc_type_result = $conn->query($sql_doc_type);

if ($doc_type_result->num_rows == 1) {
    $row = $doc_type_result->fetch_assoc();
    // define variables and set to empty values
    $doctor_type_id = $row["id"];
    $doctor_type_name = $row["type"];
    $fee = $row["fee"];
    $sc_fee = $row["sc_fee"];
} else {
    $sql_doc_type = "SELECT * FROM doctor_types WHERE symptoms_handled <" . $sel_symptoms->num_rows . " ORDER BY symptoms_handled DESC LIMIT 1";
    $doc_type_result = $conn->query($sql_doc_type);

    if ($doc_type_result->num_rows == 1) {
        $row = $doc_type_result->fetch_assoc();
        // define variables and set to empty values
        $doctor_type_id = $row["id"];
        $doctor_type_name = $row["type"];
        $fee = $row["fee"];
        $sc_fee = $row["sc_fee"];
    }
}
// Compute Age and Fee
$today = date('Y-m-d');
$diff = date_diff(date_create($birthdate), date_create($today));
$age = $diff->format('%y');
if ($age >= 60) {
    $selected_fee = $sc_fee;
} else {
    $selected_fee = $fee;
}

// Doctors
$sql_docs = "SELECT * FROM doctors WHERE doctor_type_id=" . $doctor_type_id;
$docs = $conn->query($sql_docs);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = test_input($_POST["doctor_id"]);

    $sql = "UPDATE consultations ";
    $sql .= "SET doctor_type_id = '" . $doctor_type_id . "', fee = '" . $selected_fee . "', doctor_id = '" . $doctor_id . "', date_updated=NOW() ";
    $sql .= "WHERE id=" . $_GET['id'];

    if ($conn->query($sql) === TRUE) {
        echo "New consultation created successfully";
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "index.php";
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

    <h2>Manage Consultation</h2>
    <a class="button-link" href="index.php" style="float:right;">BACK</a>

    <form action="manage_consultation.php?id=<?php echo $_GET['id']; ?>" method="POST">
        <label for="patient_id">Patient:</label><br>
        <input readonly type="text" id="patient_id" name="patient_id" size="80" value="<?php echo $last_name . ", " . $first_name . ", " . $middle_name . " " . $suffix_name . " | " . $birthdate . " | " . $gender; ?>"><br>
        <label for="chief_complaint">Chief Complaint:</label><br>
        <textarea readonly id="chief_complaint" name="chief_complaint" rows="4" cols="50"><?php echo $chief_complaint; ?></textarea><br>
        <label>Symptoms:</label><br>
        <?php
        if ($sel_symptoms->num_rows > 0) {
            // output data of each row
            while ($symp = $sel_symptoms->fetch_assoc()) { ?>
                <input type="checkbox" disabled="disabled" checked="checked" id="symp<?php echo $symp['id']; ?>" name="symptoms[]" value="<?php echo $symp['id']; ?>">
                <label for="symp<?php echo $symp['id']; ?>"><?php echo $symp['name']; ?></label><br>
        <?php
            }
        }
        ?>
        <label for="doctor_type_id">Doctor Type:</label><br>
        <input readonly type="text" id="doctor_type_id" name="doctor_type_id" value="<?php echo $doctor_type_name; ?>"><br>

        <label for="fee">Fee:</label><br>
        <input readonly type="text" id="fee" name="fee" value="<?php echo $selected_fee; ?>"><br>

        <label for="doctor_id">Doctor:</label><br>
        <select id="doctor_id" name="doctor_id">
            <?php
            if ($docs->num_rows > 0) {
                // output data of each row
                while ($doc = $docs->fetch_assoc()) {
                    echo "<option value='" . $doc["id"] . "'>" . $doc["name"] . "</option>";
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
