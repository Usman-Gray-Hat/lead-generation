<?php
session_start();
$conn = mysqli_connect("localhost","root","","emeraldx");
if(!$conn)
{
    echo "<script> alert('Database not connected!'); </script>";
}
?>