<?php
include("dbConnect.php");
session_destroy();
header("Location:index.php");
?>