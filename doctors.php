 <?php
    require_once(dirname(__FILE__) . "/db_connection.inc.php");
    require_once(dirname(__FILE__) . "/helpers.inc.php");


    // Symptomps
    $sql = "SELECT doctors.*,";
    $sql .= "doctor_types.type as doctor_type_name,";
    $sql .= "doctor_types.fee as doctor_fee,";
    $sql .= "doctor_types.sc_fee as doctor_sc_fee ";
    $sql .= "FROM doctors ";
    $sql .= "LEFT JOIN doctor_types ON doctor_types.id = doctors.doctor_type_id ";
    if (!empty($_GET['s'])) {
        $sql .= "WHERE doctors.name LIKE '%" . $_GET['s'] . "%'";
    }
    $sql .= "ORDER BY doctors.date_created DESC";
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

     <h2>Doctors</h2>
     <a class="button-link" href="add_doctor.php">NEW DOCTOR</a>
     <a class="button-link" href="index.php" style="float:right;">BACK</a>

     <form style="margin-top: 20px;" action="doctors.php?s=<?php echo $_GET['s']; ?>" method="GET">
         <input type="text" id="s" name="s">
         <input type="submit" value="Search">
     </form>
     <table style="margin-top: 20px;">
         <tr>
             <th>Name</th>
             <th>Contact No</th>
             <th>License No</th>
             <th>Type</th>
             <th>Fee</th>
             <th>Senior Citizen Fee</th>
             <th>Date Created</th>
             <th>Actions</th>
         </tr>
         <?php
            if ($consultations->num_rows > 0) {
                // output data of each row
                while ($row = $consultations->fetch_assoc()) { ?>

                 <tr>
                     <td><?php echo $row['name']; ?></td>
                     <td><?php echo $row['contact_no']; ?></td>
                     <td><?php echo $row['license_no']; ?></td>
                     <td><?php echo $row['doctor_type_name']; ?></td>
                     <td><?php echo $row['doctor_fee']; ?></td>
                     <td><?php echo $row['doctor_sc_fee']; ?></td>
                     <td><?php echo date('Y-m-d', strtotime($row['date_created'])); ?></td>
                     <td>
                         <a class="button-link" href="edit_doctor.php?id=<?php echo $row['id']; ?>">Edit</a>
                     </td>
                 </tr>
         <?php
                }
            }
            ?>
     </table>

 </body>

 </html>