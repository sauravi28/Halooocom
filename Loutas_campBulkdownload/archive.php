<?php
 ini_set('max_execution_time', 6900);
 ini_set('memory_limit','1024M');


date_default_timezone_set('Asia/Kolkata');
 
 //VoiceLog DB//
$hostname='localhost';
$user2 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'Voicelogs';
$link = mysqli_connect($hostname, $user2, $password,$mysql_database);

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

if($txt_campaign !=''){
		if ($en_convert >= $st_convert)
		 {
			for ($day = 0; $day < $total_days; $day++)
			{
				$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
			   
				$mydir ="/srv/www/htdocs/Haloo_Voicelogs/voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))."/";// $date;
				//echo $mydir."<br>";
				$myfiles = array_diff(scandir($mydir), array('.', '..'));   
				foreach($myfiles as $myfilesname){
					if (strpos($myfilesname, $txt_campaign) !== false) {				
						 $date.="voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))."/".$myfilesname." ";
					}
				
				}
				
			}
		}

}
else{
	if ($en_convert >= $st_convert)
	 {
		for ($day = 0; $day < $total_days; $day++)
		{
			$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
			$date.="voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))." ";
			
		}
	}
}
}
//$val = "tar czfP backup_$user.tgz $date";

shell_exec("tar czfP backup_$user.tgz $date");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user&cam=$txt_campaign");
?>
