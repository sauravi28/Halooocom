<?php
 ini_set('max_execution_time', 6900);
 ini_set('memory_limit','1024M');


 date_default_timezone_set('Asia/Kolkata');
 $date_range = $_POST['daterange'];
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
  	for ($day = 0; $day < $total_days; $day++)
  	{
    	$dates[]= date("Y-m-d", strtotime("{$st_convert} + {$day} days"));
  	    $date.="voicefiles/".date("Y-m-d", strtotime("{$st_convert} + {$day} days"))." ";
		
	}
}
print_r($date);
//$val = "tar czfP backup_$user.tgz $date";
shell_exec("tar czfP backup_$user.tgz $date");

//##########################################
//##########################################
header("Location: page2.php?download=ok&user=$user");
?>
