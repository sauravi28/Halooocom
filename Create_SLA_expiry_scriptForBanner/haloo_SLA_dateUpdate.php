<?php

// URL to call
$url = 'https://crm.haloocom.in/haloocomCRM/stop_script_date.php?id=Tbangl20240507';
// Make the GET request and get the response
$response = file_get_contents($url);
//echo "==>".$response;
$newresponse = explode("==",$response);
// Check if the response is "true"
if ($newresponse[0] === "True"){
	
   $stop_date = $newresponse[1];
   $install_date = $newresponse[2];
   $payment_status = "Unpaid";
   $user_group = "ADMIN";
   
    $hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$db = mysqli_connect($hostname, $user, $password,$mysql_database);
        mysqli_select_db($db,$mysql_database);
	if (mysqli_connect_errno())
	  {
	   die("Connection failed: " . mysqli_connect_error());
	  }

	$stmt_select = "TRUNCATE TABLE vicidial_expiry_date";
    $rslt_rs = mysqli_query($db,$stmt_select);
	
	$stmt_exdate = "INSERT INTO vicidial_expiry_date (start_date,end_date,user_group,payment_status)VALUES('$install_date','$stop_date','$user_group','$payment_status')";        
	$rslt_exdate = mysqli_query($db,$stmt_exdate);
			

} else {
    // Handle other cases if needed
    echo "Response is not true.";
}
?>

