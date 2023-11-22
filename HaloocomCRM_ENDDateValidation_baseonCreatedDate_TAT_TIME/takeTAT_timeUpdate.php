<?php 

	date_default_timezone_set('Asia/Kolkata');

		$startDate = $_REQUEST['date_entered'];	
		$endDate = $_REQUEST['endDate'];
		if($startDate !="" && $endDate !=""){
			
			$input_format = "Y-m-d h:i A";
			$output_format = 'Y-m-d G:i';
			$date =  DateTime::createFromFormat($input_format, $startDate);
			$startDateVal = $date->format($output_format); 

			$date2 =  DateTime::createFromFormat($input_format, $endDate);
			$endDateVal = $date2->format($output_format); 

			//getting time diff//
			$datetime1 = new DateTime($startDateVal);
			$datetime2 = new DateTime($endDateVal);
			$interval = $datetime1->diff($datetime2);
			$elapsed = $interval->format('%H:%I:%S');
			
			echo $elapsed;
	
		}
		
			
		?>