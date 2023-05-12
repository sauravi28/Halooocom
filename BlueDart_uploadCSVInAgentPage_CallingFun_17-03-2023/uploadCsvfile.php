<?php
date_default_timezone_set('Asia/Kolkata');
//upload.php

	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$campaign=$_REQUEST['campaign'];

session_start();

$error = '';

$html = '';


$entry_date_time = date('Y-m-d H:i:s');

if($_FILES['file']['name'] != '')
{
 $file_array = explode(".", $_FILES['file']['name']);

 $extension = end($file_array);

 if($extension == 'csv')
 {
	  
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
				
				$phone_number  = $line[0];
                $first_name    = $line[1];
				
				/*$sel_data = 'select count(*) from bluedart_list_upload where phone_number = "'.$phone_number.'" ';
				$res_data = mysqli_query($link,$sel_data);
				$row_data = mysqli_fetch_array($res_data);
				
				if($row_data[0] > 0){
					
					//Dublicate files checking.. for avoiding 
				}
				else{*/
				   // Insert member data in the database
					$sql_insert = 'insert into bluedart_list_upload (phone_number,customer_name,entry_date_time,campaign) values("'.$phone_number.'","'.$first_name.'","'.$entry_date_time.'","'.$campaign.'")';
					  
				    $res_insert =mysqli_query($link,$sql_insert);	
			    //}
                	  
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
              $error = 'File Uploaded Sussfully !!';
        }
		else{
             $error = 'File Not Uploaded';
        }
 }
 else
 {
  $error = 'Only CSV File Allowed.. Please Select CSV File';
 }
}
else
{
 $error = 'Please Select CSV File';
}

$output = array(
 'error'  => $error,
 'output' => $html
);

echo json_encode($output);


?>

