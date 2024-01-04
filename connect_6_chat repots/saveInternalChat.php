<?php 
									$senderAgentName = $_REQUEST['user'];
									$InternalChatContent = $_REQUEST['InternalChatContent'];
									$messageReceiver = $_REQUEST['agent_Name'];
									$campaignlog = $_REQUEST['campaignlog'];
									
									require_once("db_connect.php");
									
									$date = date("Y-m-d H:i:s");
									
	$stmt_insert_live="insert into internalChat(created_date,senderName,message,messageReceiver,user,status,campaign) values('$date','$senderAgentName','$InternalChatContent','$messageReceiver','$senderAgentName','NEW','$campaignlog');";
	$rslt_insert_live= mysqli_query($conn,$stmt_insert_live);
								 
		$stmt_login="SELECT * from internalChat where senderName='$senderAgentName' ORDER BY created_date DESC LIMIT 1;";
		$rslt_login= mysqli_query($conn,$stmt_login);
	                          
							   while($row = mysqli_fetch_assoc($rslt_login)) {
								  $in = $row["message"];
								  $date_chat = $row["created_date"];
							   }
									if($in == ''){										
								echo '<li class="in">        				
										<div class="chat-body">
											<div class="chat-message">        						
												<p></p>
											</div>
										</div>
									</li>';
									 }elseif($in != ''){ 
								echo '<li class="out">        			
										<div class="chat-body">
											<div class="chat-message">        					
						<p>'.$in.' <br><span class="time-left" style="float: right;font-size:7px;">'.$date_chat.'</span></p>
											</div>
										</div>
									</li>';
									 }
									 ?>


