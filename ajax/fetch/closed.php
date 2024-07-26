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
        $query = "SELECT * FROM targets 
        WHERE employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        ORDER BY id DESC LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM targets 
        WHERE admin_id_FK='$id'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')
        ORDER BY id DESC LIMIT $start, $length";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query = "SELECT * FROM targets 
        WHERE date_created BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')
        ORDER BY date_created ASC LIMIT $start, $length";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM targets 
        WHERE admin_id_FK='$id'
        AND date_created BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')
        ORDER BY date_created ASC LIMIT $start, $length";
    }   
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
$status = "";
$quality_of_lead = "";

while ($row = mysqli_fetch_assoc($result)) 
{
    if($row['channel_link']==="" || $row['channel_link']==="-")
    {
        $channel_link = "<b class='text-danger'>Channel link is missing</b>";
    }
    else
    {
        $channel_link = $row['channel_link'];
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
        'date_created' => date('M-d-Y',strtotime($row['date_created'])),
        'employee_name' => $row['employee_name'],
        'account_name' => $row['account_name'],
        'platform_name' => $row['platform_name'],
        'client_username' => $row['client_username'],
        'channel_link' => $channel_link,
        'items' => $items,
        'amount' => $closed_amount,
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM targets 
        WHERE employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM targets 
        WHERE admin_id_FK='$id' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
    {
        $query2 = "SELECT * FROM targets 
        WHERE date_created BETWEEN '$from' AND '$to' 
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%' 
        OR channel_link LIKE '%$searchValue%'
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')";        
    }
    else
    {
        $id = $_SESSION['id'];
        $query2 = "SELECT * FROM targets 
        WHERE admin_id_FK='$id'
        AND date_created BETWEEN '$from' AND '$to'
        AND (employee_name LIKE '%$searchValue%'
        OR account_name LIKE '%$searchValue%' 
        OR platform_name LIKE '%$searchValue%'
        OR client_username LIKE '%$searchValue%'
        OR channel_link LIKE '%$searchValue%' 
        OR items LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%')";
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