<?php 	
		$id =$_REQUEST['id'];	
		
			require_once("db_connect.php");		
		$stmt_select="SELECT * from email_template where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			echo $row["template_id"]."*".$row["template_body"]."*".$row["id"]."*".$row["campaign_id"]."*".$row["subject"];
			}



?>