<?php

require_once("dbconnect_mysqli.php");
$dispo = $_REQUEST['dispo'];
$campaign = $_REQUEST['campaign'];

$sub_get = "select substatus from vicidial_campaign_statuses where status = '$dispo' and campaign_id='$campaign'";
//echo "==>".$sub_get;
$result_get=mysqli_query($link,$sub_get);
$row = mysqli_fetch_row($result_get);
echo $row[0];

?>
