<?php
$date = date('Y-m-d');
$report_Name = "agent_performance_detailReport_".$date.".csv"; 
require_once "/srv/www/htdocs/Mail.php"; // PEAR Mail package
require_once "/srv/www/htdocs/Mail/mime.php"; // PEAR Mail_Mime packge
$from = "Haloo_Email<haloopri@gmail.com>";
//$to = "<jose.mathew@haloocom.in>";
$to = "<sauravi.sarode@haloocom.com>";
$subject =$report_Name;
$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
$text = "Dear Team,
Please find the attached Agent Performance Detail report for
192.168.0.100 server.
THANKS "; // text and html versions of email.
$crlf = "\n";
$file ="/srv/www/htdocs/admin/".$report_Name;
 //echo $file;exit;
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'application/octet-stream');
$body = $mime->get();
$headers = $mime->headers($headers);
$host = "smtp.gmail.com";
$username = "haloopri@gmail.com";
$password = "Haloocom$2020&";
$port ="587";
$smtp = Mail::factory('smtp', array ('host' => $host,'port'=>$port, 'auth' => true, 'username' =>$username,'password' => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail))
{
echo("<p>" . $mail->getMessage() . "</p>");
}
else
{
echo("Message successfully sent!");
}
unset($data);
?>
