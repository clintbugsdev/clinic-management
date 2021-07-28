 <?php
    require_once(dirname(__FILE__) . "/db_connection.inc.php");
    require_once(dirname(__FILE__) . "/helpers.inc.php");

    // Patients
    $sql = "SELECT * FROM patients ";
    if (!empty($_GET['s'])) {
        $sql .= "WHERE first_name LIKE '%" . $_GET['s'] . "%' ";
        $sql .= "OR middle_name LIKE '%" . $_GET['s'] . "%' ";
        $sql .= "OR last_name LIKE '%" . $_GET['s'] . "%' ";
    }
    $sql .= "ORDER BY date_created DESC";
    $patients = $conn->query($sql);
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

     <h2>Patients</h2>
     <a class="button-link" href="add_patient.php">NEW PATIENT</a>
     <a class="button-link" href="index.php" style="float:right;">BACK</a>

     <form style="margin-top: 20px;" action="patients.php?s=<?php echo $_GET['s']; ?>" method="GET">
         <input type="text" id="s" name="s">
         <input type="submit" value="Search">
     </form>
     <table style="margin-top: 20px;">
         <tr>
             <th>Last Name</th>
             <th>First Name</th>
             <th>Middle Name</th>
             <th>Suffix</th>
             <th>Birthdate</th>
             <th>Age</th>
             <th>Gender</th>
             <th>Date Created</th>
             <th>Actions</th>
         </tr>
         <?php
            if ($patients->num_rows > 0) {
                // output data of each row
                while ($row = $patients->fetch_assoc()) { ?>

                 <tr>
                     <td><?php echo $row['last_name']; ?></td>
                     <td><?php echo $row['first_name']; ?></td>
                     <td><?php echo $row['middle_name']; ?></td>
                     <td><?php echo $row['suffix_name']; ?></td>
                     <td><?php echo $row['birthdate']; ?></td>
                     <td>
                         <?php
                            $today = date('Y-m-d');
                            $diff = date_diff(date_create($row['birthdate']), date_create($today));
                            echo $diff->format('%y');
                            ?>
                     </td>
                     <td><?php echo $row['gender']; ?></td>
                     <td><?php echo date('Y-m-d', strtotime($row['date_created'])); ?></td>
                     <td>
                         <a class="button-link" href="edit_patient.php?id=<?php echo $row['id']; ?>">Edit</a>
                     </td>
                 </tr>
         <?php
                }
            }
            ?>
     </table>

 </body>

 </html>