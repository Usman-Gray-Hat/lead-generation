<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
 
if($searchValue==="")
{
    $query = "SELECT * FROM employees 
    WHERE status='Active' 
    ORDER BY id DESC 
    LIMIT $start, $length";
}
else
{
    $query = "SELECT * FROM employees 
    WHERE full_name LIKE '%$searchValue%'
    OR father_name LIKE '%$searchValue%' 
    OR cell_no LIKE '%$searchValue%'
    OR email LIKE '%$searchValue%' 
    OR cnic_no LIKE '%$searchValue%'
    OR address LIKE '%$searchValue%'
    OR shift LIKE '%$searchValue%'
    OR date_of_joining LIKE '%$searchValue%'
    ORDER BY id ASC 
    LIMIT $start, $length";
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
$firstSale = "";
while ($row = mysqli_fetch_assoc($result)) 
{
    if($row['first_sale']==null || $row['first_sale']=="" || $row['first_sale']=="0000-00-00")
    {
        $firstSale = "<b style='color:red;'> Not Yet </b>";
    }
    else
    {
        $firstSale = "<b style='color:green;'>".date('F-d-Y',strtotime($row['first_sale']))."</b>";
    }
$data[] = [
        'id' => $row['id'],
        'sr_no' => $increment++,
        'full_name' => $row['full_name'],
        'father_name' => $row['father_name'],
        'cell_no' => $row['cell_no'],
        'email' => $row['email'],
        'cnic_no' => $row['cnic_no'],
        'address' => $row['address'],
        'shift' => $row['shift'],
        'date_of_joining' => date('F-d-Y',strtotime($row['date_of_joining'])),
        'first_sale' => $firstSale,
    ];
}

$query2 = "SELECT * FROM employees 
WHERE full_name LIKE '%$searchValue%'
OR father_name LIKE '%$searchValue%' 
OR cell_no LIKE '%$searchValue%'
OR email LIKE '%$searchValue%' 
OR cnic_no LIKE '%$searchValue%'
OR address LIKE '%$searchValue%'
OR shift LIKE '%$searchValue%'
OR date_of_joining LIKE '%$searchValue%'";

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