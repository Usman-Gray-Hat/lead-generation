<?php
include("../dbConnect.php");
$employee_id = $_GET['employee_id']??"";
$employee_name = $_GET['employee_name']??"";
$from = $_GET['from']??"";
$to = $_GET['to']??"";

// For generating file name using employee name without white spaces
$fileName = trim($employee_name);

// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment;filename=$fileName.xls");

if($from==="" || $to==="") // If date range is not selected
{
   $msg = "ATTENDANCE REPORT OF ".strtoupper($employee_name)."";

   $query = "SELECT * FROM attendance 
   WHERE employee_id_FK='$employee_id' 
   ORDER BY date_created ASC";
}
else // If date range is selected
{
   $msg = "ATTENDANCE REPORT OF ".strtoupper($employee_name)." 
   (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";

   $query = "SELECT * FROM attendance 
   WHERE employee_id_FK='$employee_id'
   AND date_created BETWEEN '$from' AND '$to'
   ORDER BY date_created ASC";
}
$result = mysqli_query($conn,$query);
$rows = mysqli_num_rows($result);
$i = 1;

if($rows>0)
{
   $table = "<table border='1px' style='text-align:center; width:45%;'>
   <thead>
   <tr>
   <th style='text-align:center; background-color: #8ea9db;' colspan='5'>".$msg."</th>
   </tr>
   <tr>
   <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
   <th style='text-align:center; background-color: #8ea9db;'>DATE OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>TYPE OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>REASON OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>ABSENT MARKED BY</th>
   </thead>
   </tr><tbody>";

   while($data=mysqli_fetch_assoc($result))
   {
      $table .= "<tr>
      <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
      <td style='text-align:center; vertical-align: middle;'>".date('M-d-Y',strtotime($data['date_created']))."</td>
      <td style='text-align:center; vertical-align: middle;'>".$data['type']."</td>
      <td style='text-align:center; vertical-align: middle;'>".$data['reason']."</td>
      <td style='text-align:center; vertical-align: middle;'>".$data['marked_by']."</td>
      </tr>";
   }
}
else
{ 
   $table = "<table border='1px' style='text-align:center;'>
   <thead>
   <tr>
   <th style='text-align:center; background-color: #8ea9db;' colspan='5'>".$msg."</th>
   </tr>
   <tr>
   <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
   <th style='text-align:center; background-color: #8ea9db;'>DATE OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>TYPE OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>REASON OF ABSENCE</th>
   <th style='text-align:center; background-color: #8ea9db;'>ABSENT MARKED BY</th>
   </thead>
   </tr><tbody><tr>
   <td style='text-align:center; vertical-align: middle;' colspan='5'>No Records Available</td>
   </tr>";
}


$table .= "</tbody></table>";
echo $table;
?>