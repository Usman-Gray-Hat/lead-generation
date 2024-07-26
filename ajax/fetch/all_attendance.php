<?php
include('../../dbConnect.php');
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
 
$from = $_POST['from'];
$to = $_POST['to']; 

// If date range is not selected
if($from=="" || $to=="")
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager")
    {
        $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id = a.employee_id_FK	
        AND MONTH(a.date_created) = MONTH(CURRENT_DATE())
        AND YEAR(a.date_created) = YEAR(CURRENT_DATE())
        WHERE (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name
        ORDER BY total_absents DESC
        LIMIT $start, $length"; 
    }
    else
    {
        $id = $_SESSION['employee_id'];
        $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND MONTH(a.date_created) = MONTH(CURRENT_DATE())
        AND YEAR(a.date_created) = YEAR(CURRENT_DATE())
        WHERE a.employee_id_FK='$id'
        AND (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name
        ORDER BY total_absents DESC
        LIMIT $start, $length"; 
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager")
    {
        $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND a.date_created BETWEEN '$from' AND '$to'
        WHERE (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name
        ORDER BY total_absents DESC
        LIMIT $start, $length";           
    }
    else
    {
        $id = $_SESSION['employee_id'];
        $query = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND a.date_created BETWEEN '$from' AND '$to'
        WHERE a.employee_id_FK='$id'
        AND (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name
        ORDER BY total_absents DESC
        LIMIT $start, $length";        
    }
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{
$data[] = [
        'employee_id_FK' => $row['employee_id'],
        'sr_no' => $increment++,
        'employee_name' => $row['employee_name'],
        'total_absents' => $row['total_absents'],
        'total_half_days' => $row['total_half_days'],
    ];
}

// If date range is not selected
if($from=="" || $to=="")
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager")
    {
        $query2 = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND MONTH(a.date_created) = MONTH(CURRENT_DATE())
        AND YEAR(a.date_created) = YEAR(CURRENT_DATE())
        WHERE (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name";           
    }
    else
    {
        $id = $_SESSION['employee_id'];
        $query2 = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND MONTH(a.date_created) = MONTH(CURRENT_DATE())
        AND YEAR(a.date_created) = YEAR(CURRENT_DATE())
        WHERE a.employee_id_FK='$id'
        AND (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name";
    }
}
else // If date range is selected
{
    if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager")
    {
        $query2 = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND a.date_created BETWEEN '$from' AND '$to'
        WHERE (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name";        
    }
    else
    {
        $id = $_SESSION['employee_id'];
        $query2 = "SELECT e.id AS employee_id,
        e.full_name AS employee_name,
        COALESCE(COUNT(CASE WHEN a.type = 'Absent' THEN 1 END),0) AS total_absents,
        COALESCE(COUNT(CASE WHEN a.type = 'Half Day' THEN 1 END),0) AS total_half_days
        FROM employees e LEFT JOIN attendance a 
        ON e.id=a.employee_id_FK
        AND a.date_created BETWEEN '$from' AND '$to'
        WHERE a.employee_id_FK='$id'
        AND (e.full_name LIKE '%$searchValue%')
        GROUP BY e.id, e.full_name";
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