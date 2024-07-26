<?php
include("../dbConnect.php");
date_default_timezone_set('Asia/Karachi');
$from = $_GET['from']??"";
$to = $_GET['to']??"";
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=LeadStats.xls");

if($from==="" || $to==="") // If date range is not selected
{
    $msg = "LEAD GENERATION MONTHLY STATS (".strtoupper(date('F-Y')).")"; 

    // Query for individual stats
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
    GROUP BY a.id";
    
    // Query for team stats
    $query_team = "SELECT COALESCE(COUNT(*),0) AS total_leads,
    COALESCE(COUNT(CASE WHEN quality_of_lead='Qualified' THEN 1 END),0) AS qualified,
    COALESCE(COUNT(CASE WHEN status='Rejected' THEN 1 END),0) AS rejected,
    COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending,
    COALESCE(COUNT(CASE WHEN status='Follow up date' THEN 1 END),0) AS followups
    FROM leads
    WHERE MONTH(lead_date) = MONTH(CURRENT_DATE())
    AND YEAR(lead_date) = YEAR(CURRENT_DATE())";

    // Query for closed deals on their respective dates (Team stats)
    $query2 = "SELECT COALESCE(COUNT(*),0) AS closed 
    FROM targets
    WHERE MONTH(date_created) = MONTH(CURRENT_DATE())
    AND YEAR(date_created) = YEAR(CURRENT_DATE())";
}
else // If date range is selected
{
    $msg = "LEAD GENERATION MONTHLY STATS  
    (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";

    // Query for individual stats
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
    GROUP BY a.id";


    // Query for team stats
    $query_team = "SELECT COALESCE(COUNT(*),0) AS total_leads,
    COALESCE(COUNT(CASE WHEN quality_of_lead='Qualified' THEN 1 END),0) AS qualified,
    COALESCE(COUNT(CASE WHEN status='Rejected' THEN 1 END),0) AS rejected,
    COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending,
    COALESCE(COUNT(CASE WHEN status='Follow up date' THEN 1 END),0) AS followups
    FROM leads
    WHERE lead_date BETWEEN '$from' AND '$to'"; 


    // Query for closed deals on their respective dates (Team stats)
    $query2 = "SELECT COALESCE(COUNT(*),0) AS closed
    FROM targets
    WHERE date_created BETWEEN '$from' AND '$to'";      
}

// For individual stats
$result = mysqli_query($conn,$query);
$row = mysqli_num_rows($result);
$i = 1; 
if($row>0)
{
    $table = "<table border='1px' style='text-align:center; width:30%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='8'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>QUALIFIED LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>REJECTED LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>PENDING</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UPS</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSED</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['employee_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['total_leads']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['qualified']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['rejected']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['pending']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['followups']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['closed']."</td>
       </tr>";
    }
}
else
{ 
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='8'>".$msg."</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMPLOYEE</th>
    <th style='text-align:center; background-color: #8ea9db;'>TOTAL LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>QUALIFIED LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>REJECTED LEADS</th>
    <th style='text-align:center; background-color: #8ea9db;'>PENDING</th>
    <th style='text-align:center; background-color: #8ea9db;'>FOLLOW UPS</th>
    <th style='text-align:center; background-color: #8ea9db;'>CLOSED</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='8'>No Records Available</td>
    </tr>";
}

// For team stats
$result_team = mysqli_query($conn,$query_team);
$data_team = mysqli_fetch_assoc($result_team);


// Query for closed deals on their respective dates
$result2 = mysqli_query($conn,$query2);
$data2 = mysqli_fetch_assoc($result2);

$table.= "</tbody>
            <tfoot>
                <tr>
                    <td style='text-align:center; background-color: #8ea9db; font-weight:bolder; padding-left:20px;' colspan='8'>TEAM STATS</td>
                </tr> 
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>TOTAL LEADS</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data_team['total_leads']."</td>
                </tr> 
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>QUALIFIED LEADS</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data_team['qualified']."</td>
                </tr>  
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>REJECTED LEADS</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data_team['rejected']."</td>
                </tr> 
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>PENDING LEADS</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data_team['pending']."</td>
                </tr>  
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>FOLLOW UPS</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data_team['followups']."</td>
                </tr> 
                <tr>
                    <td style='text-align:right; background-color: #8ea9db; font-weight:bolder; padding-right:10px;' colspan='4'>CLOSED</td>
                    <td style='text-align:left; background-color: #8ea9db; font-weight:bolder; padding-left:10px;' colspan='4'>".$data2['closed']."</td>
                </tr>                                                                                
            </tfoot>
</table>";
echo $table;
?>