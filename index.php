 <?php
    require_once(dirname(__FILE__) . "/db_connection.inc.php");
    require_once(dirname(__FILE__) . "/helpers.inc.php");
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
     </style>
 </head>

 <body>

     <h2>Patients</h2>

     <table>
         <tr>
             <th>Company</th>
             <th>Contact</th>
             <th>Country</th>
         </tr>
         <tr>
             <td>Alfreds Futterkiste</td>
             <td>Maria Anders</td>
             <td>Germany</td>
         </tr>
     </table>

 </body>

 </html>