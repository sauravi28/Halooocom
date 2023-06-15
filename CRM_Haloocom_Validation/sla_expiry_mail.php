<?php

					$hostname='localhost';
					$user = 'root';
					$password = 'Hal0o(0m@72427242';
					$mysql_database = 'haloocomCRM';
					$link = mysqli_connect($hostname, $user, $password,$mysql_database);


$stmt="SELECT sla_date_c,id_c FROM accounts_cstm WHERE `sla_date_c` !=''";
	//echo $stmt;
	//echo "<br>";
	$rslt=mysqli_query($link,$stmt);
	while($row_rslt = mysqli_fetch_array($rslt))
	{
			$sla_expiry = $row_rslt[0];
			$accountId = $row_rslt[1];
			//echo $sla_expiry;
			//echo "<br>";
	        $today = date('Y-m-d');
			
			$date_before = $sla_expiry; //date from database 
			$str2 = date('Y-m-d', strtotime('-5 days', strtotime($date_before)));


			$date_1 = $str2;   
			$date_2 = $sla_expiry;
             
		$sql_user = "SELECT name FROM `accounts` where id = '".$accountId."'";
		$result_user = mysqli_query($link,$sql_user);
		$row_user = mysqli_fetch_array($result_user);
		$accountName = $row_user['name'];
				
				
	    if($date_1== $today){
			
			
		require_once "/var/www/html/Mail.php"; // PEAR Mail package
		require_once "/var/www/html/Mail/mime.php"; // PEAR Mail_Mime packge
		
				$from = "<crm@haloocom.com>";  //haloopri@gmail.com
				$to = "<support@haloocom.com>";
				$subject = "SLA Expiry"; //'Re:' . $Lastemailsubject; 
				$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
				$text = " Dear Team,

Reminder Mail for SLA Renewal. The SLA/AMC for Account ".$accountName." is expiring on Date (".$sla_expiry."). The support is to be renewed within next 05 days." ; // text and html versions of email.
				$crlf = "\n";
				//$file ="/srv/www/htdocs/admin/".$report_Name;
				//echo $file;exit;
				$mime = new Mail_mime($crlf);
				$mime->setTXTBody($text);
				//$mime->setHTMLBody($html);
				//$mime->addAttachment($file, 'application/octet-stream');
				$body = $mime->get();
				$headers = $mime->headers($headers);

				$host = "smtp.gmail.com"; 
				$username = "crm@haloocom.com"; 
				$password = "zggfhiqxqbmhzcou";  
				$port ="587";  

				$smtp = Mail::factory('smtp', array ('host' => $host,'port'=>$port, 'auth' => true, 'username' =>$username,'password' => $password));
				$mail = $smtp->send($to, $headers, $body);
					if (PEAR::isError($mail))
						{
							echo("<p>" . $mail->getMessage() . "</p>");
						}
					else
						{
							echo("Email successfully sent!");
							
							//$update_emailUpdateSla = 'update accounts_cstm set slaemail_update_c="1"  where id_c="'.$accountId.'"'; 
							//mysqli_query($link,$update_emailUpdateSla);
							
						}
			
		}
			

	}



?>