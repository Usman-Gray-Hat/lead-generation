<?php
include("../dbConnect.php");
// For Exporting
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=EmployeesInfo.xls");
$query = "SELECT * FROM employees WHERE status='Active' ORDER BY id ASC";
$result = mysqli_query($conn,$query);
$i = 1;
$row = mysqli_num_rows($result);
if($row>0)
{
    $table = "<table border='1px' style='text-align:center; width:70%;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='10'>EMPLOYEE DETAILS</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>FULL NAME</th>
    <th style='text-align:center; background-color: #8ea9db;'>FATHER NAME</th>
    <th style='text-align:center; background-color: #8ea9db;'>CELL NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMAIL</th>
    <th style='text-align:center; background-color: #8ea9db;'>CNIC NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>ADDRESS</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE OF JOINING</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE OF FIRST SALE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ADDED BY</th>
    </thead>
    </tr><tbody>";

    while($data=mysqli_fetch_assoc($result))
    {
       if($data['first_sale']==null || $data['first_sale']=="" || $data['first_sale']=="0000-00-00")
       {
           $first_sale = "Not Yet";
       }
       else
       {
           $first_sale = date('F-d-Y',strtotime($data['first_sale']));
       }
       $table.="<tr>
       <td style='text-align:center; vertical-align: middle;'>".$i++."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['full_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['father_name']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['cell_no']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['email']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['cnic_no']."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['address']."</td>
       <td style='text-align:center; vertical-align: middle;'>".date('F-d-Y',strtotime($data['date_of_joining']))."</td>
       <td style='text-align:center; vertical-align: middle;'>".$first_sale."</td>
       <td style='text-align:center; vertical-align: middle;'>".$data['added_by']."</td>
       </tr>";
    }
}
else
{
    $table = "<table border='1px' style='text-align:center;'>
    <thead>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;' colspan='10'>EMPLOYEE DETAILS</th>
    </tr>
    <tr>
    <th style='text-align:center; background-color: #8ea9db;'>SR.NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>FULL NAME</th>
    <th style='text-align:center; background-color: #8ea9db;'>FATHER NAME</th>
    <th style='text-align:center; background-color: #8ea9db;'>CELL NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>EMAIL</th>
    <th style='text-align:center; background-color: #8ea9db;'>CNIC NO</th>
    <th style='text-align:center; background-color: #8ea9db;'>ADDRESS</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE OF JOINING</th>
    <th style='text-align:center; background-color: #8ea9db;'>DATE OF FIRST SALE</th>
    <th style='text-align:center; background-color: #8ea9db;'>ADDED BY</th>
    </thead>
    </tr><tbody><tr>
    <td style='text-align:center; vertical-align: middle;' colspan='10'>No Records Available</td>
    </tr>";
}

$table.= "</tbody></table>";
echo $table;
?>