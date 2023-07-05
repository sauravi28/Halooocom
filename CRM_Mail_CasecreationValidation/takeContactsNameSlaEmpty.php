<?php 
		//$loginId =  $_COOKIE["ck_login_id_20"];	
		$link  = mysqli_connect('localhost','root','Hal0o(0m@72427242','haloocomCRM');	
	
		$name = $_REQUEST['name'];
		if($name !=""){
		$sql_userid = "SELECT id FROM `accounts` where name = '".$name."'";
		$result_userid = mysqli_query($link,$sql_userid);
		$row_userid = mysqli_fetch_array($result_userid);
		$accountNameId = $row_userid['id'];
		

		$stmt="SELECT no_of_tickets_c,sla_date_c,id_c FROM accounts_cstm WHERE id_c='".$accountNameId."' ";
		//echo $stmt;
		//echo "<br>";
		$rslt=mysqli_query($link,$stmt);
		$row_rslt = mysqli_fetch_array($rslt);
		
				$sla_no_of_tickets = $row_rslt[0];
				$sla_date_c = $row_rslt[1];
				
				if($sla_no_of_tickets == "" && $sla_date_c == "")
				{
					echo "1";
				}
				else {
					echo "0";
					}
				
		}	 
			
		?>