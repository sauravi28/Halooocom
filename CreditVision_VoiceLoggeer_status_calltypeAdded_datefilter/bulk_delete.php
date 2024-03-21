<?php

$fileName = $_REQUEST['path'];	
$path = rtrim($fileName, ',');

$con = mysqli_connect("localhost","root","Hal0o(0m@72427242","Voicelogs");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$update_value = "update voicefiles set deleted = '1' where filename IN($path)"; 
mysqli_query($con,$update_value);

echo "Recording Deleted Successfully..";


?>
		