<!DOCTYPE html> 
<html> 

<head> 
	<link rel="stylesheet" href= 
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<link rel="stylesheet" href="style.css"> 

	<script> 
		function getEmails() { 
			document.getElementById('dataDivID') 
				.style.display = "block"; 
		} 
	</script> 
	<style>
	body { 
font-family: Arial; 
} 
table { 
	font-family: arial, sans-serif; 
	border-collapse: collapse; 
	width: 100%; 
} 
tr:nth-child(even) { 
	background-color: #dddddd; 
} 
td, th { 
	padding: 8px; 
	width:100px; 
	border: 1px solid #dddddd; 
	text-align: left;				 
} 
.form-container { 
	padding: 20px; 
	background: #F0F0F0; 
	border: #e0dfdf 1px solid;				 
	border-radius: 2px; 
} 
* { 
	box-sizing: border-box; 
} 

.columnClass { 
	float: left; 
	padding: 10px; 
} 

.row:after { 
	content: ""; 
	display: table; 
	clear: both; 
} 

.btn { 
	background: #333; 
	border: #1d1d1d 1px solid; 
	color: #f0f0f0; 
	font-size: 0.9em; 
	width: 200px; 
	border-radius: 2px; 
	background-color: #f1f1f1; 
	cursor: pointer; 
} 

.btn:hover { 
	background-color: #ddd; 
} 

.btn.active { 
	background-color: #666; 
	color: white; 
} 

	</style>
</head> 

<body> 
	<h2>List Emails from Gmail using PHP and IMAP</h2> 

	<div id="btnContainer"> 
		<button class="btn active" onclick="getEmails()"> 
			<i class="fa fa-bars"></i>Click to get gmail mails 
		</button> 
	</div> 
	<br> 
	
	<div id="dataDivID" class="form-container" style="display:none;"> 



<?php

		$hostname='localhost';
		$user = 'root';
		$password = 'Hal0o(0m@72427242';
		$mysql_database = 'haloocomCRM';
		$link = mysqli_connect($hostname, $user, $password,$mysql_database);
		
$server = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
$username = 'crm@haloocom.com';
$password = 'zggfhiqxqbmhzcou';

// Connect to the IMAP server
$mailbox = imap_open($server, $username, $password);

if (!$mailbox) {
    die('Connection failed: ' . imap_last_error());
}

// Search for SENT folder mails
$search_criteria = 'SUBJECT "SLA Expiring on Date"';
$emails = imap_search($mailbox, $search_criteria);

if ($emails === false) {
    die('Search failed: ' . imap_last_error());
}

// Initialize an array to store the email data
//$emails_data = [];

/* Mail output variable starts*/ 
				$mailOutput = ''; 
				$mailOutput.= '<table><tr><th>Subject </th><th> From </th> 
						<th> Date Time </th> <th> Content </th></tr>'; 

// Loop through the emails and retrieve headers
foreach ($emails as $email_id) {
    // Fetch header
	$headers = imap_fetch_overview($mailbox, $email_id, 0); 
$mailOutput.= '<div class="row">';
 
$subject = $headers[0]->subject;
$msg = substr($subject,-11);
$sla_expiry = substr($msg,0,10);
$to = $headers[0]->to;

$date = $headers[0]->date;
$msgDT = substr($date,5,20);
$msg1DT = substr($msgDT,0,11);
$msg2DT = substr($msgDT,-9);
$entry_date_time = date("Y-m-d", strtotime($msg1DT)).$msg2DT;

 

							$sql_insert = 'insert into email_send (to_email_id,client_name,email_status,sla_or_ticket_base_cust,entry_date_time,sla_expiry,subject) values("'.$to.'","","Email successfully sent","SLA","'.$entry_date_time.'","'.$sla_expiry.'","'.$subject.'")'; 
							//echo "==>".$sql_insert;
							$res_insert =mysqli_query($link,$sql_insert);



					/* Gmail MAILS header information */				 
					$mailOutput.= '<td><span class="columnClass">' . 
								$headers[0]->subject . '</span></td> '; 
					$mailOutput.= '<td><span class="columnClass">' . 
								$headers[0]->to . '</span></td>'; 
					$mailOutput.= '<td><span class="columnClass">' . 
								$headers[0]->date . '</span></td>'; 
					$mailOutput.= '</div>'; 

					/* Mail body is returned */ 
					$mailOutput.= '<td><span class="column">test</span></td></tr></div>'; 
				}// End foreach 
				$mailOutput.= '</table>'; 
				echo $mailOutput; 

// Close the connection
imap_close($mailbox);

// Process $emails_data as needed (e.g., extract information, save to database, etc.)

?>
</div> 
</body> 

</html>

