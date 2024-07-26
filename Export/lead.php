<?php
include("../dbConnect.php");
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=LeadGenerationInfo.xls");
$from = $_GET['from']??"";
$to = $_GET['to']??"";


if($from==="" || $to==="") // If date range is not selected
{
    $msg = "SALES PRODUCTIVITY RECORDS";
    $query = "SELECT * FROM leads 
    ORDER BY id ASC";
}
else // If date range is selected
{
    $msg = "SALES PRODUCTIVITY RECORDS (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO 
    ".strtoupper(date('M-d-Y',strtotime($to))).")";
    $query = "SELECT * FROM leads 
    WHERE lead_date BETWEEN '$from' AND '$to' 
    ORDER BY id ASC";
}
$result = mysqli_query($conn,$query);
$i = 1;
$rows = mysqli_num_rows($result);

if($rows>0)
{
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='13'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>TIMESTAMP</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>COMMENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>STATUS</th>
    <th style='text-align:center; background-color: #8ea9db;'>ITEMS</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UP DATE WITH REMARKS</th>
    <th style='text-align:center; background-color: #8ea9db;'>REASON OF REJECTION</th>
    <th style='text-align:center; background-color: #8ea9db;'>QUALITY</th>
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

        if($data['follow_up_date_with_remarks']==="")
        {
            $follow_up_date_with_remarks = "-";
        }
        else
        {
            $follow_up_date_with_remarks = $data['follow_up_date_with_remarks'];
        }

        if(trim($data['items'])==="")
        {
            $items = "-";
        }
        else
        {
            $items = $data['items'];
        }

        if($data['reason_of_rejection']==="")
        {
            $reason_of_rejection = "-";
        }
        else
        {
            $reason_of_rejection = $data['reason_of_rejection'];
        }

        if($data['status']==="Closed")
        {
            $status = "<b style='color:green;'>".$data['status']."</b>";
        }
        else if($data['status']==="Follow up date")
        {
            $status = "<b style='color:blue;'>Follow up</b>";
        }
        else if($data['status']==="Rejected")
        {
            $status = "<b style='color:red;'>".$data['status']."</b>";
        }
        else
        {
            $status = $data['status'];
        }
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".date("M-d-Y h:i a",strtotime($data['lead_timestamp']))."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['account_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['platform_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['client_username']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$channel_link."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['comments']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$status."</td>
       <td style='text-align:center; vertical-align: middle;'>".$items."</td>
       <td style='text-align:center; vertical-align: middle;'>".$follow_up_date_with_remarks."</td>
       <td style='text-align:center; vertical-align: middle;'>".$reason_of_rejection."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['quality_of_lead']."</td>
       <td style='text-align:center; vertical-align: middle;'>$".number_format($data['amount'],2)."</td>
       </tr>";
    }
}
else
{
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='13'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>TIMESTAMP</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ACCOUNT</th>
    <th style='text-align:center; background-color: #8ea9db;'>PLATFORM</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLIENT</th>
    <th style='text-align:center; background-color: #8ea9db;'>CHANNEL LINK</th>
    <th style='text-align:center; background-color: #8ea9db;'>COMMENTS</th>
    <th style='text-align:center; background-color: #8ea9db;'>STATUS</th>
    <th style='text-align:center; background-color: #8ea9db;'>ITEMS</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UP DATE WITH REMARKS</th>
    <th style='text-align:center; background-color: #8ea9db;'>REASON OF REJECTION</th>
    <th style='text-align:center; background-color: #8ea9db;'>QUALITY</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSED AMOUNT</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='13'>No Records Available</td>
    </tr>";
}

$table.= "</tbody></table>";
echo $table;
?>