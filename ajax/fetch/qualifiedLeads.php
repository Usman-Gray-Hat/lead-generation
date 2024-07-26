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
        WHERE quality_of_lead='Qualified'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')
        ORDER BY id DESC 
        LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND quality_of_lead='Qualified'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')
        ORDER BY id DESC 
        LIMIT $start, $length";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query = "SELECT * FROM leads 
        WHERE quality_of_lead='Qualified'
        AND lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')
        ORDER BY id ASC 
        LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM leads 
        WHERE admin_id_FK='$id' 
        AND quality_of_lead='Qualified'
        AND lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')
        ORDER BY id ASC 
        LIMIT $start, $length";
    }   
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];

while ($row = mysqli_fetch_assoc($result)) 
{
    $closed_amount = "$".number_format($row['amount'],2)."";

    if($row['status']=="Closed")
    {
        $status = "<b class='text-success'>".$row['status']."</b>";
    }
    else if($row['status']==="Follow up date")
    {
        $status = "<b class='text-primary'>Follow up</b>";
    }
    else if($row['status']==="Rejected")
    {
        $status = "<b class='text-danger'>Rejected</b>";
    }
    else
    {
        $status = $row['status'];
    }

    if($row['channel_link']==="" || $row['channel_link']==="-")
    {
        $channel_link = "<b class='text-danger'>Channel link is missing</b>";
    }
    else
    {
        $channel_link = $row['channel_link'];
    }

    $data[] = [
        'lead_timestamp' => date("M-d-Y h:i a",strtotime($row['lead_timestamp'])),
        'employee_name' => $row['employee_name'],
        'account_name' => $row['account_name'],
        'platform_name' => $row['platform_name'],
        'client_username' => $row['client_username'],
        'channel_link' => $channel_link,
        'comments' => $row['comments'],
        'status' => $status,
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM leads 
        WHERE quality_of_lead='Qualified'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM leads 
        WHERE admin_id_FK='$id'
        AND  quality_of_lead='Qualified'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM leads 
        WHERE lead_date BETWEEN '$from' AND '$to'
        AND quality_of_lead='Qualified' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM leads 
        WHERE admin_id_FK='$id'
        AND quality_of_lead='Qualified'
        AND lead_date BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR comments LIKE '%$searchValue%'
        OR status LIKE '%$searchValue%')";
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