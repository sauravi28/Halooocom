<?php
date_default_timezone_set('Asia/Kolkata'); 
$id = $_REQUEST['id'];
$con = mysqli_connect("localhost","root","Hal0o(0m@72427242","Voicelogs");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$update_value = "update voicefiles set deleted = '1' where id = '$id'"; 
mysqli_query($con,$update_value);

echo "Recording Deleted Successfully..";

?>
