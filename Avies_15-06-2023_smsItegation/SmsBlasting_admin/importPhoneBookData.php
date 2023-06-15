<?php
// Load the database configuration file
require('dbconnect_mysqli.php');
//echo "test";exit;

$entry_date_time = date('Y-m-d H:i:s');
if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
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
				$name    = $line[1];
				
				$sel_data = 'select count(*) from sms_blasting where phone_number = "'.$phone_number.'" ';
				$res_data = mysqli_query($link,$sel_data);
				$row_data = mysqli_fetch_array($res_data);
				
				if($row_data[0] > 0){
					
					//Dublicate files checking.. for avoiding 
				}
				else{
				   // Insert member data in the database
					$sql_insert = 'insert into sms_blasting (name,phone_number) values("'.$name.'","'.$phone_number.'")';
					  
				    $res_insert =mysqli_query($link,$sql_insert);	
			    }
                	  
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location:admin_uploadPhone_book.php".$qstring);