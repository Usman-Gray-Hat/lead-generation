<?php
include("../dbConnect.php");
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=Follow-upsReport.xls");


$msg = "FOLLOW UP RECORDS";
$query = "SELECT * FROM leads 
WHERE status='Follow up date'
ORDER BY id DESC";

$result = mysqli_query($conn,$query);
$i = 1;
$rows = mysqli_num_rows($result);

if($rows>0)
{
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='8'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>COMMENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UP DATE WITH REMARKS</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
        if($data['channel_link']==="") 
        {
            $channel_link = "Channel link is missing";
        }
        else
        {
            $channel_link = $data['channel_link'];
        }
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['account_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['platform_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['client_username']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$channel_link."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['comments']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['follow_up_date_with_remarks']."</td>
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
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>COMMENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UP DATE WITH REMARKS</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='8'>No Records Available</td>
    </tr>";
}

$table.= "</tbody></table>";
echo $table;
?>