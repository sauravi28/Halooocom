<?php
# list_download.php
# 
# downloads the entire contents of a vicidial list ID to a flat text file
# that is tab delimited
#
# Copyright (C) 2015  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 90209-1310 - First build
# 90508-0644 - Changed to PHP long tags
# 90721-1238 - Added rank and owner as vicidial_list fields
# 100119-1039 - Filtered comments for \n newlines
# 100508-1439 - Added header row to output
# 100702-1335 - Added custom fields
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100804-1745 - Added option to download DNC and FPGN lists
# 100924-1609 - Added ALL-LISTS option for downloading everything
# 100929-1919 - Fixed ALL-LISTS download option to include custom fields
# 101004-2108 - Added generic custom column headers for custom fields
# 120713-2110 - Added extended_vl_fields option
# 120907-1217 - Raised extended fields up to 99
# 130414-0228 - Added report logging
# 130610-0945 - Finalized changing of all ereg instances to preg
# 130618-0043 - Added filtering of input to prevent SQL injection attacks and new user auth
# 130901-0902 - Changed to mysqli PHP functions
# 140108-0723 - Added webserver and hostname to report logging
# 140326-2235 - Changed to allow for custom fields with a different entry_list_id
# 141114-0036 - Finalized adding QXZ translation to all admin files
# 141229-1840 - Added code for on-the-fly language translations display
# 150727-2158 - Enabled user features for hiding phone numbers and lead data
# 150903-1538 - Added compatibility for custom fields data options
#

$startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["list_id"]))				{$list_id=$_GET["list_id"];}
	elseif (isset($_POST["list_id"]))		{$list_id=$_POST["list_id"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))					{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["group_id"]))				{$group_id=$_GET["group_id"];}
	elseif (isset($_POST["group_id"]))		{$group_id=$_POST["group_id"];}
if (isset($_GET["download_type"]))			{$download_type=$_GET["download_type"];}
	elseif (isset($_POST["download_type"]))	{$download_type=$_POST["download_type"];}

if (strlen($shift)<2) {$shift='ALL';}
if ($group_id=='SYSTEM_INTERNAL') {$download_type='systemdnc';}

$report_name = 'Download List';
$db_source = 'M';


#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,custom_fields_enabled,enable_languages,language_method,active_modules FROM system_settings;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$non_latin =					$row[0];
	$outbound_autodial_active =		$row[1];
	$slave_db_server =				$row[2];
	$reports_use_slave_db =			$row[3];
	$custom_fields_enabled =		$row[4];
	$SSenable_languages =			$row[5];
	$SSlanguage_method =			$row[6];
	$active_modules =				$row[7];
	}
##### END SETTINGS LOOKUP #####
###########################################

if ($non_latin < 1)
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
	}
else
	{
	$PHP_AUTH_PW = preg_replace("/'|\"|\\\\|;/","",$PHP_AUTH_PW);
	$PHP_AUTH_USER = preg_replace("/'|\"|\\\\|;/","",$PHP_AUTH_USER);
	}
$list_id = preg_replace('/[^-_0-9a-zA-Z]/','',$list_id);
$group_id = preg_replace('/[^-_0-9a-zA-Z]/','',$group_id);
$download_type = preg_replace('/[^-_0-9a-zA-Z]/','',$download_type);

$stmt="SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$VUselected_language =		$row[0];
	}

$auth=0;
$auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'',1);
if ($auth_message == 'GOOD')
	{$auth=1;}

if ($auth < 1)
	{
	$VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
	if ($auth_message == 'LOCK')
		{
		$VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
		Header ("Content-type: text/html; charset=utf-8");
		echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
		exit;
		}
	Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$PHP_AUTH_PW|$auth_message|\n";
	exit;
	}

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and download_lists='1';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$download_auth=$row[0];

if ($download_auth < 1)
	{
	Header ("Content-type: text/html; charset=utf-8");
    echo _QXZ("No list download permission").": |$PHP_AUTH_USER|\n";
    exit;
	}

##### BEGIN log visit to the vicidial_report_log table #####
$LOGip = getenv("REMOTE_ADDR");
$LOGbrowser = getenv("HTTP_USER_AGENT");
$LOGscript_name = getenv("SCRIPT_NAME");
$LOGserver_name = getenv("SERVER_NAME");
$LOGserver_port = getenv("SERVER_PORT");
$LOGrequest_uri = getenv("REQUEST_URI");
$LOGhttp_referer = getenv("HTTP_REFERER");
if (preg_match("/443/i",$LOGserver_port)) {$HTTPprotocol = 'https://';}
  else {$HTTPprotocol = 'http://';}
if (($LOGserver_port == '80') or ($LOGserver_port == '443') ) {$LOGserver_port='';}
else {$LOGserver_port = ":$LOGserver_port";}
$LOGfull_url = "$HTTPprotocol$LOGserver_name$LOGserver_port$LOGrequest_uri";

$LOGhostname = php_uname('n');
if (strlen($LOGhostname)<1) {$LOGhostname='X';}
if (strlen($LOGserver_name)<1) {$LOGserver_name='X';}

$stmt="SELECT webserver_id FROM vicidial_webservers where webserver='$LOGserver_name' and hostname='$LOGhostname' LIMIT 1;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
$webserver_id_ct = mysqli_num_rows($rslt);
if ($webserver_id_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$webserver_id = $row[0];
	}
else
	{
	##### insert webserver entry
	$stmt="INSERT INTO vicidial_webservers (webserver,hostname) values('$LOGserver_name','$LOGhostname');";
	if ($DB) {echo "$stmt\n";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$affected_rows = mysqli_affected_rows($link);
	$webserver_id = mysqli_insert_id($link);
	}

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$list_id, $group_id, $download_type|', url='$LOGfull_url', webserver='$webserver_id';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$report_log_id = mysqli_insert_id($link);
##### END log visit to the vicidial_report_log table #####

if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
	{
	mysqli_close($link);
	$use_slave_server=1;
	$db_source = 'S';
	require("dbconnect_mysqli.php");
#	echo "<!-- Using slave server $slave_db_server $db_source -->\n";
	}

$stmt="SELECT user_group,admin_hide_lead_data,admin_hide_phone_data,admin_cf_show_hidden from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGuser_group =				$row[0];
$LOGadmin_hide_lead_data =		$row[1];
$LOGadmin_hide_phone_data =		$row[2];
$LOGadmin_cf_show_hidden =		$row[3];

$stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGallowed_campaigns = $row[0];
$LOGallowed_reports =	$row[1];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
	{
	Header ("Content-type: text/html; charset=utf-8");
    echo _QXZ("You are not allowed to view this report").": |$PHP_AUTH_USER|$report_name|\n";
    exit;
	}

if (file_exists('options.php'))
	{require('options.php');}
$extended_vl_fields_SQL='';
$extended_vl_fields_HEADER='';
if ($extended_vl_fields > 0)
	{
	$extended_vl_fields_SQL = ',q01,q02,q03,q04,q05,q06,q07,q08,q09,q10,q11,q12,q13,q14,q15,q16,q17,q18,q19,q20,q21,q22,q23,q24,q25,q26,q27,q28,q29,q30,q31,q32,q33,q34,q35,q36,q37,q38,q39,q40,q41,q42,q43,q44,q45,q46,q47,q48,q49,q50,q51,q52,q53,q54,q55,q56,q57,q58,q59,q60,q61,q62,q63,q64,q65,q66,q67,q68,q69,q70,q71,q72,q73,q74,q75,q76,q77,q78,q79,q80,q81,q82,q83,q84,q85,q86,q87,q88,q89,q90,q91,q92,q93,q94,q95,q96,q97,q98,q99';
	$extended_vl_fields_HEADER = ",q01,q02,q03,q04,q05,q06,q07,q08,q09,q10,q11,q12,q13,q14,q15,q16,q17,q18,q19,q20,q21,q22,q23,q24,q25,q26,q27,q28,q29,q30,q31,q32,q33,q34,q35,q36,q37,q38,q39,q40,q41,q42,q43,q44,q45,q46,q47,q48,q49,q50,q51,q52,q53,q54,q55,q56,q57,q58,q59,q60,q61,q62,q63,q64,q65,q66,q67,q68,q69,q70,q71,q72,q73,q74,q75,q76,q77,q78,q79,q80,q81,q82,q83,q84,q85,q86,q87,q88,q89,q90,q91,q92,q93,q94,q95,q96,q97,q98,q99";
	}

$LOGallowed_campaignsSQL='';
if ( (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) )
	{
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}

if ($download_type == 'systemdnc')
	{
	##### System DNC list validation #####
	$event_code_type='SYSTEM INTERNAL DNC';
	if (strlen($LOGallowed_campaignsSQL) > 2)
		{
		echo _QXZ("You are not allowed to download this list").": $list_id\n";
		exit;
		}

	$stmt="select count(*) from vicidial_dnc;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$leads_count =$row[0];
		$i++;
		}

	if ($leads_count < 1)
		{
		echo _QXZ("There are no phone numbers in list").": SYSTEM INTERNAL DNC\n";
		exit;
		}
	}
elseif ($download_type == 'dnc')
	{
	##### Campaign DNC list validation #####
	$event_code_type='CAMPAIGN DNC';
	$stmt="select count(*) from vicidial_campaigns where campaign_id='$group_id' $LOGallowed_campaignsSQL;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$lists_allowed =$row[0];
		$i++;
		}

	if ($lists_allowed < 1)
		{
		echo _QXZ("You are not allowed to download this list").": $group_id\n";
		exit;
		}

	$stmt="select count(*) from vicidial_campaign_dnc where campaign_id='$group_id';";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$leads_count =$row[0];
		$i++;
		}

	if ($leads_count < 1)
		{
		echo _QXZ("There are no leads in Campaign DNC list").": $group_id\n";
		exit;
		}
	}
elseif ($download_type == 'fpgn')
	{
	##### Filter Phone Group list validation #####
	$event_code_type='FILTER PHONE GROUP';
	$stmt="select count(*) from vicidial_filter_phone_groups where filter_phone_group_id='$group_id';";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$lists_allowed =$row[0];
		$i++;
		}

	if ($lists_allowed < 1)
		{
		echo _QXZ("You are not allowed to download this list").": $group_id\n";
		exit;
		}

	$stmt="select count(*) from vicidial_filter_phone_numbers where filter_phone_group_id='$group_id';";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$leads_count =$row[0];
		$i++;
		}

	if ($leads_count < 1)
		{
		echo _QXZ("There are no leads in this filter phone group").": $group_id\n";
		exit;
		}
	}
else
	{
	##### list download validation #####
	$event_code_type='LIST';
	$stmt="select count(*) from vicidial_lists where list_id='$list_id' $LOGallowed_campaignsSQL;";
	if ($list_id=='ALL-LISTS')
		{$stmt="select count(*) from vicidial_lists where list_id > 0 $LOGallowed_campaignsSQL;";}
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$lists_allowed =$row[0];
		$i++;
		}

	if ($lists_allowed < 1)
		{
		echo _QXZ("You are not allowed to download this list").": $list_id\n";
		exit;
		}

	$stmt="select count(*) from vicidial_list where list_id='$list_id';";
	if ($list_id=='ALL-LISTS')
		{$stmt="select count(*) from vicidial_list where list_id > 0;";}
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$count_to_print = mysqli_num_rows($rslt);
	if ($count_to_print > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$leads_count =$row[0];
		$i++;
		}

	if ($leads_count < 1)
		{
		echo _QXZ("There are no leads in list_id").": $list_id\n";
		exit;
		}
	}


$US='_';
$MT[0]='';
$ip = getenv("REMOTE_ADDR");
$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$FILE_TIME = date("Ymd-His");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}


if ($download_type == 'systemdnc')
	{
	$TXTfilename = "SYSTEM_DNC_$FILE_TIME.csv";
	$header_row = "phone_number";
	$header_columns='';
	$stmt="select phone_number from vicidial_dnc;";
	}
elseif ($download_type == 'dnc')
	{
	$TXTfilename = "DNC_$group_id$US$FILE_TIME.csv";
	$header_row = "phone_number";
	$header_columns='';
	$stmt="select phone_number from vicidial_campaign_dnc where campaign_id='$group_id';";
	}
elseif ($download_type == 'fpgn')
	{
	$TXTfilename = "FPGN_$group_id$US$FILE_TIME.csv";
	$header_row = "phone_number";
	$header_columns='';
	$stmt="select phone_number from vicidial_filter_phone_numbers where filter_phone_group_id='$group_id';";
	}
else
	{
	$TXTfilename = "LIST_$list_id$US$FILE_TIME.csv";
	$list_id_header='';
	$stmt="select lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id $extended_vl_fields_SQL from vicidial_list where list_id='$list_id';";
	if ($list_id=='ALL-LISTS')
		{
		$list_id_header="list_id,";   
		$stmt="select list_id,lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id $extended_vl_fields_SQL from vicidial_list where list_id > 0;";
		}
	$header_row = $list_id_header . "lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id$extended_vl_fields_HEADER";
	$header_columns='';
	}

// We'll be outputting a TXT file
//header('Content-type: application/octet-stream');
header('Content-type: text/csv');

// It will be called LIST_101_20090209-121212.txt
header("Content-Disposition: attachment; filename=\"$TXTfilename\"");
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
ob_clean();
flush();


$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
$leads_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $leads_to_print)
	{
	$row=mysqli_fetch_row($rslt);

	if ( ($download_type == 'systemdnc') or ($download_type == 'dnc') or ($download_type == 'fpgn') )
		{
		$row_data[$i] .= "$row[0]";
		}
	else
		{
		if ($LOGadmin_hide_phone_data != '0')
			{
			if ($DB > 0) {echo "HIDEPHONEDATA|$row[11]|$LOGadmin_hide_phone_data|\n";}
			$phone_temp = $row[11];
			if (strlen($phone_temp) > 0)
				{
				if ($LOGadmin_hide_phone_data == '4_DIGITS')
					{$row[11] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
				elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
					{$row[11] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
				elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
					{$row[11] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
				else
					{$row[11] = preg_replace("/./",'X',$phone_temp);}
				}
			}
		if ($LOGadmin_hide_lead_data != '0')
			{
			if ($DB > 0) {echo "HIDELEADDATA|$row[5]|$row[6]|$row[12]|$row[13]|$row[14]|$row[15]|$row[16]|$row[17]|$row[18]|$row[19]|$row[20]|$row[21]|$row[22]|$row[26]|$row[27]|$row[28]|$LOGadmin_hide_lead_data|\n";}
			if (strlen($row[5]) > 0)
				{$data_temp = $row[5];   $row[5] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[6]) > 0)
				{$data_temp = $row[6];   $row[6] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[12]) > 0)
				{$data_temp = $row[12];   $row[12] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[13]) > 0)
				{$data_temp = $row[13];   $row[13] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[14]) > 0)
				{$data_temp = $row[14];   $row[14] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[15]) > 0)
				{$data_temp = $row[15];   $row[15] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[16]) > 0)
				{$data_temp = $row[16];   $row[16] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[17]) > 0)
				{$data_temp = $row[17];   $row[17] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[18]) > 0)
				{$data_temp = $row[18];   $row[18] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[19]) > 0)
				{$data_temp = $row[19];   $row[19] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[20]) > 0)
				{$data_temp = $row[20];   $row[20] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[21]) > 0)
				{$data_temp = $row[21];   $row[21] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[22]) > 0)
				{$data_temp = $row[22];   $row[22] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[26]) > 0)
				{$data_temp = $row[26];   $row[26] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[27]) > 0)
				{$data_temp = $row[27];   $row[27] = preg_replace("/./",'X',$data_temp);}
			if (strlen($row[28]) > 0)
				{$data_temp = $row[28];   $row[28] = preg_replace("/./",'X',$data_temp);}
			}
			
		$row[29] = preg_replace("/\n|\r/",'!N',$row[29]);
		$extended_vl_fields_DATA='';

		if ($list_id=='ALL-LISTS')
			{
			if ($extended_vl_fields > 0)
				{$extended_vl_fields_DATA = ",$row[36],$row[37],$row[38],$row[39],$row[40],$row[41],$row[42],$row[43],$row[44],$row[45],$row[46],$row[47],$row[48],$row[49],$row[50],$row[51],$row[52],$row[53],$row[54],$row[55],$row[56],$row[57],$row[58],$row[59],$row[60],$row[61],$row[62],$row[63],$row[64],$row[65],$row[66],$row[67],$row[68],$row[69],$row[70],$row[71],$row[72],$row[73],$row[74],$row[75],$row[76],$row[77],$row[78],$row[79],$row[80],$row[81],$row[82],$row[83],$row[84],$row[85],$row[86],$row[87],$row[88],$row[89],$row[90],$row[91],$row[92],$row[93],$row[94],$row[95],$row[96],$row[97],$row[98],$row[99],$row[100],$row[101],$row[102],$row[103],$row[104],$row[105],$row[106],$row[107],$row[108],$row[109],$row[110],$row[111],$row[112],$row[113],$row[114],$row[115],$row[116],$row[117],$row[118],$row[119],$row[120],$row[121],$row[122],$row[123],$row[124],$row[125],$row[126],$row[127],$row[128],$row[129],$row[130],$row[131],$row[132],$row[133],$row[134]";}
			$row_data[$i] .= "$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[30],$row[31],$row[32],$row[33],$row[34],$row[35]$extended_vl_fields_DATA";
			$export_list_id[$i] = $row[0];
			if ( ($row[35] > 99) and (strlen($row[35]) > 2) )
				{$export_list_id[$i] = $row[35];}
			$export_lead_id[$i] = $row[1];
			}
		else
			{
			if ($extended_vl_fields > 0)
				{$extended_vl_fields_DATA = ",$row[35],$row[36],$row[37],$row[38],$row[39],$row[40],$row[41],$row[42],$row[43],$row[44],$row[45],$row[46],$row[47],$row[48],$row[49],$row[50],$row[51],$row[52],$row[53],$row[54],$row[55],$row[56],$row[57],$row[58],$row[59],$row[60],$row[61],$row[62],$row[63],$row[64],$row[65],$row[66],$row[67],$row[68],$row[69],$row[70],$row[71],$row[72],$row[73],$row[74],$row[75],$row[76],$row[77],$row[78],$row[79],$row[80],$row[81],$row[82],$row[83],$row[84],$row[85],$row[86],$row[87],$row[88],$row[89],$row[90],$row[91],$row[92],$row[93],$row[94],$row[95],$row[96],$row[97],$row[98],$row[99],$row[100],$row[101],$row[102],$row[103],$row[104],$row[105],$row[106],$row[107],$row[108],$row[109],$row[110],$row[111],$row[112],$row[113],$row[114],$row[115],$row[116],$row[117],$row[118],$row[119],$row[120],$row[121],$row[122],$row[123],$row[124],$row[125],$row[126],$row[127],$row[128],$row[129],$row[130],$row[131],$row[132],$row[133]";}
			$row_data[$i] .= "$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[30],$row[31],$row[32],$row[33],$row[34]$extended_vl_fields_DATA";
			$export_list_id[$i] = $list_id;
			if ( ($row[34] > 99) and (strlen($row[34]) > 2) )
				{$export_list_id[$i] = $row[34];}
			$export_lead_id[$i] = $row[0];
			}
		}
	$i++;
	}

$ch=0;
if ( ($custom_fields_enabled > 0) and ($event_code_type=='LIST') )
	{
	$valid_custom_table=0;
	if ($list_id=='ALL-LISTS')
		{
		$stmtA = "SELECT list_id from vicidial_lists;";
		$rslt=mysql_to_mysqli($stmtA, $link);
		if ($DB) {echo "$stmtA\n";}
		$lists_ct = mysqli_num_rows($rslt);
		$u=0;
		while ($lists_ct > $u)
			{
			$row=mysqli_fetch_row($rslt);
			$custom_list_id[$u] =	$row[0];
			$u++;
			}
		$u=0;
		while ($lists_ct > $u)
			{
			$stmt="SHOW TABLES LIKE \"custom_$custom_list_id[$u]\";";
			if ($DB>0) {echo "$stmt";}
			$rslt=mysql_to_mysqli($stmt, $link);
			$tablecount_to_print = mysqli_num_rows($rslt);
			$custom_tablecount[$u] = $tablecount_to_print;
			$u++;
			}
		$u=0;
		while ($lists_ct > $u)
			{
			$custom_columns[$u]=0;
			if ($custom_tablecount[$u] > 0)
				{
				$stmtA = "describe custom_$custom_list_id[$u];";
				$rslt=mysql_to_mysqli($stmtA, $link);
				if ($DB) {echo "$stmtA\n";}
				$columns_ct = mysqli_num_rows($rslt);
				$custom_columns[$u] = $columns_ct;
				}
			if ($DB) {echo "$custom_list_id[$u]|$custom_tablecount[$u]|$custom_columns[$u]\n";}
			$u++;
			}
		$valid_custom_table=1;
		}
	else
		{
		$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$tablecount_to_print = mysqli_num_rows($rslt);
		if ($tablecount_to_print > 0) 
			{
			$stmtA = "describe custom_$list_id;";
			$rslt=mysql_to_mysqli($stmtA, $link);
			if ($DB) {echo "$stmtA\n";}
			$columns_ct = mysqli_num_rows($rslt);
			$u=0;
			while ($columns_ct > $u)
				{
				$row=mysqli_fetch_row($rslt);
				$column =	$row[0];
				if ($u > 0)
					{$header_columns .= ",$column";}
				$u++;
				}
			if ($columns_ct > 1)
				{
				$valid_custom_table=1;
				}
			}
		}
	if ($valid_custom_table > 0)
		{
		$i=0;
		while ($i < $leads_to_print)
			{
			if ($list_id=='ALL-LISTS')
				{
				$valid_custom_table=0;
				$u=0;
				while ($lists_ct > $u)
					{
					if ( ($export_list_id[$i] == "$custom_list_id[$u]") and ($custom_columns[$u] > 1) )
						{
						$valid_custom_table=1;
						$columns_ct = $custom_columns[$u];
						}
					$u++;
					}
				}
			if ($valid_custom_table > 0)
				{
				$column_list='';
				$encrypt_list='';
				$hide_list='';
				$stmt = "DESCRIBE custom_$export_list_id[$i];";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($DB) {echo "$stmt\n";}
				$columns_ct = mysqli_num_rows($rslt);
				$u=0;
				while ($columns_ct > $u)
					{
					$row=mysqli_fetch_row($rslt);
					$column =	$row[0];
					$column_list .= "$row[0],";
					$u++;
					}
				if ($columns_ct > 1)
					{
					$column_list = preg_replace("/lead_id,/",'',$column_list);
					$column_list = preg_replace("/,$/",'',$column_list);
					$column_list_array = explode(',',$column_list);
					if (preg_match("/cf_encrypt/",$active_modules))
						{
						$enc_fields=0;
						$stmt = "SELECT count(*) from vicidial_lists_fields where field_encrypt='Y' and list_id='$export_list_id[$i]';";
						$rslt=mysql_to_mysqli($stmt, $link);
						if ($DB) {echo "$stmt\n";}
						$enc_field_ct = mysqli_num_rows($rslt);
						if ($enc_field_ct > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$enc_fields =	$row[0];
							}
						if ($enc_fields > 0)
							{
							$stmt = "SELECT field_label from vicidial_lists_fields where field_encrypt='Y' and list_id='$export_list_id[$i]';";
							$rslt=mysql_to_mysqli($stmt, $link);
							if ($DB) {echo "$stmt\n";}
							$enc_field_ct = mysqli_num_rows($rslt);
							$r=0;
							while ($enc_field_ct > $r)
								{
								$row=mysqli_fetch_row($rslt);
								$encrypt_list .= "$row[0],";
								$r++;
								}
							$encrypt_list = ",$encrypt_list";
							}
						if ($LOGadmin_cf_show_hidden < 1)
							{
							$hide_fields=0;
							$stmt = "SELECT count(*) from vicidial_lists_fields where field_show_hide!='DISABLED' and list_id='$export_list_id[$i]';";
							$rslt=mysql_to_mysqli($stmt, $link);
							if ($DB) {echo "$stmt\n";}
							$hide_field_ct = mysqli_num_rows($rslt);
							if ($hide_field_ct > 0)
								{
								$row=mysqli_fetch_row($rslt);
								$hide_fields =	$row[0];
								}
							if ($hide_fields > 0)
								{
								$stmt = "SELECT field_label from vicidial_lists_fields where field_show_hide!='DISABLED' and list_id='$export_list_id[$i]';";
								$rslt=mysql_to_mysqli($stmt, $link);
								if ($DB) {echo "$stmt\n";}
								$hide_field_ct = mysqli_num_rows($rslt);
								$r=0;
								while ($hide_field_ct > $r)
									{
									$row=mysqli_fetch_row($rslt);
									$hide_list .= "$row[0],";
									$r++;
									}
								$hide_list = ",$hide_list";
								}
							}
						}
					$stmt = "SELECT $column_list from custom_$export_list_id[$i] where lead_id='$export_lead_id[$i]' limit 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($DB) {echo "$stmt\n";}
					$customfield_ct = mysqli_num_rows($rslt);
					if ($customfield_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$t=0;
						while ($columns_ct >= $t) 
							{
							if ($enc_fields > 0)
								{
								$field_enc='';   $field_enc_all='';
								if ($DB) {echo "|$column_list|$encrypt_list|\n";}
								if ( (preg_match("/,$column_list_array[$t],/",$encrypt_list)) and (strlen($row[$t]) > 0) )
									{
									exec("../agc/aes.pl --decrypt --text=$row[$t]", $field_enc);
									$field_enc_ct = count($field_enc);
									$k=0;
									while ($field_enc_ct > $k)
										{
										$field_enc_all .= $field_enc[$k];
										$k++;
										}
									$field_enc_all = preg_replace("/CRYPT: |\n|\r|\t/",'',$field_enc_all);
									$row[$t] = base64_decode($field_enc_all);
									}
								}
							if ( (preg_match("/,$column_list_array[$t],/",$hide_list)) and (strlen($row[$t]) > 0) )
								{
								$field_temp_val = $row[$t];
								$row[$t] = preg_replace("/./",'X',$field_temp_val);
								}
							$custom_data[$i] .= ",$row[$t]";
							if ($ch <= $t)
								{
								$ch++;
								$header_columns .= ",custom$ch";
								}
							$t++;
							}
						}
					}
				$custom_data[$i] = preg_replace("/\r\n/",'!N',$custom_data[$i]);
				$custom_data[$i] = preg_replace("/\n/",'!N',$custom_data[$i]);
				}
			$i++;
			}
		}
	}



echo "$header_row$header_columns\r\n";

$i=0;
while ($i < $leads_to_print)
	{
	echo "$row_data[$i]$custom_data[$i]\r\n";

	$i++;
	}

if ($db_source == 'S')
	{
	mysqli_close($link);
	$use_slave_server=0;
	$db_source = 'M';
	require("dbconnect_mysqli.php");
	}

$endMS = microtime();
$startMSary = explode(" ",$startMS);
$endMSary = explode(" ",$endMS);
$runS = ($endMSary[0] - $startMSary[0]);
$runM = ($endMSary[1] - $startMSary[1]);
$TOTALrun = ($runS + $runM);

$stmt="UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$report_log_id';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);

### LOG INSERTION Admin Log Table ###
$SQL_log = "$stmt|$stmtA|";
$SQL_log = preg_replace('/;/', '', $SQL_log);
$SQL_log = addslashes($SQL_log);
$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LEADS', event_type='EXPORT', record_id='$list_id', event_code='ADMIN EXPORT $event_code_type', event_sql=\"$SQL_log\", event_notes='';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);

exit;

?>
