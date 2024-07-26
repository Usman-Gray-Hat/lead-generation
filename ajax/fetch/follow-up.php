<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];

if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
{
    $query = "SELECT * FROM leads 
    WHERE status='Follow up date'
    AND (employee_name LIKE '%$searchValue%'
    OR account_name LIKE '%$searchValue%' 
    OR platform_name LIKE '%$searchValue%'
    OR client_username LIKE '%$searchValue%'
    OR channel_link LIKE '%$searchValue%' 
    OR comments LIKE '%$searchValue%'
    OR follow_up_date_with_remarks LIKE '%$searchValue%')
    ORDER BY id DESC LIMIT $start, $length";
}
else
{
    $id = $_SESSION['id'];
    $query = "SELECT * FROM leads 
    WHERE admin_id_FK='$id'
    AND status='Follow up date'
    AND (employee_name LIKE '%$searchValue%'
    OR account_name LIKE '%$searchValue%' 
    OR platform_name LIKE '%$searchValue%'
    OR client_username LIKE '%$searchValue%'
    OR channel_link LIKE '%$searchValue%' 
    OR comments LIKE '%$searchValue%'
    OR follow_up_date_with_remarks LIKE '%$searchValue%')
    ORDER BY id DESC LIMIT $start, $length";
}


$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];

while ($row = mysqli_fetch_assoc($result)) 
{
    if($row['channel_link']==="")
    {
        $channel_link = "<b class='text-danger'>Channel link is missing</b>";
    }
    else
    {
        $channel_link = $row['channel_link'];
    }

$data[] = [
        'sr_no' => $increment++,
        'employee_name' => $row['employee_name'],
        'account_name' => $row['account_name'],
        'platform_name' => $row['platform_name'],
        'client_username' => $row['client_username'],
        'channel_link' => $channel_link,
        'comments' => $row['comments'],
        'follow_up_date_with_remarks' => $row['follow_up_date_with_remarks'],
    ];
}

if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer")
{
    $query2 = "SELECT * FROM leads 
    WHERE status='Follow up date'
    AND (employee_name LIKE '%$searchValue%'
    OR account_name LIKE '%$searchValue%' 
    OR platform_name LIKE '%$searchValue%'
    OR client_username LIKE '%$searchValue%' 
    OR channel_link LIKE '%$searchValue%'
    OR comments LIKE '%$searchValue%'
    OR follow_up_date_with_remarks LIKE '%$searchValue%')";
}
else
{
    $id = $_SESSION['id'];
    $query2 = "SELECT * FROM leads 
    WHERE admin_id_FK='$id'
    AND status='Follow up date'
    AND (employee_name LIKE '%$searchValue%'
    OR account_name LIKE '%$searchValue%' 
    OR platform_name LIKE '%$searchValue%'
    OR client_username LIKE '%$searchValue%' 
    OR channel_link LIKE '%$searchValue%'
    OR comments LIKE '%$searchValue%'
    OR follow_up_date_with_remarks LIKE '%$searchValue%')";
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