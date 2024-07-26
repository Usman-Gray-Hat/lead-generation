<?php
include("../dbConnect.php");
date_default_timezone_set('Asia/Karachi');
$from = $_GET['from']??"";
$to = $_GET['to']??"";

// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=MonthlyTarget.xls");
 
if($from==="" || $to==="") // If date range is not selected
{
    $msg = "TARGET ACHIEVED IN THIS MONTH (".strtoupper(date('F-Y')).")";

    $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(SUM(t.amount), 0) AS total_amount
        FROM employees e
        LEFT JOIN targets t 
        ON e.id = t.employee_id_FK 
        AND MONTH(t.date_created) = MONTH(CURRENT_DATE())
        AND YEAR(t.date_created) = YEAR(CURRENT_DATE())
        LEFT JOIN admins a 
        ON e.id = a.employee_id_FK
        WHERE a.type = '2' AND a.role = 'Lead Generation'
        GROUP BY e.id, e.full_name
        ORDER BY total_amount DESC";
}
else // If date range is selected
{
    $msg = "TARGET ACHIEVED 
    (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";

    $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(SUM(t.amount), 0) AS total_amount
        FROM employees e
        LEFT JOIN targets t 
        ON e.id = t.employee_id_FK 
        AND t.date_created BETWEEN '$from' AND '$to'
        LEFT JOIN admins a 
        ON e.id = a.employee_id_FK
        WHERE a.type = '2' AND a.role = 'Lead Generation'
        GROUP BY e.id, e.full_name
        ORDER BY total_amount DESC";
}

$result = mysqli_query($conn,$query);
$row = mysqli_num_rows($result);
$i = 1;
if($row>0)
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='6'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TARGET ACHIEVED</th>
    <th style='text-align:center; background-color: #8ea9db;'>REMAINING TARGET</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL TARGET</th>
    <th style='text-align:center; background-color: #8ea9db;'>STATUS</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
       $total_target = "$".number_format(1000,2)."";
       $target_achieved = "$".number_format($data['total_amount'],2)."";
       $remaining_target = "$".number_format(1000 - $data['total_amount'],2)."";

       if($data['total_amount']>=1000)
       {
           $status = "<b style='color:green;'>Target Accomplished</b>";
           $remaining_target = "<b style='color:green; font-weight:bolder;'>Completed</b>";
       }
       else if($data['total_amount']>=500)
       {
           $status = "<b style='color:blue;'>Safe Zone</b>";
       }
       else if($data['total_amount']<500)
       {
           $status = "<b style='color:red;'>Red Zone</b>";
       }

       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>$".number_format($data['total_amount'],2)."</td>
       <td style='text-align:center; vertical-align: middle;'>".$remaining_target."</td>
       <td style='text-align:center; vertical-align: middle;'>".$total_target."</td>
       <td style='text-align:center; vertical-align: middle;'>".$status."</td>
       </tr>";
    }
}
else
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='6'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TARGET ACHIEVED</th>
    <th style='text-align:center; background-color: #8ea9db;'>REMAINING TARGET</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL TARGET</th>
    <th style='text-align:center; background-color: #8ea9db;'>STATUS</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='6'>No Records Available</td>
    </tr></tbody>";
}

$table.= "</tbody></table>";
echo $table;
?>