<?php 
 
// Load the database configuration file 
require('dbconnect_mysqli.php');
 
// Include XLSX generator library 
require_once 'PhpXlsxGenerator.php';
 
 if ($_GET["download"] == "1"){
	 
	  $fileNameVal1 = $_GET["fileNameVal"];
      $fileNameVal = trim($fileNameVal1);
	  //echo $fileNameVal;
	  //die;
		// Excel file name for download 
		$fileName = "account&Contact-data_" . date('Y-m-d') . ".xlsx"; 
		 
		// Define column names 
		$excelData[] = array('accno','final_city', 'state', 'zone', 'name', 'pos','emi_amt','total_overdue','last_payment_amount','last_payment_date','due','m0','m1','m2','m3','allocationtype','financier_id','currentaddress','currentfinalcity','permanent_email_address','permanentfinalcity','workaddress','workfinalcity','phn_1_curr_contact1','phn_3_curr_mobile1','phn_5_perm_contact1','phn_7_perm_mobile1','phn_10_work_contact2','phn_11_work_mobile1','phn_9_work_contact1','last_phone_contacted','phonenumber1','phonenumber2','phonenumber3'); 
		
		// Fetch records from database and store in an array 
		$query = " select va.accno,va.final_city,va.state,va.zone,va.name,va.pos,va.emi_amt,va.total_overdue,va.last_payment_amount,va.last_payment_date,va.due,va.m0,va.m1,va.m2,va.m3,va.allocationtype,vc.financier_id,vc.currentaddress,vc.currentfinalcity,vc.permanent_email_address,vc.permanentfinalcity,vc.workaddress,vc.workfinalcity,vc.phn_1_curr_contact1,vc.phn_3_curr_mobile1,vc.phn_5_perm_contact1,vc.phn_7_perm_mobile1,vc.phn_10_work_contact2,vc.phn_11_work_mobile1,vc.phn_9_work_contact1,vc.last_phone_contacted,vc.phonenumber1,vc.phonenumber2,vc.phonenumber3 from vicidial_Accountdetails va,vicidial_Contactdetails vc where va.fileName='$fileNameVal' and va.accno=vc.account_reference order by va.id ASC";
		//echo $query;
		//die;
		$res_data = mysqli_query($link,$query);
		
		while($row=mysqli_fetch_assoc($res_data)){
		
				$lineData = array( $row['accno'], $row['final_city'], $row['state'], $row['zone'], $row['name'], $row['pos'],$row['emi_amt'],$row['total_overdue'],$row['last_payment_amount'],$row['last_payment_date'],$row['due'],$row['m0'],$row['m1'],$row['m2'],$row['m3'],$row['allocationtype'],$row['financier_id'],$row['currentaddress'],$row['currentfinalcity'],$row['permanent_email_address'],$row['permanentfinalcity'],$row['workaddress'],$row['workfinalcity'],$row['phn_1_curr_contact1'],$row['phn_3_curr_mobile1'],$row['phn_5_perm_contact1'],$row['phn_7_perm_mobile1'],$row['phn_10_work_contact2'],$row['phn_11_work_mobile1'],$row['phn_9_work_contact1'],$row['last_phone_contacted'],$row['phonenumber1'],$row['phonenumber2'],$row['phonenumber3']);  
				$excelData[] = $lineData; 
				
				$sql="delete from vicidial_Accountdetails where accno='".$row['accno']."'";
				//echo $sql;
				$res_data1 = mysqli_query($link,$sql);
				
				$sql2="delete from vicidial_Contactdetails where account_reference='".$row['accno']."'";
				//echo $sql2;
				$res_data12 = mysqli_query($link,$sql2);
				
				/*if($res_data1){
					echo "insi if";
					die;
				}
				else{
					echo "else ";
					die;
				}*/
				
			} 
		 
       
     // Export data to excel and download as xlsx file 
		$xlsx = CodexWorld\PhpXlsxGenerator::fromArray( $excelData ); 
		$xlsx->downloadAs($fileName); 
        
		//sleep(6);
		//$qstring = '?delete=1';
		//header("Location:admin_uploadAccount_contact.php".$qstring);	

/*$sqlVal = "delete from `vicidial_Accountdetails`";
		$res= mysqli_query($link,$sqlVal);
	   if($res){
		   echo "from if con";
		   //die;
		    header ("Location: admin_uploadAccount_contact.php");
			
	   }else {
		   echo "else con";
		   //die;
	   }*/		
exit;

 }
?>