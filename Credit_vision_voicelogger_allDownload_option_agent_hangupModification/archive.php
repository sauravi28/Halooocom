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
 
 $start_date = strtotime($start_date);
 $st_convert =date('Y-m-d',$start_date);
 
 $end_date = strtotime($end_date);
 $en_convert =date('Y-m-d',$end_date);


$current_date = date('Y-m-d');

$total_days = round(abs(strtotime($en_convert) - strtotime($st_convert)) / 86400, 0) + 1;

if ($en_convert >= $st_convert)
 {
	$y='';
  	for ($day = 0; $day < $total_days; $day++)
  	{
    	$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
  	    $datetest = "voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
		$fromDate = date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
		
		
		
               /* $sql = "select user_group from vicidial_users where user = '$user' ";
				//echo "==> UserGroup ==>".$sql;
				//echo "<br>";
				$res = mysqli_query($db,$sql);
				$row = mysqli_fetch_array($res);
				$sql1 = "select allowed_campaigns from vicidial_user_groups where user_group = '$row[0]'";
				//echo "==> UserGroup ==>".$sql1;
				//echo "<br>";
				$res1 = mysqli_query($db,$sql1);
				$row1 = mysqli_fetch_array($res1);
				$campaign = trim($row1[0]);
				
				if($campaign == "-ALL-CAMPAIGNS- -")
				{
					$sql_all_camp = "select campaign_id from vicidial_campaigns";
					//echo $sql_all_camp."<br>";	
					$res_camp = mysqli_query($db,$sql_all_camp);
					
					while($row_camp = mysqli_fetch_array($res_camp))
					{
						$campaign_all .= trim($row_camp[0].",");
						
					}
					//echo $campaign_all;
					$arr = explode(",",$campaign_all);
					//print_r($arr);
					$str = implode("','",$arr);	
					$selectVoiceFile = "SELECT filename FROM voicefiles WHERE campaign in ('$str')and date='$fromDate'";
					$res_voiceFile = mysqli_query($link,$selectVoiceFile);
					//$rowVoiceFile = mysqli_fetch_array($res_voiceFile);
					//echo $date."/".$rowVoiceFile[0]."<br>";
					
					while($row_voiceFile = mysqli_fetch_array($res_voiceFile))
					{
						//echo $datenew.= $datetest."/".$row_voiceFile[0];
						//echo $datenew = $datetest."/20210710-123716_8688787956_sauravi_QUESS_CB-all.gsm  //20210710-123859_8688787956_sauravi_QUESS_CB-all.gsm";
						//echo " <br>";
						$y.=$datetest."/".$row_voiceFile[0]."/ "; 
					}
					
				}*/
				/*else {
				$arr = explode(" ",$campaign);
				//print_r($arr);
				$str = implode("','",$arr);	*/
				
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
				
				/*}*/	
			
				//echo $date."/".$voiceFiles."<br>";
				//$date1.= $date."/".$voiceFiles;	
		
		
	
		/*$myfiles1 = scandir($datetest, 1);	
		
		foreach($myfiles1 as $voiceFiles){
			
            if(substr($voiceFiles,-4) == ".gsm"){

            }			
		}*/
		
	}
}
//print_r($y);
//$val = "tar czfP backup_$user.tgz $date";
shell_exec("tar czfP backup_$user.tgz $y");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user");
?>
