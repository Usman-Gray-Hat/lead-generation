<?php
include("../../dbConnect.php");
if(isset($_POST['notification']) && isset($_POST['notification'])!=="")
{
    // Query to get the overall max lead
    $query = "SELECT MAX(sum_total_lead) AS max_lead
        FROM (
            SELECT SUM(total_leads) AS sum_total_lead
            FROM leads_count
            WHERE MONTH(leads_count_date) = MONTH(CURRENT_DATE())
            AND YEAR(leads_count_date) = YEAR(CURRENT_DATE())
            GROUP BY admin_id_FK
            ) AS subquery";

    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    $max_lead = $data['max_lead'];

    // Query to get the max lead for the logged-in user
    $id = $_SESSION['id'];
    $query2 = "SELECT MAX(sum_total_lead) AS max_lead
            FROM (
                SELECT SUM(total_leads) AS sum_total_lead
                FROM leads_count
                WHERE admin_id_FK='$id'
                AND MONTH(leads_count_date) = MONTH(CURRENT_DATE())
                AND YEAR(leads_count_date) = YEAR(CURRENT_DATE())
                GROUP BY employee_id_FK
                ) AS subquery";  

    $result2 = mysqli_query($conn, $query2);
    $data2 = mysqli_fetch_assoc($result2);
    $emp_max_lead = $data2['max_lead'];

    if($emp_max_lead < $max_lead)
    {
        $remaining = $max_lead - $emp_max_lead;
        $remaining = $remaining+1;
        echo $remaining;
    }
    else
    {
        echo "false";
    }
}
?>