<?php
include("../dbConnect.php");
date_default_timezone_set('Asia/Karachi');
$from = $_GET['from']??"";
$to = $_GET['to']??"";
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=MonthlyAttendance.xls");

if($from==="" || $to==="") // If date range is not selected
{
    $msg = "TOTAL ABSENTS IN (".strtoupper(date('F-Y')).")";

    $query = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
    COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
    FROM employees e LEFT JOIN attendance a 
    ON e.id=a.employee_id_FK
    AND MONTH(a.date_created) = MONTH(CURRENT_DATE())
    AND YEAR(a.date_created) = YEAR(CURRENT_DATE())
    GROUP BY e.id, e.full_name
    ORDER BY total_absents DESC";    
}
else // If date range is selected
{
    $msg = "TOTAL ABSENTS  
    (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";
    
    $query = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
    COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
    FROM employees e LEFT JOIN attendance a 
    ON e.id=a.employee_id_FK
    AND a.date_created BETWEEN '$from' AND '$to'
    GROUP BY e.id, e.full_name
    ORDER BY total_absents DESC";  
}

$result = mysqli_query($conn,$query);
$row = mysqli_num_rows($result);
$i = 1;
if($row>0)
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='4'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL ABSENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL HALF DAYS</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['total_absents']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['total_half_days']."</td>
       </tr>";
    }
}
else
{ 
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='4'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL ABSENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL HALF DAYS</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='4'>No Records Available</td>
    </tr></tbody>";
}

$table.= "</tbody></table>";
echo $table;
?>