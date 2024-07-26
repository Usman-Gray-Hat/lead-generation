<?php
if(isset($_POST['from']) && isset($_POST['from'])!="" && isset($_POST['to']) && isset($_POST['to'])!="")
{
    $from = $_POST['from'];
    $to = $_POST['to'];   
    $heading = "(".strtoupper(date('F-d-Y',strtotime($from)))." TO ".strtoupper(date('F-d-Y',strtotime($to))).")";
    echo $heading;
}
?>