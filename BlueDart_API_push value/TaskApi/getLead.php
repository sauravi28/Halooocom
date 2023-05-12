<?php


	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$phone_number = $_REQUEST['contactnumber'];
	$reamrk = $_REQUEST['reamrk'];
	$disposition = $_REQUEST['disposition'];
	
	
	
	$select_stmt = "SELECT lead_id from vicidial_list where phone_number='$phone_number';";
	$resultstmt = mysqli_query($link,$select_stmt);
	$row1 = mysqli_fetch_array($resultstmt);
	$lead_id = $row1[0];
	
	$update_value = 'update vicidial_list set status = "'.$disposition.'",comments="'.$reamrk.'" where phone_number = "'.$phone_number.'" and lead_id="'.$lead_id.'"'; 
	mysqli_query($link,$update_value);

	
	
					
	

