<?php
# lead_report_export.php
# 
# displays options to select for downloading of leads and their latest 
# vicidial_log and/or vicidial_closer_log information by status, list_id and 
# date range. downloads to a flat text file that is tab delimited
#
# Copyright (C) 2015  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 110624-0834 - First build, based upon calls_report_export.php
# 111104-1245 - Added user_group restrictions for selecting in-groups
# 130414-0135 - Added report logging
# 130610-0945 - Finalized changing of all ereg instances to preg
# 130619-2307 - Added filtering of input to prevent SQL injection attacks and new user auth
# 130901-1928 - Changed to mysqli PHP functions
# 131023-1959 - Fixed bug in 'NONE' conditional check
# 140108-0710 - Added webserver and hostname to report logging
# 141114-0039 - Finalized adding QXZ translation to all admin files
# 141230-0951 - Added code for on-the-fly language translations display
# 150108-1705 - Fixed issue with inbound and no outbound calls export
# 150727-2145 - Enabled user features for hiding phone numbers and lead data
# 150903-1539 - Added compatibility for custom fields data options
# 150909-0749 - Fixed issues with translated select list values, issue #885
# 
$startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");


$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))		{$end_date=$_POST["end_date"];}
if (isset($_GET["campaign"]))				{$campaign=$_GET["campaign"];}
	elseif (isset($_POST["campaign"]))		{$campaign=$_POST["campaign"];}
if (isset($_GET["group"]))					{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))			{$group=$_POST["group"];}
if (isset($_GET["user_group"]))				{$user_group=$_GET["user_group"];}
	elseif (isset($_POST["user_group"]))	{$user_group=$_POST["user_group"];}
if (isset($_GET["list_id"]))				{$list_id=$_GET["list_id"];}
	elseif (isset($_POST["list_id"]))		{$list_id=$_POST["list_id"];}
if (isset($_GET["status"]))					{$status=$_GET["status"];}
	elseif (isset($_POST["status"]))		{$status=$_POST["status"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["run_export"]))				{$run_export=$_GET["run_export"];}
	elseif (isset($_POST["run_export"]))	{$run_export=$_POST["run_export"];}
if (isset($_GET["header_row"]))				{$header_row=$_GET["header_row"];}
	elseif (isset($_POST["header_row"]))	{$header_row=$_POST["header_row"];}
if (isset($_GET["rec_fields"]))				{$rec_fields=$_GET["rec_fields"];}
	elseif (isset($_POST["rec_fields"]))	{$rec_fields=$_POST["rec_fields"];}
if (isset($_GET["custom_fields"]))			{$custom_fields=$_GET["custom_fields"];}
	elseif (isset($_POST["custom_fields"]))	{$custom_fields=$_POST["custom_fields"];}
if (isset($_GET["call_notes"]))				{$call_notes=$_GET["call_notes"];}
	elseif (isset($_POST["call_notes"]))	{$call_notes=$_POST["call_notes"];}
if (isset($_GET["export_fields"]))			{$export_fields=$_GET["export_fields"];}
	elseif (isset($_POST["export_fields"]))	{$export_fields=$_POST["export_fields"];}
if (isset($_GET["submit"]))					{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}




if (strlen($shift)<2) {$shift='ALL';}

$report_name = 'Export Leads Report';
$db_source = 'M';
$file_exported=0;

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
$reports_auth=0;
$admin_auth=0;
$auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'REPORTS',1);
if ($auth_message == 'GOOD')
	{$auth=1;}

if ($auth > 0)
	{
	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and view_reports='1';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$row=mysqli_fetch_row($rslt);
	$admin_auth=$row[0];

	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 6 and view_reports='1';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$row=mysqli_fetch_row($rslt);
	$reports_auth=$row[0];

	if ($reports_auth < 1)
		{
		$VDdisplayMESSAGE = _QXZ("You are not allowed to view reports");
		Header ("Content-type: text/html; charset=utf-8");
		echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
		exit;
		}
	if ( ($reports_auth > 0) and ($admin_auth < 1) )
		{
		$ADD=999999;
		$reports_only_user=1;
		}
	}
else
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

$stmt="SELECT export_reports,user_group,admin_hide_lead_data,admin_hide_phone_data,admin_cf_show_hidden from vicidial_users where user='$PHP_AUTH_USER';";
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGexport_reports =			$row[0];
$LOGuser_group =				$row[1];
$LOGadmin_hide_lead_data =		$row[2];
$LOGadmin_hide_phone_data =		$row[3];
$LOGadmin_cf_show_hidden =		$row[4];

if ($LOGexport_reports < 1)
	{
	Header ("Content-type: text/html; charset=utf-8");
	echo _QXZ("You do not have permissions for export reports").": |$PHP_AUTH_USER|\n";
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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$campaign[0], $query_date, $end_date|', url='$LOGfull_url', webserver='$webserver_id';";
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

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
	{
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
    exit;
	}

$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if ( (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) )
	{
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}
$regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i',$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}

$LOGadmin_viewable_call_timesSQL='';
$whereLOGadmin_viewable_call_timesSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i', $LOGadmin_viewable_call_times)) and (strlen($LOGadmin_viewable_call_times) > 3) )
	{
	$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ -/",'',$LOGadmin_viewable_call_times);
	$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_call_timesSQL);
	$LOGadmin_viewable_call_timesSQL = "and call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
	$whereLOGadmin_viewable_call_timesSQL = "where call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
	}

##### START RUN THE EXPORT AND OUTPUT FLAT DATA FILE #####
if ($run_export > 0)
	{
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

	$campaign_ct = count($campaign);
	$group_ct = count($group);
	$user_group_ct = count($user_group);
	$list_ct = count($list_id);
	$status_ct = count($status);
	$campaign_string='|';
	$group_string='|';
	$user_group_string='|';
	$list_string='|';
	$status_string='|';

	$i=0;
	while($i < $campaign_ct)
		{
		if ( (preg_match("/ $campaign[$i] /",$regexLOGallowed_campaigns)) or (preg_match("/-ALL/",$LOGallowed_campaigns)) )
			{
			$campaign_string .= "$campaign[$i]|";
			$campaign_SQL .= "'$campaign[$i]',";
			}
		$i++;
		}
	if ( (preg_match('/\-\-NONE\-\-/',$campaign_string) ) or ($campaign_ct < 1) )
		{
		$campaign_SQL = "campaign_id IN('')";
		$RUNcampaign=0;
		}
	else
		{
		$campaign_SQL = preg_replace('/,$/i', '',$campaign_SQL);
		$campaign_SQL = "and vl.campaign_id IN($campaign_SQL)";
		$RUNcampaign++;
		}

	$i=0;
	while($i < $group_ct)
		{
		$group_string .= "$group[$i]|";
		$group_SQL .= "'$group[$i]',";
		$i++;
		}
	if ( (preg_match('/\-\-NONE\-\-/',$group_string) ) or ($group_ct < 1) )
		{
		$group_SQL = "''";
		$group_SQL = "campaign_id IN('')";
		$RUNgroup=0;
		}
	else
		{
		$group_SQL = preg_replace('/,$/i', '',$group_SQL);
		$group_SQL = "and vl.campaign_id IN($group_SQL)";
		$RUNgroup++;
		}

	$i=0;
	while($i < $user_group_ct)
		{
		$user_group_string .= "$user_group[$i]|";
		$user_group_SQL .= "'$user_group[$i]',";
		$i++;
		}
		if ( (preg_match('/\-\-ALL\-\-/',$user_group_string) ) or ($user_group_ct < 1) )
		{
		$user_group_SQL = "";
		}
	else
		{
		$user_group_SQL = preg_replace('/,$/i', '',$user_group_SQL);
		$user_group_SQL = "and vl.user_group IN($user_group_SQL)";
		}

	$i=0;
	while($i < $list_ct)
		{
		$list_string .= "$list_id[$i]|";
		$list_SQL .= "'$list_id[$i]',";
		$i++;
		}
	if ( (preg_match('/\-\-ALL\-\-/',$list_string) ) or ($list_ct < 1) )
		{
		$list_SQL = "";
		}
	else
		{
		$list_SQL = preg_replace('/,$/i', '',$list_SQL);
		$list_SQL = "and vi.list_id IN($list_SQL)";
		}

	$i=0;
	while($i < $status_ct)
		{
		$status_string .= "$status[$i]|";
		$status_SQL .= "'$status[$i]',";
		$i++;
		}
	if ( (preg_match('/\-\-ALL\-\-/',$status_string) ) or ($status_ct < 1) )
		{
		$status_SQL = "";
		}
	else
		{
		$status_SQL = preg_replace('/,$/i', '',$status_SQL);
		$status_SQL = "and vi.status IN($status_SQL)";
		}

	$export_fields_SQL='';
	$EFheader='';
	if ($export_fields == 'EXTENDED')
		{
		$export_fields_SQL = ",entry_date,vi.called_count,last_local_call_time,modify_date,called_since_last_reset";
		$EFheader = ",entry_date,called_count,last_local_call_time,modify_date,called_since_last_reset";
		}


	if ($DB > 0)
		{
		echo "<BR>\n";
		echo "$campaign_ct|$campaign_string|$campaign_SQL\n";
		echo "<BR>\n";
		echo "$group_ct|$group_string|$group_SQL\n";
		echo "<BR>\n";
		echo "$user_group_ct|$user_group_string|$user_group_SQL\n";
		echo "<BR>\n";
		echo "$list_ct|$list_string|$list_SQL\n";
		echo "<BR>\n";
		echo "$status_ct|$status_string|$status_SQL\n";
		echo "<BR>\n";
		}

$outbound_calls=0;
	$export_rows='';
	$k=0;
	if ($RUNcampaign > 0)
		{
			
	// ###############################code for current month ######################################################		 
			 $stmt_out = "SELECT vl.uniqueid,vi.entry_list_id,UNIX_TIMESTAMP(vl.call_date)$export_fields_SQL,vl.call_date,vl.lead_id,vl.list_id,vl.phone_number,vl.length_in_sec,vl.user,vl.user_group,vl.campaign_id,vl.status,vl.term_reason,vl.alt_dial,vi.title,vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.vendor_lead_code,vi.phone_number,vi.phone_code,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vi.field1,vi.field2,vi.field3,vi.field4,vi.field5,vi.field6,vi.field7,vi.field8,vi.field9,vi.field10,vi.field11,vi.field12,vi.field13,vi.field14,vi.field15,vi.field16,vi.field17,vi.field18,vi.field19,vi.field20,vi.field21,vi.field22,vi.field23,vi.field24,vi.field25,vi.field26,vi.field27,vi.field28,vi.field29,vi.field30,vi.field31,vi.field32,vi.field33,vi.field34,vi.field35,vi.field36,vi.field37,vi.field38,vi.field39,vi.field40 from vicidial_log vl,vicidial_list vi where vl.call_date >= '$query_date 00:00:00' and vl.call_date <= '$end_date 23:59:59' and vi.lead_id=vl.lead_id $list_SQL $campaign_SQL $user_group_SQL $status_SQL order by vl.call_date desc limit 500000;";
			
		//echo $stmt_out;exit;
                $rslt_out=mysql_to_mysqli($stmt_out, $link);
		if ($DB) {echo "$stmt\n";}
		$outbound_to_print = mysqli_num_rows($rslt_out);
		if ( ($outbound_to_print < 1) and ($RUNgroup < 1) )
			{
			echo _QXZ("There are no outbound calls during this time period for these parameters")."\n";
			exit;
			}
		else
			{
			$i=0;
			while ($i < $outbound_to_print)
				{
				$row_out_data=mysqli_fetch_row($rslt_out);
 
				
				$export_status[$k] =		$row_out_data[11];
				$export_list_id[$k] =		$row_out_data[5];
				$export_lead_id[$k] =		$row_out_data[4];
				$export_uniqueid[$k] =		$row_out_data[0];
				$export_vicidial_id[$k] =	$row_out_data[0];
				$export_entry_list_id[$k] =	$row_out_data[1];
				$export_epoch_time[$k] =	$row_out_data[2];
				$export_unique[$k] =		$row_out_data[0];
				$export_duplicate_check_line[$k] = "$export_lead_id[$k]---$export_epoch_time[$k]---$k";


$out_title = ($row_out_data[14]=="---HIDE---")?"":(",$row_out_data[14]");
$out_first_name = ($row_out_data[15]=="---HIDE---")?"":(",$row_out_data[15]");
$out_middle_initial = ($row_out_data[16]=="---HIDE---")?"":(",$row_out_data[16]");
$out_last_name = ($row_out_data[17]=="---HIDE---")?"":(",$row_out_data[17]");
$out_address1 = ($row_out_data[18]=="---HIDE---")?"":(",$row_out_data[18]");
$out_address2 = ($row_out_data[19]=="---HIDE---")?"":(",$row_out_data[19]");
$out_address3 = ($row_out_data[20]=="---HIDE---")?"":(",$row_out_data[20]");
$out_city = ($row_out_data[21]=="---HIDE---")?"":(",$row_out_data[21]");
$out_state = ($row_out_data[22]=="---HIDE---")?"":(",$row_out_data[22]");
$out_province = ($row_out_data[23]=="---HIDE---")?"":(",$row_out_data[23]");
$out_postal_code = ($row_out_data[24]=="---HIDE---")?"":(",$row_out_data[24]");
$out_vendor_lead_code = ($row_out_data[25]=="---HIDE---")?"":(",$row_out_data[25]");
$out_phone_number = ($row_out_data[26]=="---HIDE---")?"":(",$row_out_data[26]");
$out_phone_code = ($row_out_data[27]=="---HIDE---")?"":(",$row_out_data[27]");
$out_alt_phone = ($row_out_data[28]=="---HIDE---")?"":(",$row_out_data[28]");
$out_email = ($row_out_data[29]=="---HIDE---")?"":(",$row_out_data[29]");
$out_security_phrase = ($row_out_data[30]=="---HIDE---")?"":(",$row_out_data[30]");
$out_comments = ($row_out_data[31]=="---HIDE---")?"":(",$row_out_data[31]");
$out_field1 = ($row_out_data[32]=="---HIDE---")?"":(",$row_out_data[32]");
$out_field2 = ($row_out_data[33]=="---HIDE---")?"":(",$row_out_data[33]");
$out_field3 = ($row_out_data[34]=="---HIDE---")?"":(",$row_out_data[34]");
$out_field4 = ($row_out_data[35]=="---HIDE---")?"":(",$row_out_data[35]");
$out_field5 = ($row_out_data[36]=="---HIDE---")?"":(",$row_out_data[36]");
$out_field6 = ($row_out_data[37]=="---HIDE---")?"":(",$row_out_data[37]");
$out_field7 = ($row_out_data[38]=="---HIDE---")?"":(",$row_out_data[38]");
$out_field8 = ($row_out_data[39]=="---HIDE---")?"":(",$row_out_data[39]");
$out_field9 = ($row_out_data[40]=="---HIDE---")?"":(",$row_out_data[40]");
$out_field10 = ($row_out_data[41]=="---HIDE---")?"":(",$row_out_data[41]");
$out_field11 = ($row_out_data[42]=="---HIDE---")?"":(",$row_out_data[42]");
$out_field12 = ($row_out_data[43]=="---HIDE---")?"":(",$row_out_data[43]");
$out_field13 = ($row_out_data[44]=="---HIDE---")?"":(",$row_out_data[44]");
$out_field14 = ($row_out_data[45]=="---HIDE---")?"":(",$row_out_data[45]");
$out_field15 = ($row_out_data[46]=="---HIDE---")?"":(",$row_out_data[46]");
$out_field16 = ($row_out_data[47]=="---HIDE---")?"":(",$row_out_data[47]");
$out_field17 = ($row_out_data[48]=="---HIDE---")?"":(",$row_out_data[48]");
$out_field18 = ($row_out_data[49]=="---HIDE---")?"":(",$row_out_data[49]");
$out_field19 = ($row_out_data[50]=="---HIDE---")?"":(",$row_out_data[50]");
$out_field20 = ($row_out_data[51]=="---HIDE---")?"":(",$row_out_data[51]");
$out_field21 = ($row_out_data[52]=="---HIDE---")?"":(",$row_out_data[52]");
$out_field22 = ($row_out_data[53]=="---HIDE---")?"":(",$row_out_data[53]");
$out_field23 = ($row_out_data[54]=="---HIDE---")?"":(",$row_out_data[54]");
$out_field24 = ($row_out_data[55]=="---HIDE---")?"":(",$row_out_data[55]");
$out_field25 = ($row_out_data[56]=="---HIDE---")?"":(",$row_out_data[56]");
$out_field26 = ($row_out_data[57]=="---HIDE---")?"":(",$row_out_data[57]");
$out_field27 = ($row_out_data[58]=="---HIDE---")?"":(",$row_out_data[58]");
$out_field28 = ($row_out_data[59]=="---HIDE---")?"":(",$row_out_data[59]");
$out_field29 = ($row_out_data[60]=="---HIDE---")?"":(",$row_out_data[60]");
$out_field30 = ($row_out_data[61]=="---HIDE---")?"":(",$row_out_data[61]");
$out_field31 = ($row_out_data[62]=="---HIDE---")?"":(",$row_out_data[62]");
$out_field32 = ($row_out_data[63]=="---HIDE---")?"":(",$row_out_data[63]");
$out_field33 = ($row_out_data[64]=="---HIDE---")?"":(",$row_out_data[64]");
$out_field34 = ($row_out_data[65]=="---HIDE---")?"":(",$row_out_data[65]");
$out_field35 = ($row_out_data[66]=="---HIDE---")?"":(",$row_out_data[66]");
$out_field36 = ($row_out_data[67]=="---HIDE---")?"":(",$row_out_data[67]");
$out_field37 = ($row_out_data[68]=="---HIDE---")?"":(",$row_out_data[68]");
$out_field38 = ($row_out_data[69]=="---HIDE---")?"":(",$row_out_data[69]");
$out_field39 = ($row_out_data[70]=="---HIDE---")?"":(",$row_out_data[70]");
$out_field40 = ($row_out_data[71]=="---HIDE---")?"":(",$row_out_data[71]");

   
		
                $call_back_date='';
				$stmtC = "SELECT callback_id,callback_time from vicidial_callbacks where lead_id='$row_out_data[4]' and lead_status='CB'";
				
				$rsltC=mysql_to_mysqli($stmtC, $link);
				if ($DB) {echo "$stmtC\n";}
				$callback_ct = mysqli_num_rows($rsltC);
				$z=0;
				while ($callback_ct > $z)
					{
					$rowC=mysqli_fetch_row($rsltC);
					$call_back_date .= "$rowC[1]";
					
					$z++;
					}

				if (($row_in_data[12] == "AGENT") || ($row_in_data[12] == "CALLER")){
				$call_status_agent = "Connected";
			}else{
				$call_status_agent = "Not Connected";
			}	
				
			
				
 $export_rows.= "$row_out_data[3],$row_out_data[4],$row_out_data[5],$row_out_data[6],$row_out_data[7],$row_out_data[8],$row_out_data[9],$row_out_data[10],$row_out_data[11],$row_out_data[12],$row_out_data[13],$out_comments$out_title$out_first_name$out_middle_initial$out_last_name$out_address1$out_address2$out_address3$out_city$out_state$out_province$out_postal_code$out_vendor_lead_code$out_phone_number$out_alt_phone$out_email$out_security_phrase$out_field1$out_field2$out_field3$out_field4$out_field5$out_field6$out_field7$out_field8$out_field9$out_field10$out_field11$out_field12$out_field13$out_field14$out_field15$out_field16$out_field17$out_field18$out_field19$out_field20$out_field21$out_field22$out_field23$out_field24$out_field25$out_field26$out_field27$out_field28$out_field29$out_field30$out_field31$out_field32$out_field33$out_field34$out_field35$out_field36$out_field37$out_field38$out_field39$out_field40,$call_back_date,$call_status_agent***";
 
 //$export_rows.= "$row_out_data[3],$row_out_data[4],$row_out_data[5],$row_out_data[6],$row_out_data[7],$row_out_data[8],$row_out_data[9],$row_out_data[10],$row_out_data[11],$row_out_data[12],$row_out_data[13],$export_fieldsDATA***";



				$i++;
				$k++;
				$outbound_calls++;
				
		}
			}
		}
		
		$export_rows = explode("***",$export_rows);
	
	if ($RUNgroup > 0)
		{
			
	// ###############################code for current month ######################################################		 
			 $stmt_in = "SELECT vl.closecallid,vi.entry_list_id,UNIX_TIMESTAMP(vl.call_date)$export_fields_SQL,vl.call_date,vl.lead_id,vl.list_id,vl.phone_number,vl.length_in_sec,vl.user,vl.user_group,vl.campaign_id,vl.status,vl.term_reason,vl.queue_seconds,vi.title,vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.vendor_lead_code,vi.phone_number,vi.phone_code,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vi.field1,vi.field2,vi.field3,vi.field4,vi.field5,vi.field6,vi.field7,vi.field8,vi.field9,vi.field10,vi.field11,vi.field12,vi.field13,vi.field14,vi.field15,vi.field16,vi.field17,vi.field18,vi.field19,vi.field20,vi.field21,vi.field22,vi.field23,vi.field24,vi.field25,vi.field26,vi.field27,vi.field28,vi.field29,vi.field30,vi.field31,vi.field32,vi.field33,vi.field34,vi.field35,vi.field36,vi.field37,vi.field38,vi.field39,vi.field40,vl.uniqueid from vicidial_closer_log vl,vicidial_list vi where vl.call_date >= '$query_date 00:00:00' and vl.call_date <= '$end_date 23:59:59' and vi.lead_id=vl.lead_id $list_SQL $group_SQL $user_group_SQL $status_SQL order by vl.call_date desc limit 500000;";
			
		//echo $stmt_in;exit;
		
                $rslt_in=mysql_to_mysqli($stmt_in, $link);
		if ($DB) {echo "$stmt_in\n";}
		$inbound_to_print = mysqli_num_rows($rslt_in);
		if ( ($inbound_to_print < 1) and ($outbound_calls < 1) )
			{
			echo _QXZ("There are no inbound calls during this time period for these parameters")."\n";
			exit;
			}
		else
			{
			$i=0;
			while ($i < $inbound_to_print)
				{
				$row_in_data=mysqli_fetch_row($rslt_in);
 
				
				$export_status[$k] =		$row_in_data[11];
				$export_list_id[$k] =		$row_in_data[5];
				$export_lead_id[$k] =		$row_in_data[4];
				$export_uniqueid[$k] =		$row_in_data[0];
				$export_vicidial_id[$k] =	$row_in_data[0];
				$export_entry_list_id[$k] =	$row_in_data[1];
				$export_epoch_time[$k] =	$row_in_data[2];
				$export_unique[$k] =		$row_in_data[72];
				$export_duplicate_check_line[$k] = "$export_lead_id[$k]---$export_epoch_time[$k]---$k";


$in_title = ($row_in_data[14]=="---HIDE---")?"":(",$row_in_data[14]");
$in_first_name = ($row_in_data[15]=="---HIDE---")?"":(",$row_in_data[15]");
$in_middle_initial = ($row_in_data[16]=="---HIDE---")?"":(",$row_in_data[16]");
$in_last_name = ($row_in_data[17]=="---HIDE---")?"":(",$row_in_data[17]");
$in_address1 = ($row_in_data[18]=="---HIDE---")?"":(",$row_in_data[18]");
$in_address2 = ($row_in_data[19]=="---HIDE---")?"":(",$row_in_data[19]");
$in_address3 = ($row_in_data[20]=="---HIDE---")?"":(",$row_in_data[20]");
$in_city = ($row_in_data[21]=="---HIDE---")?"":(",$row_in_data[21]");
$in_state = ($row_in_data[22]=="---HIDE---")?"":(",$row_in_data[22]");
$in_province = ($row_in_data[23]=="---HIDE---")?"":(",$row_in_data[23]");
$in_postal_code = ($row_in_data[24]=="---HIDE---")?"":(",$row_in_data[24]");
$in_vendor_lead_code = ($row_in_data[25]=="---HIDE---")?"":(",$row_in_data[25]");
$in_phone_number = ($row_in_data[26]=="---HIDE---")?"":(",$row_in_data[26]");
$in_phone_code = ($row_in_data[27]=="---HIDE---")?"":(",$row_in_data[27]");
$in_alt_phone = ($row_in_data[28]=="---HIDE---")?"":(",$row_in_data[28]");
$in_email = ($row_in_data[29]=="---HIDE---")?"":(",$row_in_data[29]");
$in_security_phrase = ($row_in_data[30]=="---HIDE---")?"":(",$row_in_data[30]");
$in_comments = ($row_in_data[31]=="---HIDE---")?"":(",$row_in_data[31]");
$in_field1 = ($row_in_data[32]=="---HIDE---")?"":(",$row_in_data[32]");
$in_field2 = ($row_in_data[33]=="---HIDE---")?"":(",$row_in_data[33]");
$in_field3 = ($row_in_data[34]=="---HIDE---")?"":(",$row_in_data[34]");
$in_field4 = ($row_in_data[35]=="---HIDE---")?"":(",$row_in_data[35]");
$in_field5 = ($row_in_data[36]=="---HIDE---")?"":(",$row_in_data[36]");
$in_field6 = ($row_in_data[37]=="---HIDE---")?"":(",$row_in_data[37]");
$in_field7 = ($row_in_data[38]=="---HIDE---")?"":(",$row_in_data[38]");
$in_field8 = ($row_in_data[39]=="---HIDE---")?"":(",$row_in_data[39]");
$in_field9 = ($row_in_data[40]=="---HIDE---")?"":(",$row_in_data[40]");
$in_field10 = ($row_in_data[41]=="---HIDE---")?"":(",$row_in_data[41]");
$in_field11 = ($row_in_data[42]=="---HIDE---")?"":(",$row_in_data[42]");
$in_field12 = ($row_in_data[43]=="---HIDE---")?"":(",$row_in_data[43]");
$in_field13 = ($row_in_data[44]=="---HIDE---")?"":(",$row_in_data[44]");
$in_field14 = ($row_in_data[45]=="---HIDE---")?"":(",$row_in_data[45]");
$in_field15 = ($row_in_data[46]=="---HIDE---")?"":(",$row_in_data[46]");
$in_field16 = ($row_in_data[47]=="---HIDE---")?"":(",$row_in_data[47]");
$in_field17 = ($row_in_data[48]=="---HIDE---")?"":(",$row_in_data[48]");
$in_field18 = ($row_in_data[49]=="---HIDE---")?"":(",$row_in_data[49]");
$in_field19 = ($row_in_data[50]=="---HIDE---")?"":(",$row_in_data[50]");
$in_field20 = ($row_in_data[51]=="---HIDE---")?"":(",$row_in_data[51]");
$in_field21 = ($row_in_data[52]=="---HIDE---")?"":(",$row_in_data[52]");
$in_field22 = ($row_in_data[53]=="---HIDE---")?"":(",$row_in_data[53]");
$in_field23 = ($row_in_data[54]=="---HIDE---")?"":(",$row_in_data[54]");
$in_field24 = ($row_in_data[55]=="---HIDE---")?"":(",$row_in_data[55]");
$in_field25 = ($row_in_data[56]=="---HIDE---")?"":(",$row_in_data[56]");
$in_field26 = ($row_in_data[57]=="---HIDE---")?"":(",$row_in_data[57]");
$in_field27 = ($row_in_data[58]=="---HIDE---")?"":(",$row_in_data[58]");
$in_field28 = ($row_in_data[59]=="---HIDE---")?"":(",$row_in_data[59]");
$in_field29 = ($row_in_data[60]=="---HIDE---")?"":(",$row_in_data[60]");
$in_field30 = ($row_in_data[61]=="---HIDE---")?"":(",$row_in_data[61]");
$in_field31 = ($row_in_data[62]=="---HIDE---")?"":(",$row_in_data[62]");
$in_field32 = ($row_in_data[63]=="---HIDE---")?"":(",$row_in_data[63]");
$in_field33 = ($row_in_data[64]=="---HIDE---")?"":(",$row_in_data[64]");
$in_field34 = ($row_in_data[65]=="---HIDE---")?"":(",$row_in_data[65]");
$in_field35 = ($row_in_data[66]=="---HIDE---")?"":(",$row_in_data[66]");
$in_field36 = ($row_in_data[67]=="---HIDE---")?"":(",$row_in_data[67]");
$in_field37 = ($row_in_data[68]=="---HIDE---")?"":(",$row_in_data[68]");
$in_field38 = ($row_in_data[69]=="---HIDE---")?"":(",$row_in_data[69]");
$in_field39 = ($row_in_data[70]=="---HIDE---")?"":(",$row_in_data[70]");
$in_field40 = ($row_in_data[71]=="---HIDE---")?"":(",$row_in_data[71]");

   
		
                $call_back_date='';
				$stmtC = "SELECT callback_id,callback_time from vicidial_callbacks where lead_id='$row_in_data[4]' and lead_status='CB'";
				
				$rsltC=mysql_to_mysqli($stmtC, $link);
				if ($DB) {echo "$stmtC\n";}
				$callback_ct = mysqli_num_rows($rsltC);
				$z=0;
				while ($callback_ct > $z)
					{
					$rowC=mysqli_fetch_row($rsltC);
					$call_back_date .= "$rowC[1]";
					
					$z++;
					}

				
			if (($row_in_data[12] == "AGENT") || ($row_in_data[12] == "CALLER")){
				$call_status_agent = "Connected";
			}else{
				$call_status_agent = "Not Connected";
			}				
			
				
 $export_rows[$k] = "$row_in_data[3],$row_in_data[4],$row_in_data[5],$row_in_data[6],$row_in_data[7],$row_in_data[8],$row_in_data[9],$row_in_data[10],$row_in_data[11],$row_in_data[12],INBOUND,$row_in_data[13]$in_comments$in_title$in_first_name$in_middle_initial$in_last_name$in_address1$in_address2$in_address3$in_city$in_state$in_province$in_postal_code$in_vendor_lead_code$in_phone_number$in_alt_phone$in_email$in_security_phrase$in_field1$in_field2$in_field3$in_field4$in_field5$in_field6$in_field7$in_field8$in_field9$in_field10$in_field11$in_field12$in_field13$in_field14$in_field15$in_field16$in_field17$in_field18$in_field19$in_field20$in_field21$in_field22$in_field23$in_field24$in_field25$in_field26$in_field27$in_field28$in_field29$in_field30$in_field31$in_field32$in_field33$in_field34$in_field35$in_field36$in_field37$in_field38$in_field39$in_field40,$call_back_date,$call_status_agent";
 
 //$export_rows.= "$row_in_data[3],$row_in_data[4],$row_in_data[5],$row_in_data[6],$row_in_data[7],$row_in_data[8],$row_in_data[9],$row_in_data[10],$row_in_data[11],$row_in_data[12],$row_in_data[13],$export_fieldsDATA***";



				$i++;
				$k++;
				
		}
			}
		}
		
		



	if (($outbound_to_print > 0) or ($inbound_to_print > 0) )
		{
          /*  $date = date('Y-m-d');
            $TXTfilename = "LeadReport-".$date.".csv";
			$fd = fopen ($TXTfilename, "w");*/

		$TXTfilename = "COMBAINED_CALL_REPORT_$FILE_TIME.csv";

		// We'll be outputting a TXT file
		header('Content-type: text/csv');

		// It will be called LIST_101_20090209-121212.txt
		header("Content-Disposition: attachment; filename=\"$TXTfilename\"");
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		ob_clean();
		flush();

		$i=0;
		while ($k > $i)
			{
			$custom_data='';
			$ex_list_name='';
			$ex_list_description='';
			$stmt = "SELECT list_name,list_description FROM vicidial_lists where list_id='$export_list_id[$i]';";
			$rslt=mysql_to_mysqli($stmt, $link);
			if ($DB) {echo "$stmt\n";}
			$ex_list_ct = mysqli_num_rows($rslt);
			if ($ex_list_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$ex_list_name =			$row[0];
				$ex_list_description =	$row[1];
				}

			$ex_status_name='';
			$stmt = "SELECT status_name FROM vicidial_statuses where status='$export_status[$i]';";
			$rslt=mysql_to_mysqli($stmt, $link);
			if ($DB) {echo "$stmt\n";}
			$ex_list_ct = mysqli_num_rows($rslt);
			if ($ex_list_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$ex_status_name =			$row[0];
				}
			else
				{
				$stmt = "SELECT status_name FROM vicidial_campaign_statuses where status='$export_status[$i]';";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($DB) {echo "$stmt\n";}
				$ex_list_ct = mysqli_num_rows($rslt);
				if ($ex_list_ct > 0)
					{
					$row=mysqli_fetch_row($rslt);
					$ex_status_name =			$row[0];
					}
				}

			
				
				
				$rec_id='';
				$rec_filename='';
				$rec_location='';
				$stmt = "SELECT recording_id,filename,location from recording_log where vicidial_id='$export_vicidial_id[$i]' order by recording_id desc ;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($DB) {echo "$stmt\n";}
				$recordings_ct = mysqli_num_rows($rslt);
				$u=0;
				while ($recordings_ct > $u)
					{
					$row=mysqli_fetch_row($rslt);
					$rec_id .=			"$row[0]|";
					$rec_filename .=	"$row[1]";
					$rec_location .=	"$row[2]|";

					$u++;
					}
					
					
				$wait_time='';
				$wrap_time='';
	$stmtA = "SELECT wait_sec,dispo_sec,agent_log_id from vicidial_agent_log where uniqueid ='$export_unique[$i]' order by agent_log_id desc ;";
				$rsltA=mysql_to_mysqli($stmtA, $link);
				if ($DB) {echo "$stmtA\n";}
				$sec_ct = mysqli_num_rows($rsltA);
				$a=0;
				while ($sec_ct > $a)
					{
					$rowA=mysqli_fetch_row($rsltA);
					$wait_time .= "$rowA[0]";
					$wrap_time .= "$rowA[1]";
					

					$a++;
					}
				
				
				
				$park_time='';
	$stmtP = "SELECT parked_sec,uniqueid from vicidial_agent_log where uniqueid ='$export_unique[$i]' order by uniqueid desc ;";
				$rsltP=mysql_to_mysqli($stmtP, $link);
				if ($DB) {echo "$stmtA\n";}
				$park_ct = mysqli_num_rows($rsltP);
				$b=0;
				while ($park_ct > $b)
					{
					$rowP=mysqli_fetch_row($rsltP);
					$park_time .= "$rowP[0]";
					
					

					$b++;
					}
				
				
				

			$extended_data_a='';
			$extended_data_b='';
			$extended_data_c='';
			if ($export_fields=='EXTENDED')
				{
				$extended_data = ",$export_uniqueid[$i]";
				if (strlen($export_uniqueid[$i]) > 0)
					{
					$uniqueidTEST = $export_uniqueid[$i];
					$uniqueidTEST = preg_replace('/\..*$/','',$uniqueidTEST);
					$stmt = "SELECT caller_code,server_ip from vicidial_log_extended where uniqueid LIKE \"$uniqueidTEST%\" and lead_id='$export_lead_id[$i]' LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($DB) {echo "$stmt\n";}
					$vle_ct = mysqli_num_rows($rslt);
					if ($vle_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$extended_data_a =	"\t$row[0]\t$row[1]";
						$export_call_id[$i] = $row[0];
						}

					$stmt = "SELECT hangup_cause,dialstatus,channel,dial_time,answered_time from vicidial_carrier_log where uniqueid LIKE \"$uniqueidTEST%\" and lead_id='$export_lead_id[$i]' LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($DB) {echo "$stmt\n";}
					$vcarl_ct = mysqli_num_rows($rslt);
					if ($vcarl_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$extended_data_b =	",$row[0],$row[1],$row[2],$row[3],$row[4]";
						}

					$stmt = "SELECT result from vicidial_cpd_log where callerid='$export_call_id[$i]' LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($DB) {echo "$stmt\n";}
					$vcpdl_ct = mysqli_num_rows($rslt);
					if ($vcpdl_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$extended_data_c =	"\t$row[0]";
						}

					}
				if (strlen($extended_data_a)<1)
					{$extended_data_a =	"\t\t";}
				if (strlen($extended_data_b)<1)
					{$extended_data_b =	"\t\t\t\t\t";}
				if (strlen($extended_data_c)<1)
					{$extended_data_c =	"\t";}
				$extended_data .= "$extended_data_a$extended_data_b$extended_data_c";
				}

			$notes_data='';
			if ($call_notes=='YES')
				{
				if (strlen($export_vicidial_id[$i]) > 0)
					{
					$stmt = "SELECT call_notes from vicidial_call_notes where vicidial_id='$export_vicidial_id[$i]' LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($DB) {echo "$stmt\n";}
					$notes_ct = mysqli_num_rows($rslt);
					if ($notes_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$notes_data =	$row[0];
						}
					$notes_data = preg_replace("/\r\n/",' ',$notes_data);
					$notes_data = preg_replace("/\n/",' ',$notes_data);
					}
				$notes_data =	"\t$notes_data";
				}

			if ( ($custom_fields_enabled > 0) and ($custom_fields=='YES') )
				{
				$CF_list_id = $export_list_id[$i];
				if ($export_entry_list_id[$i] > 99)
					{$CF_list_id = $export_entry_list_id[$i];}
				$stmt="SHOW TABLES LIKE \"custom_$CF_list_id\";";
				if ($DB>0) {echo "$stmt";}
				$rslt=mysql_to_mysqli($stmt, $link);
				$tablecount_to_print = mysqli_num_rows($rslt);
				if ($tablecount_to_print > 0) 
					{
					$column_list='';
					$encrypt_list='';
					$hide_list='';
					$stmt = "DESCRIBE custom_$CF_list_id;";
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
							$stmt = "SELECT count(*) from vicidial_lists_fields where field_encrypt='Y' and list_id='$CF_list_id';";
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
								$stmt = "SELECT field_label from vicidial_lists_fields where field_encrypt='Y' and list_id='$CF_list_id';";
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
								$stmt = "SELECT count(*) from vicidial_lists_fields where field_show_hide!='DISABLED' and list_id='$CF_list_id';";
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
									$stmt = "SELECT field_label from vicidial_lists_fields where field_show_hide!='DISABLED' and list_id='$CF_list_id';";
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
						$stmt = "SELECT $column_list from custom_$CF_list_id where lead_id='$export_lead_id[$i]' limit 1;";
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
									if ($DB) {echo "|$column_list|$encrypt_list|$hide_list|\n";}
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
								$custom_data .= "\t$row[$t]";
								$t++;
								}
							}
						}
					$custom_data = preg_replace("/\r\n/",'!N',$custom_data);
					$custom_data = preg_replace("/\n/",'!N',$custom_data);
					}
				}

		$RAW_EXPORT[$i] = "$export_rows[$i],$ex_status_name,$rec_filename,$ex_list_name,$ex_list_description,$wait_time,$wrap_time,$park_time\r\n";
			$i++;
			}

		if ($header_row==_QXZ("YES"))
			{
			$RFheader = '';
			$NFheader = '';
			$CFheader = '';
			$EXheader = '';
			if ($rec_fields=='ID')
				{$RFheader = ",recording_id";}
			if ($rec_fields=='FILENAME')
				{$RFheader = ",recording_filename";}
			if ($rec_fields=='LOCATION')
				{$RFheader = ",recording_location";}
			if ($rec_fields=='ALL')
				{$RFheader = ",recording_id,recording_filename,recording_location";}
			if ($export_fields=='EXTENDED')
				{$EXheader = ",uniqueid,caller_code,server_ip,hangup_cause,dialstatus,channel,dial_time,answered_time,cpd_result";}
			if ($call_notes=='YES')
				{$NFheader = ",call_notes";}
			if ( ($custom_fields_enabled > 0) and ($custom_fields=='YES') )
				{$CFheader = ",custom_fields";}
     
     
        //change the header here replace with mapped fields
		$stmtc = "SELECT screen_labels  from vicidial_campaigns where campaign_id='$campaign[0]';";
		
		$rsltc=mysql_to_mysqli($stmtc, $link);
        $rowc = mysqli_fetch_row($rsltc);
      
    $stmtl = "SELECT  label_title,label_first_name,label_middle_initial,label_last_name,label_address1,label_address2,label_address3,label_city,label_state,label_province,label_postal_code,label_vendor_lead_code,label_gender,label_phone_number,label_phone_code,label_alt_phone,label_email,label_security_phrase,label_comments,label_field1,label_field2,label_field3,label_field4,label_field5,label_field6,label_field7,label_field8,label_field9,label_field10,label_field11,label_field12,label_field13,label_field14,label_field15,label_field16,label_field17,label_field18,label_field19,label_field20,label_field21,label_field22,label_field23,label_field24,label_field25,label_field26,label_field27,label_field28,label_field29,label_field30,label_field31,label_field32,label_field33,label_field34,label_field35,label_field36,label_field37,label_field38,label_field39,label_field40 from vicidial_screen_labels where  label_id='$rowc[0]';";
		$rsltl=mysql_to_mysqli($stmtl, $link);
        $row_data = mysqli_fetch_array($rsltl);
	
$label_title = ($row_data[0]=="---HIDE---")?"":(",$row_data[0]");
$label_first_name = ($row_data[1]=="---HIDE---")?"":(",$row_data[1]");
$label_middle_initial = ($row_data[2]=="---HIDE---")?"":(",$row_data[2]");
$label_last_name = ($row_data[3]=="---HIDE---")?"":(",$row_data[3]");
$label_address1 = ($row_data[4]=="---HIDE---")?"":(",$row_data[4]");
$label_address2 = ($row_data[5]=="---HIDE---")?"":(",$row_data[5]");
$label_address3 = ($row_data[6]=="---HIDE---")?"":(",$row_data[6]");
$label_city = ($row_data[7]=="---HIDE---")?"":(",$row_data[7]");
$label_state = ($row_data[8]=="---HIDE---")?"":(",$row_data[8]");
$label_province = ($row_data[9]=="---HIDE---")?"":(",$row_data[9]");
$label_postal_code = ($row_data[10]=="---HIDE---")?"":(",$row_data[10]");
$label_vendor_lead_code = ($row_data[11]=="---HIDE---")?"":(",$row_data[11]");
$label_gender = ($row_data[12]=="---HIDE---")?"":(",$row_data[12]");
$label_phone_number = ($row_data[13]=="---HIDE---")?"":(",$row_data[13]");
$label_phone_code = ($row_data[14]=="---HIDE---")?"":(",$row_data[14]");
$label_alt_phone = ($row_data[15]=="---HIDE---")?"":(",$row_data[15]");
$label_email = ($row_data[16]=="---HIDE---")?"":(",$row_data[16]");
$label_security_phrase = ($row_data[17]=="---HIDE---")?"":(",$row_data[17]");
$label_comments = ($row_data[18]=="---HIDE---")?"":(",$row_data[18]");
$label_field1 = ($row_data[19]=="---HIDE---")?"":(",$row_data[19]");
$label_field2 = ($row_data[20]=="---HIDE---")?"":(",$row_data[20]");
$label_field3 = ($row_data[21]=="---HIDE---")?"":(",$row_data[21]");
$label_field4 = ($row_data[22]=="---HIDE---")?"":(",$row_data[22]");
$label_field5 = ($row_data[23]=="---HIDE---")?"":(",$row_data[23]");
$label_field6 = ($row_data[24]=="---HIDE---")?"":(",$row_data[24]");
$label_field7 = ($row_data[25]=="---HIDE---")?"":(",$row_data[25]");
$label_field8 = ($row_data[26]=="---HIDE---")?"":(",$row_data[26]");
$label_field9 = ($row_data[27]=="---HIDE---")?"":(",$row_data[27]");
$label_field10 = ($row_data[28]=="---HIDE---")?"":(",$row_data[28]");
$label_field11 = ($row_data[29]=="---HIDE---")?"":(",$row_data[29]");
$label_field12 = ($row_data[30]=="---HIDE---")?"":(",$row_data[30]");
$label_field13 = ($row_data[31]=="---HIDE---")?"":(",$row_data[31]");
$label_field14 = ($row_data[32]=="---HIDE---")?"":(",$row_data[32]");
$label_field15 = ($row_data[33]=="---HIDE---")?"":(",$row_data[33]");
$label_field16 = ($row_data[34]=="---HIDE---")?"":(",$row_data[34]");
$label_field17 = ($row_data[35]=="---HIDE---")?"":(",$row_data[35]");
$label_field18 = ($row_data[36]=="---HIDE---")?"":(",$row_data[36]");
$label_field19 = ($row_data[37]=="---HIDE---")?"":(",$row_data[37]");
$label_field20 = ($row_data[38]=="---HIDE---")?"":(",$row_data[38]");
$label_field21 = ($row_data[39]=="---HIDE---")?"":(",$row_data[39]");
$label_field22 = ($row_data[40]=="---HIDE---")?"":(",$row_data[40]");
$label_field23 = ($row_data[41]=="---HIDE---")?"":(",$row_data[41]");
$label_field24 = ($row_data[42]=="---HIDE---")?"":(",$row_data[42]");
$label_field25 = ($row_data[43]=="---HIDE---")?"":(",$row_data[43]");
$label_field26 = ($row_data[44]=="---HIDE---")?"":(",$row_data[44]");
$label_field27 = ($row_data[45]=="---HIDE---")?"":(",$row_data[45]");
$label_field28 = ($row_data[46]=="---HIDE---")?"":(",$row_data[46]");
$label_field29 = ($row_data[47]=="---HIDE---")?"":(",$row_data[47]");
$label_field30 = ($row_data[48]=="---HIDE---")?"":(",$row_data[48]");
$label_field31 = ($row_data[49]=="---HIDE---")?"":(",$row_data[49]");
$label_field32 = ($row_data[50]=="---HIDE---")?"":(",$row_data[50]");
$label_field33 = ($row_data[51]=="---HIDE---")?"":(",$row_data[51]");
$label_field34 = ($row_data[52]=="---HIDE---")?"":(",$row_data[52]");
$label_field35 = ($row_data[53]=="---HIDE---")?"":(",$row_data[53]");
$label_field36 = ($row_data[54]=="---HIDE---")?"":(",$row_data[54]");
$label_field37 = ($row_data[55]=="---HIDE---")?"":(",$row_data[55]");
$label_field38 = ($row_data[56]=="---HIDE---")?"":(",$row_data[56]");
$label_field39 = ($row_data[57]=="---HIDE---")?"":(",$row_data[57]");
$label_field40 = ($row_data[58]=="---HIDE---")?"":(",$row_data[58]");

		
			
			echo "call_date_time,lead_id,list_id,phone_dialed_no,duration,agent_name,user_group,campaign/ingroup_name,status,disconnected_by,call_type,queue_seconds,caller_remarks$label_title$label_first_name$label_middle_initial$label_last_name$label_address1$label_address2$label_address3$label_city$label_state$label_province$label_postal_code$label_vendor_lead_code$label_phone_number$label_phone_code$label_alt_phone$label_email$label_security_phrase$label_field1$label_field2$label_field3$label_field4$label_field5$label_field6$label_field7$label_field8$label_field9$label_field10$label_field11$label_field12$label_field13$label_field14$label_field15$label_field16$label_field17$label_field18$label_field19$label_field20$label_field21$label_field22$label_field23$label_field24$label_field25$label_field26$label_field27$label_field28$label_field29$label_field30$label_field31$label_field32$label_field33$label_field34$label_field35$label_field36$label_field37$label_field38$label_field39$label_field40,callback_date_time,call_status,status_name,recording_filename,list_name,list_description,wait_time,wrapup_time,hold_time\r\n";
			
			}

		### sort the duplicate check in newest to oldest call order by lead_id
		rsort($export_duplicate_check_line);
 $length = count($export_duplicate_check_line);
          
         for ($m = 0; $m < $length; $m++) {
  //echo "*".$export_duplicate_check_line[$m];echo "<br/>";
            $current_line_array = explode('---',$export_duplicate_check_line[$m]);
			$current_lead_id = $current_line_array[0];
			$current_line_number = $current_line_array[2];
            echo "$RAW_EXPORT[$current_line_number]";
           //fputs($fd,$RAW_EXPORT[$check_line_number]);
  
  
}
		
		$file_exported++;
		}
	else
		{
		echo _QXZ("There are no calls during this time period for these parameter")."\n";
		exit;
		}
	}
##### END RUN THE EXPORT AND OUTPUT FLAT DATA FILE #####


else
	{
	$NOW_DATE = date("Y-m-d");
	$NOW_TIME = date("Y-m-d H:i:s");
	$STARTtime = date("U");
	if (!isset($group)) {$group = '';}
	if (!isset($query_date)) {$query_date = $NOW_DATE;}
	if (!isset($end_date)) {$end_date = $NOW_DATE;}

	$stmt="select campaign_id from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$campaigns_to_print = mysqli_num_rows($rslt);
	$i=0;
		$LISTcampaigns[$i]='---NONE---';
		$i++;
		$campaigns_to_print++;
	while ($i < $campaigns_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTcampaigns[$i] =$row[0];
		$i++;
		}

	$stmt="select group_id from vicidial_inbound_groups $whereLOGadmin_viewable_groupsSQL order by group_id;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$groups_to_print = mysqli_num_rows($rslt);
	$i=0;
		$LISTgroups[$i]='---NONE---';
		$i++;
		$groups_to_print++;
	while ($i < $groups_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTgroups[$i] =$row[0];
		$i++;
		}

	$stmt="select user_group from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$user_groups_to_print = mysqli_num_rows($rslt);
	$i=0;
		$LISTuser_groups[$i]='---ALL---';
		$i++;
		$user_groups_to_print++;
	while ($i < $user_groups_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTuser_groups[$i] =$row[0];
		$i++;
		}

	$stmt="select list_id from vicidial_lists $whereLOGallowed_campaignsSQL order by list_id;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$lists_to_print = mysqli_num_rows($rslt);
	$i=0;
		$LISTlists[$i]='---ALL---';
		$i++;
		$lists_to_print++;
	while ($i < $lists_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTlists[$i] =$row[0];
		$i++;
		}

	$stmt="select status from vicidial_statuses order by status;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$statuses_to_print = mysqli_num_rows($rslt);
	$i=0;
		$LISTstatus[$i]='---ALL---';
		$i++;
		$statuses_to_print++;
	while ($i < $statuses_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTstatus[$i] =$row[0];
		$i++;
		}

	$stmt="select distinct status from vicidial_campaign_statuses $whereLOGallowed_campaignsSQL order by status;";
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$Cstatuses_to_print = mysqli_num_rows($rslt);
	$j=0;
	while ($j < $Cstatuses_to_print)
		{
		$row=mysqli_fetch_row($rslt);
		$LISTstatus[$i] =$row[0];
		$i++;
		$j++;
		}
	$statuses_to_print = ($statuses_to_print + $Cstatuses_to_print);

	echo "<HTML><HEAD>\n";

	echo "<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
	echo "<link rel=\"stylesheet\" href=\"calendar.css\">\n";

	echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
	echo "<TITLE>"._QXZ("ADMINISTRATION").": "._QXZ("$report_name");

	##### BEGIN Set variables to make header show properly #####
	$ADD =					'100';
	$hh =					'lists';
	$LOGast_admin_access =	'1';
	$SSoutbound_autodial_active = '1';
	$ADMIN =				'admin.php';
	$page_width='770';
	$section_width='750';
	$header_font_size='3';
	$subheader_font_size='2';
	$subcamp_font_size='2';
	$header_selected_bold='<b>';
	$header_nonselected_bold='';
	$lists_color =		'#FFFF99';
	$lists_font =		'BLACK';
	$lists_color =		'#E6E6E6';
	$subcamp_color =	'#C6C6C6';
	##### END Set variables to make header show properly #####

	require("admin_header.php");



echo "<section style='margin-top: -56px;' class='content-header'>
          <h1 style='font-size:23px; color:#4a4a4a;'>
            "._QXZ("Export Leads Report")."
          </h1>
       </section>";

echo "<section class='content'>
          <div class='row' style='margin-top: 55px;'>
            <div class='col-xs-12'>
              <div class='box'>
		<div class='box-body'>";

	echo "<BR>\n";
	
	echo "<FONT SIZE=2 FACE=\"Arial,Helvetica\">"._QXZ("This report pulls lead information for calls dialed in the selected date range. A lead is only exported once no matter how many calls were handled. The current lead status is used.")."</FONT><BR><BR>\n";
	echo "<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
	echo "<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">";
	echo "<INPUT TYPE=HIDDEN NAME=run_export VALUE=\"1\">";
	echo "<TABLE BORDER=0 CELLSPACING=8><TR><TD ALIGN=center VALIGN=TOP ROWSPAN=3>\n";

	echo "<font class=\"select_bold\"><B>"._QXZ("Date Range").":</B></font><BR><CENTER>\n";
	echo "<INPUT TYPE=TEXT NAME=query_date SIZE=10 MAXLENGTH=10 VALUE=\"$query_date\" class='form-control'>";

	?>
	<script language="JavaScript">
	var o_cal = new tcal ({
		// form name
		'formname': 'vicidial_report',
		// input name
		'controlname': 'query_date'
	});
	o_cal.a_tpl.yearscroll = false;
	// o_cal.a_tpl.weekstart = 1; // Monday week start
	</script>
	<?php

	echo "<BR>"._QXZ("to")."<BR>\n";
	echo "<INPUT TYPE=TEXT NAME=end_date SIZE=10 MAXLENGTH=10 VALUE=\"$end_date\" class='form-control'>";

	?>
	<script language="JavaScript">
	var o_cal = new tcal ({
		// form name
		'formname': 'vicidial_report',
		// input name
		'controlname': 'end_date'
	});
	o_cal.a_tpl.yearscroll = false;
	// o_cal.a_tpl.weekstart = 1; // Monday week start
	</script>
	<?php

	echo "<BR><BR>\n";

	echo "<B>"._QXZ("Header Row").":</B><BR>\n";
	echo "<select size=1 name=header_row class='form-control'><option selected value=\"YES\">"._QXZ("YES")."</option><option value=\"NO\">"._QXZ("NO")."</option></select>\n";

	echo "<BR><BR>\n";

	echo "<B>"._QXZ("Recording Fields").":</B><BR>\n";
	echo "<select size=1 name=rec_fields class='form-control'>";
	echo "<option value='ID'>"._QXZ("ID")."</option>";
	echo "<option value='FILENAME'>"._QXZ("FILENAME")."</option>";
	echo "<option value='LOCATION'>"._QXZ("LOCATION")."</option>";
	echo "<option value='ALL'>"._QXZ("ALL")."</option>";
	echo "<option value='NONE' selected>"._QXZ("NONE")."</option>";
	echo "</select>\n";

	if ($custom_fields_enabled > 0)
		{
		echo "<BR><BR>\n";

		echo "<B>"._QXZ("Custom Fields").":</B><BR>\n";
		echo "<select size=1 name=custom_fields class='form-control'><option value='YES'>"._QXZ("YES")."</option><option value='NO' selected>"._QXZ("NO")."</option></select>\n";
		}

	echo "<BR><BR>\n";

	echo "<B>"._QXZ("Per Call Notes").":</B><BR>\n";
	echo "<select size=1 name=call_notes class='form-control'><option value='YES'>"._QXZ("YES")."</option><option value='NO' selected>"._QXZ("NO")."</option></select>\n";

	echo "<BR><BR>\n";

	echo "<B>"._QXZ("Export Fields").":</B><BR>\n";
	echo "<select size=1 name=export_fields class='form-control'><option value='STANDARD' selected>"._QXZ("STANDARD")."</option><option value='EXTENDED'>"._QXZ("EXTENDED")."</option></select>\n";

	### bottom of first column

	echo "</TD><TD ALIGN=center VALIGN=TOP ROWSPAN=2>\n";
	echo "<font class=\"select_bold\"><B>"._QXZ("Campaigns").":</B></font><BR><CENTER>\n";
	echo "<SELECT SIZE=20 NAME=campaign[] multiple class='form-control'>\n";
		$o=0;
		while ($campaigns_to_print > $o)
		{
			if (preg_match("/\|$LISTcampaigns[$o]\|/",$campaign_string)) 
				{echo "<option selected value=\"$LISTcampaigns[$o]\">$LISTcampaigns[$o]</option>\n";}
			else 
				{echo "<option value=\"$LISTcampaigns[$o]\">$LISTcampaigns[$o]</option>\n";}
			$o++;
		}
	echo "</SELECT>\n"; 

	echo "</TD><TD ALIGN=center VALIGN=TOP ROWSPAN=3>\n";
	echo "<font class=\"select_bold\"><B>"._QXZ("Inbound Groups").":</B></font><BR><CENTER>\n";
	echo "<SELECT SIZE=20 NAME=group[] multiple class='form-control'>\n";
		$o=0;
		while ($groups_to_print > $o)
		{
			if (preg_match("/\|$LISTgroups[$o]\|/",$group_string)) 
				{echo "<option selected value=\"$LISTgroups[$o]\">$LISTgroups[$o]</option>\n";}
			else
				{echo "<option value=\"$LISTgroups[$o]\">$LISTgroups[$o]</option>\n";}
			$o++;
		}
	echo "</SELECT>\n";
	echo "</TD><TD ALIGN=center VALIGN=TOP ROWSPAN=3>\n";
	echo "<font class=\"select_bold\"><B>"._QXZ("Lists").":</B></font><BR><CENTER>\n";
	echo "<SELECT SIZE=20 NAME=list_id[] multiple class='form-control'>\n";
		$o=0;
		while ($lists_to_print > $o)
		{
			if (preg_match("/\|$LISTlists[$o]\|/",$list_string)) 
				{echo "<option selected value=\"$LISTlists[$o]\">$LISTlists[$o]</option>\n";}
			else 
				{echo "<option value=\"$LISTlists[$o]\">$LISTlists[$o]</option>\n";}
			$o++;
		}
	echo "</SELECT>\n";
	echo "</TD><TD ALIGN=center VALIGN=TOP ROWSPAN=3>\n";
	echo "<font class=\"select_bold\"><B>"._QXZ("Statuses").":</B></font><BR><CENTER>\n";
	echo "<SELECT SIZE=20 NAME=status[] multiple class='form-control'>\n";
		$o=0;
		while ($statuses_to_print > $o)
		{
			if (preg_match("/\|$LISTstatus[$o]\|/",$list_string)) 
				{echo "<option selected value=\"$LISTstatus[$o]\">$LISTstatus[$o]</option>\n";}
			else 
				{echo "<option value=\"$LISTstatus[$o]\">$LISTstatus[$o]</option>\n";}
			$o++;
		}
	echo "</SELECT>\n";
	echo "</TD><TD ALIGN=center VALIGN=TOP ROWSPAN=3>\n";
	echo "<font class=\"select_bold\"><B>"._QXZ("User Groups").":</B></font><BR><CENTER>\n";
	echo "<SELECT SIZE=20 NAME=user_group[] multiple class='form-control'>\n";
		$o=0;
		while ($user_groups_to_print > $o)
		{
			if (preg_match("/\|$LISTuser_groups[$o]\|/",$user_group_string)) 
				{echo "<option selected value=\"$LISTuser_groups[$o]\">$LISTuser_groups[$o]</option>\n";}
			else 
				{echo "<option value=\"$LISTuser_groups[$o]\">$LISTuser_groups[$o]</option>\n";}
			$o++;
		}
	echo "</SELECT>\n";

	echo "</TD></TR><TR></TD><TD ALIGN=LEFT VALIGN=TOP COLSPAN=2> &nbsp; \n";

	echo "</TD></TR><TR></TD><TD ALIGN=CENTER VALIGN=TOP COLSPAN=5>\n";
	echo "<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='"._QXZ("SUBMIT")."' class='btn bg-navy margin'>\n";
	echo "</TD></TR></TABLE>\n";
	echo "</FORM>\n\n";
	echo "</div></div></div></div></section>";

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

if ($file_exported > 0)
	{
	### LOG INSERTION Admin Log Table ###
	$SQL_log = "$stmt|$stmtA|";
	$SQL_log = preg_replace('/;/', '', $SQL_log);
	$SQL_log = addslashes($SQL_log);
	$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LEADS', event_type='EXPORT', record_id='', event_code='ADMIN EXPORT LEADS REPORT', event_sql=\"$SQL_log\", event_notes='';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_to_mysqli($stmt, $link);
	}
fclose($fd);
exit;

?>
