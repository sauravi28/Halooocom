<?php

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



$mail_id = $_REQUEST['email_id']; //"sarode.sauravi28@gmail.com";
$sub  = $_REQUEST['subj']; 
$message  =$_REQUEST['message'];

$date = date('Y-m-d H:i:s');


$emailid = $mail_id;

$subject_email = $sub;

$msg = $message;

$sel = "insert into email_send(client_email,subject_email,message_email,datecreate) values ('$emailid','$subject_email','$msg','$date')";
$res = mysqli_query($db,$sel);

echo "Email is triggered";
//echo $emailid.'<br>';
//echo $subject_email;
//echo $msg;


?>
