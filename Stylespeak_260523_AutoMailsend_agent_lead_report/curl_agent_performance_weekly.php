<?php
$handle = curl_init();
$date = date('Y-m-d');

$ts = strtotime($date);
$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
$start_date = date('Y-m-d', $start);
$end_date = date('Y-m-d', strtotime('next saturday', $start));

//$url ="http://10.9.6.4/admin/lead_report_export_mail.php?DB=&run_export=1&query_date=$date&end_date=$date&header_row=YES&rec_fields=NONE&call_notes=NO&export_fields=STANDARD&campaign%5B%5D=---NONE---&group%5B%5D=---NONE---&list_id%5B%5D=---ALL---&status%5B%5D=---ALL---&user_group%5B%5D=---ALL---&SUBMIT=SUBMIT";
$url ="http://192.168.0.100/admin/AST_agent_performance_detail_mail_weekly.php?DB=&query_date=$start_date&end_date=$end_date&group%5B%5D=--ALL--&user_group%5B%5D=ADMIN&users%5B%5D=--ALL--&shift=ALL&SUBMIT=SUBMIT";

//"http://192.168.0.100/admin/AST_agent_performance_detail.php?DB=&query_date=2023-05-26&end_date=2023-05-26&group%5B%5D=HBS&group%5B%5D=HBSCONF&group%5B%5D=STYLESPK&group%5B%5D=Test&user_group%5B%5D=ADMIN&users%5B%5D=--ALL--&shift=ALL&SUBMIT=SUBMIT";
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($handle);
curl_close($handle);
echo $output;
?>
