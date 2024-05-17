<?php
/*
date_default_timezone_set('Asia/Kolkata'); 
$id = $_REQUEST['id'];
require 'dbconnect_mysqli.php';

$sql = "SELECT rec_filename FROM `cdr` where id = '$id'";
$res = mysqli_query($link,$sql);
$row = mysqli_fetch_array($res);
$filename = $row[0];

$file  = '/var/www/html/voicefiles/'.$filename;
$input=$file;


$file1 = explode(".",$filename);

$output ='/var/www/html/voicefiles/'.$file1[0].'.gsm';  

$out=shell_exec("sox $input $output");

$output ='http://3.108.49.57/voicefiles/'.$file1[0].'.gsm';

echo $output; */

date_default_timezone_set('Asia/Kolkata'); 
$filename = $_REQUEST['id'];
require 'dbconnect_mysqli.php';



$file  = '/var/www/html/voicefiles/'.$filename;   //test.gsm
$input=$file;


$file1 = explode(".",$filename);

$output ='/var/www/html/voicefiles_wav/'.$file1[0].'.ogg';  

$out=shell_exec("sox $input $output");

$output ='http://13.235.10.182/voicefiles_wav/'.$file1[0].'.ogg'; //Haloocom_report/report.php

echo $output;


?>
