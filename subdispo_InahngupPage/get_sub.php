<?php

require_once("dbconnect_mysqli.php");
$dispo = $_REQUEST['dispo'];

$sub_get = "select substatus from vicidial_campaign_statuses where status = '$dispo'";
$result_get=mysqli_query($link,$sub_get);
$row = mysqli_fetch_row($result_get);
echo $row[0];

?>
