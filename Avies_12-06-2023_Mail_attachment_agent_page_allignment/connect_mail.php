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

$sel = "select id,client_email,subject_email,message_email,upload_fileName from email_send where st = '0'";
$res = mysqli_query($db,$sel);
while($row = mysqli_fetch_array($res))
{

$from = "ZIFFYTECH<ziffytech@ziffytech.com>";

$to = $row[1];
//echo "---->".$to;
$subject =$row[2];
//echo "=====>".$sub;

$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
$text = $row[3];
$AttachFile = $row[4];

$AttachFileName = substr($AttachFile,10);
$crlf = "\n";
$file ="/srv/www/htdocs/haloocom_connect4/upload_emailFile/".$AttachFileName;
//echo $file;exit;
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'application/octet-stream');
$body = $mime->get();
$headers = $mime->headers($headers);

$host = "smtp.office365.com";
$username = "ziffytech@ziffytech.com";
$password = "qfnrgftjzdwcjphs";
$port ="587";

/*$host = "email-smtp.us-west-2.amazonaws.com";
$username = "awssupport@go-dgtl.in";
$password = "Prutech2021";
$port ="465";*/

$smtp = Mail::factory('smtp', array ('host' => $host,'port'=>$port, 'auth' => true, 'username' =>$username,'password' => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail))
{
echo("<p>" . $mail->getMessage() . "</p>");
$selu = "update email_send set  st = '1',mail_status = 'Message not successfully sent' where id = '$row[0]'";
$resu = mysqli_query($db,$selu);
}
else
{
echo("Message successfully sent!");
$selu = "update email_send set  st = '1',mail_status = 'Message successfully sent' where id = '$row[0]'";
$resu = mysqli_query($db,$selu);
}
unset($data);
}
?>
