<?php
if(isset($_POST['from']) && isset($_POST['from'])!="" && isset($_POST['to']) && isset($_POST['to'])!="")
{
    $from = $_POST['from'];
    $to = $_POST['to'];
    $heading = "CLOSED DEALS INFORMATION 
    (FROM ".strtoupper(date('M-d-Y',strtotime($from)))." TO ".strtoupper(date('M-d-Y',strtotime($to))).")";
    echo $heading;
}
?>