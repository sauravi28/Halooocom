<?php 
		//$loginId =  $_COOKIE["ck_login_id_20"];	
		$link  = mysqli_connect('localhost','root','Hal0o(0m@72427242','haloocomCRM');	
	
		$name = $_REQUEST['name'];
		if($name !=""){
		$sql_userid = "SELECT id FROM `accounts` where name = '".$name."'";
		$result_userid = mysqli_query($link,$sql_userid);
		$row_userid = mysqli_fetch_array($result_userid);
		$accountNameId = $row_userid['id'];
		
		
		$sql_caseCount = "SELECT COUNT(*) as CaseCount FROM `cases` WHERE `account_id` = '".$accountNameId."'";
		$result_caseCount = mysqli_query($link,$sql_caseCount);
		$row_caseCount = mysqli_fetch_array($result_caseCount);
		$caseCount = $row_caseCount['CaseCount'];
			//echo $caseCount;
			//echo "<br>";
		$stmt="SELECT no_of_tickets_c,id_c FROM accounts_cstm WHERE `no_of_tickets_c` !='' and id_c='".$accountNameId."' ";
		//echo $stmt;
		//echo "<br>";
		$rslt=mysqli_query($link,$stmt);
		$row_rslt = mysqli_fetch_array($rslt);
		
				$sla_no_of_tickets = $row_rslt[0];
				//echo "noofticket==>".$sla_no_of_tickets;
				if($sla_no_of_tickets !=""){
					if($caseCount == $sla_no_of_tickets){
						echo "1";
					}
					else {
						
						echo "0";
					}
				}
		}	 
			
		?>