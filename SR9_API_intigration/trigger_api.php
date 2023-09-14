<?php


date_default_timezone_set('Asia/Kolkata');


	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$currant_date = date('Y-m-d');
	//echo "==>".$currant_date_time;
	

	$hostnameVl='localhost';
	$userVl = 'root';
	$passwordVl = 'Hal0o(0m@72427242';
	$mysql_databaseVl = 'Voicelogs';
	$linkVl = mysqli_connect($hostnameVl, $userVl, $passwordVl,$mysql_databaseVl);
	
	
	
	$select_stmt = "SELECT recording_filename,callDate FROM `salesforceApi_data` WHERE  api_update='0' and  callDate like '$currant_date%'";
					
					//echo $select_stmt;
					//die;
					$resultstmt = mysqli_query($link,$select_stmt);
					//$row1 = mysqli_fetch_array($resultstmt);
					
					while($row1 = mysqli_fetch_array($resultstmt)){
						
						$recording_filename1 = $row1[0];
						$callDate1 = $row1[1];
						$CalldatVal = substr($callDate1,0,10);
						
						$sel_id = "select count(filename),filename from voicefiles where date like '$CalldatVal%' and filename='$recording_filename1'";
						//echo $sel_id;
						//die;
						$rslt_id=mysqli_query($linkVl,$sel_id);	
						$row_id = mysqli_fetch_row($rslt_id);
						$cnt =$row_id[0];
						$filename =$row_id[1];
						if($cnt > 0 )
						{
								$sel_apiVal = "select recording_filename,callDate,lead_id,username,loanId,disposition,sub_disposition1,sub_disposition2,comments,amount,popupdate,attempt_type,attempt_status,mode_of_payment,session_id,phone_number,id from salesforceApi_data where recording_filename = '$filename'";
								//echo $sel_apiVal;die;
								$res_apiVal = mysqli_query($link,$sel_apiVal);
								$row_apiVal= mysqli_fetch_array($res_apiVal);

							
							$recording_filename = $row_apiVal['recording_filename'];
							$callDate = $row_apiVal['callDate']; 
							$lead_id = $row_apiVal['lead_id'];
							$user_name = $row_apiVal['username'];
							$loanId = $row_apiVal['loanId'];
							$disposition = $row_apiVal['disposition'];
							$sub_disposition1 = $rorow_apiValw1['sub_disposition1'];
							$sub_disposition2 = $row_apiVal['sub_disposition2'];
							$comments = $row_apiVal['comments'];
							$amount = $row_apiVal['amount'];
							$popupdate = $row_apiVal['popupdate'];
							$attempt_type = $row_apiVal['attempt_type'];
							$attempt_status = $row_apiVal['attempt_status'];
							$mode_of_payment = $row_apiVal['mode_of_payment'];
							$session_id = $row_apiVal['session_id'];
							$phone_number = $row_apiVal['phone_number'];
							$id = $row_apiVal['id'];
							
							$curl = curl_init();

							curl_setopt_array($curl, array(
							  CURLOPT_URL => 'https://call-admin.smartcoin.co.in/bot_api/getApiAccessToken',
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => '',
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => 'POST',
							  CURLOPT_HTTPHEADER => array(
								'agency_name: mark',
								'bot-api-client-id: t3rx0UoPez',
								'bot-api-client-key: iVCFa0I6Jx',
								'Content-Length: 0'
							  ),
							));
							$result = curl_exec($curl);
							$response = json_decode($result);
							//echo print_r($response,1)."\n";
							curl_close($curl);
							
							$access_token = $response->access_token;
							
							$refresh_token = $response->refresh_token;
						

							//2nd API //

							$url = 'https://call-admin.smartcoin.co.in/bot_api/getApiAccessTokenFromRefreshToken';

							$data = array("refresh_token" => $refresh_token );

							$postdata = json_encode($data);

							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
							$result = curl_exec($ch);
							$response = json_decode($result);
							//echo print_r($response,1)."\n";
							curl_close($ch);
							$access_token2 = $response->access_token;

							

							//3rd Api //


							$data = array(
									'sessionId' => $session_id,
									'loanId' => $loanId,
									'phone' => $phone_number,
									'attemptType'=>$attempt_type,
									'attemptStatus'=>$attempt_status,
									'disposition' => $disposition,
									'subdisposition1'=>$sub_disposition1,
									'subdisposition2'=>$sub_disposition2,
									'remark'=>$comments,
									'popupdate'=>$popupdate,
									'amount'=>$amount,
									'modeOfPayment'=>$mode_of_payment,
									'call_recording_mapping'=>$recording_filename,
									'callDate' => $callDate 
							);

							$postdata = json_encode($data);

							//echo "Body ===>".$postdata;

							//echo "<br>";

							$curl = curl_init();

							curl_setopt_array($curl, array(
							  CURLOPT_URL => 'https://call-admin.smartcoin.co.in/bot_api/getApiCallDataFromBotApi',
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => '',
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => 'POST',
							  CURLOPT_POSTFIELDS => $postdata,
							  CURLOPT_HTTPHEADER => array(
								'Authorization:'.$access_token2,
								 'agency_name: mark',
								 'Content-Type: application/json',
							  ),
							));

							 $result = curl_exec($curl);
							$response = json_decode($result);
							curl_close($curl);
							//echo print_r($response)."\n";

							//Move voicefile to ftp server //
							
							$entry_date_time = date('Y-m-d H:i:s');


							//$mydir = 'voicefiles';   
							//$myfiles = array_diff(scandir($mydir), array('.', '..')); 
							//foreach($myfiles as $datefolder){
								
								$datefolder = date('Y-m-d');
								$voiceFiles = $recording_filename;
								
											$voiceFilesPathVal = $datefolder."/".$voiceFiles;
											$voiceFilesPath = $datefolder;
											//echo $voiceFiles; 
											//echo $voiceFilesPath;
											//die;
											
											$dataFile = $voiceFiles; //'20230425-120951_7219419407_amanc_SMARTCOI-all.gsm';
											$sftpServer = "35.207.210.88";
											$sftpUsername = "MarkIndustries";
											$sftpPassword ="dxnP5n1OI6";
											$sftpPort = "22";
											$sftpRemoteDir ="/upload";
											
											$sel_voiceFile = 'select count(*),folder_name,file_name from voiceFile_move_status where file_name = "'.$voiceFiles.'" and folder_name = "'.$datefolder.'" ';
											$res_voiceFile = mysqli_query($link,$sel_voiceFile);
											$row_voiceFile = mysqli_fetch_array($res_voiceFile);
											
											if($row_voiceFile[0] > 0){
												
												//echo "Dublicate files checking.. for avoiding"; 
											}
											else{
														$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . basename($dataFile));
														$fh = fopen('voicefiles/'.$voiceFilesPath.'/'.$dataFile, 'r');
													if ($fh){
															curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
															curl_setopt($ch, CURLOPT_UPLOAD, true);
															curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
															curl_setopt($ch, CURLOPT_INFILE, $fh);
															curl_setopt($ch, CURLOPT_INFILESIZE, filesize('voicefiles/'.$voiceFilesPath.'/'.$dataFile));
															curl_setopt($ch, CURLOPT_VERBOSE, true);
															$verbose = fopen('php://temp', 'w+');
															curl_setopt($ch, CURLOPT_STDERR, $verbose);
															$response = curl_exec($ch);
															$error = curl_error($ch);
															curl_close($ch);
															if ($response) {
																echo "Success";
																
																$sql_insert = 'insert into voiceFile_move_status (entry_date_time,folder_name,file_name,path)values("'.$entry_date_time.'","'.$datefolder.'","'.$voiceFiles.'","'.$voiceFilesPathVal.'" )';
													
																$res = mysqli_query($link,$sql_insert);
																
																
																$update_drop = "update salesforceApi_data set api_update = '1',api_response='$result' where id = '$id'"; 
																mysqli_query($link,$update_drop);
															} 
															else {
																	echo "Failure";
																	rewind($verbose);
																	$verboseLog = stream_get_contents($verbose);
																	echo "Verbose information:\n" . $verboseLog . "\n";
																}
														}
														
											}
											

							
							
							
						}
						
						
						
					}

?>