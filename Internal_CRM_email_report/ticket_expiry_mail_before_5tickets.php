<?php

					$hostname='localhost';
					$user = 'root';
					$password = 'Hal0o(0m@72427242';
					$mysql_database = 'haloocomCRM';
					$link = mysqli_connect($hostname, $user, $password,$mysql_database);


	$entry_date_time = date('Y-m-d H:i:s');
	$stmt="SELECT no_of_tickets_c,id_c,installation_date_c FROM accounts_cstm WHERE `no_of_tickets_c` !='' and `support_tickets_or_date_c`='no_of_tickets'";
	//echo $stmt;
	//echo "<br>";
	$rslt=mysqli_query($link,$stmt);
	while($row_rslt = mysqli_fetch_array($rslt))
	{
			$no_of_tickets_c = $row_rslt[0];
			$accountId = $row_rslt[1];
			//echo $no_of_tickets_c;
			//echo "<br>";
			$installation_date_c = $row_rslt[2];
	        $today = date('Y-m-d');
			
		$sql_case = "SELECT count(*) as count FROM `cases` WHERE `account_id`='".$accountId."' and date_entered >= '$installation_date_c 00:00:00' and date_entered <= '$today 23:59:59'";
		//echo $sql_case;
		$result_case = mysqli_query($link,$sql_case);
		$row_case = mysqli_fetch_array($result_case);
		$No_of_ticketsUse = $row_case['count'];
		
		$pendingNo_of_tickets = $no_of_tickets_c - $No_of_ticketsUse;
             
		$sql_user = "SELECT name FROM `accounts` where id = '".$accountId."'";
		$result_user = mysqli_query($link,$sql_user);
		$row_user = mysqli_fetch_array($result_user);
		$accountName = $row_user['name'];
		
		/*echo "====================> accountName ".$accountName;
		echo "<br>";
		echo "=======================> no of tickets ".$no_of_tickets_c;
		echo "<br>";
		echo "=======================> no of use tickets ".$No_of_ticketsUse;
		echo "<br>";
		echo "=======================> Pending ".$pendingNo_of_tickets ;
		echo "<br>";*/
		
		
		
		$sql_useremail = "SELECT customer_email_id_c FROM `accounts_cstm` where id_c = '".$accountId."'";
		$result_useremail = mysqli_query($link,$sql_useremail);
		$row_useremail = mysqli_fetch_array($result_useremail);
		$client_email_id = $row_useremail['customer_email_id_c'];
				
				
	    if($pendingNo_of_tickets == '5'){
			
		//echo "inside if";	
		require_once "/var/www/html/Mail.php"; // PEAR Mail package
		require_once "/var/www/html/Mail/mime.php"; // PEAR Mail_Mime packge
		
				$from = "<crm@haloocom.com>";  //haloopri@gmail.com
				$to = $client_email_id.",<support@haloocom.com>";//"<sauravi.sarode@haloocom.com>";
				$subject = "Important Update Regarding Your Haloocom Ticket-Based Support Subscription"; //'Re:' . $Lastemailsubject; 
				$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
				
				$text = "Dear ".$accountName.",
We are writing to provide you with a significant update concerning your Haloocom Ticket-Based Support Subscription.
We would like to inform you that you have utilized [".$No_of_ticketsUse."] out of [ ".$no_of_tickets_c." ] allotted tickets from your support subscription. This implies that you still have [".$pendingNo_of_tickets."] tickets available for your upcoming support requirements.
Our dedicated support team is committed to assisting you with any queries, challenges, or requests you may have. Should you need further support or wish to make use of your remaining tickets, please feel free to get in touch with our support experts.
Thank you for entrusting Haloocom Technologies with your support needs. We are eager to continue providing you with exceptional service.";
				
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
							$sql_insert1 = 'insert into email_send (to_email_id,client_name,email_status,sla_or_ticket_base_cust,entry_date_time,pending_tickets,subject) values("'.$to.'","'.$accountName.'","Email successfully sent","Ticket","'.$entry_date_time.'","'.$pendingNo_of_tickets.'","'.$subject.'")'; 
							$res_insert1 =mysqli_query($link,$sql_insert1);

						}
					else
						{
							echo("Email successfully sent!");
							
							$sql_insert = 'insert into email_send (to_email_id,client_name,email_status,sla_or_ticket_base_cust,entry_date_time,pending_tickets,subject) values("'.$to.'","'.$accountName.'","Email successfully sent","Ticket","'.$entry_date_time.'","'.$pendingNo_of_tickets.'","'.$subject.'")'; 
							$res_insert =mysqli_query($link,$sql_insert);

							
							//$update_emailUpdateSla = 'update accounts_cstm set slaemail_update_c="1"  where id_c="'.$accountId.'"'; 
							//mysqli_query($link,$update_emailUpdateSla);
							
						}
			
		}
			

	}



?>