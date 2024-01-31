<?php
// Load the database configuration file
require('dbconnect_mysqli.php');
//echo "test";exit;

$entry_date_time = date('Y-m-d H:i:s');

if(isset($_POST['importSubmit'])){
    
	$uploadFile = $_FILES['file']['name'];
	//echo $uploadFile;
	//die;
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
		//echo "inside 1st if";
		//echo "<br>";
		
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile1 = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile1);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile1)) !== FALSE){
                // Get row data
				
				$ACCNO  = $line[0];
                $FINAL_CITY = $line[1];
				$STATE = $line[2];
				$ZONE = $line[3];
				$NAME = $line[4];
				$POS = $line[5];
				$EMI_AMT = $line[6];
				$TOTAL_OVERDUE = $line[7];
				$LAST_PAYMENT_AMOUNT = $line[8];
				$LAST_PAYMENT_DATE = $line[9];
				$Due = $line[10];
				$M0 = $line[11];
				$M1 = $line[12]; 
				$M2 = $line[13];
				$M3 = $line[14];
				$AllocationType = $line[15];
				
			
				
				   // Insert member data in the database
					$sql_insert = 'insert into vicidial_Accountdetails (accno,final_city,state,zone,name,pos,emi_amt,total_overdue,last_payment_amount,last_payment_date,due,m0,m1,m2,m3,allocationtype,entry_date_time,fileName) 
					values("'.$ACCNO.'","'.$FINAL_CITY.'","'.$STATE.'","'.$ZONE.'","'.$NAME.'","'.$POS.'","'.$EMI_AMT.'","'.$TOTAL_OVERDUE.'","'.$LAST_PAYMENT_AMOUNT.'","'.$LAST_PAYMENT_DATE.'","'.$Due.'","'.$M0.'","'.$M1.'","'.$M2.'","'.$M3.'","'.$AllocationType.'","'.$entry_date_time.'","'.$uploadFile.'")'; 
				   // echo $sql_insert;
					//die;
					$res_insert =mysqli_query($link,$sql_insert);

				
                	  
            }
			
			
            // Close opened CSV file
            fclose($csvFile1);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }
	if(!empty($_FILES['file_contactdetails']['name']) && in_array($_FILES['file_contactdetails']['type'], $csvMimes)){
        
		//echo "inside 2st if";
		//echo "<br>";
		
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file_contactdetails']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file_contactdetails']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
			
			$contactdetails = array();
			
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
				
				$ACCOUNT_REFERENCE  = $line[0];
                $FINANCIER_ID = $line[1];
				$CURRENTADDRESS = $line[2];
				$CURRENTFINALCITY = $line[3];
				//$PHN_1_CURR_CONTACT1 = $line[4];
				//$PHN_3_CURR_MOBILE1 = $line[5];
				$PERMANENT_EMAIL_ADDRESS = $line[6];
				$PERMANENTFINALCITY = $line[7];
				//$PHN_5_PERM_CONTACT1 = $line[8];   
				//$PHN_7_PERM_MOBILE1 = $line[9];
				$WORKADDRESS = $line[10];
				$WORKFINALCITY = $line[11];
				//$PHN_10_WORK_CONTACT2 = $line[12]; 
				//$PHN_11_WORK_MOBILE1 = $line[13];
				//$PHN_9_WORK_CONTACT1 = $line[14];
				//$LAST_PHONE_CONTACTED = $line[15];
				//$PHONENUMBER1 = $line[16];
				//$PHONENUMBER2 = $line[17];
				//$PHONENUMBER3 = $line[18];
				
				
				$PHN_1_CURR_CONTACT1_len = $line[4];
				$PHN_3_CURR_MOBILE1_len = $line[5];
				$PHN_5_PERM_CONTACT1_len = $line[8];   
				$PHN_7_PERM_MOBILE1_len = $line[9];
				$PHN_10_WORK_CONTACT2_len = $line[12]; 
				$PHN_11_WORK_MOBILE1_len = $line[13];
				$PHN_9_WORK_CONTACT1_len = $line[14];
				$LAST_PHONE_CONTACTED_len = $line[15];
				$PHONENUMBER1_len = $line[16];
				$PHONENUMBER2_len = $line[17];
				$PHONENUMBER3_len = $line[18];
				
				if(strlen($PHN_1_CURR_CONTACT1_len) == 10){
					$PHN_1_CURR_CONTACT1 = $PHN_1_CURR_CONTACT1_len;
				}else{$PHN_1_CURR_CONTACT1 = '';}
				if(strlen($PHN_3_CURR_MOBILE1_len) == 10){
					$PHN_3_CURR_MOBILE1 = $PHN_3_CURR_MOBILE1_len;
				}else{$PHN_3_CURR_MOBILE1 = '';}
				if(strlen($PHN_5_PERM_CONTACT1_len) == 10){
					$PHN_5_PERM_CONTACT1 = $PHN_5_PERM_CONTACT1_len;
				}else{$PHN_5_PERM_CONTACT1 = '';}
				if(strlen($PHN_7_PERM_MOBILE1_len) == 10){
					$PHN_7_PERM_MOBILE1 = $PHN_7_PERM_MOBILE1_len;
				}else{$PHN_7_PERM_MOBILE1 = '';}
				if(strlen($PHN_10_WORK_CONTACT2_len) == 10){
					$PHN_10_WORK_CONTACT2 = $PHN_10_WORK_CONTACT2_len;
				}else{$PHN_10_WORK_CONTACT2 = '';}
				if(strlen($PHN_11_WORK_MOBILE1_len) == 10){
					$PHN_11_WORK_MOBILE1 = $PHN_11_WORK_MOBILE1_len;
				}else{$PHN_11_WORK_MOBILE1 = '';}
				if(strlen($PHN_9_WORK_CONTACT1_len) == 10){
					$PHN_9_WORK_CONTACT1 = $PHN_9_WORK_CONTACT1_len;
				}else{$PHN_9_WORK_CONTACT1 = '';}
				if(strlen($LAST_PHONE_CONTACTED_len) == 10){
					$LAST_PHONE_CONTACTED = $LAST_PHONE_CONTACTED_len;
				}else{$LAST_PHONE_CONTACTED = '';}
				if(strlen($PHONENUMBER1_len) == 10){
					$PHONENUMBER1 = $PHONENUMBER1_len;
				}else{$PHONENUMBER1 = '';}
				if(strlen($PHONENUMBER2_len) == 10){
					$PHONENUMBER2 = $PHONENUMBER2_len;
				}else{$PHONENUMBER2 = '';}
				if(strlen($PHONENUMBER3_len) == 10){
					$PHONENUMBER3 = $PHONENUMBER3_len;
				}else{$PHONENUMBER3 = '';}
				
				$contactdetails=array($PHN_1_CURR_CONTACT1,$PHN_3_CURR_MOBILE1,$PHN_5_PERM_CONTACT1,$PHN_7_PERM_MOBILE1,$PHN_10_WORK_CONTACT2,$PHN_11_WORK_MOBILE1,$PHN_9_WORK_CONTACT1,$LAST_PHONE_CONTACTED,$PHONENUMBER1,$PHONENUMBER2,$PHONENUMBER3);    
				$removeempty = array_filter($contactdetails);
				//print_r($removeempty);
				 //echo "<br>";
				$uniqueVal = array_unique($removeempty);
				//print_r($uniqueVal);
				 //echo "<br>";
				 $new_array = array_values($uniqueVal);
				 //print_r($new_array);
				 //echo "<br>";
				$totalArraycount = count($new_array);
				//echo $totalArraycount;
				 //echo "<br>";
				
				if($totalArraycount == 1){
					//echo " === inside if";
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 ='';
					$PHN_5_PERM_CONTACT1 ='';   
					$PHN_7_PERM_MOBILE1 ='';
					$PHN_10_WORK_CONTACT2 =''; 
					$PHN_11_WORK_MOBILE1 ='';
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
				}else if($totalArraycount == 2){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 ='';   
					$PHN_7_PERM_MOBILE1 ='';
					$PHN_10_WORK_CONTACT2 =''; 
					$PHN_11_WORK_MOBILE1 ='';
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 3){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 ='';
					$PHN_10_WORK_CONTACT2 =''; 
					$PHN_11_WORK_MOBILE1 ='';
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 4){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 =''; 
					$PHN_11_WORK_MOBILE1 ='';
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 5){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 ='';
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 6){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 ='';
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 7){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 = $new_array[6];
					$LAST_PHONE_CONTACTED =''; 
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 8){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 = $new_array[6];
					$LAST_PHONE_CONTACTED = $new_array[7];
					$PHONENUMBER1 ='';
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 9){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 = $new_array[6];
					$LAST_PHONE_CONTACTED = $new_array[7];
					$PHONENUMBER1 = $new_array[8];
					$PHONENUMBER2 ='';
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 10){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 = $new_array[6];
					$LAST_PHONE_CONTACTED = $new_array[7];
					$PHONENUMBER1 = $new_array[8];
					$PHONENUMBER2 = $new_array[9];
					$PHONENUMBER3 ='';
                }else if($totalArraycount == 11){
					$PHN_1_CURR_CONTACT1 = $new_array[0];
					$PHN_3_CURR_MOBILE1 = $new_array[1];
					$PHN_5_PERM_CONTACT1 = $new_array[2];   
					$PHN_7_PERM_MOBILE1 = $new_array[3];
					$PHN_10_WORK_CONTACT2 = $new_array[4];
					$PHN_11_WORK_MOBILE1 = $new_array[5];
					$PHN_9_WORK_CONTACT1 = $new_array[6];
					$LAST_PHONE_CONTACTED = $new_array[7];
					$PHONENUMBER1 = $new_array[8];
					$PHONENUMBER2 = $new_array[9];
					$PHONENUMBER3 = $new_array[10];
                }																	
				 
				   // Insert member data in the database
					$sql_insert1 = 'insert into vicidial_Contactdetails (account_reference,financier_id,currentaddress,currentfinalcity,phn_1_curr_contact1,phn_3_curr_mobile1,permanent_email_address,permanentfinalcity,phn_5_perm_contact1,phn_7_perm_mobile1,workaddress,workfinalcity,phn_10_work_contact2,phn_11_work_mobile1,phn_9_work_contact1,last_phone_contacted,phonenumber1,phonenumber2,phonenumber3,entry_date_time,fileName) 
					values("'.$ACCOUNT_REFERENCE.'","'.$FINANCIER_ID.'","'.$CURRENTADDRESS.'","'.$CURRENTFINALCITY.'","'.$PHN_1_CURR_CONTACT1.'","'.$PHN_3_CURR_MOBILE1.'","'.$PERMANENT_EMAIL_ADDRESS.'","'.$PERMANENTFINALCITY.'","'.$PHN_5_PERM_CONTACT1.'","'.$PHN_7_PERM_MOBILE1.'","'.$WORKADDRESS.'","'.$WORKFINALCITY.'","'.$PHN_10_WORK_CONTACT2.'","'.$PHN_11_WORK_MOBILE1.'","'.$PHN_9_WORK_CONTACT1.'","'.$LAST_PHONE_CONTACTED.'","'.$PHONENUMBER1.'","'.$PHONENUMBER2.'","'.$PHONENUMBER3.'","'.$entry_date_time.'","'.$uploadFile.'")'; 
				   
				   //echo $sql_insert1;
				   
				   $res_insert =mysqli_query($link,$sql_insert1);

                	  
            }
			
			
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
			$uploadFilename='fileNameVal='.$uploadFile;
        }else{
            $qstring = '?status=err';
        }
    }
	
	else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location:admin_uploadAccount_contact.php".$qstring."&".$uploadFilename);