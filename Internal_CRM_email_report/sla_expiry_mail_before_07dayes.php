<?php

					$hostname='localhost';
					$user = 'root';
					$password = 'Hal0o(0m@72427242';
					$mysql_database = 'haloocomCRM';
					$link = mysqli_connect($hostname, $user, $password,$mysql_database);

$entry_date_time = date('Y-m-d H:i:s');

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
			$str2 = date('Y-m-d', strtotime('-7 days', strtotime($date_before)));


			$date_1 = $str2;   
			$date_2 = $sla_expiry;
             
		$sql_user = "SELECT name FROM `accounts` where id = '".$accountId."'";
		$result_user = mysqli_query($link,$sql_user);
		$row_user = mysqli_fetch_array($result_user);
		$accountName = $row_user['name'];
		
		$sql_useremail = "SELECT customer_email_id_c FROM `accounts_cstm` where id_c = '".$accountId."'";
		$result_useremail = mysqli_query($link,$sql_useremail);
		$row_useremail = mysqli_fetch_array($result_useremail);
		$client_email_id = $row_useremail['customer_email_id_c'];
				
				
	    if($date_1== $today){
			
			
		require_once "/var/www/html/Mail.php"; // PEAR Mail package
		require_once "/var/www/html/Mail/mime.php"; // PEAR Mail_Mime packge
		
				$from = "<crm@haloocom.com>";  //haloopri@gmail.com
				$to = $client_email_id.",<support@haloocom.com>";//"<sauravi.sarode@haloocom.com>";
				$subject = "SLA Expiring on Date (".$sla_expiry.")"; //'Re:' . $Lastemailsubject; 
				$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
				
				$text = "Dear ".$accountName.",
We hope you're doing well. This email is to inform you that your support is about to expire in less than 7 days. Its expiring on Date (".$sla_expiry."). To continue receiving our support services, we kindly request you to renew your support package as soon as possible.Renewing your support package ensures that we can provide you with uninterrupted assistance and quick resolutions to any issues you may face. Our team is committed to delivering timely solutions and ensuring your satisfaction.Please contact our customer support team at (+91 80 35510099) or (support@haloocom.com) to discuss the renewal process and available support packages. Without renewal, you will be unable to create new support tickets.
Thank you for your continued trust in our products and services. We value our relationship with you and are here to assist you.
Best regards,";
				//$text = " Dear Team,
//Reminder Mail for SLA Renewal. The SLA/AMC for Account ".$accountName." is expiring on Date (".$sla_expiry."). The support is to be renewed within next 05 days." ; // text and html versions of email.
				
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
							$sql_insert1 = 'insert into email_send (to_email_id,client_name,email_status,sla_or_ticket_base_cust,entry_date_time,sla_expiry,subject) values("'.$to.'","'.$accountName.'","Email Not sent","SLA","'.$entry_date_time.'","'.$sla_expiry.'","'.$subject.'")'; 
							//echo "==>".$sql_insert;
							$res_insert1 =mysqli_query($link,$sql_insert1);

						}
					else
						{
							echo("Email successfully sent!");
							
							$sql_insert = 'insert into email_send (to_email_id,client_name,email_status,sla_or_ticket_base_cust,entry_date_time,sla_expiry,subject) values("'.$to.'","'.$accountName.'","Email successfully sent","SLA","'.$entry_date_time.'","'.$sla_expiry.'","'.$subject.'")'; 
							$res_insert =mysqli_query($link,$sql_insert);

							
							//$update_emailUpdateSla = 'update accounts_cstm set slaemail_update_c="1"  where id_c="'.$accountId.'"'; 
							//mysqli_query($link,$update_emailUpdateSla);
							
						}
			
		}
			

	}



?>