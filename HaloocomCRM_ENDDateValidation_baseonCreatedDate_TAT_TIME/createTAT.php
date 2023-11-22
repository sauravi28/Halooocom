<?php


	date_default_timezone_set('Asia/Kolkata');


	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'haloocomCRM';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	//$currant_date = date('Y-m-d');
	//echo "==>".$currant_date_time;
	
    $select_stmt = "select id,date_entered,CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time from cases where state in ('Closed','Resolved')";
											
					//echo $select_stmt;
					//die;
					$resultstmt = mysqli_query($link,$select_stmt);
					//$row1 = mysqli_fetch_array($resultstmt);
					
					while($row1 = mysqli_fetch_array($resultstmt)){
						
						$id = $row1['id'];
						$time = $row1['time'];
						$date_created = date("Y-m-d h:i A", strtotime($time));
						//echo "<br>";
						
						$sql_endDate = "SELECT CONVERT_TZ(`end_date_c`,'+00:00','+05:30') as end_datetime FROM `cases_cstm` WHERE `id_c` = '$id'";
						//echo $sql_endDate;
						//echo "<br>";
						$res_endDate = mysqli_query($link,$sql_endDate);
						$row_endDate = mysqli_fetch_array($res_endDate);
						
						$endDate = $row_endDate['end_datetime'];
						$date_end = date("Y-m-d h:i A", strtotime($endDate));	
						
						$input_format = "Y-m-d h:i A";
						$output_format = 'Y-m-d G:i';
						$date =  DateTime::createFromFormat($input_format, $date_created);
						$startDateVal = $date->format($output_format); 

						$date2 =  DateTime::createFromFormat($input_format, $date_end);
						$endDateVal = $date2->format($output_format); 

						//getting time diff//
						$datetime1 = new DateTime($startDateVal);
						$datetime2 = new DateTime($endDateVal);
						$interval = $datetime1->diff($datetime2);
						$elapsed = $interval->format('%H:%I:%S');
						//echo "<br>";
						//echo $elapsed;
	
							$update_TAT = "update cases_cstm set tat_time_c = '$elapsed' where id_c = '$id'"; 
							mysqli_query($link,$update_TAT);
							
						
						
						
					}

?>