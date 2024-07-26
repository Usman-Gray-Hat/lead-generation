<?php
include("../dbConnect.php");
$id = $_GET['id']??"";
$name = $_GET['name']??"";
$from = $_GET['from']??"";
$to = $_GET['to']??"";

// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$name.xls");

if($from==="" || $to==="") // If date range is not selected
{
    $msg = "LEADS COUNT TRACK RECORD OF ".strtoupper($name)."";

    $query = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id' 
    ORDER BY id DESC";

    // Query for Sum and Average
    $query2 = "SELECT COALESCE(SUM(total_leads),0) AS total_leads,
    AVG(total_leads) AS average_leads
    FROM leads_count 
    WHERE admin_id_FK='$id'";
}
else // If date range is selected
{
    $msg = "LEADS COUNT TRACK RECORD OF ".strtoupper($name)." (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";

    $query = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id'
    AND leads_count_date BETWEEN '$from' AND '$to'
    ORDER BY leads_count_date ASC"; 

    // Query for Sum and Average
    $query2 = "SELECT COALESCE(SUM(total_leads),0) AS total_leads,
    AVG(total_leads) AS average_leads 
    FROM leads_count 
    WHERE admin_id_FK='$id' 
    AND leads_count_date BETWEEN '$from' AND '$to'";
}

// For sum
$result2 = mysqli_query($conn,$query2);
$data2 = mysqli_fetch_assoc($result2);

$sum_of_leads = $data2['total_leads'];
$average_of_leads = $data2['average_leads'];

$result = mysqli_query($conn,$query);
$rows = mysqli_num_rows($result);
$i = 1;

if($rows>0)
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='3'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL LEADS</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".date('F-d-Y',strtotime($data['leads_count_date']))."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['total_leads']."</td>
       </tr>";
    }
}
else
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='3'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL LEADS</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='3'>No Records Available</td>
    </tr>"; 
}

$table.= "</tbody><tfoot>
<tr>
    <td style='text-align:right; vertical-align: middle; background-color: #8ea9db;' colspan='2'>SUM</td>
    <td style='text-align:center; vertical-align: middle; background-color: #8ea9db;' colspan='1'>".$sum_of_leads." </td>
</tr>
<tr>
    <td style='text-align:right; vertical-align: middle; background-color: #8ea9db;' colspan='2'>AVERAGE</td>
    <td style='text-align:center; vertical-align: middle; background-color: #8ea9db;' colspan='1'>".number_format($average_of_leads,1)." </td>
</tr>
</tfoot></table>";
echo $table;
?>