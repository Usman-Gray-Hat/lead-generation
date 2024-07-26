<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
 
if($searchValue==="")
{
    $query = "SELECT * FROM admins 
    ORDER BY id DESC 
    LIMIT $start, $length";
}
else
{
    $query = "SELECT * FROM admins 
    WHERE name LIKE '%$searchValue%'
    OR username LIKE '%$searchValue%' 
    OR email LIKE '%$searchValue%'
    OR role LIKE '%$searchValue%'
    ORDER BY id ASC 
    LIMIT $start, $length";
}

$result = mysqli_query($conn, $query);
$increment = 1;
$type = '';
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{

    if($row['type']==1)
    {
        $type = "Admin";
    }
    else if($row['type']==2)
    {
        $type = "User";
    }

$data[] = [
        'id' => $row['id'],
        'sr_no' => $increment++,
        'name' => $row['name'],
        'username' => $row['username'],
        'email' => $row['email'],
        'role' => $row['role'],
        'type' => $type,
    ];
}

$query2 = "SELECT * FROM admins 
WHERE name LIKE '%$searchValue%' 
OR username LIKE '%$searchValue%'
OR email LIKE '%$searchValue%'
OR role LIKE '%$searchValue%'";

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