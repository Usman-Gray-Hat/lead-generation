<?php
include('../../dbConnect.php');
if(isset($_POST['result']))
{
    $from = $_POST['from'];
    $to = $_POST['to'];
    if($from==="" || $to==="") // If date range is not selected
    {
        $query = "SELECT COALESCE(COUNT(*),0) AS total_leads,
        COALESCE(COUNT(CASE WHEN quality_of_lead='Qualified' THEN 1 END),0) AS qualified,
        COALESCE(COUNT(CASE WHEN status='Rejected' THEN 1 END),0) AS rejected,
        COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending,
        COALESCE(COUNT(CASE WHEN status='Follow up date' THEN 1 END),0) AS followup
        FROM leads
        WHERE MONTH(lead_date) = MONTH(CURRENT_DATE())
        AND YEAR(lead_date) = YEAR(CURRENT_DATE())";

        // Query for closed deals on their respective dates
        $query2 = "SELECT COALESCE(COUNT(*),0) AS closed 
        FROM targets
        WHERE MONTH(date_created) = MONTH(CURRENT_DATE())
        AND YEAR(date_created) = YEAR(CURRENT_DATE())";
    }
    else // If date range is selected
    {
        $query = "SELECT COALESCE(COUNT(*),0) AS total_leads,
        COALESCE(COUNT(CASE WHEN quality_of_lead='Qualified' THEN 1 END),0) AS qualified,
        COALESCE(COUNT(CASE WHEN status='Rejected' THEN 1 END),0) AS rejected,
        COALESCE(COUNT(CASE WHEN status='Pending' THEN 1 END),0) AS pending,
        COALESCE(COUNT(CASE WHEN status='Follow up date' THEN 1 END),0) AS followup
        FROM leads
        WHERE lead_date BETWEEN '$from' AND '$to'";
        
        // Query for closed deals on their respective dates
        $query2 = "SELECT COALESCE(COUNT(*),0) AS closed
        FROM targets
        WHERE date_created BETWEEN '$from' AND '$to'";        
    }
    
    $result = mysqli_query($conn,$query);
    $data = mysqli_fetch_assoc($result);
    $total_leads = $data['total_leads'];
    $qualified = $data['qualified'];
    $rejected = $data['rejected'];
    $pending = $data['pending'];
    $followup = $data['followup'];
    
    // For closed
    $result2 = mysqli_query($conn,$query2);
    $data2 = mysqli_fetch_assoc($result2);
    $closed = $data2['closed'];

    $response = array(
        'total_leads' => $total_leads,
        'qualified' => $qualified,
        'rejected' => $rejected,
        'pending' => $pending,
        'followup' => $followup,
        'closed' => $closed,
    );
    echo json_encode($response);
}
?>