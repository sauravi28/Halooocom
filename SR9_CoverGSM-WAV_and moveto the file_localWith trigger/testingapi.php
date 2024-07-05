<?php 


								$datefolder = date('Y-m-d');
								$recording_filename="20240612-133622_9740080311_SR9AB0433_INT-all.gsm";
								
								$changeFileext = str_replace("gsm","wav",$recording_filename);
				
								$voiceFiles = $changeFileext;
				
								echo $datefolder;
								echo $voiceFiles;
											$voiceFilesPathVal = $datefolder."/".$voiceFiles;
											$voiceFilesPath = $datefolder;
											//echo $voiceFiles; 
											//echo $voiceFilesPath;
											//die;
											
											$dataFile = $voiceFiles; //'2024-05-2120240521-093723_9743155449_Trainee6_INT-all.gsm';
											//$dataFile = '2024-05-2120240521-093723_9743155449_Trainee6_INT-all.gsm';
                                            $sftpServer = "35.207.210.88";
											$sftpUsername = "SR9";
											$sftpPassword ="jrk41aq1dz";
											$sftpPort = "22";
											$sftpRemoteDir ="/upload";
											
														$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . basename($dataFile));
														$fh = fopen('/srv/www/htdocs/voicefiles_Wav/'.$voiceFilesPath.'/'.$dataFile, 'r');
													if ($fh){
															curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
															curl_setopt($ch, CURLOPT_UPLOAD, true);
															curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
															curl_setopt($ch, CURLOPT_INFILE, $fh);
															curl_setopt($ch, CURLOPT_INFILESIZE, filesize('/srv/www/htdocs/voicefiles_Wav/'.$voiceFilesPath.'/'.$dataFile));
															curl_setopt($ch, CURLOPT_VERBOSE, true);
															$verbose = fopen('php://temp', 'w+');
															curl_setopt($ch, CURLOPT_STDERR, $verbose);
															$response = curl_exec($ch);
															$error = curl_error($ch);
															curl_close($ch);
															if ($response) {
																echo "Success";
																
																
															} 
															else {
																	echo "Failure";
																	rewind($verbose);
																	$verboseLog = stream_get_contents($verbose);
																	echo "Verbose information:\n" . $verboseLog . "\n";
																}
														}
														
											

?>