<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
$id = $_POST['id'];
$from = $_POST['from'];
$to = $_POST['to'];

if($from==="" || $to==="") // If date range is not selected
{
    $query = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id' 
    AND (total_leads LIKE '%$searchValue%') 
    ORDER BY id DESC 
    LIMIT $start, $length";
}
else // If date range is selected
{
    $query = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id' 
    AND leads_count_date BETWEEN '$from' AND '$to'
    AND (total_leads LIKE '%$searchValue%') 
    ORDER BY leads_count_date ASC 
    LIMIT $start, $length";
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{
$data[] = [
        'sr_no' => $increment++,
        'leads_count_date' => date("F-d-Y",strtotime($row['leads_count_date'])),
        'total_leads' => $row['total_leads']
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    $query2 = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id' 
    AND (total_leads LIKE '%$searchValue%')";
}
else // If date range is selected
{
    $query2 = "SELECT * FROM leads_count 
    WHERE admin_id_FK='$id' 
    AND leads_count_date BETWEEN '$from' AND '$to'
    AND (total_leads LIKE '%$searchValue%')";
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