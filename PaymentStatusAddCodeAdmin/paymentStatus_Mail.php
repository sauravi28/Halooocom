<?php
require '/srv/www/htdocs/Mail.php'; // PEAR Mail package
require '/srv/www/htdocs/Mail/mime.php'; // PEAR Mail_Mime packge
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

$sel = "select id,filename from vicidial_PaymentStatus_data where st = '0'";
$res = mysqli_query($db,$sel);
while($row = mysqli_fetch_array($res))
{				
$from = "Haloo_Email<crm@haloocom.com>";
$to = "<sauravi.sarode@haloocom.com>";
$subject ="Payment Update Status By Customer";
$text = "Restore the services as payment is made.";
$AttachFileName = $row[1];
$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
$crlf = "\n";
$file ="/srv/www/htdocs/admin/uploadsPaymentStatus_files/".$AttachFileName;
echo "FileName ==>".$file;
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'application/octet-stream');
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
	$selu1 = "update vicidial_PaymentStatus_data set st = '1',mail_status = 'Message not successfully sent' where id = '$row[0]'";
	$resu1 = mysqli_query($db,$selu1);
}
else{
	echo("Message successfully sent!");
	$selu2 = "update vicidial_PaymentStatus_data set st = '1', mail_status = 'Message successfully sent' where id = '$row[0]'";
	$resu2 = mysqli_query($db,$selu2);
}
			
}
?>