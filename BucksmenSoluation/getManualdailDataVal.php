<?php
require_once("dbconnect_mysqli.php");

$phoneNo = $_REQUEST['telno'];

$phoneNo_ct = "SELECT count(*) FROM `vicidial_log` WHERE `phone_number`='$phoneNo'";
//echo $phoneNo_ct;
$res_ct = mysqli_query($link,$phoneNo_ct);
$row_ct = mysqli_fetch_array($res_ct);
$CountPhoneNo = $row_ct[0];

echo $CountPhoneNo;

?>