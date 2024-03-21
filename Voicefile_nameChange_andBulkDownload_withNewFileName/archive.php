<?php
 ini_set('max_execution_time', 6900);
 ini_set('memory_limit','1024M');


 date_default_timezone_set('Asia/Kolkata');
 $date_range = $_POST['daterange'];
 
 $txt_campaign = $_POST['txt_campaign'];
 $txt_campaign = strtoupper($txt_campaign);
 $user = $_POST['user'];
 unlink('backup_$user.tgz'); 
 $ex = explode("-",$date_range);
 $start_date = $ex[0];
 $end_date   = $ex[1];

$from1 = trim($ex[0]," ");
$to1 = trim($ex[1]," ");

$from1_split = explode("/",$from1);
$from1_day = $from1_split[1];
$from1_month = $from1_split[0];
$from1_year = $from1_split[2];
$from = $from1_year."-".$from1_month."-".$from1_day;

$to1_split = explode("/",$to1);
$to1_day = $to1_split[1];
$to1_month = $to1_split[0];
$to1_year = $to1_split[2];
$to = $to1_year."-".$to1_month."-".$to1_day;
 
 $start_date = strtotime($start_date);
 $st_convert =date('Y-m-d',$start_date);
 
 $end_date = strtotime($end_date);
 $en_convert =date('Y-m-d',$end_date);


$current_date = date('Y-m-d');

$total_days = round(abs(strtotime($en_convert) - strtotime($st_convert)) / 86400, 0) + 1;

$hostname = 'localhost';
$host_user = 'root';
$password = 'Hal0o(0m@72427242';
$database = 'asterisk';
$conn = mysqli_connect($hostname, $host_user, $password, $database);

$stmt = "select 1 from vicidial_log where campaign_id='$txt_campaign' and call_date >= '$from 00:00:00' and call_date <= '$to 23:59:59' limit 1 ";
$rslt = mysqli_query($conn,$stmt);
$count = mysqli_num_rows($rslt);

if($count == 0){
	echo "Data not Available";
	header("Location: page2.php?download=fail&user=$user&cam=$txt_campaign");
	exit;
}
else
{

if($txt_campaign !=''){


if ($en_convert >= $st_convert)
{
	for ($day = 0; $day < $total_days; $day++)
	{
		$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		$date.="voicefiles_campaign/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))."/$txt_campaign"." ";

	}
}

}
else{
	if ($en_convert >= $st_convert)
	{
		for ($day = 0; $day < $total_days; $day++)
		{
			$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
			$date.="voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))."/ ";
			
		}
	}
}

}
//echo "<pre>";
//print_r($date);
//$val = "tar czfP backup_$user.tgz $date";
shell_exec("tar czfP backup_$user.tgz $date");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user&cam=$txt_campaign");
?>


