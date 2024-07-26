<?php
if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['employee_name']))
{
    $from = date('M-d-Y',strtotime($_POST['from']));
    $to = date('M-d-Y',strtotime($_POST['to']));
    $name = $_POST['employee_name'];

    $heading = "ATTENDANCE REPORT OF ".strtoupper($name). " (FROM ".strtoupper($from)." TO ".strtoupper($to).")";
    echo $heading;
}
?>