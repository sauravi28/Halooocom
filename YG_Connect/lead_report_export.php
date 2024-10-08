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
			//test change1 haloo
             /*$cur=date('Y-m-01');
			 if(strtotime($end_date) < strtotime($cur))
			 {
				$stmtc1 = "SELECT screen_labels  from vicidial_campaigns where campaign_id='$campaign[0]';";
		        $rsltc1=mysqli_query($link,$stmtc1);
                $rowc1 = mysqli_fetch_row($rsltc1);
				$sql_query = "select * from vicidial_screen_labels where label_id = '$rowc1[0]'";  
                $sql_result = mysqli_query($link, $sql_query);
                $row = mysqli_fetch_assoc($sql_result);
				foreach($row as $column => $value) {
          if($value=="ACC_NO")
          {
         
          $qry.=$column." as ACC_NO,";
          }
          if($value=="LOAN_NUMBER")
          {
         
         $qry.=$column." as LOAN_NUMBER,";
          }
          if($value=="LOAN_NO")
          {
        
          $qry.=$column." as LOAN_NO ,";
          }

          if($value=="REMARKS")
          {
        
          $qry.=$column." as REMARKS,";
          }
           if($value=="PRODUCT")
          {

          $qry.=$column." as PRODUCT,";
          }
	  if($value=="CUST_NAME")
          {

          $qry.=$column." as CUSTOMER_NAME,";
          }
       }
	 
	 
	   
	   
                    $qry=rtrim($qry,',');

                      if($qry == ',')
                      {
                        $qry = '';
                       }

					$qry = str_replace("label_","",$qry);
          			$qr = "SELECT vl.call_date,vl.phone_number,vl.status,vl.user,vi.comments as Feedback,$qry from vicidial_users vu,vicidial_log vl,vicidial_list vi where vl.call_date >= '$query_date 00:00:00' and vl.call_date <= '$end_date 23:59:59' and vu.user=vl.user and vi.lead_id=vl.lead_id $list_SQL $campaign_SQL $user_group_SQL $status_SQL order by vl.call_date desc limit 500000;";
					$filename = "old_$end_date.csv"; // File Name
// Download file
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/csv");
$user_query = mysqli_query($link,$qr);
// Write data to file
$flag = false;
while ($row = mysqli_fetch_assoc($user_query)) {
    if (!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    echo implode("\t", array_values($row)) . "\r\n";
}
			exit;	
				
			 }*/
	// ###############################code for current month ######################################################		 
			
		
   
			$stmt = "SELECT vl.call_date,vl.length_in_sec,vl.status,vl.user,vu.full_name,vl.campaign_id,vi.vendor_lead_code,vi.source_id,vi.list_id,vi.gmt_offset_now,vi.phone_code,vi.phone_number,vi.title,vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.country_code,vi.gender,vi.date_of_birth,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vl.length_in_sec,vl.user_group,vl.alt_dial,vi.rank,vi.owner,vi.lead_id,vl.uniqueid,vi.entry_list_id,UNIX_TIMESTAMP(vl.call_date)$export_fields_SQL,vi.field1,vi.field2,vi.field3,vi.field4,vi.field5,vi.field6,vi.field7,vi.field8,vi.field9,vi.field10,vi.field11,vi.field12,vi.field13,vi.field14,vi.field15,vi.field16,vi.field17,vi.field18,vi.field19,vi.field20,vi.field21,vi.field22,vi.field23,vi.field24,vi.field25,vi.field26,vi.field27,vi.field28,vi.field29,vi.field30,vi.field31,vi.field32,vi.field33,vi.field34,vi.field35,vi.field36,vi.field37,vi.field38,vi.field39,vi.field40,vl.phone_number from vicidial_users vu,vicidial_log vl,vicidial_list vi where vl.call_date >= '$query_date 00:00:00' and vl.call_date <= '$end_date 23:59:59' and vu.user=vl.user and vi.lead_id=vl.lead_id $list_SQL $campaign_SQL $user_group_SQL $status_SQL order by vl.call_date desc limit 500000;";
	//	echo $stmt;exit;
                $rslt=mysql_to_mysqli($stmt, $link);
		if ($DB) {echo "$stmt\n";}
		$outbound_to_print = mysqli_num_rows($rslt);
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
				$row=mysqli_fetch_row($rslt);
 
				$row[29] = preg_replace("/\n|\r/",'!N',$row[29]);

				$export_status[$k] =		$row[2];
				$export_list_id[$k] =		$row[8];
				$export_lead_id[$k] =		$row[35];
				$export_uniqueid[$k] =		$row[36];
				$export_vicidial_id[$k] =	$row[36];
				$export_entry_list_id[$k] =	$row[37];
				$export_epoch_time[$k] =	$row[38];
				$export_duplicate_check_line[$k] = "$export_lead_id[$k]---$export_epoch_time[$k]---$k";

				if ($LOGadmin_hide_phone_data != '0')
					{
					if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
					$phone_temp = $row[1];
					if (strlen($phone_temp) > 0)
						{
						if ($LOGadmin_hide_phone_data == '4_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
						elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
						elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
						else
							{$row[1] = preg_replace("/./",'X',$phone_temp);}
						}
					}
				if ($LOGadmin_hide_lead_data != '0')
					{
					if ($DB > 0) {echo "HIDELEADDATA|$row[6]|$row[7]|$row[12]|$row[13]|$row[14]|$row[15]|$row[16]|$row[17]|$row[18]|$row[19]|$row[20]|$row[21]|$row[22]|$row[26]|$row[27]|$row[28]|$LOGadmin_hide_lead_data|\n";}
					if (strlen($row[6]) > 0)
						{$data_temp = $row[6];   $row[6] = preg_replace("/./",'X',$data_temp);}
					if (strlen($row[7]) > 0)
						{$data_temp = $row[7];   $row[7] = preg_replace("/./",'X',$data_temp);}
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

				$export_fieldsDATA='';
				if ($export_fields == 'EXTENDED')
					{$export_fieldsDATA = "$row[39],$row[40],$row[41],$row[42],$row[43],";}
				
				// test change2
		
$selc = "select timestamp,comment from vicidial_comments where lead_id = '$row[35]'";
$resc = mysqli_query($link,$selc);
while($rowc=mysqli_fetch_array($resc))
{
$cm.= $rowc[0].":".$rowc[1]."   ";
}	


		
			

$row[29] = preg_replace('/\s+/', ' ',$cm);
$row[29] = str_replace(',', '_', $cm);
$row[12] = str_replace(',', '', $row[12]);
$row[13] = str_replace(',', '', $row[13]);
$row[14] = str_replace(',', '', $row[14]);
$row[15] = str_replace(',', '', $row[15]);
$row[16] = str_replace(',', '', $row[16]);
$row[17] = str_replace(',', '', $row[17]);
$row[18] = str_replace(',', '', $row[18]);
$row[19] = str_replace(',', '', $row[19]);
$row[20] = str_replace(',', '', $row[20]);
$row[21] = str_replace(',', '', $row[21]);
$row[22] = str_replace(',', '', $row[22]);
$row[23] = str_replace(',', '', $row[23]);
$row[24] = str_replace(',', '', $row[24]);
$row[25] = str_replace(',', '', $row[25]);
$row[26] = str_replace(',', '', $row[26]);
$row[27] = str_replace(',', '', $row[27]);
$row[28] = str_replace(',', '', $row[28]);

$row[1]=gmdate("H:i:s", $row[1]);

$row[40] = str_replace(',', '', $row[40]);
$row[41] = str_replace(',', '', $row[41]);
$row[42] = str_replace(',', '', $row[42]);
$row[43] = str_replace(',', '', $row[43]);
$row[44] = str_replace(',', '', $row[44]);
$row[45] = str_replace(',', '', $row[45]);
$row[46] = str_replace(',', '', $row[46]);
$row[47] = str_replace(',', '', $row[47]);
$row[48] = str_replace(',', '', $row[48]);
$row[49] = str_replace(',', '', $row[49]);
$row[50] = str_replace(',', '', $row[50]);
$row[51] = str_replace(',', '', $row[51]);
$row[52] = str_replace(',', '', $row[52]);
$row[53] = str_replace(',', '', $row[53]);
$row[54] = str_replace(',', '', $row[54]);
$row[55] = str_replace(',', '', $row[55]);
$row[56] = str_replace(',', '', $row[56]);
$row[57] = str_replace(',', '', $row[57]);
$row[58] = str_replace(',', '', $row[58]);
$row[59] = str_replace(',', '', $row[59]);
$row[60] = str_replace(',', '', $row[60]);
$row[61] = str_replace(',', '', $row[61]);
$row[62] = str_replace(',', '', $row[62]);
$row[63] = str_replace(',', '', $row[63]);
$row[64] = str_replace(',', '', $row[64]);
$row[65] = str_replace(',', '', $row[65]);
$row[66] = str_replace(',', '', $row[66]);
$row[67] = str_replace(',', '', $row[67]);
$row[68] = str_replace(',', '', $row[68]);
$row[69] = str_replace(',', '', $row[69]);
$row[70] = str_replace(',', '', $row[70]);
$row[71] = str_replace(',', '', $row[71]);
$row[72] = str_replace(',', '', $row[72]);
$row[73] = str_replace(',', '', $row[73]);
$row[74] = str_replace(',', '', $row[74]);
$row[75] = str_replace(',', '', $row[75]);
$row[76] = str_replace(',', '', $row[76]);
$row[77] = str_replace(',', '', $row[77]);
$row[78] = str_replace(',', '', $row[78]);
$row[79] = str_replace(',', '', $row[79]);





				//$export_rows[$k] = "$row[0],$row[1],$row[79],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[39],$row[40],$row[41],$row[42],$row[43],$row[44],$row[45],$row[46],$row[47],$row[48],$row[49],$row[50],$row[51],$row[52],$row[53],$row[54],$row[55],$row[56],$row[57],$row[58],$row[59],$row[60],$row[61],$row[62],$row[63],$row[64],$row[65],$row[66],$row[67],$row[68],$row[69],$row[70],$row[71],$row[72],$row[73],$row[74],$row[75],$row[76],$row[77],$row[78],$row[31],$row[32],$row[33],$row[34],$row[35],$export_fieldsDATA";
				
				$export_rows.= "$row[0],$row[1],$row[79],$row[2],$row[3],$row[4],$row[5],,$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[39],$row[40],$row[41],$row[42],$row[43],$row[44],$row[45],$row[46],$row[47],$row[48],$row[49],$row[50],$row[51],$row[52],$row[53],$row[54],$row[55],$row[56],$row[57],$row[58],$row[59],$row[60],$row[61],$row[62],$row[63],$row[64],$row[65],$row[66],$row[67],$row[68],$row[69],$row[70],$row[71],$row[72],$row[73],$row[74],$row[75],$row[76],$row[77],$row[78],$row[31],$row[32],$row[33],$row[34],$row[35],$export_fieldsDATA***";
				
//echo $export_rows[$k];echo "<br/>";

				$i++;
				$k++;
				$outbound_calls++;
				$cm='';
		}
			}
		}
		
		$export_rows = explode("***",$export_rows);
//die;
	if ($RUNgroup > 0)
		{
		
		$stmtA = "SELECT vl.call_date,length_in_sec,vi.status,vl.user,vu.full_name,vl.campaign_id,vi.vendor_lead_code,vi.source_id,vi.list_id,vi.gmt_offset_now,vi.phone_code,vi.phone_number,vi.title,vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.country_code,vi.gender,vi.date_of_birth,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vl.length_in_sec,vl.user_group,vl.queue_seconds,vi.rank,vi.owner,vi.lead_id,vl.closecallid,vi.entry_list_id,vl.uniqueid,UNIX_TIMESTAMP(vl.call_date)$export_fields_SQL,vi.field1,vi.field2,vi.field3,vi.field4,vi.field5,vi.field6,vi.field7,vi.field8,vi.field9,vi.field10,vi.field11,vi.field12,vi.field13,vi.field14,vi.field15,vi.field16,vi.field17,vi.field18,vi.field19,vi.field20,vi.field21,vi.field22,vi.field23,vi.field24,vi.field25,vi.field26,vi.field27,vi.field28,vi.field29,vi.field30,vi.field31,vi.field32,vi.field33,vi.field34,vi.field35,vi.field36,vi.field37,vi.field38,vi.field39,vi.field40,vl.phone_number from vicidial_users vu,vicidial_closer_log vl,vicidial_list vi where vl.call_date >= '$query_date 00:00:00' and vl.call_date <= '$end_date 23:59:59' and vu.user=vl.user and vi.lead_id=vl.lead_id $list_SQL $group_SQL $user_group_SQL $status_SQL order by vl.call_date desc limit 500000;";
   $rslt=mysql_to_mysqli($stmtA, $link);
		if ($DB) {echo "$stmtA\n";}
		$inbound_to_print = mysqli_num_rows($rslt);
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
				$row=mysqli_fetch_row($rslt);

				$row[29] = preg_replace("/\n|\r/",'!N',$row[29]);

				$export_status[$k] =		$row[2];
				$export_list_id[$k] =		$row[8];
				$export_lead_id[$k] =		$row[35];
				$export_vicidial_id[$k] =	$row[36];
				$export_entry_list_id[$k] =	$row[37];
				$export_uniqueid[$k] =		$row[38];
				$export_epoch_time[$k] =	$row[39];
				$export_duplicate_check_line[$k] = "$export_lead_id[$k]---$export_epoch_time[$k]---$k";

				if ($LOGadmin_hide_phone_data != '0')
					{
					if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
					$phone_temp = $row[1];
					if (strlen($phone_temp) > 0)
						{
						if ($LOGadmin_hide_phone_data == '4_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
						elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
						elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
							{$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
						else
							{$row[1] = preg_replace("/./",'X',$phone_temp);}
						}
					}
				if ($LOGadmin_hide_lead_data != '0')
					{
					if ($DB > 0) {echo "HIDELEADDATA|$row[6]|$row[7]|$row[12]|$row[13]|$row[14]|$row[15]|$row[16]|$row[17]|$row[18]|$row[19]|$row[20]|$row[21]|$row[22]|$row[26]|$row[27]|$row[28]|$LOGadmin_hide_lead_data|\n";}
					if (strlen($row[6]) > 0)
						{$data_temp = $row[6];   $row[6] = preg_replace("/./",'X',$data_temp);}
					if (strlen($row[7]) > 0)
						{$data_temp = $row[7];   $row[7] = preg_replace("/./",'X',$data_temp);}
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
//$row[1]=gmdate("H:i:s", $row[1]);

				$export_fieldsDATA='';
				if ($export_fields == 'EXTENDED')
					{$export_fieldsDATA = "$row[40],$row[41],$row[42],$row[43],$row[44],";}

$row[29] = preg_replace('/\s+/', ' ',$row[29]);
$row[29] = str_replace(',', '_', $row[29]);
$row[12] = str_replace(',', '', $row[12]);
$row[13] = str_replace(',', '', $row[13]);
$row[14] = str_replace(',', '', $row[14]);
$row[15] = str_replace(',', '', $row[15]);
$row[16] = str_replace(',', '', $row[16]);
$row[17] = str_replace(',', '', $row[17]);
$row[18] = str_replace(',', '', $row[18]);
$row[19] = str_replace(',', '', $row[19]);
$row[20] = str_replace(',', '', $row[20]);
$row[21] = str_replace(',', '', $row[21]);
$row[22] = str_replace(',', '', $row[22]);
$row[23] = str_replace(',', '', $row[23]);
$row[24] = str_replace(',', '', $row[24]);
$row[25] = str_replace(',', '', $row[25]);
$row[26] = str_replace(',', '', $row[26]);
$row[27] = str_replace(',', '', $row[27]);
$row[28] = str_replace(',', '', $row[28]);

$row[1]=gmdate("H:i:s", $row[1]);

$row[40] = str_replace(',', '', $row[40]);
$row[41] = str_replace(',', '', $row[41]);
$row[42] = str_replace(',', '', $row[42]);
$row[43] = str_replace(',', '', $row[43]);
$row[44] = str_replace(',', '', $row[44]);
$row[45] = str_replace(',', '', $row[45]);
$row[46] = str_replace(',', '', $row[46]);
$row[47] = str_replace(',', '', $row[47]);
$row[48] = str_replace(',', '', $row[48]);
$row[49] = str_replace(',', '', $row[49]);
$row[50] = str_replace(',', '', $row[50]);
$row[51] = str_replace(',', '', $row[51]);
$row[52] = str_replace(',', '', $row[52]);
$row[53] = str_replace(',', '', $row[53]);
$row[54] = str_replace(',', '', $row[54]);
$row[55] = str_replace(',', '', $row[55]);
$row[56] = str_replace(',', '', $row[56]);
$row[57] = str_replace(',', '', $row[57]);
$row[58] = str_replace(',', '', $row[58]);
$row[59] = str_replace(',', '', $row[59]);
$row[60] = str_replace(',', '', $row[60]);
$row[61] = str_replace(',', '', $row[61]);
$row[62] = str_replace(',', '', $row[62]);
$row[63] = str_replace(',', '', $row[63]);
$row[64] = str_replace(',', '', $row[64]);
$row[65] = str_replace(',', '', $row[65]);
$row[66] = str_replace(',', '', $row[66]);
$row[67] = str_replace(',', '', $row[67]);
$row[68] = str_replace(',', '', $row[68]);
$row[69] = str_replace(',', '', $row[69]);
$row[70] = str_replace(',', '', $row[70]);
$row[71] = str_replace(',', '', $row[71]);
$row[72] = str_replace(',', '', $row[72]);
$row[73] = str_replace(',', '', $row[73]);
$row[74] = str_replace(',', '', $row[74]);
$row[75] = str_replace(',', '', $row[75]);
$row[76] = str_replace(',', '', $row[76]);
$row[77] = str_replace(',', '', $row[77]);
$row[78] = str_replace(',', '', $row[78]);
$row[79] = str_replace(',', '', $row[79]);
	
	
				$sql_groupName  = "SELECT `group_name` FROM `vicidial_inbound_groups` where `group_id`='$row[5]'";
				//echo $sql_groupName;
				//echo "<br>";
				$res_groupName = mysqli_query($link,$sql_groupName);
				$row_groupName = mysqli_fetch_array($res_groupName);
				$IngroupName = $row_groupName[0];
				

	
	//$row[5];
	$export_rows[$k] = "$row[0],$row[1],$row[79],$row[2],$row[3],$row[4],$row[5],$IngroupName,$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[39],$row[40],$row[41],$row[42],$row[43],$row[44],$row[45],$row[46],$row[47],$row[48],$row[49],$row[50],$row[51],$row[52],$row[53],$row[54],$row[55],$row[56],$row[57],$row[58],$row[59],$row[60],$row[61],$row[62],$row[63],$row[64],$row[65],$row[66],$row[67],$row[68],$row[69],$row[70],$row[71],$row[72],$row[73],$row[74],$row[75],$row[76],$row[77],$row[78],$row[31],$row[32],$row[33],$row[34],$row[35],$export_fieldsDATA"
;
				//$export_rows[$k] = "$row[0],$row[1],$row[79],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20],$row[21],$row[22],$row[23],$row[24],$row[25],$row[26],$row[27],$row[28],$row[29],$row[30],$row[31],$row[32],$row[33],$row[34],$row[35],$export_fieldsDATA";
				$i++;
				$k++;
				}
			}
		}

	if ( ($outbound_to_print > 0) or ($inbound_to_print > 0) )
		{
          /*  $date = date('Y-m-d');
            $TXTfilename = "LeadReport-".$date.".csv";
			$fd = fopen ($TXTfilename, "w");*/

		$TXTfilename = "EXPORT_LEAD_REPORT_$FILE_TIME.csv";

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

			$rec_data='';
			if ( ($rec_fields=='ID') or ($rec_fields=='FILENAME') or ($rec_fields=='LOCATION') or ($rec_fields=='ALL') )
				{
				$rec_id='';
				$rec_filename='';
				$rec_location='';
				$stmt = "SELECT recording_id,filename,location from recording_log where vicidial_id='$export_vicidial_id[$i]' order by recording_id desc LIMIT 10;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($DB) {echo "$stmt\n";}
				$recordings_ct = mysqli_num_rows($rslt);
				$u=0;
				while ($recordings_ct > $u)
					{
					$row=mysqli_fetch_row($rslt);
					$rec_id .=			"$row[0]|";
					$rec_filename .=	"$row[1]|";
					$rec_location .=	"$row[2]|";

					$u++;
					}
				$rec_id = preg_replace("/.$/",'',$rec_id);
				$rec_filename = preg_replace("/.$/",'',$rec_filename);
				$rec_location = preg_replace("/.$/",'',$rec_location);

				if ($rec_fields=='ID')
					{$rec_data = ",$rec_id";}
				if ($rec_fields=='FILENAME')
					{$rec_data = ",$rec_filename";}
				if ($rec_fields=='LOCATION')
					{$rec_data = ",$rec_location";}
				if ($rec_fields=='ALL')
					{$rec_data = ",$rec_id,$rec_filename,$rec_location";}
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

			$RAW_EXPORT[$i] = "$export_rows[$i]$ex_list_name,$ex_list_description,$ex_status_name$rec_data$extended_data$notes_data$custom_data\r\n";
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
        $rowl = mysqli_fetch_row($rsltl);
			echo "call_date,duration,phone_dialed_no,status,user,full_name,campaign_id,Ingroup_Name,$rowl[11],source_id,list_id,gmt_offset_now,$rowl[14],$rowl[13],$rowl[0],$rowl[1],$rowl[2],$rowl[3],$rowl[4],$rowl[5],$rowl[6],$rowl[7],$rowl[8],$rowl[9],$rowl[10],$rowl[11],gender,date_of_birth,$rowl[15],$rowl[16],$rowl[17],comments,$rowl[19],$rowl[20],$rowl[21],$rowl[22],$rowl[23],$rowl[24],$rowl[25],$rowl[26],$rowl[27],$rowl[28],$rowl[29],$rowl[30],$rowl[31],$rowl[32],$rowl[33],$rowl[34],$rowl[35],$rowl[36],$rowl[37],$rowl[38],$rowl[39],$rowl[40],$rowl[41],$rowl[42],$rowl[43],$rowl[44],$rowl[45],$rowl[46],$rowl[47],$rowl[48],$rowl[49],$rowl[50],$rowl[51],$rowl[52],$rowl[53],$rowl[54],$rowl[55],$rowl[56],$rowl[57],$rowl[58],user_group,alt_dial,rank,owner,lead_id$EFheader,list_name,list_description,status_name$RFheader$EXheader$NFheader$CFheader\r\n";
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
           // fputs($fd,$RAW_EXPORT[$check_line_number]);
  
  
}
		### always print the first line(newest call on highest lead_id)
		/*$check_line_array = explode('---',$export_duplicate_check_line[0]);
		$check_lead_id = $check_line_array[0];
		$check_line_number = $check_line_array[2];
		echo "$RAW_EXPORT[$check_line_number]";

		$j=1;
		while ($i > $j)
			{
			$current_line_array = explode('---',$export_duplicate_check_line[$j]);
			$current_lead_id = $current_line_array[0];
			$current_line_number = $current_line_array[2];
			if ($current_lead_id == "$check_lead_id")
				{$duplicate_lead++;}
			else
				{
				$unique_lead++;
				$check_lead_id = $current_lead_id;
				echo "$RAW_EXPORT[$current_line_number]";
				}
			$j++;
			}*/
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
