<?php
include("../../dbConnect.php");
date_default_timezone_set('Asia/Karachi');
if(isset($_POST['account_name']) && isset($_POST['platform_name']) && isset($_POST['client_username']))
{
    $id = $_SESSION['id']; // Admin ID
    $employee_id = $_SESSION['employee_id']; // Employee ID

    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $name = mysqli_real_escape_string($conn, $_SESSION['name']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $platform_name = mysqli_real_escape_string($conn, $_POST['platform_name']);
    $client_username = trim(mysqli_real_escape_string($conn, $_POST['client_username']));
    $channel_link = trim(mysqli_real_escape_string($conn, $_POST['channel_link'])); 

    // Prevent duplication of client's username
    $pre_query = "SELECT admin_id_FK, employee_name, lead_date FROM leads 
    WHERE client_username='$client_username'";
    $result = mysqli_query($conn,$pre_query);
    $rows = mysqli_num_rows($result);
    if($rows>0)
    {
        $data = mysqli_fetch_assoc($result);
        $existed_admin_id_FK = $data['admin_id_FK'];
        $existed_employee_name = $data['employee_name'];
        $existed_date = date('F-d-Y',strtotime($data['lead_date']));

        // Check if the user who added the lead and the user who is now adding the lead are same or not 
        if($existed_admin_id_FK===$_SESSION['id'])
        {
            echo "You have already added this lead at $existed_date";
        }
        else
        {
            echo "This lead has already been added by $existed_employee_name at $existed_date";
        }
    }
    else
    {
        $query = "INSERT INTO leads (employee_name, account_name, platform_name,
        client_username, channel_link, admin_id_FK,employee_id_FK)
        VALUES ('$name', '$account_name', '$platform_name', '$client_username', '$channel_link', '$id','$employee_id')";
        $exec = mysqli_query($conn,$query);
        if($exec==true)
        {
            // For Leads Count Table
            $currentDate = date('Y-m-d');
            // Extracting total leads
            $qry = "SELECT * FROM leads 
            WHERE lead_date='$currentDate' AND admin_id_FK='$id'";
            $res = mysqli_query($conn,$qry);
            $total_leads = mysqli_num_rows($res);
    
            $query = "SELECT * FROM leads_count 
            WHERE leads_count_date='$currentDate' AND admin_id_FK='$id'";
            $result = mysqli_query($conn,$query);
            $rows = mysqli_num_rows($result);
            if($rows>0)
            {
                $query = "UPDATE leads_count SET total_leads='$total_leads' 
                WHERE leads_count_date='$currentDate' AND admin_id_FK='$id'"; 
                $exec = mysqli_query($conn,$query);
            }
            else
            {
                $query = "INSERT INTO leads_count (name,admin_id_FK,employee_id_FK,total_leads) 
                VALUES ('$name','$id','$employee_id','$total_leads')";
                $exec = mysqli_query($conn,$query);
            }
            echo "Your lead has been added";
        }
        else
        {
            echo "Your lead has not been added";
        }
    }
}
?>