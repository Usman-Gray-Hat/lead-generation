<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    // Extracting admin id and date from "leads" table
    $query = "SELECT * FROM leads
    WHERE id='$id'";
    $result = mysqli_query($conn,$query);
    $data = mysqli_fetch_assoc($result);
    $admin_id_FK = $data['admin_id_FK'];
    $lead_date = $data['lead_date'];

    // Exclude 1 lead from "leads_count" table if any lead deleted from "leads" table
    $query2 = "SELECT * FROM leads_count
    WHERE leads_count_date='$lead_date' AND admin_id_FK='$admin_id_FK'";
    $result2 = mysqli_query($conn,$query2);
    $data2 = mysqli_fetch_assoc($result2);
    $total_leads = $data2['total_leads']-1;

    // Deleting lead from "leads" table
    $query3 = "DELETE FROM leads WHERE id='$id'";
    $exec = mysqli_query($conn,$query3);
    if($exec==true)
    {
        // Delete closed amount from target table
        $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
        $exec = mysqli_query($conn,$query);
        if($exec==true)
        {
            // Subtract 1 lead from the "total_leads" column of "leads_count" table
            $query = "UPDATE leads_count 
            SET total_leads='$total_leads'
            WHERE leads_count_date='$lead_date' AND admin_id_FK='$admin_id_FK'";
            $exec = mysqli_query($conn,$query);
            if($exec==true)
            {
                echo "Lead has been deleted";
            }
            else
            {
                echo "Lead has not been deleted";
            }
        }
    }
    else
    {
        echo "Lead has not been deleted";
    }
}
?>