<?php
require_once(dirname(__FILE__) . "/db_connection.inc.php");
require_once(dirname(__FILE__) . "/helpers.inc.php");

// define variables and set to empty values
$first_name = $middle_name = $last_name = $suffix_name = "";
$permanent_address = $current_address = $work_address = "";
$birthdate = $gender = "";
$mobile_no = $work_no = $callback_no = $home_no = $other_no = "";
// Patient
$sql_pat = "SELECT * FROM patients WHERE id=" . $_GET['id'];
$pat_result = $conn->query($sql_pat);

if ($pat_result->num_rows == 1) {
    $row = $pat_result->fetch_assoc();
    // define variables and set to empty values
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $suffix_name = $row["suffix_name"];
    $permanent_address = $row["permanent_address"];
    $current_address = $row["current_address"];
    $work_address = $row["work_address"];
    $birthdate = $row["birthdate"];
    $gender = $row["gender"];
    $mobile_no = $row["mobile_no"];
    $work_no = $row["work_no"];
    $callback_no = $row["callback_no"];
    $home_no = $row["home_no"];
    $other_no = $row["other_no"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = test_input($_POST["first_name"]);
    $middle_name = test_input($_POST["middle_name"]);
    $last_name = test_input($_POST["last_name"]);
    $suffix_name = test_input($_POST["suffix_name"]);
    $permanent_address = test_input($_POST["permanent_address"]);
    $current_address = test_input($_POST["current_address"]);
    $work_address = test_input($_POST["work_address"]);
    $birthdate = test_input($_POST["birthdate"]);
    $gender = test_input($_POST["gender"]);
    $mobile_no = test_input($_POST["mobile_no"]);
    $work_no = test_input($_POST["work_no"]);
    $callback_no = test_input($_POST["callback_no"]);
    $home_no = test_input($_POST["home_no"]);
    $other_no = test_input($_POST["other_no"]);


    $sql = "UPDATE patients ";
    $sql .= "SET ";
    $sql .= "first_name='" . $first_name . "', middle_name='" . $middle_name . "', last_name='" . $last_name . "', suffix_name='" . $suffix_name . "', ";
    $sql .= "permanent_address='" . $permanent_address . "', current_address='" . $current_address . "', work_address='" . $work_address . "', ";
    $sql .= "birthdate='" . $birthdate . "', gender='" . $gender . "', ";
    $sql .= "mobile_no='" . $mobile_no . "', work_no='" . $work_no . "', callback_no='" . $callback_no . "', home_no='" . $home_no . "', other_no='" . $other_no . "', ";
    $sql .= "date_updated=NOW()";
    $sql .= "WHERE id=" . $_GET['id'];

    if ($conn->query($sql) === TRUE) {
        echo "New patient created successfully";
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "patients.php";
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

    <h2>Edit Patient</h2>
    <a class="button-link" href="index.php" style="float:right;">BACK</a>

    <form action="edit_patient.php?id=<?php echo $_GET['id']; ?>" method="POST">
        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>"><br>
        <label for="middle_name">Middle Name:</label><br>
        <input type="text" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>"><br>
        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>"><br>
        <label for="suffix_name">Suffix:</label><br>
        <input type="text" id="suffix_name" name="suffix_name" value="<?php echo $suffix_name; ?>"><br>
        <label for="permanent_address">Permanent Address:</label><br>
        <input type="text" id="permanent_address" name="permanent_address" size="80" value="<?php echo $permanent_address; ?>"><br>
        <label for="current_address">Current Address:</label><br>
        <input type="text" id="current_address" name="current_address" size="80" value="<?php echo $current_address; ?>"><br>
        <label for="work_address">Work Address:</label><br>
        <input type="text" id="work_address" name="work_address" size="80" value="<?php echo $work_address; ?>"><br>
        <label for="birthdate">Birthdate:</label><br>
        <input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>"><br>
        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender">
            <option <?php echo $gender == "Male" ? 'selected="selected"' : ''; ?> value="Male">Male</option>
            <option <?php echo $gender == "Female" ? 'selected="selected"' : ''; ?> value="Female">Female</option>
            <option <?php echo $gender == "Other" ? 'selected="selected"' : ''; ?> value="Other">Other</option>
        </select><br>
        <label for="mobile_no">Mobile No:</label><br>
        <input type="text" id="mobile_no" name="mobile_no" value="<?php echo $mobile_no; ?>"><br>
        <label for="work_no">Work No:</label><br>
        <input type="text" id="work_no" name="work_no" value="<?php echo $work_no; ?>"><br>
        <label for="callback_no">Callback No:</label><br>
        <input type="text" id="callback_no" name="callback_no" value="<?php echo $callback_no; ?>"><br>
        <label for="home_no">Home No:</label><br>
        <input type="text" id="home_no" name="home_no" value="<?php echo $home_no; ?>"><br>
        <label for="other_no">Other No:</label><br>
        <input type="text" id="other_no" name="other_no" value="<?php echo $other_no; ?>"><br>
        <br>
        <input type="submit" value="Submit">
    </form>


</body>

</html>
<?php

$conn->close();
