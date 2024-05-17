<?php


	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$phone_number = $_REQUEST['contactnumber'];
	$user = $_REQUEST['user'];
	$disposition = $_REQUEST['disposition'];
	$campaign_id = $_REQUEST['campaign'];
	$callback_time = $_REQUEST['callback_time'];
	$NOW_TIME = date('Y-m-d H:i:s');
	
	//https://bluedart.haloocom.in/CallbackApi/getCallbackVal.php?contactnumber=9370868920&disposition=CB&user=Test&campaign=HALOOCOM&callback_time=2023-04-14+16%3A15%3A00
	
	
	
	$select_stmt = "SELECT lead_id,list_id,called_since_last_reset from vicidial_list where phone_number='$phone_number';";
	$resultstmt = mysqli_query($link,$select_stmt);
	$row1 = mysqli_fetch_array($resultstmt);
	
	//while($row1 = mysqli_fetch_array($resultstmt)){
		
		
		$lead_id = $row1[0];
        $list_id = $row1[1];
		$called_since_last_reset = $row1[2];
		
			if (preg_match('/Y/',$called_since_last_reset))
				{
				$called_since_last_reset = preg_replace('/Y/','',$called_since_last_reset);
				if (strlen($called_since_last_reset) < 1) {$called_since_last_reset = 0;}
				$called_since_last_reset++;
				$called_since_last_reset = "Y$called_since_last_reset";
				}
			else {$called_since_last_reset = 'Y';}
		 		
		$update_value = 'update vicidial_list set status = "CBHOLD",called_since_last_reset="'.$called_since_last_reset.'" where phone_number = "'.$phone_number.'" and lead_id="'.$lead_id.'"'; 
		mysqli_query($link,$update_value);
		
		//echo $update_value;
		//echo "upadte";
		
		
		$sql_insert="INSERT INTO vicidial_callbacks (lead_id,list_id,campaign_id,status,entry_time,callback_time,user,recipient,comments,user_group,lead_status) values('$lead_id','$list_id','$campaign_id','ACTIVE','$NOW_TIME','$callback_time','$user','ANYONE','','ADMIN','$disposition');";
		
		$res_insert =mysqli_query($link,$sql_insert);
		
	//}

//https://bluedart.haloocom.in/LeadUpdateApi/getLead.php?contactnumber=9370868920&reamrk=Lead%20CallReamrk%20Upadte&disposition=AB
	
	
					
	

