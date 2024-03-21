<?php
/*$file = $_REQUEST['file'];
$fl = $_REQUEST['fl'];
$fl= "20190807-174143_8075742338_6666_HALOOCOM-all.gsm";
$folder = date('Y-m-d');
$folder = "2019-08-07";*/

$file = "voicefiles/2024-03-05/20240305-103751_8939478414_USER3_CYC15-all.gsm";
$output = "TestFilewavFormat/20240305-103751_8939478414_USER3_CYC15-all.wav";
$input=$file;
$folder = date('Y-m-d');
$output = str_replace("gsm","wav",$output);
$play=exec("sox $input $output");
//echo $play;
?>
