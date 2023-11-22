<?php 

date_default_timezone_set('Asia/Kolkata');

		$startDate = $_REQUEST['date_entered'];	
		$endDate = $_REQUEST['endDate'];
		
		if($startDate == ""){
			$startDate = date("Y-m-d H:i:s");
		}
		else {
			$startDate = $_REQUEST['date_entered'];
		}
		$input_format = "Y-m-d h:i A";
		$output_format = 'Y-m-d G:i';
		$date =  DateTime::createFromFormat($input_format, $startDate);
		$startDateVal = $date->format($output_format); 

		$date2 =  DateTime::createFromFormat($input_format, $endDate);
		$endDateVal = $date2->format($output_format); 

		$A = strtotime($startDateVal); 
		$B = strtotime($endDateVal);
		
		//getting time diff//
		$datetime1 = new DateTime($startDateVal);
		$datetime2 = new DateTime($endDateVal);
		$interval = $datetime1->diff($datetime2);
		$elapsed = $interval->format('%H:%I:%S');


		if ($A >= $B)
		{  
			echo "1";
		}
		else if($elapsed <='00:15:00')
		{
			echo "2";
		}
		else 
		{
			echo $elapsed;
		}
 
		
			
		?>