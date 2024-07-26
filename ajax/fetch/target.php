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
    $query = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(SUM(t.amount), 0) AS total_amount
    FROM employees e LEFT JOIN targets t 
    ON e.id = t.employee_id_FK 
    AND MONTH(t.date_created) = MONTH(CURRENT_DATE())
    AND YEAR(t.date_created) = YEAR(CURRENT_DATE())
    LEFT JOIN admins a 
    ON e.id = a.employee_id_FK
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (e.full_name LIKE '%$searchValue%')
    GROUP BY e.id, e.full_name
    ORDER BY total_amount DESC
    LIMIT $start, $length";
}
else // If date range is selected
{
    $query = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(SUM(t.amount), 0) AS total_amount
    FROM employees e LEFT JOIN targets t 
    ON e.id = t.employee_id_FK 
    AND t.date_created BETWEEN '$from' AND '$to'
    LEFT JOIN admins a 
    ON e.id = a.employee_id_FK
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (e.full_name LIKE '%$searchValue%')
    GROUP BY e.id, e.full_name
    ORDER BY total_amount DESC
    LIMIT $start, $length";
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{
    $total_target = "$".number_format(1000,2)."";
    $target_achieved = "$".number_format($row['total_amount'],2)."";
    $remaining_target = "$".number_format(1000 - $row['total_amount'],2)."";

    if($row['total_amount']>=1000)
    {
        $status = "<b class='text-success'>Target Accomplished</b>";
        $remaining_target = "<b class='text-success'>Completed</b>";
    }
    else if($row['total_amount']>=500)
    {
        $status = "<b class='text-primary'>Safe Zone</b>";
    }
    else if($row['total_amount']<500)
    {
        $status = "<b class='text-danger'>Red Zone</b>";
    }

    $data[] = [
        'sr_no' => $increment++,
        'employee_name' => $row['employee_name'],
        'target_achieved' => $target_achieved,
        'remaining_target' => $remaining_target,
        'total_target' => $total_target,
        'status' => $status,
    ];
}


if($from==="" || $to==="") // If date range is not selected
{
    $query2 = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(SUM(t.amount), 0) AS total_amount
    FROM employees e LEFT JOIN targets t 
    ON e.id = t.employee_id_FK 
    AND MONTH(t.date_created) = MONTH(CURRENT_DATE())
    AND YEAR(t.date_created) = YEAR(CURRENT_DATE())
    LEFT JOIN admins a 
    ON e.id = a.employee_id_FK
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (e.full_name LIKE '%$searchValue%')
    GROUP BY e.id, e.full_name";
}
else // If date range is selected
{
    $query2 = "SELECT e.id AS employee_id,
    e.full_name AS employee_name,
    COALESCE(SUM(t.amount), 0) AS total_amount
    FROM employees e LEFT JOIN targets t 
    ON e.id = t.employee_id_FK 
    AND t.date_created BETWEEN '$from' AND '$to'
    LEFT JOIN admins a 
    ON e.id = a.employee_id_FK
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (e.full_name LIKE '%$searchValue%')
    GROUP BY e.id, e.full_name";
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