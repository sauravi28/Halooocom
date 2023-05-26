<?php
$handle = curl_init();
$date = date('Y-m-d');

$startDate = date("Y-m-01", strtotime($date));
$endDate = date("Y-m-t", strtotime($date)); 

//$url ="http://10.9.6.4/admin/lead_report_export_mail.php?DB=&run_export=1&query_date=$date&end_date=$date&header_row=YES&rec_fields=NONE&call_notes=NO&export_fields=STANDARD&campaign%5B%5D=---NONE---&group%5B%5D=---NONE---&list_id%5B%5D=---ALL---&status%5B%5D=---ALL---&user_group%5B%5D=---ALL---&SUBMIT=SUBMIT";
$url ="http://192.168.0.100/admin/AST_agent_performance_detail_mail_monthly.php?DB=&query_date=$startDate&end_date=$endDate&group%5B%5D=--ALL--&user_group%5B%5D=ADMIN&users%5B%5D=--ALL--&shift=ALL&SUBMIT=SUBMIT";

//"http://192.168.0.100/admin/AST_agent_performance_detail.php?DB=&query_date=2023-05-26&end_date=2023-05-26&group%5B%5D=HBS&group%5B%5D=HBSCONF&group%5B%5D=STYLESPK&group%5B%5D=Test&user_group%5B%5D=ADMIN&users%5B%5D=--ALL--&shift=ALL&SUBMIT=SUBMIT";
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($handle);
curl_close($handle);
echo $output;
?>
