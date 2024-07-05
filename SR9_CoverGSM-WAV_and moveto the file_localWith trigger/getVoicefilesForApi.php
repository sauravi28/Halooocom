<?php
require_once("dbconnect_mysqli.php");

//echo "inside";

$lead_id = $_REQUEST['lead_id'];
$user_name = $_REQUEST['username'];
$loanId = $_REQUEST['loanId'];
$disposition = $_REQUEST['disposition'];
$sub_disposition1 = $_REQUEST['sub_disposition1'];
$sub_disposition2 = $_REQUEST['sub_disposition2'];
$comments = $_REQUEST['comments'];
$amount = $_REQUEST['amount'];
$popupdate = $_REQUEST['popupdate'];
$attempt_type = $_REQUEST['attempt_type'];
$attempt_status = $_REQUEST['attempt_status'];
$mode_of_payment = $_REQUEST['mode_of_payment'];
$session_id = $_REQUEST['session_id'];
$phone_number = $_REQUEST['phone_number'];
$callDate = date('Y-m-d H:i:s');
$recording_filename1 = $_REQUEST['recording_filename'];


$recording_filename2 = $recording_filename1."-all.wav";
//$recording_filename1."-all.gsm"


		$sql = 'insert into salesforceApi_data (lead_id,username,loanId,disposition,sub_disposition1,sub_disposition2,comments,amount,popupdate,attempt_type,attempt_status,mode_of_payment,session_id,phone_number,callDate,recording_filename) 
		values("'.$lead_id.'","'.$user_name.'","'.$loanId.'","'.$disposition.'","'.$sub_disposition1.'","'.$sub_disposition2.'","'.$comments.'","'.$amount.'","'.$popupdate.'","'.$attempt_type.'","'.$attempt_status.'","'.$mode_of_payment.'","'.$session_id.'","'.$phone_number.'","'.$callDate.'","'.$recording_filename2.'")';
		echo $sql;
		$result =mysqli_query($link,$sql);	






?>