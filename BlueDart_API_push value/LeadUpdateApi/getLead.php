<?php


	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$phone_number = $_REQUEST['contactnumber'];
	$reamrk = $_REQUEST['reamrk'];
	$disposition = $_REQUEST['disposition'];
	
	$comments = urldecode($reamrk);
	
	
	
	$select_stmt = "SELECT lead_id from vicidial_list where phone_number='$phone_number';";
	$resultstmt = mysqli_query($link,$select_stmt);
	//$row1 = mysqli_fetch_array($resultstmt);
	
	while($row1 = mysqli_fetch_array($resultstmt)){
		
		$lead_id = $row1[0];	
		$update_value = 'update vicidial_list set status = "'.$disposition.'",comments="'.$comments.'" where phone_number = "'.$phone_number.'" and lead_id="'.$lead_id.'"'; 
		mysqli_query($link,$update_value);
	}

//https://bluedart.haloocom.in/LeadUpdateApi/getLead.php?contactnumber=9370868920&reamrk=Lead%20CallReamrk%20Upadte&disposition=AB
	
	
					
	

