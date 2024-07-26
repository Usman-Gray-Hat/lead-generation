<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['account_name']) && isset($_POST['platform_name']) && isset($_POST['client_username']))
{
    $id = $_POST['id']; // Primary Key Of Lead Table

    $admin_id_FK = $_POST['admin_id']; // This will goes into target table
    $employee_id_FK = $_POST['employee_id']; // This will goes into target table
    $employee_name = $_POST['employee_name']; // This will goes into target table

    $account_name = $_POST['account_name'];
    $platform_name = $_POST['platform_name'];

    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $client_username = mysqli_real_escape_string($conn, $_POST['client_username']);
    $channel_link = mysqli_real_escape_string($conn, $_POST['channel_link']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $follow_up_date_with_remarks = trim($_POST['follow_up_date_with_remarks']);
    $follow_up_date_with_remarks = mysqli_real_escape_string($conn, $follow_up_date_with_remarks);

    $reason_of_rejection = trim($_POST['reason_of_rejection']);
    $reason_of_rejection = mysqli_real_escape_string($conn, $reason_of_rejection);

    $items = mysqli_real_escape_string($conn, $_POST['items']);
    $amount = $_POST['amount'];
    

    // Prevent duplication of client's username at the time of updation
    $pre_query = "SELECT id, admin_id_FK, employee_name, client_username ,lead_date 
    FROM leads WHERE client_username='$client_username'";
    $result = mysqli_query($conn,$pre_query);
    $rows = mysqli_num_rows($result);
    if($rows>0)
    {
        $data = mysqli_fetch_assoc($result);
        $existed_lead_id = $data['id'];
        $existed_admin_id_FK = $data['admin_id_FK'];
        $existed_employee_name = $data['employee_name'];
        $existed_client_username = $data['client_username'];
        $existed_date = date('F-d-Y',strtotime($data['lead_date']));

        // Check if the lead which is already exist and the lead which is about to be updated are same or not
        if($existed_lead_id===$id)
        {
            $query = "UPDATE leads SET account_name='$account_name', platform_name='$platform_name', client_username='$client_username',
            channel_link='$channel_link', comments='$comments', status='$status', follow_up_date_with_remarks='$follow_up_date_with_remarks',
            reason_of_rejection='$reason_of_rejection', items='$items', amount='$amount' WHERE id='$id'";
            $exec = mysqli_query($conn,$query);
            if($exec==true)
            {
                // IF STATUS IS (CLOSED)
                if($status==="Closed" && $amount!=="" && $amount!==null && $amount!="undefined" && $amount!=0 && $items!=="" && $items!==null && $items!=="-")
                {
                    $query = "SELECT * FROM targets WHERE lead_id_FK='$id'";
                    $result = mysqli_query($conn,$query);
                    $row = mysqli_num_rows($result);
                    if($row>0)
                    {
                        $query = "UPDATE targets SET employee_name='$employee_name', account_name='$account_name',
                        platform_name='$platform_name', client_username='$client_username', channel_link='$channel_link',
                        items='$items' ,amount='$amount' WHERE lead_id_FK='$id'";
                        $exec = mysqli_query($conn,$query);
                        if($exec==true)
                        {
                            echo "Lead has been updated";
                        }
                    }
                    else
                    {
                        $query = "INSERT INTO targets (employee_name,account_name, platform_name, client_username,
                        channel_link, items, amount, admin_id_FK, employee_id_FK, lead_id_FK)
                        VALUES ('$employee_name','$account_name','$platform_name','$client_username',
                        '$channel_link','$items','$amount','$admin_id_FK','$employee_id_FK','$id')";
                        $exec = mysqli_query($conn,$query);
                        if($exec==true)
                        {
                            $query2 = "UPDATE leads SET follow_up_date_with_remarks='', reason_of_rejection='', 
                            quality_of_lead='Qualified', comments='Deal Closed' WHERE id='$id'";
                            $exec2 = mysqli_query($conn,$query2);
                            if($exec2==true)
                            {
                                echo "Deal closed";
                            }
                        }                
                    }
                }
                // IF STATUS IS (FOLLOW UP)
                else if($status==="Follow up date")
                {
                    $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                    $exec = mysqli_query($conn,$query);
        
                    $query2 = "UPDATE leads SET amount=0, items='', reason_of_rejection='' WHERE id='$id'";
                    $exec2 = mysqli_query($conn,$query2);
                    echo "Lead has been updated";
                }
                // IF STATUS IS (REJECTED)
                else if($status==="Rejected")
                {
                    $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                    $exec = mysqli_query($conn,$query);
        
                    $query2 = "UPDATE leads SET amount=0, items='', follow_up_date_with_remarks='',
                    comments='Got Rejected', quality_of_lead='Disqualified' WHERE id='$id'";
                    $exec2 = mysqli_query($conn,$query2);
                    echo "Lead has been updated";
                }
                // IF STATUS IS (PENDING)
                else if($status==="Pending")
                {
                    $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                    $exec = mysqli_query($conn,$query);
        
                    $query2 = "UPDATE leads SET amount=0,items='',
                    follow_up_date_with_remarks='', reason_of_rejection='',
                    quality_of_lead='Pending'
                    WHERE id='$id'";
                    $exec2 = mysqli_query($conn,$query2);
                    echo "Lead has been updated";
                }
            }
            else
            {
                echo "Lead has not been updated";
            }
        }
        else
        {
            // Check if the user who added the lead and the user who is updating the lead are same or not 
            if($existed_admin_id_FK===$_SESSION['id'])
            {
                echo "You have already added this lead at $existed_date";
            }
            else
            {
                echo "This lead has already been added by $existed_employee_name at $existed_date";
            }
        }
    }
    else
    {
        $query = "UPDATE leads SET account_name='$account_name', platform_name='$platform_name', client_username='$client_username',
        channel_link='$channel_link', comments='$comments', status='$status', follow_up_date_with_remarks='$follow_up_date_with_remarks',
        reason_of_rejection='$reason_of_rejection', items='$items', amount='$amount' WHERE id='$id'";
        $exec = mysqli_query($conn,$query);
        if($exec==true)
        {
            // IF STATUS IS (CLOSED)
            if($status==="Closed" && $amount!=="" && $amount!==null && $amount!="undefined" && $amount!=0 && $items!=="" && $items!==null && $items!=="-")
            {
                $query = "SELECT * FROM targets WHERE lead_id_FK='$id'";
                $result = mysqli_query($conn,$query);
                $row = mysqli_num_rows($result);
                if($row>0)
                {
                    $query = "UPDATE targets SET employee_name='$employee_name', account_name='$account_name',
                    platform_name='$platform_name', client_username='$client_username', channel_link='$channel_link',
                    items='$items' ,amount='$amount' WHERE lead_id_FK='$id'";
                    $exec = mysqli_query($conn,$query);
                    if($exec==true)
                    {
                        echo "Lead has been updated";
                    }
                }
                else
                {
                    $query = "INSERT INTO targets (employee_name,account_name, platform_name, client_username,
                    channel_link, items, amount, admin_id_FK, employee_id_FK, lead_id_FK)
                    VALUES ('$employee_name','$account_name','$platform_name','$client_username',
                    '$channel_link','$items','$amount','$admin_id_FK','$employee_id_FK','$id')";
                    $exec = mysqli_query($conn,$query);
                    if($exec==true)
                    {
                        $query2 = "UPDATE leads SET follow_up_date_with_remarks='', reason_of_rejection='', 
                        quality_of_lead='Qualified', comments='Deal Closed' WHERE id='$id'";
                        $exec2 = mysqli_query($conn,$query2);
                        if($exec2==true)
                        {
                            echo "Deal closed";
                        }
                    }                
                }
            }
            // IF STATUS IS (FOLLOW UP)
            else if($status==="Follow up date")
            {
                $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                $exec = mysqli_query($conn,$query);
    
                $query2 = "UPDATE leads SET amount=0, items='', reason_of_rejection='' WHERE id='$id'";
                $exec2 = mysqli_query($conn,$query2);
                echo "Lead has been up dated";
            }
            // IF STATUS IS (REJECTED)
            else if($status==="Rejected")
            {
                $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                $exec = mysqli_query($conn,$query);
    
                $query2 = "UPDATE leads SET amount=0, items='', follow_up_date_with_remarks='',
                comments='Got Rejected', quality_of_lead='Disqualified' WHERE id='$id'";
                $exec2 = mysqli_query($conn,$query2);
                echo "Lead has been updated";
            }
            // IF STATUS IS (PENDING)
            else if($status==="Pending")
            {
                $query = "DELETE FROM targets WHERE lead_id_FK='$id'";
                $exec = mysqli_query($conn,$query);
    
                $query2 = "UPDATE leads SET amount=0,items='',
                follow_up_date_with_remarks='', reason_of_rejection='',
                quality_of_lead='Pending'
                WHERE id='$id'";
                $exec2 = mysqli_query($conn,$query2);
                echo "Lead has been updated";
            }
        }
        else
        {
            echo "Lead has not been updated";
        }
    }
}
?>