<?php
if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['name']))
{
    $from = date('M-d-Y',strtotime($_POST['from']));
    $to = date('M-d-Y',strtotime($_POST['to']));
    $name = $_POST['name'];

    $heading = "LEADS COUNT TRACK RECORD OF ".strtoupper($name). " (FROM ".strtoupper($from)." TO ".strtoupper($to).")";
    echo $heading;
}
?>