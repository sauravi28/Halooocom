<?php

date_default_timezone_set("Asia/Calcutta");

$user = $_REQUEST['user'];
$txt_sms_no = $_REQUEST['txt_sms_no'];
$SmsNo = "+91".$txt_sms_no;

$msg = $_REQUEST['txt_sms'];

$txt_Msg = urlencode($msg);

//$txt_sms_no =(int)$txt_sms_noval;

//$url= "https://veup.versatilesmshub.com/api/sendsms.php?api=YASwXHEjb0OgwldqvmTfMg&senderid=CUTISB&channel=Trans&DCS=0&flashsms=0&number=".$txt_sms_no."&text=".$txt_Msg."&SmsCampaignId=1&EntityID=1501592250000012395&DLT_TE_ID=1507165355479919087";;

//$url = "http://mobicomm.dove-sms.com//submitsms.jsp?user=AviesPub&key=93a39697ecXX&mobile=".$SmsNo."&message=Your%20Order%20of%20%E2%80%98Banco%20Executive%20Reference%20Book%20yuyi%20is%20dispatched%20by%20Avies%20Publication,%20Kolhapur.%20Details%20have%20been%20sent%20to%20your%20registered%20Mail%20Id.%20You%20will%20receive%20your%20order%20within%205-7%20business%20days.%20Get%20back%20to%20us%20in%20case%20of%20any%20issues.%20Contact:%209168653001%20;%20aviespublication&senderid=AVIESP&accusage=1&entityid=1701158053992275676&tempid=1707162634760436088";
$url = "http://mobicomm.dove-sms.com//submitsms.jsp?user=AviesPub&key=93a39697ecXX&mobile=".$SmsNo."&message=".$txt_Msg."&senderid=AVIESP&accusage=1&entityid=1701158053992275676&tempid=1707162634760436088";

//echo $url;

$ch=curl_init($url);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$curl_scraped_page=curl_exec($ch);

curl_close($ch);

$resp1= $curl_scraped_page;
$response=print_r($resp1,true);

echo "\nRESPONSE:\n".$resp1;

?>