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
    $query = "SELECT a.id AS admin_id,
    a.name AS admin_name,
    COALESCE(SUM(l.total_leads), 0) AS total_leads,
    COALESCE(AVG(l.total_leads), 0) AS average_leads
    FROM admins a LEFT JOIN leads_count l 
    ON a.id = l.admin_id_FK 
    AND MONTH(l.leads_count_date) = MONTH(CURRENT_DATE())
    AND YEAR(l.leads_count_date) = YEAR(CURRENT_DATE())
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id, a.name
    ORDER BY total_leads DESC
    LIMIT $start, $length";    
}
else // If date range is selected
{
    $query = "SELECT a.id AS admin_id,
    a.name AS admin_name,
    COALESCE(SUM(l.total_leads), 0) AS total_leads,
    COALESCE(AVG(l.total_leads), 0) AS average_leads
    FROM admins a LEFT JOIN leads_count l 
    ON a.id = l.admin_id_FK 
    AND l.leads_count_date BETWEEN '$from' AND '$to'
    WHERE a.type = '2' AND a.role = 'Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id, a.name
    ORDER BY total_leads DESC
    LIMIT $start, $length";    
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];
while ($row = mysqli_fetch_assoc($result)) 
{
    // Positions
    if($increment==1)
    {
        $position = "1st";
    }
    else if($increment==2)
    {
        $position = "2nd";
    }
    else if($increment==3)
    {
        $position = "3rd";
    }
    else if($increment==4)
    {
        $position = "4th";
    }
    else if($increment==5)
    {
        $position = "5th";
    }
    else if($increment==6)
    {
        $position = "6th";
    }
    else if($increment==7)
    {
        $position = "7th";
    }
    else if($increment==8)
    {
        $position = "8th";
    }
    else if($increment==9)
    {
        $position = "9th";
    }
    else if($increment==10)
    {
        $position = "10th";
    }
    else
    {
        $position = "<b class='text-danger'>Not Eligible</b>";
    }

    // Rank
    if ($row['total_leads'] >= 170) 
    {
        $rank = "<i class='fas fa-crown rank-icon platinum-icon' title='Platinum'></i>";
    } 
    elseif ($row['total_leads'] >= 120) 
    {
        $rank = "<i class='fas fa-gem rank-icon diamond-icon' title='Diamond'></i>";
    } 
    elseif ($row['total_leads'] >= 80) 
    {
        $rank = "<i class='fas fa-trophy rank-icon gold-icon' title='Gold'></i>";
    } 
    elseif ($row['total_leads'] >= 40) 
    {
        $rank = "<i class='fas fa-trophy rank-icon silver-icon' title='Silver'></i>";
    } 
    elseif ($row['total_leads'] >= 20) 
    {
        $rank = "<i class='fas fa-medal rank-icon bronze-icon' title='Bronze'></i>";
    } 
    else 
    {
        $rank = "<b class='text-danger'>Not Eligible</b>";
    }
$data[] = [

        'admin_id_FK' => $row['admin_id'],
        'sr_no' => $increment++,
        'name' => $row['admin_name'],
        'total_leads' => $row['total_leads'],
        'average_leads' => number_format($row['average_leads'],1),
        'position' => $position,
        'rank' => $rank,
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    $query2 = "SELECT * FROM leads_count
    WHERE MONTH(leads_count_date) = MONTH(CURRENT_DATE())
    AND YEAR(leads_count_date) = YEAR(CURRENT_DATE())
    AND (name LIKE '%$searchValue%')
    GROUP BY admin_id_FK";
}
else // If date range is selected
{
    $query2 = "SELECT * FROM leads_count 
    WHERE leads_count_date BETWEEN '$from' AND '$to'
    AND (name LIKE '%$searchValue%')";
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