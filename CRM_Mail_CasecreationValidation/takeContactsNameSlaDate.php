<?php 
		//$loginId =  $_COOKIE["ck_login_id_20"];	
		$link  = mysqli_connect('localhost','root','Hal0o(0m@72427242','haloocomCRM');	
	
		$name = $_REQUEST['name'];
		if($name !=""){
		$sql_userid = "SELECT id FROM `accounts` where name = '".$name."'";
		$result_userid = mysqli_query($link,$sql_userid);
		$row_userid = mysqli_fetch_array($result_userid);
		$accountNameId = $row_userid['id'];
			
		$stmt="SELECT sla_date_c,id_c FROM accounts_cstm WHERE `sla_date_c` !='' and id_c='".$accountNameId."' ";
		//echo $stmt;
		//echo "<br>";
		$rslt=mysqli_query($link,$stmt);
		$row_rslt = mysqli_fetch_array($rslt);
		
				$sla_expiry = $row_rslt[0];
				$accountId = $row_rslt[1];
				//echo $sla_expiry;
				//echo "<br>";
				$today = date('Y-m-d');
				
				$date_before = $sla_expiry; //date from database 
				$str2 = date('Y-m-d', strtotime('-5 days', strtotime($date_before)));

			if($sla_expiry !=""){
				$date_1 = $str2;   
				$date_2 = $sla_expiry;
				
				if($date_2 <= $today){
					
					echo "1";
				}
				else {
					
					echo "0";
				}
			}
		}	 
			
		?>