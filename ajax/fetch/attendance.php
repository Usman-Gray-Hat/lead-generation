<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];

$employee_id = $_POST['employee_id'];
$from = $_POST['from'];
$to = $_POST['to'];

// If date range is not selected
if($from==="" || $to==="")
{
    $query = "SELECT * FROM attendance 
    WHERE employee_id_FK='$employee_id' 
    AND (type LIKE '%$searchValue%'
    OR reason LIKE '%$searchValue%'
    OR marked_by LIKE '%$searchValue%')
    ORDER BY id DESC 
    LIMIT $start, $length";
}
else
{
    // If date range is selected
    $query = "SELECT * FROM attendance 
    WHERE employee_id_FK='$employee_id' 
    AND date_created BETWEEN '$from' AND '$to'
    AND (type LIKE '%$searchValue%'
    OR reason LIKE '%$searchValue%' 
    OR marked_by LIKE '%$searchValue%')
    ORDER BY date_created ASC 
    LIMIT $start, $length";
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{
$data[] = [
        'id' => $row['id'],
        'sr_no' => $increment++,
        'date_created' => date('M-d-Y',strtotime($row['date_created'])),
        'type' => $row['type'],
        'reason' => $row['reason'],
        'marked_by' => $row['marked_by'],
    ];
}

// If date range is not selected
if($from=="" || $to=="")
{
    $query2 = "SELECT * FROM attendance 
    WHERE employee_id_FK='$employee_id' 
    AND (type LIKE '%$searchValue%'
    OR reason LIKE '%$searchValue%' 
    OR marked_by LIKE '%$searchValue%')";
}
else
{
    // If date range is selected
    $query2 = "SELECT * FROM attendance 
    WHERE employee_id_FK='$employee_id' 
    AND date_created BETWEEN '$from' AND '$to'
    AND (type LIKE '%$searchValue%'
    OR reason LIKE '%$searchValue%' 
    OR marked_by LIKE '%$searchValue%')";
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