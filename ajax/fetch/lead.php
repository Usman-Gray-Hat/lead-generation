<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
$from = $_POST['from'];
$to = $_POST['to'];

if($from==="" || $to==="") // If date range is not selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query = "SELECT * FROM leads 
        WHERE employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%'
        ORDER BY id DESC LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')
        ORDER BY id DESC LIMIT $start, $length";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query = "SELECT * FROM leads 
        WHERE lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')
        ORDER BY lead_date ASC LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')
        ORDER BY lead_date ASC LIMIT $start, $length";
    }   
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
$status = "";
$quality_of_lead = "";

while ($row = mysqli_fetch_assoc($result)) 
{
    if($row['quality_of_lead']==='Disqualified')
    {
        $status = "<b class='text-danger'>".$row['status']."</b>";
        $quality_of_lead = "<b class='text-danger'>".$row['quality_of_lead']."</b>";
    }
    else if($row['quality_of_lead']=='Qualified')
    {
        $status = "<b class='text-success'>".$row['status']."</b>";
        $quality_of_lead = "<b class='text-success'>".$row['quality_of_lead']."</b>";
    }
    else 
    {
        $status = "<p>".$row['status']."</p>";
        $quality_of_lead = "<p>".$row['quality_of_lead']."</p>";     
    }
    if($row['status']==="Follow up date")
    {
        $status = "<b class='text-primary'>Follow up</b>";
    }

    if($row['channel_link']==="" || $row['channel_link']==="-")
    {
        $channel_link = "<b class='text-danger'>Channel link is missing</b>";
    }
    else
    {
        $channel_link = $row['channel_link'];
    }

    if($row['follow_up_date_with_remarks']==="")
    {
        $follow_up_date_with_remarks = "-";
    }
    else
    {
        $follow_up_date_with_remarks = $row['follow_up_date_with_remarks'];
    }

    if($row['reason_of_rejection']==="")
    {
        $reason_of_rejection = "-";
    }
    else
    {
        $reason_of_rejection = $row['reason_of_rejection'];
    }

    if(trim($row['items'])==="")
    {
        $items = "-";
    }
    else
    {
        $items = $row['items'];
    }

    $closed_amount = "$".number_format($row['amount'],2)."";

$data[] = [
        'id' => $row['id'],
        'admin_id_FK' => $row['admin_id_FK'],
        'lead_timestamp' => date("M-d-Y h:i a",strtotime($row['lead_timestamp'])),
        'employee_name' => $row['employee_name'],
        'account_name' => $row['account_name'],
        'platform_name' => $row['platform_name'],
        'client_username' => $row['client_username'],
        'channel_link' => $channel_link,
        'comments' => $row['comments'],
        'status' => $status,
        'reason_of_rejection' => $reason_of_rejection,
        'quality_of_lead' => $quality_of_lead,
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM leads 
        WHERE employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%'";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM leads WHERE 
        lead_date BETWEEN '$from' AND '$to' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR follow_up_date_with_remarks LIKE '%$searchValue%'
        OR reason_of_rejection LIKE '%$searchValue%'
        OR quality_of_lead LIKE '%$searchValue%')";
    }
}

$result2 = mysqli_query($conn,$query2);
$totalRecords = mysqli_num_rows($result2);
$response = [
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
];
header('Content-Type: application/json');
echo json_encode($response);
?> 