<?php
//fetch.php

$hostname='localhost';
        $user = 'root';
        $password = 'Hal0o(0m@72427242';
        $mysql_database = 'haloocomCRM';
        $link = mysqli_connect($hostname, $user, $password,$mysql_database);
        mysqli_select_db($db,$mysql_database);
        if (mysqli_connect_errno())
  {
   die("Connection failed: " . mysqli_connect_error());
  }		

$request = mysqli_real_escape_string($link, $_POST["query"]);
$query = "SELECT name FROM accounts WHERE name LIKE '%".$request."%'";
$result = mysqli_query($link, $query);
$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["name"];
 }
 echo json_encode($data);
}

?>
