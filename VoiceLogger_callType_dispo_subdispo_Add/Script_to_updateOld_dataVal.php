<?php 
  
  $conn = mysqli_connect("localhost","root","Hal0o(0m@72427242","Voicelogs");
            // Check connection
            if (mysqli_connect_errno())
           {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
           }
 			$sql1 = "select `filename`,`id` from voicefiles where `disposition`='' and `sub_disposition`='' and `call_type`=''";
            $res  = mysqli_query($conn,$sql1);
				while($row  = mysqli_fetch_array($res,MYSQLI_NUM))
					{
                        $filename1 = $row[0];
						$id = $row[1];
						
                        if($filename1)
			            {
							
						    $link = mysqli_connect("localhost","root","Hal0o(0m@72427242","asterisk");
								// Check connection
								if (mysqli_connect_errno())
							       {
										echo "Failed to connect to MySQL: " . mysqli_connect_error();
							        }
				 // Dispo and Sub Dispo//
						$voicefilesVal =  substr($filename1,0,-8);
						//echo $voicefilesVal;
						//echo "<br>";
						
						$sql_fileid = "SELECT vicidial_id from recording_log where filename like '%$voicefilesVal%'";
						//echo $sql_fileid ;
						//die;
						$result_fileid = mysqli_query($link,$sql_fileid);
						$row_fileid    = mysqli_fetch_array($result_fileid,MYSQLI_NUM);			
						if($row_fileid[0] !=''){
							
							$sel_status = "select count(*),status,field40 From vicidial_log where uniqueid = '$row_fileid[0]'";
							//echo $sel_status;
							//echo "<br>";
							$res_status = mysqli_query($link,$sel_status);
							$row_status = mysqli_fetch_array($res_status,MYSQLI_NUM);
							
							//Incoming//
							$sel_status_in = "select count(*),status,field40 From vicidial_closer_log  where closecallid = '$row_fileid[0]'";
							//echo $sel_status_in;
							//echo "<br>";
							$res_status_in = mysqli_query($link,$sel_status_in);
							$row_status_in = mysqli_fetch_array($res_status_in,MYSQLI_NUM);
							
							if($row_status[0] > 0){
							$Disposition_out = $row_status[1];
							$SubDisposition_out = $row_status[2];
							
							$Disposition = $Disposition_out;
							$SubDisposition = $SubDisposition_out;
							$call_type = "Outgoing";
							}
							else if($row_status_in[0] > 0){
								
							$Disposition_In = $row_status_in[1];
							$SubDisposition_In = $row_status_in[2];
							
							$Disposition = $Disposition_In;
							$SubDisposition = $SubDisposition_In;
							$call_type = "Incoming";	
							
							}else{
								
								$Disposition = '';
							    $SubDisposition = '';
							
							}
								
						}
						   
								
							$update_value = 'update voicefiles set disposition = "'.$Disposition.'",sub_disposition="'.$SubDisposition.'",call_type="'.$call_type.'" where filename = "'.$filename1.'" and id="'.$id.'"'; 
							$result = mysqli_query($conn,$update_value);
										
									echo $sql;         
									echo "<br>";
						}
			}
        mysqli_close($conn);
   
 
        	  

?>
