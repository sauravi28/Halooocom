<?php 
		//$loginId =  $_COOKIE["ck_login_id_20"];	
		$link  = mysqli_connect('localhost','root','Hal0o(0m@72427242','haloocomCRM');	
	
		$name = $_REQUEST['name'];
		if($name !=""){
		$sql_userid = "SELECT id FROM `accounts` where name = '".$name."'";
		$result_userid = mysqli_query($link,$sql_userid);
		$row_userid = mysqli_fetch_array($result_userid);
		$accountNameId = $row_userid['id'];
			
		$stmt="SELECT customer_active_sla_c,id_c FROM accounts_cstm WHERE `customer_active_sla_c` !='' and id_c='".$accountNameId."' ";
		//echo $stmt;
		//echo "<br>";
		$rslt=mysqli_query($link,$stmt);
		$row_rslt = mysqli_fetch_array($rslt);
		
				$customer_active_sla_c = $row_rslt[0];
				$accountId = $row_rslt[1];
				//echo $sla_expiry;
				//echo "<br>";
				
				
			if($customer_active_sla_c !=""){
				
				if($customer_active_sla_c == "No"){
					
					echo "1";
				}
				else {
					
					echo "0";
				}
			}
		}	 
			
		?>