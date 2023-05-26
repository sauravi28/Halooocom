<?php
$handle = curl_init();
$date = date('Y-m-d');
$url ="http://192.168.0.100/admin/lead_report_exportMail.php?DB=&run_export=1&query_date=$date&end_date=$date&header_row=YES&rec_fields=NONE&call_notes=NO&export_fields=STANDARD&campaign%5B%5D=---NONE---&group%5B%5D=---NONE---&list_id%5B%5D=---ALL---&status%5B%5D=DROP&user_group%5B%5D=---ALL---&SUBMIT=SUBMIT";
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($handle);
curl_close($handle);
echo $output;
?>
