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
				
				$Circle  = $line[0];
                $Product    = $line[1];
				$Report_Generation_Date = $line[2];
				$SubscriberBPID = $line[3];
				$AccountID = $line[4];
				$CustomerName = $line[5];
				$PAIDAMT = $line[6];
				$DATE = $line[7];
				$TCNAME = $line[8];
				$NewCode = $line[9];
				
			
				$sel_data = 'select count(*) from vicidial_upload_DND_no_phonebook where accountID = "'.$AccountID.'" ';
				$res_data = mysqli_query($link,$sel_data);
				$row_data = mysqli_fetch_array($res_data);
				
				if($row_data[0] > 0){
					
					//Dublicate files checking.. for avoiding 
				}
				else{
				   // Insert member data in the database
					$sql_insert = 'insert into vicidial_upload_DND_no_phonebook (circle,product,report_generation_date,subscriberBPID,accountID,customer_name,paid_amt,date,tc_name,new_code,entry_date_time) values("'.$Circle.'","'.$Product.'","'.$Report_Generation_Date.'","'.$SubscriberBPID.'","'.$AccountID.'","'.$CustomerName.'","'.$PAIDAMT.'","'.$DATE.'","'.$TCNAME.'","'.$NewCode.'","'.$entry_date_time.'")'; 
				    $res_insert =mysqli_query($link,$sql_insert);

					
			    }
                	  
            }
			
			$select_stmt = "SELECT `accountID` FROM `vicidial_upload_DND_no_phonebook`";
			$resultstmt = mysqli_query($link,$select_stmt);
			//$row1 = mysqli_fetch_array($resultstmt);
			
			while($row1 = mysqli_fetch_array($resultstmt)){
				
				$accountIDVal = $row1[0];

				$select_phone = "SELECT lead_id FROM `vicidial_list` where `address3`='$accountIDVal' and `status`='NEW'";
				//echo $select_phone;
			    //echo "<br>";
						
				$resultstmtPH = mysqli_query($link,$select_phone);
			
					while($row2 = mysqli_fetch_array($resultstmtPH)){
						
						$lead_id = $row2[0];	
						$sql_queryd = "delete from vicidial_list where address3='$accountIDVal' and lead_id = '$lead_id'";
						//echo $sql_queryd;
						$resultd = mysqli_query($link,$sql_queryd);
						
						//$update_value = 'update vicidial_list set status = "Paid",called_since_last_reset="Y",modify_date="'.$entry_date_time.'" where phone_number = "'.$phone_numberVal.'" and lead_id="'.$lead_id.'"'; 
						//mysqli_query($link,$update_value);
						   
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
header("Location:admin_uploadPhone_book_DND.php".$qstring);