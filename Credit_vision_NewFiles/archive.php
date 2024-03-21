<?php
 ini_set('max_execution_time', 6900);
 ini_set('memory_limit','1024M');

//asterisk//
$hostname='localhost';
$user1 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'asterisk';
$db = mysqli_connect($hostname, $user1, $password,$mysql_database);

//VoiceLog DB//
$hostname='localhost';
$user2 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'Voicelogs';
$link = mysqli_connect($hostname, $user2, $password,$mysql_database);


 date_default_timezone_set('Asia/Kolkata');
 $date_range = $_POST['daterange'];
 
 $txt_campaign = $_POST['txt_campaign'];
 //$txt_campaign = strtoupper($txt_campaign);
 
 $multicamparray = array();
 // Retrieving each selected option
    foreach ($txt_campaign as $subject) {
               
			   array_push($multicamparray,$subject);
            }
 
$strselectedCamp = implode("','",$multicamparray);
//die;
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

$stmt = "select 1 from vicidial_log where campaign_id in ('$strselectedCamp') and call_date >= '$from 00:00:00' and call_date <= '$to 23:59:59' limit 1 ";
$rslt = mysqli_query($db,$stmt);
$count = mysqli_num_rows($rslt);

if($count == 0){
	echo "Data not Available";
	header("Location: page2.php?download=fail&user=$user&cam=$txt_campaign");
	exit;
}
else
{
if ($en_convert >= $st_convert)
 {
	$y='';
  	for ($day = 0; $day < $total_days; $day++)
  	{
    	$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
  	    $datetest = "voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
		$fromDate = date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
				$selectVoiceFile1 = "SELECT filename FROM voicefiles WHERE campaign in ('$strselectedCamp')and date='$fromDate'";
				//echo $selectVoiceFile1;
				//die;
				$res_voiceFile1 = mysqli_query($link,$selectVoiceFile1);
				//$rowVoiceFile1 = mysqli_fetch_array($res_voiceFile1);
				//echo $date."/".$rowVoiceFile1[0]."<br>";
					
					while($row_voiceFile1 = mysqli_fetch_array($res_voiceFile1))
					{
						 $y.=$datetest."/".$row_voiceFile1[0]."/ "; 
								 
					}
				
		
			
	}
	
}
}
//echo "<pre>";
//print_r($y);
//$val = "tar czfP backup_$user.tgz $date";
shell_exec("tar czfP backup_$user.tgz $y");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user");
?>
