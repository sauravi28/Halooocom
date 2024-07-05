<?php 
	date_default_timezone_set("Asia/kolkata");
	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$firstname = $_REQUEST['firstname'];
	$contactnumber = $_REQUEST['contactnumber'];
	$listid = $_REQUEST['listid'];
	$date = date('Y-m-d H:i:s');
	
	//https://{host}/LeadPushApi/getLead.php?firstname=XXXXXXXXXXX&contactnumber=XXXXXXX&listid=xxx

	$sql = "insert into vicidial_list(first_name,phone_number,entry_date,status,list_id)values('".$firstname."','".$contactnumber."','".$date."','NEW','".$listid."')";
	$result = mysqli_query($link,$sql);
	if($result){
		echo "Lead Uploaded Successfully Created";exit;	
	}else{
		echo "invalid request check your parameters";exit;	
	}

?>