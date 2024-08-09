<?php 	
		$id =$_REQUEST['id'];
		require_once("db_connect.php");		
					$stmt_select="SELECT * from user_group where id='$id'";
	                $rslt_rs= mysqli_query($conn,$stmt_select);
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			   echo $row["group_id"]."*".$row["group_name"]."*".$row["id"];
		}



?>