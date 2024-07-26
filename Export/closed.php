<?php
include("../dbConnect.php");
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=ClosedDealsReport.xls");
$from = $_GET['from']??"";
$to = $_GET['to']??"";


if($from==="" || $to==="") // If date range is not selected
{
    $msg = "CLOSED DEALS INFORMATION";
    $query = "SELECT * FROM targets 
    ORDER BY id ASC";
}
else // If date range is selected
{
    $msg = "CLOSED DEALS INFORMATION (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO 
    ".strtoupper(date('M-d-Y',strtotime($to))).")";
    $query = "SELECT * FROM targets 
    WHERE date_created BETWEEN '$from' AND '$to' 
    ORDER BY id ASC";
}
$result = mysqli_query($conn,$query);
$i = 1;
$rows = mysqli_num_rows($result);

if($rows>0)
{
    $table = "<table border='1px' style='text-align:center; width:70%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='8'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSING DATE</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>ITEMS</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSED AMOUNT</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
        if($data['channel_link']==="" || $data['channel_link']==="-") 
        {
            $channel_link = "Channel link is missing";
        }
        else
        {
            $channel_link = $data['channel_link'];
        }
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".date("M-d-Y",strtotime($data['date_created']))."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['account_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['platform_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['client_username']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$channel_link."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['items']."</td>
       <td style='text-align:center; vertical-align: middle;'>$".number_format($data['amount'],2)."</td>
       </tr>";
    }
}
else
{
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='8'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSING DATE</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>ITEMS</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSED AMOUNT</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='8'>No Records Available</td>
    </tr>";
}

$table.= "</tbody></table>";
echo $table;
?>