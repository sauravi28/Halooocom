<?php
 ini_set('max_execution_time', 6900);
 ini_set('memory_limit','1024M');

//VoiceLog DB//
$hostname='localhost';
$user2 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'Voicelogs';
$link = mysqli_connect($hostname, $user2, $password,$mysql_database);


 date_default_timezone_set('Asia/Kolkata');
 $date_range = $_POST['daterange'];
 $txt_campaign = $_POST['txt_campaign'];
 $txt_campaign = strtoupper($txt_campaign);
 $user = $_POST['user'];
 unlink('backup_$user.tgz'); 
 $ex = explode("-",$date_range);
 $start_date = $ex[0];
 $end_date   = $ex[1];
 
 $start_date = strtotime($start_date);
 $st_convert =date('Y-m-d',$start_date);
 
 $end_date = strtotime($end_date);
 $en_convert =date('Y-m-d',$end_date);


$current_date = date('Y-m-d');

$total_days = round(abs(strtotime($en_convert) - strtotime($st_convert)) / 86400, 0) + 1;

$stmt = "SELECT count(campaign) FROM voicefiles WHERE campaign='$txt_campaign' and date >='$st_convert' and date <= '$en_convert'";
//echo $stmt;
//die;
$res1 = mysqli_query($link,$stmt);
$row1 = mysqli_fetch_array($res1);
$count = $row1[0];
				
if($count == 0){
	//echo "inside";
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
		
				
				$selectVoiceFile1 = "SELECT filename FROM voicefiles WHERE campaign = '$txt_campaign' and date='$fromDate'";
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
//print_r($y);
//$val = "tar czfP backup_$user.tgz $date";
shell_exec("tar czfP backup_$user.tgz $y");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user&cam=$txt_campaign");
?>
