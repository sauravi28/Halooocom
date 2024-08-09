<?php
require_once("dbconnect_mysqli.php");

$user_logged = $_REQUEST['user'];

//echo $user_logged;

$date_login =date('Y-m-d');
$date_login = $date_login." 00:00:00";
//echo $VD_login;
$SuserName = trim($user_logged);

$sel_lg = "select sum(wait_sec+talk_sec+dispo_sec+dead_sec+pause_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%' ";
$res_lg = mysqli_query($link,$sel_lg);
$row_lg = mysqli_fetch_array($res_lg);


$login_sec = $row_lg[0];
$login_sec1 = $row_lg[0];

if($login_sec <= '3600')
{
$login_sec = 1;
}
else
{	
$login_sec = gmdate("H", $login_sec);	
}
$login_time = gmdate("H:i:s", $login_sec1);

//echo $login_time;

//talk time//

/*$sel_tk = "select sum(talk_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%'";
$res_tk = mysqli_query($link,$sel_tk);
$row_tk = mysqli_fetch_array($res_tk);
$tk_sec = $row_tk[0];
$tk_time = gmdate("H:i:s", $tk_sec);

//echo $tk_time;
//Pause time//

$sel_bk = "select sum(pause_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%'";
//echo $sel_bk;
$res_bk = mysqli_query($link,$sel_bk);
$row_bk = mysqli_fetch_array($res_bk);
$bk_sec = $row_bk[0];
$bk_time = gmdate("H:i:s", $bk_sec);
//echo $bk_time;*/

//total IB calls//
$sel_ib = "select count(*) from vicidial_closer_log where call_date >='$date_login' and  user LIKE '$SuserName%' and status !='DROP'";
$res_ib = mysqli_query($link,$sel_ib);
$row_ib = mysqli_fetch_array($res_ib);
$ib   = $row_ib[0];
$totib = $totib + $ib;



//total outB calls//
$sel_outb = "select count(*) from vicidial_log where call_date >='$date_login' and  user LIKE '$SuserName%' and status !='DROP'";
$res_outb = mysqli_query($link,$sel_outb);
$row_outb = mysqli_fetch_array($res_outb);
$outb   = $row_outb[0];
$totoutb = $totoutb + $outb;


//Pause time//

$sel_pause_sec = "select sum(pause_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%' and sub_status='SB'";
//echo $sel_pause_sec;
$res_pause_sec = mysqli_query($link,$sel_pause_sec);
$row_pause_sec = mysqli_fetch_array($res_pause_sec);
$pause_sec_SB = $row_pause_sec[0];
$pause_time_SB = gmdate("H:i:s", $pause_sec_SB);
//echo $pause_time_SB;

$sel_pause_secMB = "select sum(pause_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%' and sub_status='MB'";
//echo $sel_pause_secMB;
$res_pause_secMB = mysqli_query($link,$sel_pause_secMB);
$row_pause_secMB = mysqli_fetch_array($res_pause_secMB);
$pause_sec_MB = $row_pause_secMB[0];
$pause_time_MB = gmdate("H:i:s", $pause_sec_MB);

$sel_pause_secBAUX = "select sum(pause_sec) from vicidial_agent_log where event_time >='$date_login' and user LIKE '$SuserName%' and sub_status='BAUX'";
//echo $sel_pause_secBAUX;
$res_pause_secBAUX = mysqli_query($link,$sel_pause_secBAUX);
$row_pause_secBAUX = mysqli_fetch_array($res_pause_secBAUX);
$pause_sec_BAUX = $row_pause_secBAUX[0];
$pause_time_BAUX = gmdate("H:i:s", $pause_sec_BAUX);

$TotalPause = $pause_sec_SB + $pause_sec_MB + $pause_sec_BAUX ;
$TotalPause_time = gmdate("H:i:s", $TotalPause);


echo $login_time;

echo "==";

echo $totib;

echo "==";

echo $totoutb;

echo "==";

echo $pause_time_SB;

echo "==";

echo $pause_time_MB;

echo "==";

echo $pause_time_BAUX;

echo "==";

echo $TotalPause_time;




?>