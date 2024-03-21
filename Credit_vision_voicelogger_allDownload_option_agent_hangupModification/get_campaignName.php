<?php
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

 $user_groupId = $_POST["user_groupID"];
 $date_range = $_POST["dateVal"];
 //echo"==>date_range".$date_range;
 $ex = explode("-",$date_range);
 $start_date = $ex[0];
 $end_date   = $ex[1];
 $start_date = strtotime($start_date);
 $st_convert =date('Y-m-d',$start_date);
 
 $end_date = strtotime($end_date);
 $en_convert =date('Y-m-d',$end_date);
 
 
 
								$sql1 = "select allowed_campaigns from vicidial_user_groups where user_group = '$user_groupId'";
								echo "==> UserGroup ==>".$sql1;
								//echo "<br>";
								$res1 = mysqli_query($db,$sql1);
								$row1 = mysqli_fetch_array($res1);
								$campaign = trim($row1[0]);
								
								if($campaign == "-ALL-CAMPAIGNS- -")
				                {
									//$query = "SELECT campaign_id FROM vicidial_campaigns";
									$query ="SELECT campaign FROM voicefiles WHERE date >='$st_convert' and date <= '$en_convert' group by(`campaign`)";
								    //echo $query;
								    $result = mysqli_query($link,$query);
									echo "<option value='all'>--Select all--</option>";
									while ($row = mysqli_fetch_array($result)){
										echo "<option value = ".$row['campaign'].">" . $row['campaign'] . "</option>";
									}	
								}
								else{
									
									$arr = explode(" ",$campaign);
									//print_r($arr);
									$str = implode("','",$arr);
									
									//$selectcamp = "SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id in ('$str')";
									$selectcamp = "SELECT campaign FROM voicefiles WHERE campaign in ('$str') and date >='$st_convert' and date <= '$en_convert' group by(`campaign`)";
									echo $selectcamp;
									$result1 = mysqli_query($link,$selectcamp);
									echo "<option value='all'>--Select all--</option>";
									while ($row1 = mysqli_fetch_array($result1)){
										echo "<option value = ".$row1['campaign'].">" . $row1['campaign'] . "</option>";
									}	
									
								}
								
 

?>