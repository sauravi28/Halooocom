<?php

$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
$txt_contact_no = $_REQUEST['ContactNo']; 
$mobileNo = "91".$txt_contact_no;
$campaign = $_REQUEST['campaign'];
$DispoChoiceValue = $_REQUEST['DispoChoiceValue'];
$calldate=date("Y-m-d h:i:s");

if($campaign == "GHITL" && $DispoChoiceValue == "Comp"){
	
		$txt_Msg = "Thank+you+for+calling.+Your+feedback+is+important.+Please+answer+%7B3%7D+simple+questions+and+help+us+improve+our+services-+%7B%23https%3A%2F%2Ftinyurl.com%2F3hybpz89+%23%7D+Good+Health+TPA.";

		//echo $txt_Msg;

		$url="http://api.smscountry.com/SMSCwebservice_bulk.aspx?User=Haloocom&passwd=%24Z6w6MP1Lt%25&mobilenumber=".$mobileNo."&message=".$txt_Msg."&sid=GUDHEL&mtype=N&DR=Y";

		//echo $url;

		//echo "<br>";
		//die;

		$curl = curl_init();

		  curl_setopt_array($curl, array(
		  CURLOPT_USERAGENT   => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0",
		  CURLOPT_URL => "http://api.smscountry.com/SMSCwebservice_bulk.aspx?User=Haloocom&passwd=%24Z6w6MP1Lt%25&mobilenumber=".$mobileNo."&message=".$txt_Msg."&sid=GUDHEL&mtype=N&DR=Y",
			  
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSLVERSION => 4,

		  CURLOPT_SSL_CIPHER_LIST => "SSLv3",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);

		if($error) {
			echo "cURL Error:" . $error;
			$sql_insert="insert into sms_log(date_time,phone_no,campaign,disposition,status)values('$calldate','$mobileNo','$campaign','$DispoChoiceValue','$error')";
			$res_insert =mysqli_query($link,$sql_insert);

		} else {
			echo $response;
			$sql_insert="insert into sms_log(date_time,phone_no,campaign,disposition,status)values('$calldate','$mobileNo','$campaign','$DispoChoiceValue','$response')";
			$res_insert =mysqli_query($link,$sql_insert);
		}
} 
if($campaign == "IIRM" && $DispoChoiceValue == "Comp"){
	
	$txt_Msg = "Thank+you+for+calling.+Your+feedback+is+important.+Please+answer+%7B3%7D+feedback+questions+and+help+improve+our+services-+https%3A%2F%2Ftinyurl.com%2F5bbyk9ty+-India+Insure.";

		//echo $txt_Msg;

		$url="http://api.smscountry.com/SMSCwebservice_bulk.aspx?User=Haloocom&passwd=%24Z6w6MP1Lt%25&mobilenumber=".$mobileNo."&message=".$txt_Msg."&sid=IDAINS&mtype=N&DR=Y";

		echo $url;

		//echo "<br>";

		$curl = curl_init();

		  curl_setopt_array($curl, array(
		  CURLOPT_USERAGENT   => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0",
		  CURLOPT_URL => "http://api.smscountry.com/SMSCwebservice_bulk.aspx?User=Haloocom&passwd=%24Z6w6MP1Lt%25&mobilenumber=".$mobileNo."&message=".$txt_Msg."&sid=IDAINS&mtype=N&DR=Y",
			  
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSLVERSION => 4,

		  CURLOPT_SSL_CIPHER_LIST => "SSLv3",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);

		if($error) {
			echo "cURL Error:" . $error;
			$sql_insert="insert into sms_log(date_time,phone_no,campaign,disposition,status)values('$calldate','$mobileNo','$campaign','$DispoChoiceValue','$error')";
			$res_insert =mysqli_query($link,$sql_insert);

		} else {
			echo $response;
			$sql_insert="insert into sms_log(date_time,phone_no,campaign,disposition,status)values('$calldate','$mobileNo','$campaign','$DispoChoiceValue','$response')";
			$res_insert =mysqli_query($link,$sql_insert);
		}

	
	
}



?>