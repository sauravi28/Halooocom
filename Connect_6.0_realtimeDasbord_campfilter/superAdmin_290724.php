<?php
$loginUser = $_REQUEST['loginUser'];
$campaign_id = $_REQUEST['campaign_id'];
require_once("db_connect.php");
$date = date('Y-m-d');
if($loginUser != ''){

if($campaign_id !=''){
	
	//Total Agents
 $stmt_Totaluser="SELECT count(*) as total_count from users where user_level='D4' and Campaign='$campaign_id';";
	                           $rslt_Totaluser= mysqli_query($conn,$stmt_Totaluser);
	                           $row_Totaluser= mysqli_fetch_row($rslt_Totaluser);
							   $Total_agent = $row_Totaluser[0];
// Login Agents							   
$stmt_login="SELECT count(*) as login,user from user_live where campaign='$campaign_id' and created_date LIKE '$date%';";

	                           $rslt_login= mysqli_query($conn,$stmt_login);
	                          // $row_login= mysqli_fetch_row($rslt_login);
						
							   while($row_login = mysqli_fetch_assoc($rslt_login)) {
							   $login_agent = $row_login['login'];
							   $user = $row_login['user'];
							  
							   // Not Login agents
							   $stmt_user="SELECT count(*) FROM users WHERE users.user_name NOT IN (SELECT user FROM user_live) AND user_level='D4' and Campaign='$campaign_id';";
							   //echo $stmt_user;
	                           $rslt_user= mysqli_query($conn,$stmt_user);
	                           $row_user= mysqli_fetch_row($rslt_user);
							   $Notlogin_agent = $row_user[0];
							   
							   // Total Calls
							   $stmt_TotalCalls="SELECT count(*) as total_calls from call_log where calltype IN('OUTBOUND','AUTO','CLICK','INBOUND') AND dial_time LIKE '$date%' and campaign='$campaign_id';";
	                           $rslt_TotalCalls= mysqli_query($conn,$stmt_TotalCalls);
	                           $row_TotalCalls= mysqli_fetch_row($rslt_TotalCalls);
							   $Total_Calls = $row_TotalCalls[0];
					
							   
							     // OUTBOUND Connected calls 
	$stmt_connect_OUT="SELECT count(*) as outconnect from call_log where calltype IN('OUTBOUND','AUTO','CLICK') AND campaign='$campaign_id' and client_time !='' AND dial_time LIKE '$date%';";
					//echo $stmt_connect_OUT;
	                           $rslt_connect_OUT= mysqli_query($conn,$stmt_connect_OUT);
							   $row_connect_OUT = mysqli_fetch_row($rslt_connect_OUT);
							   $Total_connect_OUT = $row_connect_OUT[0];
							   
							   
							   // INBOUND Connected calls 
	$stmt_connect_IN="SELECT count(*) as inconnect from call_log where calltype IN('INBOUND') AND campaign='$campaign_id' and agent_time !='' AND dial_time LIKE '$date%';";
					//echo $stmt_connect_IN;
	                           $rslt_connect_IN= mysqli_query($conn,$stmt_connect_IN);
							   $row_connect_IN = mysqli_fetch_row($rslt_connect_IN);
							   $Total_connect_IN = $row_connect_IN[0];
							   
							   $Total_AnsCalls  = $Total_connect_OUT + $Total_connect_IN;
							   
 // OUTBOUND Total Talk Sec
$stmt_TalkSec_OUT="SELECT sum(client_duration) as total_talksec from call_log where calltype IN('OUTBOUND','AUTO','CLICK') AND campaign='$campaign_id' and client_time !='' AND dial_time LIKE '$date%';";
	                           $rslt_TalkSec_OUT= mysqli_query($conn,$stmt_TalkSec_OUT);
							   $row_TalkSec_OUT = mysqli_fetch_row($rslt_TalkSec_OUT);
							   $Total_Sec_OUT = $row_TalkSec_OUT[0];
							   
							   
							  							   
// INBOUND Total Talk Sec
$stmt_TalkSec_IN="SELECT sum(agent_duration) as total_talksec_IN from call_log where calltype IN('INBOUND') and campaign='$campaign_id' AND agent_time !='' AND dial_time LIKE '$date%';";
	                           $rslt_TalkSec_IN= mysqli_query($conn,$stmt_TalkSec_IN);
							   $row_TalkSec_IN = mysqli_fetch_row($rslt_TalkSec_IN);
							   $Total_Sec_IN = $row_TalkSec_IN[0];
							   
							   $Total_Sec = ($Total_Sec_OUT + $Total_Sec_IN);
							   $Total_TalkSec = gmdate("H:i:s", $Total_Sec);											   
							 
							   // Oncall Agents
							   $stmt_oncall="SELECT count(*) as oncall_agents from user_live where callstatus IN('DIAL','WRAPUP','ONCALL') and campaign='$campaign_id' AND created_date LIKE '$date%';";
	                           $rslt_oncall= mysqli_query($conn,$stmt_oncall);
	                           $row_oncall= mysqli_fetch_row($rslt_oncall);
							   $Total_oncall = $row_oncall[0];
							   
							   // Available Agents
							   $stmt_available="SELECT count(*) as available_agents from user_live where callstatus IN('IDLE') and campaign='$campaign_id' AND status='READY' AND created_date LIKE '$date%';";
	                           $rslt_available= mysqli_query($conn,$stmt_available);
	                           $row_available= mysqli_fetch_row($rslt_available);
							   $Total_available = $row_available[0];
							   
							   // Dialable leads
							   $stmt_dialable="SELECT count(*) as dialable_leads from haloo_list where status='NEW' and campaign='$campaign_id' AND created_date LIKE '$date%';";
	                           $rslt_dialable= mysqli_query($conn,$stmt_dialable);
	                           $row_dialable= mysqli_fetch_row($rslt_dialable);
							   $Total_dialable = $row_dialable[0];
							   
							   //Calls In Queue
$stmt_queueCalls="SELECT count(*) as total_queuecalls from agent_queue where status='1' AND calltype IN ('INBOUND','pd') AND queue_time LIKE '$date%';";
	                           $rslt_queueCalls= mysqli_query($conn,$stmt_queueCalls);
	                           $row_queueCalls= mysqli_fetch_row($rslt_queueCalls);
							   $queueCount = $row_queueCalls[0];
		
		                   // Total Not Answered Calls
								$stmt_TotalNotAnsCalls="SELECT count(*) as total_notanscalls from call_log where calltype IN('OUTBOUND','CLICK','AUTO') and campaign='$campaign_id' AND (client_time = '' OR client_time IS NULL) and dial_time LIKE '$date%';";
	                           $rslt_TotalNotAnsCalls= mysqli_query($conn,$stmt_TotalNotAnsCalls);
	                           $row_TotalNotAnsCalls= mysqli_fetch_row($rslt_TotalNotAnsCalls);
							   $Total_NotAnsCalls = $row_TotalNotAnsCalls[0];
							  
                              //drop calls
                               $stmt_dropCalls="SELECT count(*) as total_dropcalls from call_log where ismissed='yes' and campaign='$campaign_id' AND dial_time LIKE '$date%';";
                               $rslt_dropCalls= mysqli_query($conn,$stmt_dropCalls);
                               $row_dropCalls= mysqli_fetch_row($rslt_dropCalls);
                               $dropCallCount = $row_dropCalls[0];
							   
							   //on calls leads
                               $stmt_onCalls="SELECT count(*) as total_oncalls from dialable_lead where status=5 and campaign='$campaign_id';";
                               $rslt_onCalls= mysqli_query($conn,$stmt_onCalls);
                               $row_onCalls= mysqli_fetch_row($rslt_onCalls);
                               $onCallCount = $row_onCalls[0];
							   
							   }
							   echo $Total_agent;
							   echo "**".$login_agent;
							   echo "**".$Notlogin_agent;
							   echo "**".$Total_Calls;
							   echo "**".$Total_AnsCalls;
							   echo "**".$Total_TalkSec;
							   echo "**".$Total_oncall;
							   echo "**".$Total_available;
							   echo "**".$Total_dialable;
							   echo "**".$queueCount;
							   echo "**".$Total_NotAnsCalls;
							   echo "**".$dropCallCount;
							   echo "**".$onCallCount;
							   echo "**";

	
}
else{
//Total Agents
 $stmt_Totaluser="SELECT count(*) as total_count from users where user_level='D4';";
	                           $rslt_Totaluser= mysqli_query($conn,$stmt_Totaluser);
	                           $row_Totaluser= mysqli_fetch_row($rslt_Totaluser);
							   $Total_agent = $row_Totaluser[0];
// Login Agents							   
$stmt_login="SELECT count(*) as login,user from user_live where created_date LIKE '$date%';";

	                           $rslt_login= mysqli_query($conn,$stmt_login);
	                          // $row_login= mysqli_fetch_row($rslt_login);
						
							   while($row_login = mysqli_fetch_assoc($rslt_login)) {
							   $login_agent = $row_login['login'];
							   $user = $row_login['user'];
							  
							   // Not Login agents
							   $stmt_user="SELECT count(*) FROM users WHERE users.user_name NOT IN (SELECT user FROM user_live) AND user_level='D4';";
							   //echo $stmt_user;
	                           $rslt_user= mysqli_query($conn,$stmt_user);
	                           $row_user= mysqli_fetch_row($rslt_user);
							   $Notlogin_agent = $row_user[0];
							   
							   // Total Calls
							   $stmt_TotalCalls="SELECT count(*) as total_calls from call_log where calltype IN('OUTBOUND','AUTO','CLICK','INBOUND') AND dial_time LIKE '$date%';";
	                           $rslt_TotalCalls= mysqli_query($conn,$stmt_TotalCalls);
	                           $row_TotalCalls= mysqli_fetch_row($rslt_TotalCalls);
							   $Total_Calls = $row_TotalCalls[0];
					
							   
							     // OUTBOUND Connected calls 
	$stmt_connect_OUT="SELECT count(*) as outconnect from call_log where calltype IN('OUTBOUND','AUTO','CLICK') AND client_time !='' AND dial_time LIKE '$date%';";
					//echo $stmt_connect_OUT;
	                           $rslt_connect_OUT= mysqli_query($conn,$stmt_connect_OUT);
							   $row_connect_OUT = mysqli_fetch_row($rslt_connect_OUT);
							   $Total_connect_OUT = $row_connect_OUT[0];
							   
							   
							   // INBOUND Connected calls 
	$stmt_connect_IN="SELECT count(*) as inconnect from call_log where calltype IN('INBOUND') AND agent_time !='' AND dial_time LIKE '$date%';";
					//echo $stmt_connect_IN;
	                           $rslt_connect_IN= mysqli_query($conn,$stmt_connect_IN);
							   $row_connect_IN = mysqli_fetch_row($rslt_connect_IN);
							   $Total_connect_IN = $row_connect_IN[0];
							   
							   $Total_AnsCalls  = $Total_connect_OUT + $Total_connect_IN;
							   
 // OUTBOUND Total Talk Sec
$stmt_TalkSec_OUT="SELECT sum(client_duration) as total_talksec from call_log where calltype IN('OUTBOUND','AUTO','CLICK') AND client_time !='' AND dial_time LIKE '$date%';";
	                           $rslt_TalkSec_OUT= mysqli_query($conn,$stmt_TalkSec_OUT);
							   $row_TalkSec_OUT = mysqli_fetch_row($rslt_TalkSec_OUT);
							   $Total_Sec_OUT = $row_TalkSec_OUT[0];
							   
							   
							  							   
// INBOUND Total Talk Sec
$stmt_TalkSec_IN="SELECT sum(agent_duration) as total_talksec_IN from call_log where calltype IN('INBOUND') AND agent_time !='' AND dial_time LIKE '$date%';";
	                           $rslt_TalkSec_IN= mysqli_query($conn,$stmt_TalkSec_IN);
							   $row_TalkSec_IN = mysqli_fetch_row($rslt_TalkSec_IN);
							   $Total_Sec_IN = $row_TalkSec_IN[0];
							   
							   $Total_Sec = ($Total_Sec_OUT + $Total_Sec_IN);
							   $Total_TalkSec = gmdate("H:i:s", $Total_Sec);											   
							 
							   // Oncall Agents
							   $stmt_oncall="SELECT count(*) as oncall_agents from user_live where callstatus IN('DIAL','WRAPUP','ONCALL') AND created_date LIKE '$date%';";
	                           $rslt_oncall= mysqli_query($conn,$stmt_oncall);
	                           $row_oncall= mysqli_fetch_row($rslt_oncall);
							   $Total_oncall = $row_oncall[0];
							   
							   // Available Agents
							   $stmt_available="SELECT count(*) as available_agents from user_live where callstatus IN('IDLE') AND status='READY' AND created_date LIKE '$date%';";
	                           $rslt_available= mysqli_query($conn,$stmt_available);
	                           $row_available= mysqli_fetch_row($rslt_available);
							   $Total_available = $row_available[0];
							   
							   // Dialable leads
							   $stmt_dialable="SELECT count(*) as dialable_leads from haloo_list where status='NEW' AND created_date LIKE '$date%';";
	                           $rslt_dialable= mysqli_query($conn,$stmt_dialable);
	                           $row_dialable= mysqli_fetch_row($rslt_dialable);
							   $Total_dialable = $row_dialable[0];
							   
							   //Calls In Queue
$stmt_queueCalls="SELECT count(*) as total_queuecalls from agent_queue where status='1' AND calltype IN ('INBOUND','pd') AND queue_time LIKE '$date%';";
	                           $rslt_queueCalls= mysqli_query($conn,$stmt_queueCalls);
	                           $row_queueCalls= mysqli_fetch_row($rslt_queueCalls);
							   $queueCount = $row_queueCalls[0];
		
		                   // Total Not Answered Calls
								$stmt_TotalNotAnsCalls="SELECT count(*) as total_notanscalls from call_log where calltype IN('OUTBOUND','CLICK','AUTO') AND (client_time = '' OR client_time IS NULL) and dial_time LIKE '$date%';";
	                           $rslt_TotalNotAnsCalls= mysqli_query($conn,$stmt_TotalNotAnsCalls);
	                           $row_TotalNotAnsCalls= mysqli_fetch_row($rslt_TotalNotAnsCalls);
							   $Total_NotAnsCalls = $row_TotalNotAnsCalls[0];
							  
                              //drop calls
                               $stmt_dropCalls="SELECT count(*) as total_dropcalls from call_log where ismissed='yes' AND dial_time LIKE '$date%';";
                               $rslt_dropCalls= mysqli_query($conn,$stmt_dropCalls);
                               $row_dropCalls= mysqli_fetch_row($rslt_dropCalls);
                               $dropCallCount = $row_dropCalls[0];
							   
							   //on calls leads
                               $stmt_onCalls="SELECT count(*) as total_oncalls from dialable_lead where status=5;";
                               $rslt_onCalls= mysqli_query($conn,$stmt_onCalls);
                               $row_onCalls= mysqli_fetch_row($rslt_onCalls);
                               $onCallCount = $row_onCalls[0];
							   
							   }
							   echo $Total_agent;
							   echo "**".$login_agent;
							   echo "**".$Notlogin_agent;
							   echo "**".$Total_Calls;
							   echo "**".$Total_AnsCalls;
							   echo "**".$Total_TalkSec;
							   echo "**".$Total_oncall;
							   echo "**".$Total_available;
							   echo "**".$Total_dialable;
							   echo "**".$queueCount;
							   echo "**".$Total_NotAnsCalls;
							   echo "**".$dropCallCount;
							   echo "**".$onCallCount;
							   echo "**";
}						   
							   ?>

 <style>
.dt-head-center {text-align: center;}
.modal-content {
width: 138%;
}
</style>			
			<table class="table m-0" id="table_agentLog">
                    <thead>
                    <tr>
                      <th>Agent Id</th>
					  <th>Agent Name</th>
					  <th>Extension</th>
					  <th>Campaign</th>
					  <th>Status</th>
					  <th>Call Status</th>
					  <th>Pause Status</th>
					  <th>Phone Number</th>
					  <th>Total Calls</th>
					  <th>Total Talk Time</th>
					  <th>Action</th>
					  
                    </tr>
                    </thead>
                    <tbody>
					<?php
					
						if($campaign_id !=''){
							$stmt_live="SELECT user,callstatus,extension,campaign,pause_status,phone_number,status from user_live where campaign='$campaign_id' and created_date LIKE '$date%';";
						}else {
							$stmt_live="SELECT user,callstatus,extension,campaign,pause_status,phone_number,status from user_live where created_date LIKE '$date%';";
							}

	                           $rslt_live= mysqli_query($conn,$stmt_live);
	                          // $row_live= mysqli_fetch_row($rslt_live);
							  while($row_live = mysqli_fetch_assoc($rslt_live)) {
							   $live_user = $row_live['user'];
							   $live_status = $row_live['callstatus'];
							    $live_extension = $row_live['extension'];
								 $live_campaign = $row_live['campaign'];
								 $live_pause = $row_live['pause_status'];
								 $live_nuber = $row_live['phone_number'];
								 $status = $row_live['status'];
								 
							  //Agent name
							   $stmt_agentName="SELECT user_name from users where user_id ='$live_user'";
	                           $rslt_agentName= mysqli_query($conn,$stmt_agentName);
	                           $row_agentName= mysqli_fetch_row($rslt_agentName);
							   $AgentName = $row_agentName[0];
							   
							    // Total Calls
								 //$stmt_TotalCalls_agent="SELECT count(*) as total_calls from call_log where agent ='$live_extension' and agentstatus='ANSWERED' and dial_time LIKE '$date%';";
								$stmt_TotalCalls_agent="SELECT count(*) as total_calls from call_log where agent ='$live_extension' and client_time is not null and dial_time LIKE '$date%';";
	                           $rslt_TotalCalls_agent= mysqli_query($conn,$stmt_TotalCalls_agent);
	                           $row_TotalCalls_agent= mysqli_fetch_row($rslt_TotalCalls_agent);
							   $Total_Calls_agent = $row_TotalCalls_agent[0];
							   
							   // Total Talk Sec
							   $stmt_TalkSec_agent="SELECT sum(client_duration) as total_talksec from call_log where agent ='$live_extension' and client_time !='0000-00-00 00:00:00' and dial_time LIKE '$date%';";
	                           $rslt_TalkSec_agent= mysqli_query($conn,$stmt_TalkSec_agent);
	                           $row_TalkSec_agent= mysqli_fetch_row($rslt_TalkSec_agent);
							   if($row_TalkSec_agent[0] == ""){
							   $Total_Sec_agent = 0;
							   }else{
								   $Total_Sec_agent = $row_TalkSec_agent[0];
							   }
							   $Total_TalkSec_agent = gmdate("H:i:s", $Total_Sec_agent);
							   if($live_user != ''){
								   
								   if($status =="READY" && $live_status =='IDLE'){
									   $bg = "#06912b";
								   }elseif($status =="READY" && ($live_status =='ONCALL' || $live_status =='DIAL')){
									   $bg = "#080169";
								   }elseif($status =="READY" && $live_status =='WRAPUP'){
									   $bg = "#eb6d00";
								   }elseif($status =="PAUSED" && ($live_status =='IDLE' || $live_status =='BREAK')){
									   $bg = "#c90f02";
								   }
					?>
					                <tr>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $live_user; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $AgentName; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $live_extension; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $live_campaign; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $status; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php if($live_status =='DIAL'){
										  echo "ONCALL";
									  }else{
										  echo $live_status;
									  }	?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $live_pause; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $live_nuber; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $Total_Calls_agent; ?></span></td>
									  <td><span style="background-color:<?php echo $bg; ?>;color:white;"><?php echo $Total_TalkSec_agent; ?></span></td>
									   <td>
								<span class="btn btn-danger btn-sm" style="background-color:#9e0337;border: 1px solid #9e0337;" onclick="document.getElementById('myModal_delete').style.display='block'; spyFun('710','<?php echo $live_extension; ?>')">Spy</span>
							    <span class="btn btn-danger btn-sm" style="background-color:#ffcc00;border: 1px solid #ffcc00;"  onclick="whisperFun('711','<?php echo $live_extension; ?>')">Whisper</span>
								<span class="btn btn-danger btn-sm" style="background-color:#33bbff;border: 1px solid #33bbff;"  onclick="bargeFun('712','<?php echo $live_extension; ?>')">Barge</span>
								</td>
									 
									</tr>
									<?php
							   }else{
									?>
									<tr>
									  <td></div></td>
									   <td></div></td>
									  <td></div></td>
									  <td></td>
									   <td></td>
									   <td></td>
									  <td></td>
									  <td></td>
									   <td></td>
									 
									</tr>
					            <?php
							   }
							  }
									?>
                    </tbody>
                  </table>
				  
	<?php 
	echo "**";
ob_start();
$c = system('/usr/sbin/asterisk -rx "sip show peers"');
$output = ob_get_clean();
$hostname = system('/bin/hostname');

if($c=='')
{

$msg = "<br>PRI line DOWN AT:".$hostname;

}
else{

$msg = "<br>PRI line UP AT:".$hostname;

} 
echo $msg;
	?>
 <?php
}
 ?>
