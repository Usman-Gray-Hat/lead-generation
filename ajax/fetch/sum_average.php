<?php
include('../../dbConnect.php');
if(isset($_POST['id']) && isset($_POST['id'])!="")
{
    $id = $_POST['id'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    if($from==="" || $to==="") // If date range is not selected
    {
        $query = "SELECT COALESCE(SUM(total_leads),0) AS sum,
        AVG(total_leads) AS average
        FROM leads_count
        WHERE admin_id_FK='$id'";
    }
    else // If date range is selected
    {
        $query = "SELECT COALESCE(SUM(total_leads),0) AS sum,
        AVG(total_leads) AS average
        FROM leads_count
        WHERE admin_id_FK='$id'
        AND leads_count_date BETWEEN '$from' AND '$to'";
    }
    
    $result = mysqli_query($conn,$query);
    $data = mysqli_fetch_assoc($result);
    $sum = $data['sum'];
    $average = number_format($data['average'],1);

    $response = array(
        'sum' => $sum,
        'average' => $average
    );
    echo json_encode($response);
}
?>