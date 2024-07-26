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
    $query = "SELECT a.id, a.name AS employee_name,
	COALESCE(leads_count, 0) AS total_leads, 
	COALESCE(qualified_leads, 0) AS qualified,
	COALESCE(rejected_leads, 0) AS rejected,
	COALESCE(followups, 0) AS followups,
	COALESCE(closed, 0) AS closed,
	COALESCE(pending_leads, 0) AS pending
    FROM admins a 
    LEFT JOIN 
    (
        SELECT admin_id_FK,
        COUNT(*) AS leads_count,
        SUM(CASE WHEN quality_of_lead = 'Qualified' THEN 1 ELSE 0 END) AS qualified_leads,
        SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_leads,
        SUM(CASE WHEN status = 'Follow up date' THEN 1 ELSE 0 END) AS followups,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_leads
        FROM leads
        WHERE MONTH(lead_date) = MONTH(CURRENT_DATE())
        AND YEAR(lead_date) = YEAR(CURRENT_DATE())
        GROUP BY admin_id_FK
    ) 
    l ON a.id = l.admin_id_FK
    LEFT JOIN 
    (
        SELECT admin_id_FK,
        COUNT(*) AS closed
        FROM targets
        WHERE MONTH(date_created) = MONTH(CURRENT_DATE())
        AND YEAR(date_created) = YEAR(CURRENT_DATE())
        GROUP BY admin_id_FK
    ) 
    t ON a.id = t.admin_id_FK
    WHERE a.type='2' AND a.role='Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id
    LIMIT $start, $length";
}
else // If date range is selected
{
    $query = "SELECT a.id, a.name AS employee_name,
	COALESCE(leads_count, 0) AS total_leads, 
	COALESCE(qualified_leads, 0) AS qualified,
	COALESCE(rejected_leads, 0) AS rejected,
	COALESCE(followups, 0) AS followups,
	COALESCE(closed, 0) AS closed,
	COALESCE(pending_leads, 0) AS pending
    FROM admins a 
    LEFT JOIN 
    (
        SELECT admin_id_FK,
        COUNT(*) AS leads_count,
        SUM(CASE WHEN quality_of_lead = 'Qualified' THEN 1 ELSE 0 END) AS qualified_leads,
        SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_leads,
        SUM(CASE WHEN status = 'Follow up date' THEN 1 ELSE 0 END) AS followups,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_leads
        FROM leads
        WHERE lead_date BETWEEN '$from' AND '$to'
        GROUP BY admin_id_FK
    ) 
    l ON a.id = l.admin_id_FK
    LEFT JOIN 
    (
        SELECT admin_id_FK,
        COUNT(*) AS closed
        FROM targets
        WHERE date_created BETWEEN '$from' AND '$to'
        GROUP BY admin_id_FK
    ) 
    t ON a.id = t.admin_id_FK
    WHERE a.type='2' AND a.role='Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id
    LIMIT $start, $length";    
}

$result = mysqli_query($conn, $query);
$increment = 1;
$data = [];

while ($row = mysqli_fetch_assoc($result)) 
{

    $data[] = [
        'sr_no' => $increment++,
        'employee_name' => $row['employee_name'],
        'total_leads' => $row['total_leads'],
        'qualified_leads' => $row['qualified'],
        'rejected_leads' => $row['rejected'],
        'pending' => $row['pending'],
        'followups' => $row['followups'],
        'closed' => $row['closed']
    ];
}

if($from==="" || $to==="") // If date range is not selected
{
    $query2 = "SELECT a.id, a.name AS employee_name,
    COALESCE(COUNT(l.admin_id_FK),0) AS total_leads, 
    COALESCE(COUNT(CASE WHEN l.quality_of_lead='Qualified' THEN 1 END),0) AS qualified_leads,
    COALESCE(COUNT(CASE WHEN l.status='Rejected' THEN 1 END),0) AS rejected_leads,
    COALESCE(COUNT(CASE WHEN l.status='Follow up date' THEN 1 END),0) AS followups,
    COALESCE(COUNT(CASE WHEN l.status='Closed' THEN 1 END),0) AS closed,
    COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending
    FROM admins a LEFT JOIN leads l
    ON a.id = l.admin_id_FK
    AND MONTH(l.lead_date) = MONTH(CURRENT_DATE())
    AND YEAR(l.lead_date) = YEAR(CURRENT_DATE())
    WHERE a.type='2' AND a.role='Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id";
}
else // If date range is selected
{
    $query2 = "SELECT a.id, a.name AS employee_name,
    COALESCE(COUNT(l.admin_id_FK),0) AS total_leads, 
    COALESCE(COUNT(CASE WHEN l.quality_of_lead='Qualified' THEN 1 END),0) AS qualified_leads,
    COALESCE(COUNT(CASE WHEN l.status='Rejected' THEN 1 END),0) AS rejected_leads,
    COALESCE(COUNT(CASE WHEN l.status='Follow up date' THEN 1 END),0) AS followups,
    COALESCE(COUNT(CASE WHEN l.status='Closed' THEN 1 END),0) AS closed,
    COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending
    FROM admins a LEFT JOIN leads l
    ON a.id = l.admin_id_FK
    AND l.lead_date BETWEEN '$from' AND '$to'
    WHERE a.type='2' AND a.role='Lead Generation'
    AND (a.name LIKE '%$searchValue%')
    GROUP BY a.id";
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