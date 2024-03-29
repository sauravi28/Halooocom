<?php 

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
if (isset($_GET["group"]))					{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))			{$group=$_POST["group"];}
if (isset($_GET["user_group"]))				{$user_group=$_GET["user_group"];}
	elseif (isset($_POST["user_group"]))	{$user_group=$_POST["user_group"];}
if (isset($_GET["users"]))					{$users=$_GET["users"];}
	elseif (isset($_POST["users"]))			{$users=$_POST["users"];}
if (isset($_GET["shift"]))					{$shift=$_GET["shift"];}
	elseif (isset($_POST["shift"]))			{$shift=$_POST["shift"];}
if (isset($_GET["stage"]))					{$stage=$_GET["stage"];}
	elseif (isset($_POST["stage"]))			{$stage=$_POST["stage"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))					{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))			{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["report_display_type"]))			{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}
if (isset($_GET["show_percentages"]))			{$show_percentages=$_GET["show_percentages"];}
	elseif (isset($_POST["show_percentages"]))	{$show_percentages=$_POST["show_percentages"];}
if (isset($_GET["time_in_sec"]))			{$time_in_sec=$_GET["time_in_sec"];}
	elseif (isset($_POST["time_in_sec"]))	{$time_in_sec=$_POST["time_in_sec"];}
if (isset($_GET["breakdown_by_date"]))			{$breakdown_by_date=$_GET["breakdown_by_date"];}
	elseif (isset($_POST["breakdown_by_date"]))	{$breakdown_by_date=$_POST["breakdown_by_date"];}
if (isset($_GET["search_archived_data"]))			{$search_archived_data=$_GET["search_archived_data"];}
	elseif (isset($_POST["search_archived_data"]))	{$search_archived_data=$_POST["search_archived_data"];}
if (isset($_GET["show_defunct_users"]))			{$show_defunct_users=$_GET["show_defunct_users"];}
	elseif (isset($_POST["show_defunct_users"]))	{$show_defunct_users=$_POST["show_defunct_users"];}

if (strlen($shift)<2) {$shift='ALL';}
if ($search_archived_data=="checked") {$agent_log_table="vicidial_agent_log_archive";} else {$agent_log_table="vicidial_agent_log";}

$TIME_HF_agentperfdetail = 'HF';
$TIME_H_agentperfdetail = 'H';
$TIME_M_agentperfdetail = 'M';

if ($file_download == 1)
	{
	$TIME_HF_agentperfdetail = 'HF';
	$TIME_H_agentperfdetail = 'HF';
	$TIME_M_agentperfdetail = 'HF';
	}

if ($time_in_sec)
	{
	$TIME_HF_agentperfdetail = 'S';
	$TIME_H_agentperfdetail = 'S';
	$TIME_M_agentperfdetail = 'S';
	}

$report_name = 'Agent Performance Detail';
$db_source = 'M';
$JS_text="<script language='Javascript'>\n";
$JS_onload="onload = function() {\n";

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,enable_languages,language_method FROM system_settings;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$non_latin =					$row[0];
	$outbound_autodial_active =		$row[1];
	$slave_db_server =				$row[2];
	$reports_use_slave_db =			$row[3];
	$SSenable_languages =			$row[4];
	$SSlanguage_method =			$row[5];
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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group[0], $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
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
	$HTML_text.="<!-- Using slave server $slave_db_server $db_source -->\n";
	}

$stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGuser_group =			$row[0];

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
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
    echo _QXZ("You are not allowed to view this report").": |$PHP_AUTH_USER|$report_name|\n";
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

$MT[0]='';
$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}


$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
	}

$i=0;
$users_string='|';
$users_ct = count($users);
while($i < $users_ct)
	{
	$users_string .= "$users[$i]|";
	$i++;
	}

$stmt="SELECT campaign_id from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$campaigns_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $campaigns_to_print)
	{
	$row=mysqli_fetch_row($rslt);
	$groups[$i] =$row[0];
	if (preg_match('/\-ALL/',$group_string) )
		{$group[$i] = $groups[$i];}
	$i++;
	}
for ($i=0; $i<count($user_group); $i++)
	{
	if (preg_match('/\-\-ALL\-\-/', $user_group[$i])) {$all_user_groups=1; $user_group="";}
	}

$stmt="SELECT user_group from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$user_groups_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $user_groups_to_print)
	{
	$row=mysqli_fetch_row($rslt);
	$user_groups[$i] =$row[0];
	if ($all_user_groups) {$user_group[$i]=$row[0];}
	$i++;
	}

$stmt="SELECT user, full_name from vicidial_users $whereLOGadmin_viewable_groupsSQL order by user";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$users_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $users_to_print)
	{
	$row=mysqli_fetch_row($rslt);
	$user_list[$i]=$row[0];
	$user_names[$i]=$row[1];
	if ($all_users) {$user_list[$i]=$row[0];}
	$i++;
	}


$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	if ( (preg_match("/ $group[$i] /",$regexLOGallowed_campaigns)) or (preg_match("/-ALL/",$LOGallowed_campaigns)) )
		{
		$group_string .= "$group[$i]|";
		$group_SQL .= "'$group[$i]',";
		$groupQS .= "&group[]=$group[$i]";
		}
	$i++;
	}
if ( (preg_match('/\-\-ALL\-\-/',$group_string) ) or ($group_ct < 1) )
	{$group_SQL = "";}
else
	{
	$group_SQL = preg_replace('/,$/i', '',$group_SQL);
	$group_SQL = "and campaign_id IN($group_SQL)";
	}

$i=0;
$user_group_string='|';
$user_group_ct = count($user_group);
while($i < $user_group_ct)
	{
	$user_group_string .= "$user_group[$i]|";
	$user_group_SQL .= "'$user_group[$i]',";
	$user_groupQS .= "&user_group[]=$user_group[$i]";
	$i++;
	}
if ( (preg_match('/\-\-ALL\-\-/',$user_group_string) ) or ($user_group_ct < 1) )
	{$user_group_SQL = "";}
else
	{
	$user_group_SQL = preg_replace('/,$/i', '',$user_group_SQL);
	$user_group_agent_log_SQL = "and ".$agent_log_table.".user_group IN($user_group_SQL)";
	$user_group_SQL = "and vicidial_users.user_group IN($user_group_SQL)";
	}

$i=0;
$user_string='|';
$user_ct = count($users);
while($i < $user_ct)
	{
	$user_string .= "$users[$i]|";
	$user_SQL .= "'$users[$i]',";
	$userQS .= "&users[]=$users[$i]";
	$i++;
	}
if ( (preg_match('/\-\-ALL\-\-/',$user_string) ) or ($user_ct < 1) )
	{$user_SQL = "";}
else
	{
	$user_SQL = preg_replace('/,$/i', '',$user_SQL);
	$user_agent_log_SQL = "and ".$agent_log_table.".user IN($user_SQL)";
	$user_SQL = "and vicidial_users.user IN($user_SQL)";
	}


if ($DB) {$HTML_text.="$user_group_string|$user_group_ct|$user_groupQS|$i<BR>";}

$LINKbase = "$PHP_SELF?query_date=$query_date&end_date=$end_date$groupQS$user_groupQS&shift=$shift&DB=$DB&show_percentages=$show_percentages&time_in_sec=$time_in_sec&search_archived_data=$search_archived_data&show_defunct_users=$show_defunct_users&breakdown_by_date=$breakdown_by_date";

//echo $LINKbase;

$NWB = " &nbsp; <a href=\"javascript:openNewWindow('help.php?ADD=99999";
$NWE = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";

$HTML_head.="<HTML>\n";
$HTML_head.="<HEAD>\n";
$HTML_head.="<STYLE type=\"text/css\">\n";
$HTML_head.="<!--\n";
$HTML_head.="   .green {color: white; background-color: green}\n";
$HTML_head.="   .red {color: white; background-color: red}\n";
$HTML_head.="   .blue {color: white; background-color: blue}\n";
$HTML_head.="   .purple {color: white; background-color: purple}\n";
$HTML_head.="-->\n";



$HTML_head.=".err:link {
	COLOR: #f06852;	
	font-weight: bold;
	
	text-decoration: none;
}
.err:visited {
	COLOR: #f06852;
	font-weight: bold;	
	
	text-decoration: none;
}

.err:active {
	COLOR: #72afd2;
	font-weight: bold; 
	
	text-decoration: none; 
}
 
.err:hover {
	COLOR: #000;
	font-weight: bold; 
	
	text-decoration: none;
}";
$HTML_head.=" .formselect {
background: none repeat scroll 0 0 #fff;
    border: 1px solid #dadada;
    border-radius: 0;
    box-shadow: 0 3px 3px #cacaca, 0 -1px #fff inset;
    font-size: 13px;
    line-height: 1;
    margin-left: 5px;
    padding: 5px;
}"; 

$HTML_head.=" .btnsubmit {
background: none repeat scroll 0 0 #001f3f;
    border: 1px solid #444444;
    border-radius: 3px;
    color: #fff;
    font-family: proxima-nova;
    font-size: 13px;
    font-weight: bold;
    height: 30px;
}";
$HTML_head.=" .leftside {
background-color: #e4f5f5;
   
}
.rightside {
background-color: #efefef;
  

}
.titletext {
font-size:17px; color:#36457E; font-family: 'Open Sans', sans-serif;
}
.titletext1 {
font-size:16px;color:#36457E; font-family: 'Open Sans', sans-serif;
font-weight:700;
}
pre{
word-break: normal;
}

"; 



$HTML_head.=" </STYLE>\n";


$HTML_head.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HTML_head.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HTML_head.="<TITLE>"._QXZ("$report_name")."</TITLE></HEAD><BODY BGCOLOR=#FFF2BF marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

	$short_header=1;

#	require("admin_header.php");
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap12.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap13.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap14.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap15.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap16.min.css\">";
echo "<link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap17.min.css\">";

$HTML_text.="<br><div class=\"row\"><div class=\"col-md-10\" style=\"margin-left: 120px;\"><TABLECELLPADDING=4 CELLSPACING=0 bgcolor=#fff><TR><TD>";

$HTML_text.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$HTML_text.= "<br> <font style='font-size:25px; margin-left:15px; color:#36457E;font-weight: 700;'>"._QXZ("$report_name")."</font><br><br>\n";
$HTML_text.="<TABLE class=\"table table-responsive dataTable\" style='background-color:#a5d26b; border: 5px solid #36457E;'><TR style='color:#36457E;'><TD VALIGN=TOP> "._QXZ("Dates").":<BR>";
$HTML_text.="<INPUT TYPE=hidden NAME=DB VALUE=\"$DB\">\n";
$HTML_text.="<INPUT TYPE=TEXT NAME=query_date SIZE=10 MAXLENGTH=10 VALUE=\"$query_date\" class='formselect'>";

$HTML_text.="<script language=\"JavaScript\">\n";
$HTML_text.="function openNewWindow(url)\n";
$HTML_text.="  {\n";
$HTML_text.="  window.open (url,\"\",'width=620,height=300,scrollbars=yes,menubar=yes,address=yes');\n";
$HTML_text.="  }\n";
$HTML_text.="var o_cal = new tcal ({\n";
$HTML_text.="	// form name\n";
$HTML_text.="	'formname': 'vicidial_report',\n";
$HTML_text.="	// input name\n";
$HTML_text.="	'controlname': 'query_date'\n";
$HTML_text.="});\n";
$HTML_text.="o_cal.a_tpl.yearscroll = false;\n";
$HTML_text.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$HTML_text.="</script>\n";

$HTML_text.=" "._QXZ("to")." <INPUT TYPE=TEXT NAME=end_date SIZE=10 MAXLENGTH=10 VALUE=\"$end_date\" class='formselect'>";

$HTML_text.="<script language=\"JavaScript\">\n";
$HTML_text.="var o_cal = new tcal ({\n";
$HTML_text.="	// form name\n";
$HTML_text.="	'formname': 'vicidial_report',\n";
$HTML_text.="	// input name\n";
$HTML_text.="	'controlname': 'end_date'\n";
$HTML_text.="});\n";
$HTML_text.="o_cal.a_tpl.yearscroll = false;\n";
$HTML_text.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$HTML_text.="</script>\n<br><br>";
$HTML_text.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> <br>\n";
$HTML_text.=" <a href=\"./admin.php?ADD=999999\">"._QXZ("REPORTS")."</a> </FONT>\n";

$HTML_text.="</TD><TD VALIGN=TOP> "._QXZ("Campaigns").":<BR>";
$HTML_text.="<SELECT SIZE=5 NAME=group[] multiple class='formselect'>\n";
if  (preg_match('/\-\-ALL\-\-/',$group_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- "._QXZ("ALL CAMPAIGNS")." --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- "._QXZ("ALL CAMPAIGNS")." --</option>\n";}
$o=0;
while ($campaigns_to_print > $o)
{
	if (preg_match("/$groups[$o]\|/i",$group_string)) {$HTML_text.="<option selected value=\"$groups[$o]\">$groups[$o]</option>\n";}
	  else {$HTML_text.="<option value=\"$groups[$o]\">$groups[$o]</option>\n";}
	$o++;
}
$HTML_text.="</SELECT>\n";
$HTML_text.="</TD><TD VALIGN=TOP>"._QXZ("User Groups").":<BR>";
$HTML_text.="<SELECT SIZE=5 NAME=user_group[] multiple class='formselect'>\n";

if  (preg_match('/\-\-ALL\-\-/',$user_group_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- "._QXZ("ALL USER GROUPS")." --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- "._QXZ("ALL USER GROUPS")." --</option>\n";}
$o=0;
while ($user_groups_to_print > $o)
	{
	if  (preg_match("/$user_groups[$o]\|/i",$user_group_string)) {$HTML_text.="<option selected value=\"$user_groups[$o]\">$user_groups[$o]</option>\n";}
	  else {$HTML_text.="<option value=\"$user_groups[$o]\">$user_groups[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT>\n";
$HTML_text.="</TD><TD VALIGN=TOP>"._QXZ("Users").": <BR>";
$HTML_text.="<SELECT SIZE=5 NAME=users[] multiple class='formselect'>\n";

if  (preg_match('/\-\-ALL\-\-/',$users_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- "._QXZ("ALL USERS")." --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- "._QXZ("ALL USERS")." --</option>\n";}
$o=0;
while ($users_to_print > $o)
	{
	if  (preg_match("/$user_list[$o]\|/i",$users_string)) {$HTML_text.="<option selected value=\"$user_list[$o]\">$user_list[$o] - $user_names[$o]</option>\n";}
	  else {$HTML_text.="<option value=\"$user_list[$o]\">$user_list[$o] - $user_names[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT>\n";
$HTML_text.="</TD><TD VALIGN=TOP>"._QXZ("Shift").":&nbsp;&nbsp;";
$HTML_text.="<SELECT SIZE=1 NAME=shift class='formselect'>\n";
$HTML_text.="<option selected value=\"$shift\">$shift</option>\n";
$HTML_text.="<option value=\"\">--</option>\n";
$HTML_text.="<option value=\"AM\">"._QXZ("AM")."</option>\n";
$HTML_text.="<option value=\"PM\">"._QXZ("PM")."</option>\n";
$HTML_text.="<option value=\"ALL\">"._QXZ("ALL")."</option>\n";
$HTML_text.="</SELECT><BR>\n";
$HTML_text.="<input type='checkbox' name='show_percentages' value='checked' $show_percentages>"._QXZ("Show %s")."<BR>\n";
$HTML_text.="<input type='checkbox' name='time_in_sec' value='checked' $time_in_sec>"._QXZ("Time in seconds")."<BR>\n";
$HTML_text.="<input type='checkbox' name='breakdown_by_date' value='checked' $breakdown_by_date>"._QXZ("Show date breakdown")."<BR>\n";
$HTML_text.="<input type='checkbox' name='search_archived_data' value='checked' $search_archived_data>"._QXZ("Search archived data")."<BR>\n";
$HTML_text.="<input type='checkbox' name='show_defunct_users' value='checked' $show_defunct_users>"._QXZ("Show defunct users")."\n";
$HTML_text.="<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='"._QXZ("SUBMIT")."' class='btn btn-success'> $NWB#agent_performance_detail$NWE\n";
//$HTML_text.="</TD><TD VALIGN=TOP>";
/*$HTML_text.=_QXZ("Display as").":<BR>";
$HTML_text.="<select name='report_display_type' class='formselect'>";
if ($report_display_type) {$HTML_text.="<option value='$report_display_type' selected>$report_display_type</option>";}
$HTML_text.="<option value='TEXT'>"._QXZ("TEXT")."</option><option value='HTML'>"._QXZ("HTML")."</option></select><BR><BR>\n";*/

$HTML_text.="</FONT>\n";
$HTML_text.="</TD></TR></TABLE>";

$HTML_text.="</FORM>\n\n";


$HTML_text.="<PRE style='border: 5px solid #36457E;'><FONT>\n";

if (!$group)
	{
	$HTML_text.="\n";
	$HTML_text.=_QXZ("PLEASE SELECT A CAMPAIGN AND DATE-TIME ABOVE AND CLICK SUBMIT")."\n";
	$HTML_text.=" "._QXZ("NOTE: stats taken from shift specified")."\n";
	}

else
{
if ($shift == 'AM') 
	{
	$time_BEGIN=$AM_shift_BEGIN;
	$time_END=$AM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "03:45:00";}   
	if (strlen($time_END) < 6) {$time_END = "15:14:59";}
	}
if ($shift == 'PM') 
	{
	$time_BEGIN=$PM_shift_BEGIN;
	$time_END=$PM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "15:15:00";}
	if (strlen($time_END) < 6) {$time_END = "23:15:00";}
	}
if ($shift == 'ALL') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
$query_date_BEGIN = "$query_date $time_BEGIN";   
$query_date_END = "$end_date $time_END";

$HTML_text.="<font class='titletext'>"._QXZ("Agent Performance Detail",47)."</font> $NOW_TIME\n";

$HTML_text.="<font class='titletext'>"._QXZ("Time range")."</font>: $query_date_BEGIN <font class='titletext'>"._QXZ("to")."</font> $query_date_END\n\n";
$HTML_text.="<font class='titletext1'>"._QXZ("AGENTS Details")." </font>\n\n";



$statuses='-';
$statusesTXT='';
$statusesHEAD='';

## Breakdown by date text
if ($breakdown_by_date) 
	{
	$BBD_header="------------+";
	$BBD_text=" | "._QXZ("CALL DATE",10); 
	$BBD_filler=" | ".sprintf("%-10s", " "); 
	$BBD_csv=_QXZ("CALL DATE",10)."\",\""; 
	$BBD_csv_filler="\",\""; 
	}
else 
	{
	$BBD_header="";
	$BBD_text="";
	$BBD_filler=""; 
	$BBD_csv="";
	$BBD_csv_filler="";
	}


$CSV_header="\""._QXZ("Agent Performance Detail",47)." $NOW_TIME\"\n";
$CSV_header.="\""._QXZ("Time range").": $query_date_BEGIN "._QXZ("to")." $query_date_END\"\n\n";
$CSV_header.="\"---------- "._QXZ("AGENTS Details")." -------------\"\n";
if ($show_percentages) {
	$CSV_header.='"'._QXZ("USER NAME").'","'._QXZ("ID").'","'._QXZ("CURRENT USER GROUP").'","'._QXZ("MOST RECENT USER GROUP").'","'.$BBD_csv._QXZ("CALLS").'","'._QXZ("TIME").'","'._QXZ("PAUSE").'","'._QXZ("PAUSE").' %","'._QXZ("PAUSAVG").'","'._QXZ("WAIT").'","'._QXZ("WAIT").' %","'._QXZ("WAITAVG").'","'._QXZ("TALK").'","'._QXZ("TALK").' %","'._QXZ("TALKAVG").'","'._QXZ("DISPO").'","'._QXZ("DISPO").' %","'._QXZ("DISPAVG").'","'._QXZ("DEAD").'","'._QXZ("DEAD").' %","'._QXZ("DEADAVG").'","'._QXZ("CUSTOMER").'","'._QXZ("CUSTOMER").' %","'._QXZ("CUSTAVG").'"';
} else {
	$CSV_header.='"'._QXZ("USER NAME").'","'._QXZ("ID").'","'._QXZ("CURRENT USER GROUP").'","'._QXZ("MOST RECENT USER GROUP").'","'._QXZ("LOGIN DATE").'","'._QXZ("LOGIN TIME").'","'._QXZ("LOGOUT DATE").'","'._QXZ("LOGOUT TIME").'","'.$BBD_csv._QXZ("CALLS").'","'._QXZ("TIME").'","'._QXZ("PAUSE").'","'._QXZ("PAUSAVG").'","'._QXZ("WAIT").'","'._QXZ("WAITAVG").'","'._QXZ("TALK").'","'._QXZ("TALKAVG").'","'._QXZ("DISPO").'","'._QXZ("DISPAVG").'","'._QXZ("DEAD").'","'._QXZ("DEADAVG").'","'._QXZ("CUSTOMER").'","'._QXZ("CUSTAVG").'"';
}


$statusesHTML='';
$statusesARY[0]='';
$j=0;
$users='-';
$usersARY[0]='';
$user_namesARY[0]='';
$k=0;

$recent_UG_stmt="SELECT max(agent_log_id), user from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 $group_SQL $user_group_agent_log_SQL $user_agent_log_SQL group by user";
if ($DB) {$HTML_text.="$recent_UG_stmt\n";}
$recent_UG_rslt=mysql_to_mysqli($recent_UG_stmt, $link);
while ($UG_row=mysqli_fetch_row($recent_UG_rslt)) {
	$agent_log_id=$UG_row[0];
	$al_stmt="SELECT user_group from ".$agent_log_table." where agent_log_id='$agent_log_id'";
	if ($DB) {$HTML_text.="$al_stmt\n";}
	$al_rslt=mysql_to_mysqli($al_stmt, $link);
	$Ugrp_row=mysqli_fetch_row($al_rslt);
	$recent_user_groups[$UG_row[1]]=$Ugrp_row[0];
}

$graph_stats=array();
$max_calls=1;
$max_time=1;
$max_pause=1;
$max_pauseavg=1;
$max_wait=1;
$max_waitavg=1;
$max_talk=1;
$max_talkavg=1;
$max_dispo=1;
$max_dispoavg=1;
$max_dead=1;
$max_deadavg=1;
$max_customer=1;
$max_customeravg=1;
$GRAPH="<BR><BR><a name='callstatsgraph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH2="<tr><th class='column_header grey_graph_cell' id='callstatsgraph1'><a href='#' onClick=\"DrawGraph('CALLS', '1'); return false;\">"._QXZ("CALLS")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph2'><a href='#' onClick=\"DrawGraph('TIME', '2'); return false;\">"._QXZ("TIME")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph3'><a href='#' onClick=\"DrawGraph('PAUSE', '3'); return false;\">"._QXZ("PAUSE")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph4'><a href='#' onClick=\"DrawGraph('PAUSEAVG', '4'); return false;\">"._QXZ("PAUSE AVG")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph5'><a href='#' onClick=\"DrawGraph('WAIT', '5'); return false;\">"._QXZ("WAIT")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph6'><a href='#' onClick=\"DrawGraph('WAITAVG', '6'); return false;\">"._QXZ("WAIT AVG")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph7'><a href='#' onClick=\"DrawGraph('TALK', '7'); return false;\">"._QXZ("TALK")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph8'><a href='#' onClick=\"DrawGraph('TALKAVG', '8'); return false;\">"._QXZ("TALK AVG")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph9'><a href='#' onClick=\"DrawGraph('DISPO', '9'); return false;\">"._QXZ("DISPO")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph10'><a href='#' onClick=\"DrawGraph('DISPOAVG', '10'); return false;\">"._QXZ("DISPO AVG")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph11'><a href='#' onClick=\"DrawGraph('DEAD', '11'); return false;\">"._QXZ("DEAD")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph12'><a href='#' onClick=\"DrawGraph('DEADAVG', '12'); return false;\">"._QXZ("DEAD AVG")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph13'><a href='#' onClick=\"DrawGraph('CUST', '13'); return false;\">"._QXZ("CUST")."</a></th><th class='column_header grey_graph_cell' id='callstatsgraph14'><a href='#' onClick=\"DrawGraph('CUSTAVG', '14'); return false;\">"._QXZ("CUST AVG")."</a></th>";
$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>"._QXZ("CALL STATS BREAKDOWN").": ("._QXZ("Statistics related to handling of calls only").")</caption><tr><th class='thgraph' scope='col'>"._QXZ("USER")."</th>";
$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("CALLS")."</th></tr>";
$TIME_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("TIME")."</th></tr>";
$PAUSE_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("PAUSE")."</th></tr>";
$PAUSEAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("PAUSE AVG")."</th></tr>";
$WAIT_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("WAIT")."</th></tr>";
$WAITAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("WAIT AVG")."</th></tr>";
$TALK_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("TALK")."</th></tr>";
$TALKAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("TALK AVG")."</th></tr>";
$DISPO_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("DISPO")."</th></tr>";
$DISPOAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("DISPO AVG")."</th></tr>";
$DEAD_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("DEAD")."</th></tr>";
$DEADAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("DEAD AVG")."</th></tr>";
$CUST_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("CUST")."</th></tr>";
$CUSTAVG_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("CUST AVG")."</th></tr>";

	if ($show_defunct_users=="checked")
		{
		$user_stmt="SELECT distinct '' as full_name, user from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' $group_SQL $user_agent_log_SQL order by user asc";
		}
	else
		{
		$user_stmt="SELECT distinct full_name,vicidial_users.user,vicidial_users.user_group from vicidial_users,".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and vicidial_users.user=".$agent_log_table.".user $group_SQL $user_group_SQL order by full_name asc";
		}
		if ($DB) {echo "$user_stmt\n";}
	$user_rslt=mysql_to_mysqli($user_stmt, $link);
	$q=0;
	while($q<mysqli_num_rows($user_rslt)) 
		{
		$user_row=mysqli_fetch_row($user_rslt);

		if ($show_defunct_users=="checked")
			{
			$defunct_user_stmt="SELECT full_name, user_group from vicidial_users where user='$user_row[1]'";
			$defunct_user_rslt=mysql_to_mysqli($defunct_user_stmt, $link);
			if (mysqli_num_rows($defunct_user_rslt)>0) 
				{
				$defunct_user_row=mysqli_fetch_row($defunct_user_rslt);
				$full_name_val=$defunct_user_row[0];
				$user_group_val=$defunct_user_row[1];
				} 
			else 
				{
				$full_name_val=$user_row[1];
				$user_group_val="** NONE **";
				}
			}
		else
			{
			$full_name_val =	$user_row[0];
			$user_group_val =	$user_row[2];
			}

		$full_name[$q] =	$full_name_val;
		$user[$q] =			$user_row[1];
		$user_group[$q] =	$user_group_val;

		if (!preg_match("/\-$user[$q]\-/i", $users))
			{
			$users .= "$user[$q]-";
			$usersARY[$k] = $user[$q];
			$user_namesARY[$k] = $full_name[$q];
			$user_groupsARY[$k] = $user_group[$q];
			$k++;
			}
		$q++;
		}


if ($show_defunct_users=="checked")
	{
	$stat_stmt="SELECT distinct status from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 $group_SQL $user_agent_log_SQL $user_SQL order by status asc";
	}
else 
	{
	$stat_stmt="SELECT distinct status from vicidial_users,".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and vicidial_users.user=".$agent_log_table.".user and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 $group_SQL $user_group_SQL $user_SQL order by status asc";
	}
$stat_rslt=mysql_to_mysqli($stat_stmt, $link);
	if ($DB) {$HTML_text.="$stat_stmt\n";}
$q=0;
$userTOTcalls=array();

while ($stat_row=mysqli_fetch_row($stat_rslt)) {
	$current_status=$stat_row[0];

	if ($show_defunct_users=="checked")
		{
		$stmt="SELECT count(*) as calls,sum(talk_sec) as talk,'' as full_name,user,sum(pause_sec),sum(wait_sec),sum(dispo_sec),status,sum(dead_sec), '' as user_group from ".$agent_log_table.",date(event_time) as call_date where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 and status='$current_status' $group_SQL $user_agent_log_SQL $user_SQL group by user,full_name,user_group,status,call_date order by full_name,user,status desc limit 500000;";
		}
	else
		{
		$stmt="SELECT count(*) as calls,sum(talk_sec) as talk,full_name,vicidial_users.user,sum(pause_sec),sum(wait_sec),sum(dispo_sec),status,sum(dead_sec), vicidial_users.user_group,date(event_time) as call_date from vicidial_users,".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and vicidial_users.user=".$agent_log_table.".user and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 and status='$current_status' $group_SQL $user_group_SQL $user_SQL group by user,full_name,user_group,status order by full_name,user,status desc limit 500000;";
		}
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {$HTML_text.="$stmt\n";} 
	$rows_to_print+=mysqli_num_rows($rslt);

	$stat_rows_to_print = mysqli_num_rows($rslt);

	$i=0;
	while ($i < $stat_rows_to_print)
		{
		$row=mysqli_fetch_row($rslt);
	#	$row[0] = ($row[0] - 1);	# subtract 1 for login/logout event compensation
		
		if ($show_defunct_users=="checked")
			{
			$defunct_user_stmt="SELECT full_name,user_group from vicidial_users where user='$row[3]'";
	if ($DB) {$HTML_text.="$defunct_user_stmt\n";}
			$defunct_user_rslt=mysql_to_mysqli($defunct_user_stmt, $link);
			if (mysqli_num_rows($defunct_user_rslt)>0) 
				{
				$defunct_user_row=mysqli_fetch_row($defunct_user_rslt);
				$full_name_val=$defunct_user_row[0];
				$user_group_val=$defunct_user_row[1];
				} 
			else 
				{
				$full_name_val=$row[3];
				$user_group_val="**NONE**";
				}
			}
		else 
			{
			$full_name_val=$row[2];
			$user_group_val=$row[9];
			}

	if ($DB) {$HTML_text.="$full_name_val - $user_group_val\n";}
		$calls[$q] =		$row[0];
		$talk_sec[$q] =		$row[1];
		$full_name[$q] =	$full_name_val;
		$user[$q] =			$row[3];
		$pause_sec[$q] =	$row[4];
		$wait_sec[$q] =		$row[5];
		$dispo_sec[$q] =	$row[6];
		$status[$q] =		strtoupper($row[7]);
		$dead_sec[$q] =		$row[8];
		$user_group[$q] =	$user_group_val;
		$call_date[$q] =		$row[10];
		$customer_sec[$q] =	($talk_sec[$q] - $dead_sec[$q]);

		if (strlen($status[$q])>0) {$userTOTcalls[$row[3]]+=$row[0];}

		$max_varname="max_".$status[$q];
		$$max_varname=1;
		
		if ($customer_sec[$q] < 1)
			{$customer_sec[$q]=0;}
		if ( (!preg_match("/\-$status[$q]\-/i", $statuses)) and (strlen($status[$q])>0) )
			{
			$statusesTXT = sprintf("%8s", $status[$q]);

			$statusesHEAD .= "----------+";
			$statusesHTML .= " $statusesTXT |";
			$CSV_header.=",\"$status[$q]\"";

			if($show_percentages) {
				$statusesHEAD .= "------------+";
				$statusesHTML .= " $statusesTXT % |";
				$CSV_header.=",\"$status[$q] %\"";
			}

			$statuses .= "$status[$q]-";
			$statusesARY[$j] = $status[$q];
			$j++;
			}
		if (!preg_match("/\-$user[$q]\-/i", $users))
			{
			$users .= "$user[$q]-";
			$usersARY[$k] = $user[$q];
			$user_namesARY[$k] = $full_name[$q];
			$user_groupsARY[$k] = $user_group[$q];
			$k++;
			}

		$i++;
		$q++;
		}
}
if ($DB) {print "<BR>\n****$users****<BR>\n";}
if ($DB) {print_r($user_groupsARY[$k]);}
$CSV_header.="\n";
$CSV_lines='';

$ASCII_text.="<font class='titletext1'>"._QXZ("CALL STATS BREAKDOWN").": ("._QXZ("Statistics related to handling of calls only").")    </font> <a href=\"$LINKbase&stage=$stage&file_download=1\">["._QXZ("DOWNLOAD")."]</a>\n";
	$ASCII_text.="<table class='table table-striped table-bordered'><tbody>";
if ($show_percentages) {
//	$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+$statusesHEAD\n";
	
	
	
	
	$ASCII_text.="<tr><td> <a href=\"$LINKbase\">"._QXZ("USER NAME",15)."</a> </td><td> <a href=\"$LINKbase&stage=ID\">"._QXZ("ID",8)."</a> </td><td> "._QXZ("CURRENT USER GROUP",20)." </td><td> "._QXZ("MOST RECENT USER GRP",20).$BBD_text." </td><td> <a href=\"$LINKbase&stage=LEADS\">"._QXZ("CALLS",6)."</a> </td><td> <a href=\"$LINKbase&stage=TIME\">"._QXZ("TIME",9)."</a> </td><td> "._QXZ("PAUSE",8)." </td><td> "._QXZ("PAUSE",8)." % </td><td>"._QXZ("PAUS AVG",7)." </td><td> "._QXZ("WAIT",8)." </td><td> "._QXZ("WAIT",8)." % </td><td>"._QXZ("WAIT AVG",7)." </td><td> "._QXZ("TALK",8)." </td><td> "._QXZ("TALK",8)." % </td><td>"._QXZ("TALK AVG",7)." </td><td> "._QXZ("DISPO",8)." </td><td> "._QXZ("DISPO",8)." % </td><td>"._QXZ("DISP AVG",7)." </td><td> "._QXZ("DEAD",7)." </td><td> "._QXZ("DEAD",8)." % </td><td>"._QXZ("DEAD AVG",7)." </td><td> "._QXZ("CUSTOMER",8)." </td><td> "._QXZ("CUSTOMER",8)." % </td><td>"._QXZ("CUST AVG",7)." </td><td>$statusesHTML</td></tr>";
	//$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+$statusesHEAD\n";
	
	
} else {
//	$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+$statusesHEAD\n";
	//$ASCII_text.="<table class='table table-striped table-bordered'><tbody>";
	$ASCII_text.="<tr><td> <a href=\"$LINKbase\">"._QXZ("USER NAME",15)."</a> </td><td> <a href=\"$LINKbase&stage=ID\">"._QXZ("ID",8)."</a> </td><td> "._QXZ("CURRENT USER GROUP",20)." </td><td> "._QXZ("MOST RECENT USER GRP",20).$BBD_text." </td><td>"._QXZ("LOGIN DATE",6)."</td><td>"._QXZ("LOGIN TIME",6)."</td><td>"._QXZ("LOGOUT DATE",6)."</td><td>"._QXZ("LOGOUT TIME",6)."</td><td> <a href=\"$LINKbase&stage=LEADS\">"._QXZ("CALLS",6)."</a> </td><td> <a href=\"$LINKbase&stage=TIME\">"._QXZ("TIME",9)."</a> </td><td> "._QXZ("PAUSE",8)." </td><td>"._QXZ("PAUS AVG",7)." </td><td> "._QXZ("WAIT",8)." </td><td>"._QXZ("WAIT AVG",7)." </td><td> "._QXZ("TALK",8)." </td><td>"._QXZ("TALK AVG",7)." </td><td> "._QXZ("DISPO",8)." </td><td>"._QXZ("DISP AVG",7)." </td><td> "._QXZ("DEAD",8)." </td><td>"._QXZ("DEAD AVG",7)." </td><td> "._QXZ("CUSTOMER",8)." </td><td>"._QXZ("CUST AVG",7)." </td><td>$statusesHTML </td></tr>";
	//$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+$statusesHEAD\n";
}


### BEGIN loop through each user ###
$m=0;
while ($m < $k)
	{
	$Suser=$usersARY[$m];

	if ($breakdown_by_date) 
		{
		$Toutput_sub="";
		$CSV_lines_sub="";

		$SstatusesHTML='';
		$CSVstatuses='';

		$Stalk_sec_pct=0;
		$Spause_sec_pct=0;
		$Swait_sec_pct=0;
		$Sdispo_sec_pct=0;
		$Sdead_sec_pct=0;

		$date_stmt="select distinct date(event_time) as call_date from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and user='$Suser' and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 $group_SQL $user_group_agent_log_SQL $user_agent_log_SQL order by call_date asc limit 500000;";
		if ($DB) {$ASCII_text.=$date_stmt."\n";}
		$date_rslt=mysql_to_mysqli($date_stmt, $link);
		while($date_row=mysqli_fetch_row($date_rslt)) 
			{
			$call_dateX=$date_row[0];

			$cd_stmt="SELECT count(*) as calls,sum(talk_sec) as talk,'' as full_name,user,sum(pause_sec),sum(wait_sec),sum(dispo_sec),status,sum(dead_sec), '' as user_group from ".$agent_log_table." where date(event_time)='$call_dateX' and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 and user='$Suser' $group_SQL $user_agent_log_SQL $user_SQL group by user,full_name,user_group,status order by full_name,user,status desc limit 500000;";
			$cd_rslt=mysql_to_mysqli($cd_stmt, $link); 
			if ($DB) {$ASCII_text.=$cd_stmt."\n";}

			$x=0;
			while ($cd_row=mysqli_fetch_row($cd_rslt)) 
				{
				$calls_sub[$x] =		$cd_row[0];
				$talk_sec_sub[$x] =		$cd_row[1];
				$user_sub[$x] =			$cd_row[3];
				$pause_sec_sub[$x] =	$cd_row[4];
				$wait_sec_sub[$x] =		$cd_row[5];
				$dispo_sec_sub[$x] =	$cd_row[6];
				$status_sub[$x] =		strtoupper($cd_row[7]);
				$dead_sec_sub[$x] =		$cd_row[8];
				$customer_sec_sub[$x] =	($talk_sec_sub[$x] - $dead_sec_sub[$x]);
				$x++;
				}
			

			$Stime=0;
			$Scalls=0;
			$Stalk_sec=0;
			$Spause_sec=0;
			$Swait_sec=0;
			$Sdispo_sec=0;
			$Sdead_sec=0;
			$Scustomer_sec=0;
			$o=0;
			$SstatusesHTML='';
			$CSVstatuses='';
			while ($o < $j)
				{
				$Sstatus=$statusesARY[$o];
				$Scall_date=$call_date[$o];
				$SstatusTXT='';

				$i=0; $status_found=0;
				while ($i < $x)
					{
					if ($Sstatus=="$status_sub[$i]")
						{
						$Scalls =		($Scalls + $calls_sub[$i]);
						$Stalk_sec =	($Stalk_sec + $talk_sec_sub[$i]);
						$Spause_sec =	($Spause_sec + $pause_sec_sub[$i]);
						$Swait_sec =	($Swait_sec + $wait_sec_sub[$i]);
						$Sdispo_sec =	($Sdispo_sec + $dispo_sec_sub[$i]);
						$Sdead_sec =	($Sdead_sec + $dead_sec_sub[$i]);
						$Scustomer_sec =	($Scustomer_sec + $customer_sec_sub[$i]);
						$SstatusTXT = sprintf("%8s", $calls_sub[$i]);
						$SstatusesHTML .= " $SstatusTXT |";

						$CSVstatuses.=",\"$calls_sub[$i]\"";

						if ($show_percentages) 
							{
							$SstatusTXT_pct=sprintf("%8s", sprintf("%0.2f", MathZDC(100*$calls[$i], $userTOTcalls[$Suser])));
							$SstatusesHTML .= " $SstatusTXT_pct % |";
							$CSVstatuses.=",\"$SstatusTXT_pct %\"";
							}

						$status_found++;
						}
					$i++;
					}
				if ($status_found < 1)
					{
					$SstatusesHTML .= "        0 |";
					$CSVstatuses.=",\"0\"";
					if ($show_percentages) 
						{
						$SstatusesHTML .= "     0.00 % |";
						$CSVstatuses.=",\"0.00 %\"";
						}
					}
				### END loop through each stat line ###
				$o++;
				}

			$Stime = ($Stalk_sec + $Spause_sec + $Swait_sec + $Sdispo_sec);
			
			$Stalk_avg = MathZDC($Stalk_sec, $Scalls);
			$Spause_avg = MathZDC($Spause_sec, $Scalls);
			$Swait_avg = MathZDC($Swait_sec, $Scalls);
			$Sdispo_avg = MathZDC($Sdispo_sec, $Scalls);
			$Sdead_avg = MathZDC($Sdead_sec, $Scalls);
			$Scustomer_avg = MathZDC($Scustomer_sec, $Scalls);

			$RAWuser = $Suser;
			$RAWcalls = $Scalls;
			$Scalls =	sprintf("%6s", $Scalls);
			$Sfull_nameRAW = $Sfull_name;
			$SuserRAW = $Suser;

			$pfUSERtime_MS =			sec_convert($Stime,$TIME_H_agentperfdetail); 
			$pfUSERtotTALK_MS =			sec_convert($Stalk_sec,$TIME_H_agentperfdetail); 
			$pfUSERtotTALK_MS_pct =		sprintf("%0.2f", MathZDC(100*$Stalk_sec, $Stime));
			$pfUSERavgTALK_MS =			sec_convert($Stalk_avg,$TIME_M_agentperfdetail); 
			$USERtotPAUSE_MS =			sec_convert($Spause_sec,$TIME_H_agentperfdetail); 
			$pfUSERtotPAUSE_MS_pct =	sprintf("%0.2f", MathZDC(100*$Spause_sec, $Stime));
			$USERavgPAUSE_MS =			sec_convert($Spause_avg,$TIME_M_agentperfdetail); 
			$USERtotWAIT_MS =			sec_convert($Swait_sec,$TIME_H_agentperfdetail); 
			$pfUSERtotWAIT_MS_pct =		sprintf("%0.2f", MathZDC(100*$Swait_sec, $Stime));
			$USERavgWAIT_MS =			sec_convert($Swait_avg,$TIME_M_agentperfdetail); 
			$USERtotDISPO_MS =			sec_convert($Sdispo_sec,$TIME_H_agentperfdetail); 
			$pfUSERtotDISPO_MS_pct =	sprintf("%0.2f", MathZDC(100*$Sdispo_sec, $Stime));
			$USERavgDISPO_MS =			sec_convert($Sdispo_avg,$TIME_M_agentperfdetail); 
			$USERtotDEAD_MS =			sec_convert($Sdead_sec,$TIME_H_agentperfdetail); 
			$pfUSERtotDEAD_MS_pct =		sprintf("%0.2f", MathZDC(100*$Sdead_sec, $Stime));
			$USERavgDEAD_MS =			sec_convert($Sdead_avg,$TIME_M_agentperfdetail); 
			$USERtotCUSTOMER_MS	=		sec_convert($Scustomer_sec,$TIME_H_agentperfdetail);
			$pfUSERtotCUSTOMER_MS_pct =	sprintf("%0.2f", MathZDC(100*$Scustomer_sec, $Stime));
			$USERavgCUSTOMER_MS =		sec_convert($Scustomer_avg,$TIME_M_agentperfdetail); 

			$pfUSERtime_MS =			sprintf("%9s", $pfUSERtime_MS);
			$pfUSERtotTALK_MS =			sprintf("%8s", $pfUSERtotTALK_MS);
			$pfUSERtotTALK_MS_pct =		sprintf("%8s", $pfUSERtotTALK_MS_pct);
			$pfUSERavgTALK_MS =			sprintf("%6s", $pfUSERavgTALK_MS);
			$pfUSERtotPAUSE_MS =		sprintf("%8s", $USERtotPAUSE_MS);
			$pfUSERtotPAUSE_MS_pct =	sprintf("%8s", $pfUSERtotPAUSE_MS_pct);
			$pfUSERavgPAUSE_MS =		sprintf("%6s", $USERavgPAUSE_MS);
			$pfUSERtotWAIT_MS =			sprintf("%8s", $USERtotWAIT_MS);
			$pfUSERtotWAIT_MS_pct =		sprintf("%8s", $pfUSERtotWAIT_MS_pct);
			$pfUSERavgWAIT_MS =			sprintf("%6s", $USERavgWAIT_MS);
			$pfUSERtotDISPO_MS =		sprintf("%8s", $USERtotDISPO_MS);
			$pfUSERtotDISPO_MS_pct =	sprintf("%8s", $pfUSERtotDISPO_MS_pct);
			$pfUSERavgDISPO_MS =		sprintf("%6s", $USERavgDISPO_MS);
			$pfUSERtotDEAD_MS =			sprintf("%8s", $USERtotDEAD_MS);
			$pfUSERtotDEAD_MS_pct =		sprintf("%8s", $pfUSERtotDEAD_MS_pct);
			$pfUSERavgDEAD_MS =			sprintf("%6s", $USERavgDEAD_MS);
			$pfUSERtotCUSTOMER_MS =		sprintf("%8s", $USERtotCUSTOMER_MS);
			$pfUSERtotCUSTOMER_MS_pct =	sprintf("%8s", $pfUSERtotCUSTOMER_MS_pct);
			$pfUSERavgCUSTOMER_MS =		sprintf("%6s", $USERavgCUSTOMER_MS);
			$PAUSEtotal[$m] = $pfUSERtotPAUSE_MS;

			if ($non_latin < 1)
				{
				$Scall_date=	sprintf("%-10s", $call_dateX);
				$Sfull_name=	sprintf("%-15s", " "); 
				$Suser_group=	sprintf("%-20s", " "); 
				$Slast_user_group=	sprintf("%-20s", " "); 
				$Suser_sub =		sprintf("%-8s", " ");
				}
			else
				{	
				$Scall_date=	sprintf("%-10s", $call_dateX);
				$Sfull_name=	sprintf("%-45s", " "); 
				$Suser_group=	sprintf("%-60s", " "); 
				$Slast_user_group=	sprintf("%-60s", " "); 
				$Suser_sub =	sprintf("%-24s", " ");
				}
				
				
					// ############ login time ##########
				$SuserName_ = trim($Suser);
				$sel_login_time = "select DATE(event_date) as login_date, TIME(event_date) as logintime from vicidial_user_log where event_date >='$Scall_date 00:00:00' and event_date <= '$Scall_date 23:59:59' and user LIKE '$SuserName_%' and event='LOGIN' ";
				//echo $sel_login_time."<br>";
				$res_login_time = mysqli_query($link,$sel_login_time);
				$row_login_time = mysqli_fetch_array($res_login_time);
				$logindt1 = $row_login_time[0];
				$loginTime1 = $row_login_time[1];
				// ############ login time ##########

				// ############ logout time ##########
				$sel_logout_time = "select DATE(event_date) as logout_date, TIME(event_date) as logouttime from vicidial_user_log where event_date >='$Scall_date 00:00:00' and event_date <= '$Scall_date 23:59:59' and user LIKE '$SuserName_%' and event='LOGOUT' ";
				$res_logout_time = mysqli_query($link,$sel_logout_time);
				//$row_logout_time = mysqli_fetch_array($res_logout_time);
				//$logoutTime = $row_logout_time[0];

				while($row_logout_time = mysqli_fetch_array($res_logout_time))
				{
					$logoutdt1 = $row_logout_time[0];
					$logoutTime1 = $row_logout_time[1];
					
				}
				// ############ logout time ##########
				

			if ($show_percentages) {
				$Toutput_sub .= "<tr><td> $Sfull_name </td><td> $Suser_sub </td><td> $Suser_group </td><td> $Slast_user_group </td><td> $Scall_date </td><td> $Scalls </td><td> $pfUSERtime_MS </td><td> $pfUSERtotPAUSE_MS </td><td> $pfUSERtotPAUSE_MS_pct % </td><td> $pfUSERavgPAUSE_MS </td><td> $pfUSERtotWAIT_MS </td><td> $pfUSERtotWAIT_MS_pct % </td><td> $pfUSERavgWAIT_MS </td><td> $pfUSERtotTALK_MS </td><td> $pfUSERtotTALK_MS_pct % </td><td> $pfUSERavgTALK_MS </td><td> $pfUSERtotDISPO_MS </td><td> $pfUSERtotDISPO_MS_pct % </td><td> $pfUSERavgDISPO_MS </td><td> $pfUSERtotDEAD_MS </td><td> $pfUSERtotDEAD_MS_pct % </td><td> $pfUSERavgDEAD_MS </td><td> $pfUSERtotCUSTOMER_MS </td><td>$pfUSERtotCUSTOMER_MS_pct % </td><td> $pfUSERavgCUSTOMER_MS </td><td>$SstatusesHTML</td></tr>";
			} else {
				$Toutput_sub .= "<tr><td> $Sfull_name </td><td> $Suser_sub </td><td> $Suser_group </td><td> $Slast_user_group </td><td>$logindt1</td><td>$loginTime1 </td><td>$logoutdt1</td><td> $logoutTime1 </td><td> $Scalls </td><td> $pfUSERtime_MS </td><td> $pfUSERtotPAUSE_MS </td><td> $pfUSERavgPAUSE_MS </td><td> $pfUSERtotWAIT_MS </td><td> $pfUSERavgWAIT_MS </td><td> $pfUSERtotTALK_MS </td><td> $pfUSERavgTALK_MS </td><td> $pfUSERtotDISPO_MS </td><td> $pfUSERavgDISPO_MS </td><td> $pfUSERtotDEAD_MS </td><td> $pfUSERavgDEAD_MS </td><td> $pfUSERtotCUSTOMER_MS </td><td> $pfUSERavgCUSTOMER_MS </td><td>$SstatusesHTML</td></tr>";
			}


			$CSV_lines_sub.="\"\",";
			if ($show_percentages) {
				$CSV_lines_sub.=preg_replace('/\s/', '', "\"\",\"\",\"\",\"$logindt1\",\"$loginTime1\",\"$logoutdt1\",\"$logoutTime1\",\"$Scall_date\",\"$Scalls\",\"$pfUSERtime_MS\",\"$pfUSERtotPAUSE_MS\",\"$pfUSERtotPAUSE_MS_pct\",\"$pfUSERavgPAUSE_MS\",\"$pfUSERtotWAIT_MS\",\"$pfUSERtotWAIT_MS_pct\",\"$pfUSERavgWAIT_MS\",\"$pfUSERtotTALK_MS\",\"$pfUSERtotTALK_MS_pct\",\"$pfUSERavgTALK_MS\",\"$pfUSERtotDISPO_MS\",\"$pfUSERtotDISPO_MS_pct\",\"$pfUSERavgDISPO_MS\",\"$pfUSERtotDEAD_MS\",\"$pfUSERtotDEAD_MS_pct\",\"$pfUSERavgDEAD_MS\",\"$pfUSERtotCUSTOMER_MS\",\"$pfUSERtotCUSTOMER_MS_pct\",\"$pfUSERavgCUSTOMER_MS\"$CSVstatuses");
			} else {
				$CSV_lines_sub.=preg_replace('/\s/', '', "\"\",\"\",\"\",\"$logindt1\",\"$loginTime1\",\"$logoutdt1\",\"$logoutTime1\",\"$Scalls\",\"$pfUSERtime_MS\",\"$pfUSERtotPAUSE_MS\",\"$pfUSERavgPAUSE_MS\",\"$pfUSERtotWAIT_MS\",\"$pfUSERavgWAIT_MS\",\"$pfUSERtotTALK_MS\",\"$pfUSERavgTALK_MS\",\"$pfUSERtotDISPO_MS\",\"$pfUSERavgDISPO_MS\",\"$pfUSERtotDEAD_MS\",\"$pfUSERavgDEAD_MS\",\"$pfUSERtotCUSTOMER_MS\",\"$pfUSERavgCUSTOMER_MS\"$CSVstatuses");
			}
			$CSV_lines_sub.="\n";

			}
		if ($show_percentages) 
			{
		//	$Toutput_sub.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+$statusesHEAD\n";
			} 
		else 
			{
		//	$Toutput_sub.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+$statusesHEAD\n";
			}
		}

	$Slast_user_group=$recent_user_groups[$Suser];

	$Sfull_name=$user_namesARY[$m];
	$Suser_group=$user_groupsARY[$m];
	$Stime=0;
	$Scalls=0;
	$Stalk_sec=0;
	$Spause_sec=0;
	$Swait_sec=0;
	$Sdispo_sec=0;
	$Sdead_sec=0;
	$Scustomer_sec=0;
	$SstatusesHTML='';
	$CSVstatuses='';

	$Stalk_sec_pct=0;
	$Spause_sec_pct=0;
	$Swait_sec_pct=0;
	$Sdispo_sec_pct=0;
	$Sdead_sec_pct=0;

	### BEGIN loop through each status ###
	$n=0;
	while ($n < $j)
		{
		$Sstatus=$statusesARY[$n];
		$SstatusTXT='';

		$varname=$Sstatus."_graph";
		$$varname=$graph_header."<th class='thgraph' scope='col'>$Sstatus</th></tr>";
		$max_varname="max_".$Sstatus;
		### BEGIN loop through each stat line ###
		$i=0; $status_found=0;
		while ($i < $rows_to_print)
			{
			if ( ($Suser=="$user[$i]") and ($Sstatus=="$status[$i]") )
				{
				$Scalls =		($Scalls + $calls[$i]);
				$Stalk_sec =	($Stalk_sec + $talk_sec[$i]);
				$Spause_sec =	($Spause_sec + $pause_sec[$i]);
				$Swait_sec =	($Swait_sec + $wait_sec[$i]);
				$Sdispo_sec =	($Sdispo_sec + $dispo_sec[$i]);
				$Sdead_sec =	($Sdead_sec + $dead_sec[$i]);
				$Scustomer_sec =	($Scustomer_sec + $customer_sec[$i]);
				$SstatusTXT = sprintf("%8s", $calls[$i]);
				$SstatusesHTML .= " $SstatusTXT |";

				if ($calls[$i]>$$max_varname) {$$max_varname=$calls[$i];}
				$graph_stats[$m][(15+$n)]=($calls[$i]+0);					
				
				$CSVstatuses.=",\"$calls[$i]\"";

				if ($show_percentages) {
					$SstatusTXT_pct=sprintf("%8s", sprintf("%0.2f", MathZDC(100*$calls[$i], $userTOTcalls[$Suser])));
					$SstatusesHTML .= " $SstatusTXT_pct % |";
					$CSVstatuses.=",\"$SstatusTXT_pct %\"";
				}

				$status_found++;
				}
			$i++;
			}
		if ($status_found < 1)
			{
			$SstatusesHTML .= "        0 |";
			$CSVstatuses.=",\"0\"";
			if ($show_percentages) {
				$SstatusesHTML .= "     0.00 % |";
				$CSVstatuses.=",\"0.00 %\"";
			}
			$graph_stats[$m][(15+$n)]=0;					
			}
		### END loop through each stat line ###
		$n++;
		}
	### END loop through each status ###
	$Stime = ($Stalk_sec + $Spause_sec + $Swait_sec + $Sdispo_sec);
	$TOTcalls=($TOTcalls + $Scalls);
	$TOTtime=($TOTtime + $Stime);
	$TOTtotTALK=($TOTtotTALK + $Stalk_sec);
	$TOTtotWAIT=($TOTtotWAIT + $Swait_sec);
	$TOTtotPAUSE=($TOTtotPAUSE + $Spause_sec);
	$TOTtotDISPO=($TOTtotDISPO + $Sdispo_sec);
	$TOTtotDEAD=($TOTtotDEAD + $Sdead_sec);
	$TOTtotCUSTOMER=($TOTtotCUSTOMER + $Scustomer_sec);
	$Stime = ($Stalk_sec + $Spause_sec + $Swait_sec + $Sdispo_sec);
	
	$Stalk_avg = MathZDC($Stalk_sec, $Scalls);
	$Spause_avg = MathZDC($Spause_sec, $Scalls);
	$Swait_avg = MathZDC($Swait_sec, $Scalls);
	$Sdispo_avg = MathZDC($Sdispo_sec, $Scalls);
	$Sdead_avg = MathZDC($Sdead_sec, $Scalls);
	$Scustomer_avg = MathZDC($Scustomer_sec, $Scalls);

	$RAWuser = $Suser;
	$RAWcalls = $Scalls;
	$Scalls =	sprintf("%6s", $Scalls);
	$Sfull_nameRAW = $Sfull_name;
	$SuserRAW = $Suser;

	if ($non_latin < 1)
		{
		$Scall_date=	sprintf("%-10s", " ");
		$Sfull_name=	sprintf("%-15s", $Sfull_name); 
		while(strlen($Sfull_name)>15) {$Sfull_name = substr("$Sfull_name", 0, -1);}
		$Suser_group=	sprintf("%-20s", $Suser_group); 
		while(strlen($Suser_group)>20) {$Suser_group = substr("$Suser_group", 0, -1);}
		$Slast_user_group=	sprintf("%-20s", $Slast_user_group); 
		while(strlen($Slast_user_group)>20) {$Slast_user_group = substr("$Slast_user_group", 0, -1);}
		$Suser =		sprintf("%-8s", $Suser);
		while(strlen($Suser)>8) {$Suser = substr("$Suser", 0, -1);}
		}
	else
		{	
		$Scall_date=	sprintf("%-10s", " ");
		$Sfull_name=	sprintf("%-45s", $Sfull_name); 
		while(mb_strlen($Sfull_name,'utf-8')>15) {$Sfull_name = mb_substr("$Sfull_name", 0, -1,'utf-8');}
		$Suser_group=	sprintf("%-60s", $Suser_group); 
		while(mb_strlen($Suser_group,'utf-8')>20) {$Suser_group = mb_substr("$Suser_group", 0, -1,'utf-8');}
		$Slast_user_group=	sprintf("%-60s", $Slast_user_group); 
		while(mb_strlen($Slast_user_group,'utf-8')>20) {$Slast_user_group = mb_substr("$Slast_user_group", 0, -1,'utf-8');}
		$Suser =	sprintf("%-24s", $Suser);
		while(mb_strlen($Suser,'utf-8')>8) {$Suser = mb_substr("$Suser", 0, -1,'utf-8');}
		}

	if (trim($Scalls)>$max_calls) {$max_calls=trim($Scalls);}
	if (trim($Stime)>$max_time) {$max_time=trim($Stime);}
	if (trim($Spause_sec)>$max_pause) {$max_pause=trim($Spause_sec);}
	if (trim($Spause_avg)>$max_pauseavg) {$max_pauseavg=trim($Spause_avg);}
	if (trim($Swait_sec)>$max_wait) {$max_wait=trim($Swait_sec);}
	if (trim($Swait_avg)>$max_waitavg) {$max_waitavg=trim($Swait_avg);}
	if (trim($Stalk_sec)>$max_talk) {$max_talk=trim($Stalk_sec);}
	if (trim($Stalk_avg)>$max_talkavg) {$max_talkavg=trim($Stalk_avg);}
	if (trim($Sdispo_sec)>$max_dispo) {$max_dispo=trim($Sdispo_sec);}
	if (trim($Sdispo_avg)>$max_dispoavg) {$max_dispoavg=trim($Sdispo_avg);}
	if (trim($Sdead_sec)>$max_dead) {$max_dead=trim($Sdead_sec);}
	if (trim($Sdead_avg)>$max_deadavg) {$max_deadavg=trim($Sdead_avg);}
	if (trim($Scustomer_sec)>$max_customer) {$max_customer=trim($Scustomer_sec);}
	if (trim($Scustomer_avg)>$max_customeravg) {$max_customeravg=trim($Scustomer_avg);}
	$graph_stats[$m][0]=trim($Sfull_name)." - ".trim($Suser);
	$graph_stats[$m][1]=trim($Scalls);
	$graph_stats[$m][2]=trim($Stime);
	$graph_stats[$m][3]=trim($Spause_sec);
	$graph_stats[$m][4]=trim($Spause_avg);
	$graph_stats[$m][5]=trim($Swait_sec);
	$graph_stats[$m][6]=trim($Swait_avg);
	$graph_stats[$m][7]=trim($Stalk_sec);
	$graph_stats[$m][8]=trim($Stalk_avg);
	$graph_stats[$m][9]=trim($Sdispo_sec);
	$graph_stats[$m][10]=trim($Sdispo_avg);
	$graph_stats[$m][11]=trim($Sdead_sec);
	$graph_stats[$m][12]=trim($Sdead_avg);
	$graph_stats[$m][13]=trim($Scustomer_sec);
	$graph_stats[$m][14]=trim($Scustomer_avg);
	

	$pfUSERtime_MS =			sec_convert($Stime,$TIME_H_agentperfdetail); 
	$pfUSERtotTALK_MS =			sec_convert($Stalk_sec,$TIME_H_agentperfdetail); 
	$pfUSERtotTALK_MS_pct =		sprintf("%0.2f", MathZDC(100*$Stalk_sec, $Stime));
	$pfUSERavgTALK_MS =			sec_convert($Stalk_avg,$TIME_M_agentperfdetail); 
	$USERtotPAUSE_MS =			sec_convert($Spause_sec,$TIME_H_agentperfdetail); 
	$pfUSERtotPAUSE_MS_pct =	sprintf("%0.2f", MathZDC(100*$Spause_sec, $Stime));
	$USERavgPAUSE_MS =			sec_convert($Spause_avg,$TIME_M_agentperfdetail); 
	$USERtotWAIT_MS =			sec_convert($Swait_sec,$TIME_H_agentperfdetail); 
	$pfUSERtotWAIT_MS_pct =		sprintf("%0.2f", MathZDC(100*$Swait_sec, $Stime));
	$USERavgWAIT_MS =			sec_convert($Swait_avg,$TIME_M_agentperfdetail); 
	$USERtotDISPO_MS =			sec_convert($Sdispo_sec,$TIME_H_agentperfdetail); 
	$pfUSERtotDISPO_MS_pct =	sprintf("%0.2f", MathZDC(100*$Sdispo_sec, $Stime));
	$USERavgDISPO_MS =			sec_convert($Sdispo_avg,$TIME_M_agentperfdetail); 
	$USERtotDEAD_MS =			sec_convert($Sdead_sec,$TIME_H_agentperfdetail); 
	$pfUSERtotDEAD_MS_pct =		sprintf("%0.2f", MathZDC(100*$Sdead_sec, $Stime));
	$USERavgDEAD_MS =			sec_convert($Sdead_avg,$TIME_M_agentperfdetail); 
	$USERtotCUSTOMER_MS	=		sec_convert($Scustomer_sec,$TIME_H_agentperfdetail);
	$pfUSERtotCUSTOMER_MS_pct =	sprintf("%0.2f", MathZDC(100*$Scustomer_sec, $Stime));
	$USERavgCUSTOMER_MS =		sec_convert($Scustomer_avg,$TIME_M_agentperfdetail); 

	$pfUSERtime_MS =			sprintf("%9s", $pfUSERtime_MS);
	$pfUSERtotTALK_MS =			sprintf("%8s", $pfUSERtotTALK_MS);
	$pfUSERtotTALK_MS_pct =		sprintf("%8s", $pfUSERtotTALK_MS_pct);
	$pfUSERavgTALK_MS =			sprintf("%6s", $pfUSERavgTALK_MS);
	$pfUSERtotPAUSE_MS =		sprintf("%8s", $USERtotPAUSE_MS);
	$pfUSERtotPAUSE_MS_pct =	sprintf("%8s", $pfUSERtotPAUSE_MS_pct);
	$pfUSERavgPAUSE_MS =		sprintf("%6s", $USERavgPAUSE_MS);
	$pfUSERtotWAIT_MS =			sprintf("%8s", $USERtotWAIT_MS);
	$pfUSERtotWAIT_MS_pct =		sprintf("%8s", $pfUSERtotWAIT_MS_pct);
	$pfUSERavgWAIT_MS =			sprintf("%6s", $USERavgWAIT_MS);
	$pfUSERtotDISPO_MS =		sprintf("%8s", $USERtotDISPO_MS);
	$pfUSERtotDISPO_MS_pct =	sprintf("%8s", $pfUSERtotDISPO_MS_pct);
	$pfUSERavgDISPO_MS =		sprintf("%6s", $USERavgDISPO_MS);
	$pfUSERtotDEAD_MS =			sprintf("%8s", $USERtotDEAD_MS);
	$pfUSERtotDEAD_MS_pct =		sprintf("%8s", $pfUSERtotDEAD_MS_pct);
	$pfUSERavgDEAD_MS =			sprintf("%6s", $USERavgDEAD_MS);
	$pfUSERtotCUSTOMER_MS =		sprintf("%8s", $USERtotCUSTOMER_MS);
	$pfUSERtotCUSTOMER_MS_pct =	sprintf("%8s", $pfUSERtotCUSTOMER_MS_pct);
	$pfUSERavgCUSTOMER_MS =		sprintf("%6s", $USERavgCUSTOMER_MS);
	$PAUSEtotal[$m] = $pfUSERtotPAUSE_MS;
	
	// ############ login time ##########
$SuserName = trim($Suser);

$sel_login_time = "select  DATE(event_date) as login_date, TIME(event_date) as logintime from vicidial_user_log where event_date >='$query_date_BEGIN' and event_date <= '$query_date_END' and user LIKE '$SuserName%' and event='LOGIN' ";
//select  DATE(event_date) as login_date , TIME(event_date) as mytime from vicidial_user_log
//echo $sel_login_time."<br>";
$res_login_time = mysqli_query($link,$sel_login_time);
$row_login_time = mysqli_fetch_array($res_login_time);
$logindt = $row_login_time[0];
$loginTime = $row_login_time[1];

// ############ login time ##########

// ############ logout time ##########
$sel_logout_time = "select DATE(event_date) as logout_date, TIME(event_date) as logouttime from vicidial_user_log where event_date >='$query_date_BEGIN' and event_date <= '$query_date_END' and user LIKE '$SuserName%' and event='LOGOUT' ";
$res_logout_time = mysqli_query($link,$sel_logout_time);
//$row_logout_time = mysqli_fetch_array($res_logout_time);
//$logoutTime = $row_logout_time[0];

while($row_logout_time = mysqli_fetch_array($res_logout_time))
{
	$logoutdt = $row_logout_time[0];
	$logoutTime = $row_logout_time[1];
	
}
// ############ logout time ##########

//############# TOTAL IB OB calls ################

$sel_ib = "select count(*) from vicidial_closer_log where call_date >='$query_date_BEGIN' and call_date <= '$query_date_END' and user LIKE '$SuserName%'";
$res_ib = mysqli_query($link,$sel_ib);
$row_ib = mysqli_fetch_array($res_ib);
$ib   = $row_ib[0];
$totib = $totib + $ib;

$sel_ob = "select count(*) from vicidial_log where call_date >='$query_date_BEGIN' and call_date <= '$query_date_END' and user LIKE '$SuserName%'";
$res_ob = mysqli_query($link,$sel_ob);
$row_ob = mysqli_fetch_array($res_ob);
$ob   = $row_ob[0];
$totob = $totob + $ob;

$TotalCalls = $ib + $ob;

$TOTALCALLSVAL = $TOTALCALLSVAL + $TotalCalls;

//############# TOTAL IB OB calls ################



	if ($show_percentages) {
		$Toutput = "<tr><td> $Sfull_name </td><td> <a href=\"./user_stats.php?user=$RAWuser\">$Suser</a> </td><td> $Suser_group </td><td> $Slast_user_group$BBD_filler </td><td> $Scalls </td><td> $pfUSERtime_MS </td><td> $pfUSERtotPAUSE_MS </td><td> $pfUSERtotPAUSE_MS_pct % </td><td> $pfUSERavgPAUSE_MS </td><td> $pfUSERtotWAIT_MS </td><td> $pfUSERtotWAIT_MS_pct % </td><td> $pfUSERavgWAIT_MS </td><td> $pfUSERtotTALK_MS </td><td> $pfUSERtotTALK_MS_pct % </td><td> $pfUSERavgTALK_MS </td><td> $pfUSERtotDISPO_MS </td><td> $pfUSERtotDISPO_MS_pct % </td><td> $pfUSERavgDISPO_MS </td><td> $pfUSERtotDEAD_MS </td><td> $pfUSERtotDEAD_MS_pct % </td><td> $pfUSERavgDEAD_MS </td><td> $pfUSERtotCUSTOMER_MS </td><td> $pfUSERtotCUSTOMER_MS_pct % </td><td> $pfUSERavgCUSTOMER_MS </td><td>$SstatusesHTML</td></tr>";
	} else {
		$Toutput = "<tr><td> $Sfull_name </td><td> <a href=\"./user_stats.php?user=$RAWuser\">$Suser</a> </td><td> $Suser_group </td><td> $Slast_user_group$BBD_filler </td><td>$logindt</td><td> $loginTime </td><td>$logoutdt</td><td> $logoutTime </td><td> $TotalCalls </td><td> $pfUSERtime_MS </td><td> $pfUSERtotPAUSE_MS </td><td> $pfUSERavgPAUSE_MS </td><td> $pfUSERtotWAIT_MS </td><td> $pfUSERavgWAIT_MS </td><td> $pfUSERtotTALK_MS </td><td> $pfUSERavgTALK_MS </td><td> $pfUSERtotDISPO_MS </td><td> $pfUSERavgDISPO_MS </td><td> $pfUSERtotDEAD_MS </td><td> $pfUSERavgDEAD_MS </td><td> $pfUSERtotCUSTOMER_MS </td><td> $pfUSERavgCUSTOMER_MS </td><td>$SstatusesHTML</td></tr>";
	}

	$Toutput.=$Toutput_sub;

	$CSV_lines.="\"$Sfull_nameRAW\",";
	if ($show_percentages) {
		$CSV_lines.=preg_replace('/\s/', '', "\"$SuserRAW\",\"$Suser_group\",\"$Slast_user_group$BBD_csv_filler\",\"$Scalls\",\"$pfUSERtime_MS\",\"$pfUSERtotPAUSE_MS\",\"$pfUSERtotPAUSE_MS_pct\",\"$pfUSERavgPAUSE_MS\",\"$pfUSERtotWAIT_MS\",\"$pfUSERtotWAIT_MS_pct\",\"$pfUSERavgWAIT_MS\",\"$pfUSERtotTALK_MS\",\"$pfUSERtotTALK_MS_pct\",\"$pfUSERavgTALK_MS\",\"$pfUSERtotDISPO_MS\",\"$pfUSERtotDISPO_MS_pct\",\"$pfUSERavgDISPO_MS\",\"$pfUSERtotDEAD_MS\",\"$pfUSERtotDEAD_MS_pct\",\"$pfUSERavgDEAD_MS\",\"$pfUSERtotCUSTOMER_MS\",\"$pfUSERtotCUSTOMER_MS_pct\",\"$pfUSERavgCUSTOMER_MS\"$CSVstatuses");
	} else {
		$CSV_lines.=preg_replace('/\s/', '', "\"$SuserRAW\",\"$Suser_group\",\"$Slast_user_group$BBD_csv_filler\",\"$logindt\",\"$loginTime\",\"$logoutdt\",\"$logoutTime\",\"$TotalCalls\",\"$pfUSERtime_MS\",\"$pfUSERtotPAUSE_MS\",\"$pfUSERavgPAUSE_MS\",\"$pfUSERtotWAIT_MS\",\"$pfUSERavgWAIT_MS\",\"$pfUSERtotTALK_MS\",\"$pfUSERavgTALK_MS\",\"$pfUSERtotDISPO_MS\",\"$pfUSERavgDISPO_MS\",\"$pfUSERtotDEAD_MS\",\"$pfUSERavgDEAD_MS\",\"$pfUSERtotCUSTOMER_MS\",\"$pfUSERavgCUSTOMER_MS\"$CSVstatuses");
	}
	$CSV_lines.="\n";
	$CSV_lines.=$CSV_lines_sub;


	$TOPsorted_output[$m] = $Toutput;

	if ($stage == 'ID')
		{$TOPsort[$m] =	'' . sprintf("%08s", $RAWuser) . '-----' . $m . '-----' . sprintf("%020s", $RAWuser);}
	if ($stage == 'LEADS')
		{$TOPsort[$m] =	'' . sprintf("%08s", $RAWcalls) . '-----' . $m . '-----' . sprintf("%020s", $RAWuser);}
	if ($stage == 'TIME')
		{$TOPsort[$m] =	'' . sprintf("%08s", $Stime) . '-----' . $m . '-----' . sprintf("%020s", $RAWuser);}
	if (!preg_match('/ID|TIME|LEADS/',$stage))
		{$ASCII_text.="$Toutput";}

	$m++;
	}
### END loop through each user ###



### BEGIN sort through output to display properly ###
if (preg_match('/ID|TIME|LEADS/',$stage))
	{
	if (preg_match('/ID/',$stage))
		{sort($TOPsort, SORT_NUMERIC);}
	if (preg_match('/TIME|LEADS/',$stage))
		{rsort($TOPsort, SORT_NUMERIC);}

	$m=0;
	while ($m < $k)
		{
		$sort_split = explode("-----",$TOPsort[$m]);
		$i = $sort_split[1];
		$sort_order[$m] = "$i";
		$ASCII_text.="$TOPsorted_output[$i]";
		$m++;
		}
	}
### END sort through output to display properly ###



###### LAST LINE FORMATTING ##########
### BEGIN loop through each status ###
$SUMstatusesHTML='';
$CSVSUMstatuses='';
$n=0;
while ($n < $j)
	{
	$Scalls=0;
	$Sstatus=$statusesARY[$n];
	$SUMstatusTXT='';
	$total_var=$Sstatus."_total";
	### BEGIN loop through each stat line ###
	$i=0; $status_found=0;
	while ($i < $rows_to_print)
		{
		if ($Sstatus=="$status[$i]")
			{
			$Scalls =		($Scalls + $calls[$i]);
			$status_found++;
			}
		$i++;
		}
	### END loop through each stat line ###
	if ($status_found < 1)
		{
		$SUMstatusesHTML .= "        0 |";
		if ($show_percentages) {
			$SUMstatusesHTML .= "     0.00 % |";
			$CSVSUMstatuses.=",\"0.00 %\"";
		}
		$$total_var=0;
		}
	else
		{
		$SUMstatusTXT = sprintf("%8s", $Scalls);
		$SUMstatusesHTML .= " $SUMstatusTXT |";
		$CSVSUMstatuses.=",\"$Scalls\"";
		if ($show_percentages) {
			$SstatusTXT_pct=sprintf("%8s", sprintf("%0.2f", MathZDC(100*$Scalls,$TOTcalls)));
			$SUMstatusesHTML .= " $SstatusTXT_pct % |";
			$CSVSUMstatuses.=",\"$SstatusTXT_pct %\"";
		}
		$$total_var=$Scalls;
		}
	$n++;
	}
### END loop through each status ###

$TOTcalls =	sprintf("%7s", $TOTcalls);
$TOT_AGENTS = sprintf("%-4s", $m);

$TOTavgTALK = MathZDC($TOTtotTALK, $TOTcalls);
$TOTavgDISPO = MathZDC($TOTtotDISPO, $TOTcalls);
$TOTavgDEAD = MathZDC($TOTtotDEAD, $TOTcalls);
$TOTavgPAUSE = MathZDC($TOTtotPAUSE, $TOTcalls);
$TOTavgWAIT = MathZDC($TOTtotWAIT, $TOTcalls);
$TOTavgCUSTOMER = MathZDC($TOTtotCUSTOMER, $TOTcalls);

$TOTtime_MS =		sec_convert($TOTtime,$TIME_H_agentperfdetail); 
$TOTtotTALK_MS =	sec_convert($TOTtotTALK,$TIME_H_agentperfdetail); 
$TOTtotDISPO_MS =	sec_convert($TOTtotDISPO,$TIME_H_agentperfdetail); 
$TOTtotDEAD_MS =	sec_convert($TOTtotDEAD,$TIME_H_agentperfdetail); 
$TOTtotPAUSE_MS =	sec_convert($TOTtotPAUSE,$TIME_H_agentperfdetail); 
$TOTtotWAIT_MS =	sec_convert($TOTtotWAIT,$TIME_H_agentperfdetail); 
$TOTtotCUSTOMER_MS =	sec_convert($TOTtotCUSTOMER,$TIME_H_agentperfdetail); 
$TOTtotTALK_MS_pct =	sprintf("%0.2f", MathZDC(100*$TOTtotTALK, $TOTtime));
$TOTtotDISPO_MS_pct =	sprintf("%0.2f", MathZDC(100*$TOTtotDISPO, $TOTtime));
$TOTtotDEAD_MS_pct =	sprintf("%0.2f", MathZDC(100*$TOTtotDEAD, $TOTtime));
$TOTtotPAUSE_MS_pct =	sprintf("%0.2f", MathZDC(100*$TOTtotPAUSE, $TOTtime));
$TOTtotWAIT_MS_pct =	sprintf("%0.2f", MathZDC(100*$TOTtotWAIT, $TOTtime));
$TOTtotCUSTOMER_MS_pct =sprintf("%0.2f", MathZDC(100*$TOTtotCUSTOMER, $TOTtime));
$TOTavgTALK_MS =	sec_convert($TOTavgTALK,$TIME_M_agentperfdetail); 
$TOTavgDISPO_MS =	sec_convert($TOTavgDISPO,$TIME_H_agentperfdetail); 
$TOTavgDEAD_MS =	sec_convert($TOTavgDEAD,$TIME_H_agentperfdetail); 
$TOTavgPAUSE_MS =	sec_convert($TOTavgPAUSE,$TIME_H_agentperfdetail); 
$TOTavgWAIT_MS =	sec_convert($TOTavgWAIT,$TIME_H_agentperfdetail); 
$TOTavgCUSTOMER_MS =	sec_convert($TOTavgCUSTOMER,$TIME_H_agentperfdetail); 

$TOTtime_MS =		sprintf("%10s", $TOTtime_MS);
$TOTtotTALK_MS =	sprintf("%10s", $TOTtotTALK_MS);
$TOTtotDISPO_MS =	sprintf("%10s", $TOTtotDISPO_MS);
$TOTtotDEAD_MS =	sprintf("%10s", $TOTtotDEAD_MS);
$TOTtotPAUSE_MS =	sprintf("%10s", $TOTtotPAUSE_MS);
$TOTtotWAIT_MS =	sprintf("%10s", $TOTtotWAIT_MS);
$TOTtotCUSTOMER_MS =	sprintf("%10s", $TOTtotCUSTOMER_MS);
$TOTtotTALK_MS_pct =	sprintf("%8s", $TOTtotTALK_MS_pct);
$TOTtotDISPO_MS_pct =	sprintf("%8s", $TOTtotDISPO_MS_pct);
$TOTtotDEAD_MS_pct =	sprintf("%8s", $TOTtotDEAD_MS_pct);
$TOTtotPAUSE_MS_pct =	sprintf("%8s", $TOTtotPAUSE_MS_pct);
$TOTtotWAIT_MS_pct =	sprintf("%8s", $TOTtotWAIT_MS_pct);
$TOTtotCUSTOMER_MS_pct =	sprintf("%8s", $TOTtotCUSTOMER_MS_pct);
$TOTavgTALK_MS =	sprintf("%6s", $TOTavgTALK_MS);
$TOTavgDISPO_MS =	sprintf("%6s", $TOTavgDISPO_MS);
$TOTavgDEAD_MS =	sprintf("%6s", $TOTavgDEAD_MS);
$TOTavgPAUSE_MS =	sprintf("%6s", $TOTavgPAUSE_MS);
$TOTavgWAIT_MS =	sprintf("%6s", $TOTavgWAIT_MS);
$TOTavgCUSTOMER_MS =	sprintf("%6s", $TOTavgCUSTOMER_MS);

if ($file_download == 0 || !$file_download)
	{
	while(strlen($TOTtime_MS)>10) {$TOTtime_MS = substr("$TOTtime_MS", 0, -1);}
	while(strlen($TOTtotTALK_MS)>10) {$TOTtotTALK_MS = substr("$TOTtotTALK_MS", 0, -1);}
	while(strlen($TOTtotDISPO_MS)>10) {$TOTtotDISPO_MS = substr("$TOTtotDISPO_MS", 0, -1);}
	while(strlen($TOTtotDEAD_MS)>10) {$TOTtotDEAD_MS = substr("$TOTtotDEAD_MS", 0, -1);}
	while(strlen($TOTtotPAUSE_MS)>10) {$TOTtotPAUSE_MS = substr("$TOTtotPAUSE_MS", 0, -1);}
	while(strlen($TOTtotWAIT_MS)>10) {$TOTtotWAIT_MS = substr("$TOTtotWAIT_MS", 0, -1);}
	while(strlen($TOTtotCUSTOMER_MS)>10) {$TOTtotCUSTOMER_MS = substr("$TOTtotCUSTOMER_MS", 0, -1);}
	while(strlen($TOTavgTALK_MS)>6) {$TOTavgTALK_MS = substr("$TOTavgTALK_MS", 0, -1);}
	while(strlen($TOTavgDISPO_MS)>6) {$TOTavgDISPO_MS = substr("$TOTavgDISPO_MS", 0, -1);}
	while(strlen($TOTavgDEAD_MS)>6) {$TOTavgDEAD_MS = substr("$TOTavgDEAD_MS", 0, -1);}
	while(strlen($TOTavgPAUSE_MS)>6) {$TOTavgPAUSE_MS = substr("$TOTavgPAUSE_MS", 0, -1);}
	while(strlen($TOTavgWAIT_MS)>6) {$TOTavgWAIT_MS = substr("$TOTavgWAIT_MS", 0, -1);}
	while(strlen($TOTavgCUSTOMER_MS)>6) {$TOTavgCUSTOMER_MS = substr("$TOTavgCUSTOMER_MS", 0, -1);}
	}

if ($show_percentages) {
	if (!$breakdown_by_date) # Prevent additional line from being printed - has to be here, won't work above in if-breakdown-by-date loop when results are sorted
		{
		//$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+$statusesHEAD\n";
		}
	$ASCII_text.="<tr><td colspan='4'>  "._QXZ("TOTALS",33)." "._QXZ("AGENTS",32,"r").":$TOT_AGENTS$BBD_filler </td><td> $TOTALCALLSVAL</td><td> $TOTtime_MS</td><td>$TOTtotPAUSE_MS</td><td> $TOTtotPAUSE_MS_pct % </td><td> $TOTavgPAUSE_MS </td><td>$TOTtotWAIT_MS</td><td> $TOTtotWAIT_MS_pct % </td><td> $TOTavgWAIT_MS </td><td>$TOTtotTALK_MS</td><td> $TOTtotTALK_MS_pct % </td><td> $TOTavgTALK_MS </td><td>$TOTtotDISPO_MS</td><td> $TOTtotDISPO_MS_pct % </td><td> $TOTavgDISPO_MS </td><td>$TOTtotDEAD_MS</td><td> $TOTtotDEAD_MS_pct % </td><td> $TOTavgDEAD_MS </td><td>$TOTtotCUSTOMER_MS</td><td> $TOTtotCUSTOMER_MS_pct % </td><td> $TOTavgCUSTOMER_MS </td><td>$SUMstatusesHTML</td></tr>";
	//$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+----------+------------+--------+$statusesHEAD\n";
} else {
	if (!$breakdown_by_date) # Prevent additional line from being printed - has to be here, won't work above in if-breakdown-by-date loop when results are sorted
		{
	//	$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+$statusesHEAD\n";
		}
	$ASCII_text.="<tr><td colspan='8'>  "._QXZ("TOTALS",33)." "._QXZ("AGENTS",32,"r").":$TOT_AGENTS$BBD_filler </td><td> $TOTALCALLSVAL</td><td>$TOTtime_MS</td><td>$TOTtotPAUSE_MS</td><td> $TOTavgPAUSE_MS </td><td>$TOTtotWAIT_MS</td><td> $TOTavgWAIT_MS </td><td>$TOTtotTALK_MS</td><td> $TOTavgTALK_MS </td><td>$TOTtotDISPO_MS</td><td> $TOTavgDISPO_MS </td><td>$TOTtotDEAD_MS</td><td> $TOTavgDEAD_MS </td><td>$TOTtotCUSTOMER_MS</td><td> $TOTavgCUSTOMER_MS </td><td>$SUMstatusesHTML</td></tr>";
	//$ASCII_text.="+-----------------+----------+----------------------+----------------------+".$BBD_header."--------+-----------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+----------+--------+$statusesHEAD\n";
	
}
	$ASCII_text.="</tbody></table>";

for ($e=0; $e<count($statusesARY); $e++) {
	$Sstatus=$statusesARY[$e];
	$SstatusTXT=$Sstatus;
	if ($Sstatus=="") {$SstatusTXT="(blank)";}
	$GRAPH2.="<th class='column_header grey_graph_cell' id='callstatsgraph".($e+15)."'><a href='#' onClick=\"DrawGraph('$Sstatus', '".($e+15)."'); return false;\">$SstatusTXT</a></th>";
}

for ($d=0; $d<count($graph_stats); $d++) {
	if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
	$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][1], $max_calls))."' height='16' />".$graph_stats[$d][1]."</td></tr>";
	$TIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][2], $max_time))."' height='16' />".sec_convert($graph_stats[$d][2], $TIME_HF_agentperfdetail)."</td></tr>";
	$PAUSE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][3], $max_pause))."' height='16' />".sec_convert($graph_stats[$d][3], $TIME_HF_agentperfdetail)."</td></tr>";
	$PAUSEAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][4], $max_pauseavg))."' height='16' />".sec_convert($graph_stats[$d][4], $TIME_HF_agentperfdetail)."</td></tr>";
	$WAIT_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][5], $max_wait))."' height='16' />".sec_convert($graph_stats[$d][5], $TIME_HF_agentperfdetail)."</td></tr>";
	$WAITAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][6], $max_waitavg))."' height='16' />".sec_convert($graph_stats[$d][6], $TIME_HF_agentperfdetail)."</td></tr>";
	$TALK_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][7], $max_talk))."' height='16' />".sec_convert($graph_stats[$d][7], $TIME_HF_agentperfdetail)."</td></tr>";
	$TALKAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][8], $max_talkavg))."' height='16' />".sec_convert($graph_stats[$d][8], $TIME_HF_agentperfdetail)."</td></tr>";
	$DISPO_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][9], $max_dispo))."' height='16' />".sec_convert($graph_stats[$d][9], $TIME_HF_agentperfdetail)."</td></tr>";
	$DISPOAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][10], $max_dispoavg))."' height='16' />".sec_convert($graph_stats[$d][10], $TIME_HF_agentperfdetail)."</td></tr>";
	$DEAD_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][11], $max_dead))."' height='16' />".sec_convert($graph_stats[$d][11], $TIME_HF_agentperfdetail)."</td></tr>";
	$DEADAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][12], $max_deadavg))."' height='16' />".sec_convert($graph_stats[$d][12], $TIME_HF_agentperfdetail)."</td></tr>";
	$CUST_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][13], $max_customer))."' height='16' />".sec_convert($graph_stats[$d][13], $TIME_HF_agentperfdetail)."</td></tr>";
	$CUSTAVG_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][14], $max_customeravg))."' height='16' />".sec_convert($graph_stats[$d][14], $TIME_HF_agentperfdetail)."</td></tr>";

	for ($e=0; $e<count($statusesARY); $e++) {
		$Sstatus=$statusesARY[$e];
		$varname=$Sstatus."_graph";
		$max_varname="max_".$Sstatus;
#		$max.= "<!-- $max_varname => ".$$max_varname." //-->\n";
			
		$$varname.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][($e+15)], $$max_varname))."' height='16' />".$graph_stats[$d][($e+15)]."</td></tr>";
	}
}
		
$CALLS_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTcalls)."</th></tr></table>";
$TIME_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtime_MS)."</th></tr></table>";
$PAUSE_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotPAUSE_MS)."</th></tr></table>";
$PAUSEAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgPAUSE_MS)."</th></tr></table>";
$WAIT_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotWAIT_MS)."</th></tr></table>";
$WAITAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgWAIT_MS)."</th></tr></table>";
$TALK_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotTALK_MS)."</th></tr></table>";
$TALKAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgTALK_MS)."</th></tr></table>";
$DISPO_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotDISPO_MS)."</th></tr></table>";
$DISPOAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgDISPO_MS)."</th></tr></table>";
$DEAD_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotDEAD_MS)."</th></tr></table>";
$DEADAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgDEAD_MS)."</th></tr></table>";
$CUST_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTtotCUSTOMER_MS)."</th></tr></table>";
$CUSTAVG_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($TOTavgCUSTOMER_MS)."</th></tr></table>";

for ($e=0; $e<count($statusesARY); $e++) {
	$Sstatus=$statusesARY[$e];
	$total_var=$Sstatus."_total";
	$graph_var=$Sstatus."_graph";
	$$graph_var.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim($$total_var)."</th></tr></table>";
}

$JS_onload.="\tDrawGraph('CALLS', '1');\n"; 
$JS_text.="function DrawGraph(graph, th_id) {\n";
$JS_text.="	var graph_CALLS=\"$CALLS_graph\";\n";
$JS_text.="	var graph_TIME=\"$TIME_graph\";\n";
$JS_text.="	var graph_PAUSE=\"$PAUSE_graph\";\n";
$JS_text.="	var graph_PAUSEAVG=\"$PAUSEAVG_graph\";\n";
$JS_text.="	var graph_WAIT=\"$WAIT_graph\";\n";
$JS_text.="	var graph_WAITAVG=\"$WAITAVG_graph\";\n";
$JS_text.="	var graph_TALK=\"$TALK_graph\";\n";
$JS_text.="	var graph_TALKAVG=\"$TALKAVG_graph\";\n";
$JS_text.="	var graph_DISPO=\"$DISPO_graph\";\n";
$JS_text.="	var graph_DISPOAVG=\"$DISPOAVG_graph\";\n";
$JS_text.="	var graph_DEAD=\"$DEAD_graph\";\n";
$JS_text.="	var graph_DEADAVG=\"$DEADAVG_graph\";\n";
$JS_text.="	var graph_CUST=\"$CUST_graph\";\n";
$JS_text.="	var graph_CUSTAVG=\"$CUSTAVG_graph\";\n";

for ($e=0; $e<count($statusesARY); $e++) {
	$Sstatus=$statusesARY[$e];
	$graph_var=$Sstatus."_graph";
	$JS_text.="	var graph_".$Sstatus."=\"".$$graph_var."\";\n";
}

$JS_text.="	for (var i=1; i<=".(count($statusesARY)+14)."; i++) {\n";
$JS_text.="		var cellID=\"callstatsgraph\"+i;\n";
$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
$JS_text.="	}\n";
$JS_text.="	var cellID=\"callstatsgraph\"+th_id;\n";
$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
$JS_text.="\n";
$JS_text.="	var graph_to_display=eval(\"graph_\"+graph);\n";
$JS_text.="	document.getElementById('call_stats_graph').innerHTML=graph_to_display;\n";
$JS_text.="}\n";
$JS_text.="</script>\n";

$GRAPH3="<tr><td colspan='".(14+count($statusesARY))."' class='graph_span_cell'><span id='call_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";

$GRAPH_text.=$JS_text.$GRAPH.$GRAPH2.$GRAPH3;

if ($show_percentages) {
	$CSV_total=preg_replace('/\s/', '', "\"\",\"\",\""._QXZ("TOTALS")."\",\""._QXZ("AGENTS").":$TOT_AGENTS\",\"$TOTALCALLSVAL\",\"$TOTtime_MS\",\"$TOTtotPAUSE_MS\",\"$TOTtotPAUSE_MS_pct %\",\"$TOTavgPAUSE_MS\",\"$TOTtotWAIT_MS\",\"$TOTtotWAIT_MS_pct %\",\"$TOTavgWAIT_MS\",\"$TOTtotTALK_MS\",\"$TOTtotTALK_MS_pct %\",\"$TOTavgTALK_MS\",\"$TOTtotDISPO_MS\",\"$TOTtotDISPO_MS_pct %\",\"$TOTavgDISPO_MS\",\"$TOTtotDEAD_MS\",\"$TOTtotDEAD_MS_pct %\",\"$TOTavgDEAD_MS\",\"$TOTtotCUSTOMER_MS\",\"$TOTtotCUSTOMER_MS_pct %\",\"$TOTavgCUSTOMER_MS\"$CSVSUMstatuses\n\n");
} else {
	$CSV_total=preg_replace('/\s/', '', "\"\",\"\",\"\",\"\",\"\",\"\",\""._QXZ("TOTALS")."\",\""._QXZ("AGENTS").":$TOT_AGENTS\",\"$TOTALCALLSVAL\",\"$TOTtime_MS\",\"$TOTtotPAUSE_MS\",\"$TOTavgPAUSE_MS\",\"$TOTtotWAIT_MS\",\"$TOTavgWAIT_MS\",\"$TOTtotTALK_MS\",\"$TOTavgTALK_MS\",\"$TOTtotDISPO_MS\",\"$TOTavgDISPO_MS\",\"$TOTtotDEAD_MS\",\"$TOTavgDEAD_MS\",\"$TOTtotCUSTOMER_MS\",\"$TOTavgCUSTOMER_MS\"$CSVSUMstatuses\n\n");
}

//totalVal
$TOTALCALLSVAL =0;

if ($file_download == 1)
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AGENT_PERFORMACE_DETAIL$US$FILE_TIME.csv";

	// We'll be outputting a TXT file
	header('Content-type: application/octet-stream');

	// It will be called LIST_101_20090209-121212.txt
	header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	ob_clean();
	flush();

	echo "$CSV_header$CSV_lines$CSV_total";

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

	exit;
	}

$CSV_report=fopen("AST_agent_performance_detail.csv", "w");
fwrite($CSV_report, $CSV_header);
fwrite($CSV_report, $CSV_lines);
fwrite($CSV_report, $CSV_total);


$ASCII_text.="\n\n";



if ($file_download == 2)
	{
	$TIME_HF_agentperfdetail = 'HF';
	$TIME_H_agentperfdetail = 'HF';
	$TIME_M_agentperfdetail = 'HF';
	}
if ($time_in_sec)
	{
	$TIME_HF_agentperfdetail = 'S';
	$TIME_H_agentperfdetail = 'S';
	$TIME_M_agentperfdetail = 'S';
	}

$sub_statuses='-';
$sub_statusesTXT='';
$sub_statusesHEAD='';
$sub_statusesHTML='';
$CSV_statuses='';
$sub_statusesARY=$MT;
$j=0;
$PCusers='-';
$PCusersARY=$MT;
$PCuser_namesARY=$MT;
$k=0;

$graph_stats=array();
$max_total=1;
$max_nonpause=1;
$max_pause=1;
$GRAPH="<BR><BR><a name='pausegraph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH2="<tr><th class='column_header grey_graph_cell' id='pausegraph1'><a href='#' onClick=\"DrawPauseGraph('TOTAL', '1'); return false;\">"._QXZ("TOTAL")."</a></th><th class='column_header grey_graph_cell' id='pausegraph2'><a href='#' onClick=\"DrawPauseGraph('NONPAUSE', '2'); return false;\">"._QXZ("NONPAUSE")."</a></th><th class='column_header grey_graph_cell' id='pausegraph3'><a href='#' onClick=\"DrawPauseGraph('PAUSE', '3'); return false;\">"._QXZ("PAUSE")."</a></th>";
$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>"._QXZ("PAUSE CODE BREAKDOWN")."</caption><tr><th class='thgraph' scope='col'>"._QXZ("STATUS")."</th>";
$TOTAL_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("TOTAL")." </th></tr>";
$NONPAUSE_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("NONPAUSE")."</th></tr>";
$PAUSE_graph=$graph_header."<th class='thgraph' scope='col'>"._QXZ("PAUSE")."</th></tr>";

$sub_status_stmt="SELECT distinct sub_status from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and pause_sec > 0 and pause_sec < 65000 $group_SQL $user_SQL order by sub_status asc limit 10000000;";
	if ($DB) {$ASCII_text.="$sub_status_stmt\n";}
$sub_status_rslt=mysql_to_mysqli($sub_status_stmt, $link);
$subs_to_print=0;
$q=0;
while($ss_row=mysqli_fetch_row($sub_status_rslt)) {
	$current_ss=$ss_row[0];

	if ($show_defunct_users=="checked")
		{
		$stmt="SELECT '' as full_name,user,sum(pause_sec),sub_status,sum(wait_sec + talk_sec + dispo_sec), '' as user_group from ".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and sub_status='$current_ss' and pause_sec<65000 $group_SQL $user_group_agent_log_SQL $user_SQL group by user,full_name,sub_status order by user,full_name,sub_status desc limit 100000;";
		}
	else
		{
		$stmt="SELECT full_name,vicidial_users.user,sum(pause_sec),sub_status,sum(wait_sec + talk_sec + dispo_sec), vicidial_users.user_group from vicidial_users,".$agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and sub_status='$current_ss' and vicidial_users.user=".$agent_log_table.".user and pause_sec<65000 $group_SQL $user_group_SQL $user_SQL group by user,full_name,sub_status order by user,full_name,sub_status desc limit 100000;";
		}
	$rslt=mysql_to_mysqli($stmt, $link);
	if ($DB) {$ASCII_text.="$stmt\n";}
	# $subs_to_print = mysqli_num_rows($rslt);
	$sub_subs_to_print = mysqli_num_rows($rslt);
	$subs_to_print+=$sub_subs_to_print;
	$i=0;
	while ($i < $sub_subs_to_print)
		{
		$row=mysqli_fetch_row($rslt);

		if ($show_defunct_users=="checked")
			{
			$defunct_user_stmt="SELECT full_name,user_group from vicidial_users where user='$row[1]'";
			$defunct_user_rslt=mysql_to_mysqli($defunct_user_stmt, $link);
			if (mysqli_num_rows($defunct_user_rslt)>0) 
				{
				$defunct_user_row=mysqli_fetch_row($defunct_user_rslt);
				$PCfull_name_val=$defunct_user_row[0];
				$PCuser_group_val=$defunct_user_row[1];
				} 
			else 
				{
				$PCfull_name_val=$row[1];
				$PCuser_group_val="**NONE**";
				}
			}
		else 
			{
			$PCfull_name_val=$row[0];
			$PCuser_group_val=$row[5];
			}
		
		
		$PCfull_name[$q] =	$PCfull_name_val;
		$PCuser[$q] =		$row[1];
		$PCpause_sec[$q] =	$row[2];
		$sub_status[$q] =	$row[3];
		$PCnon_pause_sec[$q] =	$row[4];
		$PCuser_group[$q] =		$PCuser_group_val;
		$max_varname="max_".$sub_status[$q];
		$$max_varname=1;

		#	echo "$sub_status[$i]|$PCpause_sec[$i]\n";
	#	if ( (!preg_match("/\-$sub_status[$i]\-/i", $sub_statuses)) and (strlen($sub_status[$i])>0) )
		if (!preg_match("/\-$sub_status[$q]\-/i", $sub_statuses))
			{
			$sub_statusesTXT = sprintf("%8s", $sub_status[$q]);
			$sub_statusesHEAD .= "----------+";
			$sub_statusesHTML .= " $sub_statusesTXT |";
			$sub_statuses .= "$sub_status[$q]-";
			$sub_statusesARY[$j] = $sub_status[$q];
			$CSV_statuses.=",\"$sub_status[$q]\"";
			$j++;
			}
		if (!preg_match("/\-$PCuser[$q]\-/i", $PCusers))
			{
			$PCusers .= "$PCuser[$q]-";
			$PCusersARY[$k] = $PCuser[$q];
			$PCuser_namesARY[$k] = $PCfull_name[$q];
			$PCuser_groupsARY[$k] = $PCuser_group[$q];
			$k++;
			}

		$i++;
		$q++;
		}
	}

$ASCII_text.="<font class='titletext1'>"._QXZ("PAUSE CODE BREAKDOWN",20).":    </font> <a href=\"$LINKbase&stage=$stage&file_download=2\">["._QXZ("DOWNLOAD")."]</a>\n\n";
$ASCII_text.="<table class='table table-striped table-bordered'><tbody>";
$ASCII_text.="<tr><td> "._QXZ("USER NAME",15)." </td><td> "._QXZ("ID",8)." </td><td> "._QXZ("CURRENT USER GROUP",20)." </td><td> "._QXZ("MOST RECENT USER GRP",20)." </td><td> "._QXZ("LOGIN TIME",10)." </td><td> "._QXZ("NONPAUSE",8)."</td><td> "._QXZ("PAUSE",8)." </td><td> $sub_statusesHTML</td></tr>";
//$ASCII_text.="+-----------------+----------+----------------------+----------------------+------------+----------+----------+  +$sub_statusesHEAD\n";

$CSV_header="\""._QXZ("Agent Performance Detail",47)." $NOW_TIME\"\n";
$CSV_header.="\""._QXZ("Time range").": $query_date_BEGIN "._QXZ("to")." $query_date_END\"\n\n";
$CSV_header.="\""._QXZ("PAUSE CODE BREAKDOWN").":\"\n";
$CSV_header.="\""._QXZ("USER NAME")."\",\""._QXZ("ID")."\",\""._QXZ("CURRENT USER GROUP")."\",\""._QXZ("MOST RECENT USER GROUP")."\",\""._QXZ("TOTAL")."\",\""._QXZ("NONPAUSE")."\",\""._QXZ("PAUSE")."\",$CSV_statuses\n";

### BEGIN loop through each user ###
$m=0;
$Suser_ct = count($usersARY);
$TOTtotNONPAUSE = 0;
$TOTtotTOTAL = 0;
$CSV_lines="";

while ($m < $k)
	{
	$d=0;
	while ($d < $Suser_ct)
		{
		if ($usersARY[$d] === "$PCusersARY[$m]")
			{$pcPAUSEtotal = $PAUSEtotal[$d];}
		$d++;
		}
	$Suser=$PCusersARY[$m];
	$Sfull_name=$PCuser_namesARY[$m];
	$Suser_group=$PCuser_groupsARY[$m];
	$Slast_user_group=$recent_user_groups[$Suser];	
	$Spause_sec=0;
	$Snon_pause_sec=0;
	$Stotal_sec=0;
	$SstatusesHTML='';
	$CSV_statuses="";

	### BEGIN loop through each status ###
	$n=0;
	while ($n < $j)
		{
		$Sstatus=$sub_statusesARY[$n];
		$SstatusTXT='';
		$varname=$Sstatus."_graph";
		$$varname=$graph_header."<th class='thgraph' scope='col'>$Sstatus</th></tr>";
		$max_varname="max_".$Sstatus;
		### BEGIN loop through each stat line ###
		$i=0; $status_found=0;
		while ($i < $subs_to_print)
			{
			if ( ($Suser=="$PCuser[$i]") and ($Sstatus=="$sub_status[$i]") )
				{
				$Spause_sec =	($Spause_sec + $PCpause_sec[$i]);
				$Snon_pause_sec =	($Snon_pause_sec + $PCnon_pause_sec[$i]);
				$Stotal_sec =	($Stotal_sec + $PCnon_pause_sec[$i] + $PCpause_sec[$i]);

				$USERcodePAUSE_MS =		sec_convert($PCpause_sec[$i],$TIME_H_agentperfdetail); 
				$pfUSERcodePAUSE_MS =	sprintf("%6s", $USERcodePAUSE_MS);

				$SstatusTXT = sprintf("%8s", $pfUSERcodePAUSE_MS);
				$SstatusesHTML .= " $SstatusTXT |";

				if ($PCpause_sec[$i]>$$max_varname) {$$max_varname=$PCpause_sec[$i];}
				$graph_stats[$m][(4+$n)]=$PCpause_sec[$i];					

				$CSV_statuses.=",\"$USERcodePAUSE_MS\"";
				$status_found++;
				}
			$i++;
			}
		if ($status_found < 1)
			{
			$SstatusesHTML .= "     0:00 |";
			$CSV_statuses.=",\"0:00:00\"";
			$graph_stats[$m][(4+$n)]=0;					
			}
		### END loop through each stat line ###
		$n++;
		}
	### END loop through each status ###
	$TOTtotPAUSE=($TOTtotPAUSE + $Spause_sec);
	$Sfull_nameRAW = $Sfull_name;
	$SuserRAW = $Suser;
	$graph_stats[$m][0]="$Sfull_name - $SuserRAW";

	if ($non_latin < 1)
		{
		$Sfull_name=	sprintf("%-15s", $Sfull_name); 
		while(strlen($Sfull_name)>15) {$Sfull_name = substr("$Sfull_name", 0, -1);}
		$Suser_group=	sprintf("%-20s", $Suser_group); 
		while(strlen($Suser_group)>20) {$Suser_group = substr("$Suser_group", 0, -1);}
		$Slast_user_group=	sprintf("%-20s", $Slast_user_group); 
		while(strlen($Slast_user_group)>20) {$Slast_user_group = substr("$Slast_user_group", 0, -1);}
		$Suser =		sprintf("%-8s", $Suser);
		while(strlen($Suser)>8) {$Suser = substr("$Suser", 0, -1);}
		}
	else
		{
		$Sfull_name=	sprintf("%-45s", $Sfull_name); 
		while(mb_strlen($Sfull_name,'utf-8')>15) {$Sfull_name = mb_substr("$Sfull_name", 0, -1,'utf-8');}
		$Suser_group=	sprintf("%-60s", $Suser_group); 
		while(mb_strlen($Suser_group,'utf-8')>20) {$Suser_group = mb_substr("$Suser_group", 0, -1,'utf-8');}
		$Slast_user_group=	sprintf("%-60s", $Slast_user_group); 
		while(mb_strlen($Slast_user_group,'utf-8')>20) {$Slast_user_group = mb_substr("$Slast_user_group", 0, -1,'utf-8');}
		$Suser =	sprintf("%-24s", $Suser);
		while(mb_strlen($Suser,'utf-8')>8) {$Suser = mb_substr("$Suser", 0, -1,'utf-8');}
		}

	$TOTtotNONPAUSE = ($TOTtotNONPAUSE + $Snon_pause_sec);
	$TOTtotTOTAL = ($TOTtotTOTAL + $Stotal_sec);

	if (trim($Stotal_sec)>$max_total) {$max_total=trim($Stotal_sec);}
	if (trim($Snon_pause_sec)>$max_nonpause) {$max_nonpause=trim($Snon_pause_sec);}
	if (trim($Spause_sec)>$max_pause) {$max_pause=trim($Spause_sec);}
	$graph_stats[$m][1]="$Stotal_sec";
	$graph_stats[$m][2]="$Snon_pause_sec";
	$graph_stats[$m][3]="$Spause_sec";

	$USERtotPAUSE_MS =		sec_convert($Spause_sec,$TIME_H_agentperfdetail); 
	$USERtotNONPAUSE_MS =	sec_convert($Snon_pause_sec,$TIME_H_agentperfdetail); 
	$USERtotTOTAL_MS =		sec_convert($Stotal_sec,$TIME_H_agentperfdetail); 

	$pfUSERtotPAUSE_MS =		sprintf("%8s", $USERtotPAUSE_MS);
	$pfUSERtotNONPAUSE_MS =		sprintf("%8s", $USERtotNONPAUSE_MS);
	$pfUSERtotTOTAL_MS =		sprintf("%10s", $USERtotTOTAL_MS);

	//$BOTTOMoutput = "| $Sfull_name | $Suser | $Suser_group | $Slast_user_group | $pfUSERtotTOTAL_MS | $pfUSERtotNONPAUSE_MS | $pfUSERtotPAUSE_MS |  |$SstatusesHTML\n";
	
	$BOTTOMoutput = "<tr><td>$Sfull_name</td><td>$Suser</td><td>$Suser_group</td><td>$Slast_user_group</td><td>$pfUSERtotTOTAL_MS</td><td>$pfUSERtotNONPAUSE_MS</td><td>$pfUSERtotPAUSE_MS</td><td>$SstatusesHTML</td></tr>";


	$BOTTOMsorted_output[$m] = $BOTTOMoutput;

	$ASCII_text.="$BOTTOMoutput";
	$CSV_lines.="\"$Sfull_nameRAW\"".preg_replace('/\s/', '', ",\"$SuserRAW\",\"$Suser_group\",\"$Slast_user_group\",\"$pfUSERtotTOTAL_MS\",\"$pfUSERtotNONPAUSE_MS\",\"$pfUSERtotPAUSE_MS\",$CSV_statuses");
	$CSV_lines.="\n";
	$m++;
	}
### END loop through each user ###



### BEGIN sort through output to display properly ###
#if (preg_match('/ID|TIME|LEADS/',$stage))
#	{
#	$n=0;
#	while ($n <= $m)
#		{
#		$i = $sort_order[$m];
#		echo "$BOTTOMsorted_output[$i]";
#		$m--;
#		}
#	}
### END sort through output to display properly ###



###### LAST LINE FORMATTING ##########
### BEGIN loop through each status ###
$SUMstatusesHTML='';
$CSVSUMstatuses='';
$TOTtotPAUSE=0;
$n=0;
while ($n < $j)
	{
	$Scalls=0;
	$Sstatus=$sub_statusesARY[$n];
	$SUMstatusTXT='';
	$total_var=$Sstatus."_total";
	### BEGIN loop through each stat line ###
	$i=0; $status_found=0;
	while ($i < $subs_to_print)
		{
		if ($Sstatus=="$sub_status[$i]")
			{
			$Scalls =		($Scalls + $PCpause_sec[$i]);
			$status_found++;
			}
		$i++;
		}
	### END loop through each stat line ###
	if ($status_found < 1)
		{
		$SUMstatusesHTML .= "        0 |";
		$$total_var=0;
		}
	else
		{
		$TOTtotPAUSE = ($TOTtotPAUSE + $Scalls);
		$$total_var=$Scalls;

		$USERsumstatPAUSE_MS =		sec_convert($Scalls,$TIME_H_agentperfdetail); 
		$pfUSERsumstatPAUSE_MS =	sprintf("%8s", $USERsumstatPAUSE_MS);

		$SUMstatusTXT = sprintf("%8s", $pfUSERsumstatPAUSE_MS);
		$SUMstatusesHTML .= " $SUMstatusTXT |";
		$CSVSUMstatuses.=",\"$USERsumstatPAUSE_MS\"";
		}
	$n++;
	}
### END loop through each status ###

	$TOT_AGENTS = sprintf("%-4s", $m);

	$TOTtotPAUSE_MS =		sec_convert($TOTtotPAUSE,$TIME_H_agentperfdetail); 
	$TOTtotNONPAUSE_MS =	sec_convert($TOTtotNONPAUSE,$TIME_H_agentperfdetail); 
	$TOTtotTOTAL_MS =		sec_convert($TOTtotTOTAL,$TIME_H_agentperfdetail); 

	$TOTtotPAUSE_MS =		sprintf("%10s", $TOTtotPAUSE_MS);
	$TOTtotNONPAUSE_MS =	sprintf("%10s", $TOTtotNONPAUSE_MS);
	$TOTtotTOTAL_MS =		sprintf("%12s", $TOTtotTOTAL_MS);

	if ($file_download == 0 || !$file_download)
		{
		while(strlen($TOTtotPAUSE_MS)>10) {$TOTtotPAUSE_MS = substr("$TOTtotPAUSE_MS", 0, -1);}
		while(strlen($TOTtotNONPAUSE_MS)>10) {$TOTtotNONPAUSE_MS = substr("$TOTtotNONPAUSE_MS", 0, -1);}
		while(strlen($TOTtotTOTAL_MS)>12) {$TOTtotTOTAL_MS = substr("$TOTtotTOTAL_MS", 0, -1);}
		}

//$ASCII_text.="+-----------------+----------+----------------------+----------------------+------------+----------+----------+  +$sub_statusesHEAD\n";
$ASCII_text.="<tr><td colspan='4'> "._QXZ("TOTALS",33)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "._QXZ("AGENTS",32,"r").":$TOT_AGENTS </td><td>$TOTtotTOTAL_MS </td><td>$TOTtotNONPAUSE_MS</td><td>$TOTtotPAUSE_MS</td><td> $SUMstatusesHTML</td></tr>";
$ASCII_text.="</tbody></table>";

for ($e=0; $e<count($sub_statusesARY); $e++) {
	$Sstatus=$sub_statusesARY[$e];
	$SstatusTXT=$Sstatus;
	if ($Sstatus=="") {$SstatusTXT="("._QXZ("blank").")";}
	$GRAPH2.="<th class='column_header grey_graph_cell' id='pausegraph".($e+4)."'><a href='#' onClick=\"DrawPauseGraph('$Sstatus', '".($e+4)."'); return false;\">$SstatusTXT</a></th>";
}

for ($d=0; $d<count($graph_stats); $d++) {
	if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
	$TOTAL_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][1], $max_total))."' height='16' />".sec_convert($graph_stats[$d][1], $TIME_H_agentperfdetail)."</td></tr>";
	$NONPAUSE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][2], $max_nonpause))."' height='16' />".sec_convert($graph_stats[$d][2], $TIME_H_agentperfdetail)."</td></tr>";
	$PAUSE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][3], $max_pause))."' height='16' />".sec_convert($graph_stats[$d][3], $TIME_H_agentperfdetail)."</td></tr>";

	for ($e=0; $e<count($sub_statusesARY); $e++) {
		$Sstatus=$sub_statusesARY[$e];
		$varname=$Sstatus."_graph";
		$max_varname="max_".$Sstatus;
	
		$$varname.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(MathZDC(400*$graph_stats[$d][($e+4)], $$max_varname))."' height='16' />".sec_convert($graph_stats[$d][($e+4)], $TIME_H_agentperfdetail)."</td></tr>";
	}
}

$TOTAL_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("LOGIN TIME").":</th><th class='thgraph' scope='col'>".trim($TOTtotTOTAL_MS)."</th></tr></table>";
$NONPAUSE_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("LOGIN TIME").":</th><th class='thgraph' scope='col'>".trim($TOTtotNONPAUSE_MS)."</th></tr></table>";
$PAUSE_graph.="<tr><th class='thgraph' scope='col'>"._QXZ("LOGIN TIME").":</th><th class='thgraph' scope='col'>".trim($TOTtotPAUSE_MS)."%</th></tr></table>";
for ($e=0; $e<count($sub_statusesARY); $e++) {
	$Sstatus=$sub_statusesARY[$e];
	$total_var=$Sstatus."_total";
	$graph_var=$Sstatus."_graph";
	$$graph_var.="<tr><th class='thgraph' scope='col'>"._QXZ("TOTAL").":</th><th class='thgraph' scope='col'>".trim(sec_convert($$total_var, $TIME_H_agentperfdetail))."</th></tr></table>";
}
$JS_onload.="\tDrawPauseGraph('TOTAL', '1');\n"; 
$JS_text="<script language='Javascript'>\n";
$JS_text.="function DrawPauseGraph(graph, th_id) {\n";
$JS_text.="	var graph_TOTAL=\"$TOTAL_graph\";\n";
$JS_text.="	var graph_NONPAUSE=\"$NONPAUSE_graph\";\n";
$JS_text.="	var graph_PAUSE=\"$PAUSE_graph\";\n";

for ($e=0; $e<count($sub_statusesARY); $e++) {
	$Sstatus=$sub_statusesARY[$e];
	$graph_var=$Sstatus."_graph";
	$JS_text.="	var graph_".$Sstatus."=\"".$$graph_var."\";\n";
}

$JS_text.="	for (var i=1; i<=".(3+count($sub_statusesARY))."; i++) {\n";
$JS_text.="		var cellID=\"pausegraph\"+i;\n";
$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
$JS_text.="	}\n";
$JS_text.="	var cellID=\"pausegraph\"+th_id;\n";
$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
$JS_text.="\n";
$JS_text.="	var graph_to_display=eval(\"graph_\"+graph);\n";
$JS_text.="	document.getElementById('pause_detail_graph').innerHTML=graph_to_display;\n";
$JS_text.="}\n";
$JS_onload.="}\n";
if ($report_display_type=='HTML') {$JS_text.=$JS_onload;}
$JS_text.="</script>\n";
$GRAPH3="<tr><td colspan='".(3+count($sub_statusesARY))."' class='graph_span_cell'><span id='pause_detail_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";

$GRAPH_text.=$JS_text.$GRAPH.$GRAPH2.$GRAPH3.$max;



$CSV_total=preg_replace('/\s/', '', "\"\",\"\",\""._QXZ("TOTALS")."\",\""._QXZ("AGENTS").":$TOT_AGENTS\",\"$TOTtotTOTAL_MS\",\"$TOTtotNONPAUSE_MS\",\"$TOTtotPAUSE_MS\",$CSVSUMstatuses");

if ($file_download == 2)
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_PAUSE_CODE_BREAKDOWN$US$FILE_TIME.csv";

	// We'll be outputting a TXT file
	header('Content-type: application/octet-stream');

	// It will be called LIST_101_20090209-121212.txt
	header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	ob_clean();
	flush();

	echo "$CSV_header$CSV_lines$CSV_total";

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

	exit;
	}
$CSV_report=fopen("AST_pause_code_breakdown.csv", "w");
fwrite($CSV_report, $CSV_header);
fwrite($CSV_report, $CSV_lines);
fwrite($CSV_report, $CSV_total);

if ($report_display_type=="HTML")
	{
	$HTML_text.=$GRAPH_text;
	}
else 
	{
	$HTML_text.=$ASCII_text;
	}

$HTML_text.="\n\n<BR>$db_source";
$HTML_text.="</TD></TR></TABLE>";

$HTML_text.="</BODY></HTML>";
}


if ($file_download == 0 || !$file_download) 
	{
	echo $HTML_head;
	require("admin_header.php");
	echo $HTML_text;
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

exit;
?>

