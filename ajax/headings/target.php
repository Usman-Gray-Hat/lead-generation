<?php
if(isset($_POST['from']) && isset($_POST['to']))
{
    $from = date('M-d-Y',strtotime($_POST['from']));
    $to = date('M-d-Y',strtotime($_POST['to']));

    $heading = "TARGET ACHIEVED (FROM ".strtoupper($from)." TO ".strtoupper($to).")";
    echo $heading;
}
?>