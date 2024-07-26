<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    $query = "SELECT * FROM attendance WHERE id='$id'";
    $result = mysqli_query($conn,$query);
    $response = array();
    $rows = mysqli_num_rows($result);
    if($rows>0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
            $response = $data;
        }
        echo json_encode($response);
    }
}
?>