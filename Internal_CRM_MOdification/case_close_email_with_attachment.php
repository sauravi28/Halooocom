<?php

		$hostname='localhost';
		$user = 'root';
		$password = 'Hal0o(0m@72427242';
		$mysql_database = 'haloocomCRM';
		$link = mysqli_connect($hostname, $user, $password,$mysql_database);
		date_default_timezone_set('UTC');


	$date = date("Y-m-d");
	$sql_ticket = "select cc.client_email_id_c, cc.email_update_c,c.id,c.name,c.case_number,c.date_entered,c.date_modified,c.modified_user_id,c.state,cc.case_type_c from cases c left join cases_cstm cc on c.id = cc.id_c where c.date_modified like '$date%' and c.state in ('Closed','Resolved') and cc.case_type_c='Health_Checkup' and c.filename!='' and cc.email_hc_attachment_sent_c='0'";		 //assigned_user_id //order by date_modified DESC limit 1
	//echo $sql_ticket;
	//die;
	$result_ticket = mysqli_query($link,$sql_ticket);
	while($row_ticket = mysqli_fetch_array($result_ticket)) {

		$ticketName = $row_ticket['name'];
		$ticketId = $row_ticket['id'];
		$case_number = $row_ticket['case_number'];
		$date_entered = $row_ticket['date_entered'];
		$date_modified = $row_ticket['date_modified'];
		$assigned_userID = $row_ticket['modified_user_id'];
		$client_email_id = $row_ticket['client_email_id_c'];
		$case_type_c = $row_ticket['case_type_c'];


		$sql_user = "SELECT user_name FROM `users` where id = '".$assigned_userID."'";
		$result_user = mysqli_query($link,$sql_user);
		$row_user = mysqli_fetch_array($result_user);
		$ticketName = $row_user['user_name'];
		$assigned_user_id = $ticketName;
 
 
		require_once "/var/www/html/Mail.php"; // PEAR Mail package
		require_once "/var/www/html/Mail/mime.php"; // PEAR Mail_Mime packge
		
				$from = "<crm@haloocom.com>";  //haloopri@gmail.com
				$to = $client_email_id.",<support@haloocom.com>";
				$subject = "Haloocom Support Ticket - ".$case_number ." and Case type -".$case_type_c." Closed."; 
				$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
				$text = " Dear Customer,
I'm pleased to inform you that your Haloocom Server's health check-up was successfully completed today. Attached, you'll find the detailed report summarizing the evaluation.
Should you have any questions about the report or need further assistance, feel free to reach out.

Note : Download the file and rename is as .pdf format.

Regards,
Haloocom Support
You can reach us on our 24 x 7 
Help Desk Line : +91 80 35510099
Email : helpdesk@haloocom.com; support@haloocom.com" ; // text and html versions of email.
				$crlf = "\n";
				$mime = new Mail_mime($crlf);
				$mime->setTXTBody($text);
				//$mime->setHTMLBody($html);
				if($case_type_c == "Health_Checkup"){
					$file ="/var/www/html/haloocomCRM/upload/".$ticketId;
					//$fileWithExt = $file.".pdf";
					//echo $fileWithExt;
					//die;
					$mime->addAttachment($file, 'application/octet-stream');
				}
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
							
							$update_emailUpdate = 'update cases_cstm set email_hc_attachment_sent_c="1"  where id_c="'.$ticketId.'"'; 
							mysqli_query($link,$update_emailUpdate);
							
						}								

}
?>
