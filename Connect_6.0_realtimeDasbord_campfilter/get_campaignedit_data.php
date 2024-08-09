<?php 	
		$id =$_REQUEST['id'];
require_once("db_connect.php");		
		$stmt_select="SELECT * from campaign where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			   echo $row["campaign_id"]."*".$row["campaign_name"]."*".$row["id"]."*".$row["active_camp"]."*".$row["dial_ratio"]."*".$row["screen_label"]."*".$row["dial_method"]."*".$row["dial_timeout"]."*".$row["park_music"]."*".$row["Ingroup"]."*".$row["sticky_agent"]."*".$row["dial_prefix"]."*".$row["call_through"]."*".$row["channel"]."*".$row["did_number"]."*".$row["feedback_ivr"]."*".$row["hopper_level"]."*".$row["group_id"];
		}



?>