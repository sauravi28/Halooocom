 <?php 
 $link = mysqli_connect("localhost","root","Hal0o(0m@72427242","asterisk");
								// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
									
	$currant_date = date('Y-m-d');
	//echo "==>".$currant_date_time;
	
	$hostnameVl='localhost';
	$userVl = 'root';
	$passwordVl = 'Hal0o(0m@72427242';
	$mysql_databaseVl = 'Voicelogs';
	$linkVl = mysqli_connect($hostnameVl, $userVl, $passwordVl,$mysql_databaseVl);
										
					$select_stmt = "SELECT `filename`,`date`,`id` FROM `voicefiles` WHERE `date` like '$currant_date%' and `call_type`='' and `data_update`='0'";
					//echo $select_stmt;
					//echo "<br>";
					//die;
					$resultstmt = mysqli_query($linkVl,$select_stmt);
					//$row1 = mysqli_fetch_array($resultstmt);
					
					$Disposition = '';
					$SubDisposition = '';
					$call_type = '';
					
					while($row1 = mysqli_fetch_array($resultstmt)){
						
						$filename1 = $row1[0];
						$callDate = $row1[1];
						$voicefileID = $row1[2];
									
						// Dispo and Sub Dispo//
						$voicefilesVal = str_replace("-all.gsm","",$filename1);
						//echo $voicefilesVal;
						//echo "<br>";
						
							$sel_status = "SELECT count(*),`status`,`field40` FROM `vicidial_log` WHERE `call_date` like '$callDate%' and `recording_filename`='$voicefilesVal'";
							//echo $sel_status;
							//echo "<br>";
							$res_status = mysqli_query($link,$sel_status);
							$row_status = mysqli_fetch_array($res_status,MYSQLI_NUM);
							
							if($row_status[0] > 0){
								$Disposition_out = $row_status[1];
								$SubDisposition_out = $row_status[2];
								
								$Disposition = $Disposition_out;
								$SubDisposition = $SubDisposition_out;
								$call_type = "Outgoing";
								
								$update_outcall = "update voicefiles set disposition='$Disposition',sub_disposition='$SubDisposition',call_type='$call_type',data_update='1' where id='$voicefileID'"; 
								//echo $update_outcall;
								//echo "<br>";
								mysqli_query($linkVl,$update_outcall);
							}
							else if($row_status[0] == '0'){
								
								//Incoming//
								$sel_status_in = "select count(*),`status`,`field40` From vicidial_closer_log  where `call_date` like '$callDate%' and `recording_filename`='$voicefilesVal'";
								//echo $sel_status_in;
								//echo "<br>";
								$res_status_in = mysqli_query($link,$sel_status_in);
								$row_status_in = mysqli_fetch_array($res_status_in,MYSQLI_NUM);
								
								if($row_status_in[0] > 0){
									
								$Disposition_In = $row_status_in[1];
								$SubDisposition_In = $row_status_in[2];
								
								$Disposition = $Disposition_In;
								$SubDisposition = $SubDisposition_In;
								$call_type = "Incoming";	
								
								$update_incall = "update voicefiles set disposition='$Disposition',sub_disposition='$SubDisposition',call_type='$call_type',data_update='1' where id='$voicefileID'"; 
								//echo $update_incall;
								//echo "<br>";
								mysqli_query($linkVl,$update_incall);
							
								}
								else {
									//echo "Inside new else";
									//echo "<br>";
									$sql_fileid = "SELECT vicidial_id from recording_log where filename='$voicefilesVal'";
									//echo $sql_fileid ;
									//die;
									$result_fileid = mysqli_query($link,$sql_fileid);
									$row_fileid    = mysqli_fetch_array($result_fileid,MYSQLI_NUM);			
									if($row_fileid[0] !=''){
										
										$sel_status1 = "select count(*),status,field40 From vicidial_log where uniqueid = '$row_fileid[0]'";
										//echo $sel_status1;
										//echo "<br>";
										$res_status1 = mysqli_query($link,$sel_status1);
										$row_status1 = mysqli_fetch_array($res_status1,MYSQLI_NUM);
										if($row_status1[0] > 0){
											
											$Disposition_out = $row_status1[1];
											$SubDisposition_out = $row_status1[2];
											
											$Disposition = $Disposition_out;
											$SubDisposition = $SubDisposition_out;
											$call_type = "Outgoing";
											
											$update_outcall = "update voicefiles set disposition='$Disposition',sub_disposition='$SubDisposition',call_type='$call_type',data_update='1' where id='$voicefileID'"; 
											//echo $update_outcall;
											//echo "<br>";
											mysqli_query($linkVl,$update_outcall);
											
										}
										else if($row_status1[0] == 0){
										//Incoming//
										$sel_status_in1 = "select count(*),status,field40 From vicidial_closer_log  where closecallid = '$row_fileid[0]'";
										//echo $sel_status_in1;
										//echo "<br>";
										$res_status_in1 = mysqli_query($link,$sel_status_in1);
										$row_status_in1 = mysqli_fetch_array($res_status_in1,MYSQLI_NUM);
										
											if($row_status_in1[0] > 0){
												
											$Disposition_In = $row_status_in1[1];
											$SubDisposition_In = $row_status_in1[2];
											
											$Disposition = $Disposition_In;
											$SubDisposition = $SubDisposition_In;
											$call_type = "Incoming";	
											
											$update_incall = "update voicefiles set disposition='$Disposition',sub_disposition='$SubDisposition',call_type='$call_type',data_update='1' where id='$voicefileID'"; 
											//echo $update_incall;
											//echo "<br>";
											mysqli_query($linkVl,$update_incall);
										
											
											}
										}
											
									}
								
							    }
								
								
								
							}
							
								
						
						
					}	
						
						
						?>