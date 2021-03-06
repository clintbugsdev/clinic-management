 <?php
    require_once(dirname(__FILE__) . "/db_connection.inc.php");
    require_once(dirname(__FILE__) . "/helpers.inc.php");


    // Symptomps
    $sql = "SELECT consultations.*, 
    patients.first_name, 
    patients.middle_name, 
    patients.last_name, 
    patients.suffix_name, 
    patients.birthdate, 
    patients.gender, 
    doctors.name as doctor_name,
    doctor_types.type as doctor_type_name
    FROM consultations 
    LEFT JOIN patients ON patients.id = consultations.patient_id 
    LEFT JOIN doctors ON doctors.id = consultations.doctor_id 
    LEFT JOIN doctor_types ON doctor_types.id = consultations.doctor_type_id 
    ORDER BY consultations.date_created DESC";
    $consultations = $conn->query($sql);
    ?>

 <!DOCTYPE html>
 <html>

 <head>
     <style>
         table {
             font-family: arial, sans-serif;
             border-collapse: collapse;
             width: 100%;
         }

         td,
         th {
             border: 1px solid #dddddd;
             text-align: left;
             padding: 8px;
         }

         tr:nth-child(even) {
             background-color: #dddddd;
         }

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
 </head>

 <body>

     <h2>Consultations</h2>

     <a class="button-link" href="add_consultation.php">NEW CONSULTATION</a>
     <a class="button-link" href="add_patient.php">NEW PATIENT</a>
     <a class="button-link" href="add_doctor.php">NEW DOCTOR</a>

     <a class="button-link" href="doctors.php" style="float:right;">DOCTORS</a>
     <a class="button-link" href="patients.php" style="float:right; margin-right:5px;">PATIENTS</a>
     <table style="margin-top: 20px;">
         <tr>
             <th>Date</th>
             <th>Time</th>
             <th>Patient</th>
             <th>Birthdate</th>
             <th>Age</th>
             <th>Gender</th>
             <th>Chief Complaint</th>
             <th>Doctor</th>
             <th>Type</th>
             <th>Fee</th>
         </tr>
         <?php
            if ($consultations->num_rows > 0) {
                // output data of each row
                while ($row = $consultations->fetch_assoc()) { ?>

                 <tr>
                     <td><?php echo date('Y-m-d', strtotime($row['date_created'])); ?></td>
                     <td><?php echo date('h:i a', strtotime($row['date_created'])); ?></td>
                     <td><?php echo $row['first_name']; ?></td>
                     <td><?php echo $row['birthdate']; ?></td>
                     <td>
                         <?php
                            $today = date('Y-m-d');
                            $diff = date_diff(date_create($row['birthdate']), date_create($today));
                            echo $diff->format('%y');
                            ?>
                     </td>
                     <td><?php echo $row['gender']; ?></td>
                     <td><?php echo $row['chief_complaint']; ?></td>
                     <td><?php echo $row['doctor_name']; ?></td>
                     <td><?php echo $row['doctor_type_name']; ?></td>
                     <td><?php echo $row['fee']; ?></td>
                 </tr>
         <?php
                }
            }
            ?>
     </table>

 </body>

 </html>