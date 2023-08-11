<?php
										
error_reporting(E_ERROR | E_WARNING | E_PARSE);									


// $loginUser = $_REQUEST['loginUser'];
 $d2UsersUnderD1ValueFromInput = $_REQUEST['d2UsersUnderD1ValueFromInput'];
 $d3UsersUnderD2ValueFromInput = $_REQUEST['d3UsersUnderD2ValueFromInput']; 
 $d1UsersUnderD2ValueFromInput = $_REQUEST['d1UsersUnderD2ValueFromInput']; 

if($d3UsersUnderD2ValueFromInput == '' && $d2UsersUnderD1ValueFromInput == '' && $d1UsersUnderD2ValueFromInput == ''){
//echo "all are empty";
?>
<style>
.dt-head-center {text-align: center;}
.modal-content {
width: 138%;
}
</style>			
<form method='post' action='download.php'>
            <input type='submit' style="margin-left: 10px;" value='CSV' name='Export'>
                
			<table class="table m-0 tableFixHead" id="table_agentLog">
                    <thead>
					<?php
					$user_arr1[] = array('SR.No','RECRUITER','EMP ID','D3','D2','A1','DID NO','LOCATION');
					?>
                    <tr>
					  <th align = "center" class = "dt-head-center">SR.No.</th>
                      <th align = "center" class = "dt-head-center">RECRUITER</th>
					  <th align = "center" class = "dt-head-center">EMP ID</th>
                      <th align = "center" class = "dt-head-center">D3</th>
					  <th align = "center" class = "dt-head-center">D2</th>
					  <th align = "center" class = "dt-head-center">A1</th>
                      <th align = "center" class = "dt-head-center">DID NO</th>
                      <th align = "center" class = "dt-head-center">LOCATION</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php
						
									
									
									
										//$location = $row->location;	
																					
									
										
										date_default_timezone_set('asia/kolkata');
									$TodayDate = date("Y-m-d");
								    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
									$bulk = new MongoDB\Driver\BulkWrite;
									$filter = [ "user_level" => "D4", "status" => "Active" ];		  //'d1_level' => "$loginUser",					
									$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 40
									$qry = new MongoDB\Driver\Query($filter,$options);
									$rowsD4_User = $mongo->executeQuery("admin.users", $qry); 
									
									$x=1;
										foreach($rowsD4_User as $row){
							
										$UserName = $row->user_name;	
										$location = $row->location;
										$did = $row->DID;
										$empid = $row->user_id_new;
										
										
										//get employeeId from user table 
										date_default_timezone_set('asia/kolkata');
										$date = date('Y-m-d');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
						 	     	    $bulk = new MongoDB\Driver\BulkWrite;
										$filter = ["d4_level" => "$UserName"];							
										$options = [  ]; //$loggedInuserName
										$qry_emplId = new MongoDB\Driver\Query($filter,$options);
										$rows_empId = $mongo->executeQuery("admin.user_assign", $qry_emplId);
										$exD_User_empId =  $rows_empId->toArray();
										$D1_UserArray_emplyeeId = json_decode(json_encode($exD_User_empId), true);
										
										$d3 = $D1_UserArray_emplyeeId[0]['d3_level'];
                                        $d2 = $D1_UserArray_emplyeeId[0]['d2_level'];
                                        $d1 = $D1_UserArray_emplyeeId[0]['d1_level'];

									$user_arr[] = array($x,$UserName,$empid,$d3,$d2,$d1,$did,$location);
						?>
									<tr>
									  <td><div  class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $x; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $UserName; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $empid; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d3 ; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d2; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d1; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $did; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $location;?></div></td>
									  
									</tr>
									
					           <?php 
								$x++;}
							?>
									
									
						
                    </tbody>
                  </table>
				  
				  <?php 
				  $serialize_user_arr1 = serialize($user_arr1);
            $serialize_user_arr = serialize($user_arr);
            ?>
            <textarea name='export_data1' style='display: none;'><?php echo $serialize_user_arr1; ?></textarea>
			<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
            </form>
			
				  
				  
				  <?php 
				  
				  $grandTotalTalkTimeForUser = gmdate("H:i:s", array_sum($grandTotalCalls));
				 $sumOfTotalSec =  array_sum($grandTotalCalls);
				 $totalLoginUserSum = count($loginUserArray);
				 $TRDcount = $sumOfTotalSec/$totalLoginUserSum;
				 $TRDValues = gmdate("H:i:s", $TRDcount);
				  
						echo "**".count($TeamCount);
						echo "**".count($loginUserArray);
						echo "**".count($NotLoginUser);
						echo "**".array_sum($totalCallsArray);
						echo "**".count($OnCalls);
						echo "**".count($AvailableForCalls);
						echo "**".$grandTotalTalkTimeForUser;
						echo "**";		
						//$
						
						$D1UserCountForSuperAdminLogined = array_count_values($D1UserUnderSuperAdmin);		
						$ArrayToStringSuperAdmin = implode("**",array_unique($D1UserUnderSuperAdmin));
						$D1UsersUnderSuperAdmin = explode("**",$ArrayToStringSuperAdmin);
						
						$D2UserCountForLogined = array_count_values($D2UserUnderD1Logined);		
						$ArrayToString = implode("**",array_unique($D2UserUnderD1Logined));
						$D2UsersUnderD1123 = explode("**",$ArrayToString);
						
						//D3 users 
						$D3UserCountForLogined = array_count_values($D3UserUnderD1Logined);		
						$ArrayToString1 = implode("**",array_unique($D3UserUnderD1Logined));
						$D3UsersUnderD1123 = explode("**",$ArrayToString1);
				  ?>
				 
				
				 
				   
				  <?php echo "**"; 
				  
						$D1UserCountForNotLoginedSuperAdmin = array_count_values($D1UserUnderD1NotLoginedSuperAdmin);		
						$ArrayToStringSuperAdmin = implode("**",array_unique($D1UserUnderD1NotLoginedSuperAdmin));
						$D1UsersUnderSuperAdminNotLogined = explode("**",$ArrayToStringSuperAdmin);
						
				        $D2UserCountForNotLogined = array_count_values($D2UserUnderD1NotLogined);		
						$ArrayToString3 = implode("**",array_unique($D2UserUnderD1NotLogined));
						$D2UsersUnderD1123NotLogined = explode("**",$ArrayToString3);
						
						//D3 users 
						$D3UserCountForNotLogined = array_count_values($D3UserUnderD1NotLogined);		
						$ArrayToString4 = implode("**",array_unique($D3UserUnderD1NotLogined));
						$D3UsersUnderD1123NotLogined = explode("**",$ArrayToString4);
				  
				  ?>
				  
				    
				  
				
				  <?php echo "**"; 
				  
				  
						$D1UserCountForAvailableUserSuperAdmin = array_count_values($D1UserUnderSuperAdminAvailableUser);		
						$ArrayToStringsuperAdmin = implode("**",array_unique($D1UserUnderSuperAdminAvailableUser));
						$D1UsersUnderD1AvailableUserSuperAdmin = explode("**",$ArrayToStringsuperAdmin);
						
				        $D2UserCountForAvailableUser = array_count_values($D2UserUnderD1AvailableUser);		
						$ArrayToString34 = implode("**",array_unique($D2UserUnderD1AvailableUser));
						$D2UsersUnderD1AvailableUser = explode("**",$ArrayToString34);
						
						//D3 users 
						$D3UserCountForAvailableUser = array_count_values($D3UserUnderD1AvailableUser);		
						$ArrayToString46 = implode("**",array_unique($D3UserUnderD1AvailableUser));
						$D3UsersUnderD1AvailableUser = explode("**",$ArrayToString46);
						
				
				  ?>
				 
				  
				   
				 
				  <?php echo "**".$TRDValues; 
						echo "**".array_sum($totalInBoundAndOutBoundConnectedCalls);  //$totalInBoundAndOutBoundConnectedCalls
	}elseif($d2UsersUnderD1ValueFromInput == '' && $d3UsersUnderD2ValueFromInput == '' && $d1UsersUnderD2ValueFromInput != ''){
		//echo "D1 Not empty";exit;
		?>
	   <style>
.dt-head-center {text-align: center;}
.modal-content {
width: 138%;
}
</style>			
		<form method='post' action='download.php'>
            <input type='submit' style="margin-left: 10px;" value='CSV' name='Export'>
                
			<table class="table m-0 tableFixHead" id="table_agentLog">
                    <thead>
					<?php
					$user_arr1[] = array('SR.No','RECRUITER','EMP ID','D3','D2','A1','DID NO','LOCATION');
					?>
                    <tr>
                     <th align = "center" class = "dt-head-center">SR.No.</th>
                      <th align = "center" class = "dt-head-center">RECRUITER</th>
					    <th align = "center" class = "dt-head-center">EMP ID</th>
                      <th align = "center" class = "dt-head-center">D3</th>
					  <th align = "center" class = "dt-head-center">D2</th>
					  <th align = "center" class = "dt-head-center">A1</th>
                      <th align = "center" class = "dt-head-center">DID NO</th>
                      <th align = "center" class = "dt-head-center">LOCATION</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php
						
								$TeamCount = array();
								$loginUserArray = array();	
								$LoginUserEmployeeId = array();	
								$LogOutUserEmployeeId = array();
								$NotLoginUser = array();
								$totalCallsArray = array();
								$OnCalls = array();
								$AvailableForCalls = array();
								$AvailableForCallsEmployees = array();
								$grandTotalCalls = array();
								$inboundConneted = array();
										$outboundConneted =array();
										
										$D2UserUnderD1NotLogined = array();
										$D3UserUnderD1NotLogined = array();
										
										$D2UserUnderD1Logined = array();
										$D3UserUnderD1Logined = array();
										
										$D2UserUnderD1AvailableUser = array();
										$D3UserUnderD1AvailableUser = array();
								
						
								
									//$loginUser = $d2UsersUnderD1ValueFromInput;
									
									date_default_timezone_set('asia/kolkata');
									$TodayDate = date("Y-m-d");
								    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
									$bulk = new MongoDB\Driver\BulkWrite;
									$filter = [ "user_level" => "D4","location"=> "$d1UsersUnderD2ValueFromInput", "status" => "Active" ];		  //'d1_level' => "$loginUser",					
									$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 40
									$qry = new MongoDB\Driver\Query($filter,$options);
									$rowsD4_User = $mongo->executeQuery("admin.users", $qry); 
									
									$x=1;
										foreach($rowsD4_User as $row){
							
										$UserName = $row->user_name;	
										//$location = $row->location;
										$did = $row->DID;
										$empid = $row->user_id_new;
										
										
										//get employeeId from user table 
										date_default_timezone_set('asia/kolkata');
										$date = date('Y-m-d');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
						 	     	    $bulk = new MongoDB\Driver\BulkWrite;
										$filter = ["d4_level" => "$UserName"];							
										$options = [  ]; //$loggedInuserName
										$qry_emplId = new MongoDB\Driver\Query($filter,$options);
										$rows_empId = $mongo->executeQuery("admin.user_assign", $qry_emplId);
										$exD_User_empId =  $rows_empId->toArray();
										$D1_UserArray_emplyeeId = json_decode(json_encode($exD_User_empId), true);
										
										$d3 = $D1_UserArray_emplyeeId[0]['d3_level'];
                                        $d2 = $D1_UserArray_emplyeeId[0]['d2_level'];
                                        $d1 = $D1_UserArray_emplyeeId[0]['d1_level'];
										
										$user_arr[] = array($x,$UserName,$empid,$d3,$d2,$d1,$did,$d1UsersUnderD2ValueFromInput);
									
						?>
									<tr>
									  <td><div  class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $x; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $UserName; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $empid; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d3 ; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d2; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d1; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $did; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d1UsersUnderD2ValueFromInput;?></div></td>
									  
									</tr>
									
					
									
						<?php 	
									
									$x++;	}// checking user active or inactive
							
							
							
						?>
                    </tbody>
                  </table>
				   <?php 
				  $serialize_user_arr1 = serialize($user_arr1);
            $serialize_user_arr = serialize($user_arr);
            ?>
            <textarea name='export_data1' style='display: none;'><?php echo $serialize_user_arr1; ?></textarea>
			<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
            </form>
				  
				  <?php 
				  
				  $grandTotalTalkTimeForUser = gmdate("H:i:s", array_sum($grandTotalCalls));
				 $sumOfTotalSec =  array_sum($grandTotalCalls);
				 $totalLoginUserSum = count($loginUserArray);
				 $TRDcount = $sumOfTotalSec/$totalLoginUserSum;
				 $TRDValues = gmdate("H:i:s", $TRDcount);
				  
						echo "**".count($TeamCount);
						echo "**".count($loginUserArray);
						echo "**".count($NotLoginUser);
						echo "**".array_sum($totalCallsArray);
						echo "**".count($OnCalls);
						echo "**".count($AvailableForCalls);
						echo "**".$grandTotalTalkTimeForUser;
						echo "**";		
						$D2UserCountForLogined = array_count_values($D2UserUnderD1Logined);		
						$ArrayToString = implode("**",array_unique($D2UserUnderD1Logined));
						$D2UsersUnderD1123 = explode("**",$ArrayToString);
						
						//D3 users 
						$D3UserCountForLogined = array_count_values($D3UserUnderD1Logined);		
						$ArrayToString1 = implode("**",array_unique($D3UserUnderD1Logined));
						$D3UsersUnderD1123 = explode("**",$ArrayToString1);
				  ?>
				   	
				  <?php echo "**"; 
				  $D2UserCountForNotLogined = array_count_values($D2UserUnderD1NotLogined);		
						$ArrayToString3 = implode("**",array_unique($D2UserUnderD1NotLogined));
						$D2UsersUnderD1123NotLogined = explode("**",$ArrayToString3);
						
						//D3 users 
						$D3UserCountForNotLogined = array_count_values($D3UserUnderD1NotLogined);		
						$ArrayToString4 = implode("**",array_unique($D3UserUnderD1NotLogined));
						$D3UsersUnderD1123NotLogined = explode("**",$ArrayToString4);
				  
				  ?>
				  
				
				  <?php echo "**"; 
				   $D2UserCountForAvailableUser = array_count_values($D2UserUnderD1AvailableUser);		
						$ArrayToString34 = implode("**",array_unique($D2UserUnderD1AvailableUser));
						$D2UsersUnderD1AvailableUser = explode("**",$ArrayToString34);
						
						//D3 users 
						$D3UserCountForAvailableUser = array_count_values($D3UserUnderD1AvailableUser);		
						$ArrayToString46 = implode("**",array_unique($D3UserUnderD1AvailableUser));
						$D3UsersUnderD1AvailableUser = explode("**",$ArrayToString46);
						
				
				  ?>
				 
				  
				
							
				  <?php echo "**".$TRDValues; 
						echo "**".$totalInBoundAndOutBoundConnectedCalls;
	}else{
								
	
			
				  ?>
				  <style>
					.dt-head-center {text-align: center;}
					</style>			
			<form method='post' action='download.php'>
            <input type='submit' style="margin-left: 10px;" value='CSV' name='Export'>
                
			<table class="table m-0 tableFixHead" id="table_agentLog">
                    <thead>
					<?php
					$user_arr1[] = array('SR.No','RECRUITER','EMP ID','D3','D2','A1','DID NO','LOCATION');
					?>
                    <tr>
                      <th align = "center" class = "dt-head-center">SR.No.</th>
                      <th align = "center" class = "dt-head-center">RECRUITER</th>
					    <th align = "center" class = "dt-head-center">EMP ID</th>
                      <th align = "center" class = "dt-head-center">D3</th>
					  <th align = "center" class = "dt-head-center">D2</th>
					  <th align = "center" class = "dt-head-center">A1</th>
                      <th align = "center" class = "dt-head-center">DID NO</th>
                      <th align = "center" class = "dt-head-center">LOCATION</th>
                    </tr>
                    </thead>
                    <tbody>
					
						<?php
						
							
						
							date_default_timezone_set('asia/kolkata');
									$TodayDate = date("Y-m-d");
								    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
									$bulk = new MongoDB\Driver\BulkWrite;
									$filter = [ "user_level" => "D4","DID" => "$d2UsersUnderD1ValueFromInput", "status" => "Active" ];		  //'d1_level' => "$loginUser",					
									$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 40
									$qry = new MongoDB\Driver\Query($filter,$options);
									$rowsD4_User = $mongo->executeQuery("admin.users", $qry); 
									
									$x=1;
										foreach($rowsD4_User as $row){
							
										$UserName = $row->user_name;	
										$location = $row->location;
										//$did = $row->DID;
										$empid = $row->user_id_new;
										
										
										//get employeeId from user table 
										date_default_timezone_set('asia/kolkata');
										$date = date('Y-m-d');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
						 	     	    $bulk = new MongoDB\Driver\BulkWrite;
										$filter = ["d4_level" => "$UserName"];							
										$options = [  ]; //$loggedInuserName
										$qry_emplId = new MongoDB\Driver\Query($filter,$options);
										$rows_empId = $mongo->executeQuery("admin.user_assign", $qry_emplId);
										$exD_User_empId =  $rows_empId->toArray();
										$D1_UserArray_emplyeeId = json_decode(json_encode($exD_User_empId), true);
										
										$d3 = $D1_UserArray_emplyeeId[0]['d3_level'];
                                        $d2 = $D1_UserArray_emplyeeId[0]['d2_level'];
                                        $d1 = $D1_UserArray_emplyeeId[0]['d1_level'];
										
										
										
										$user_arr[] = array($x,$UserName,$empid,$d3,$d2,$d1,$d2UsersUnderD1ValueFromInput,$location);
									
						?>
									<tr>
									  <td><div  class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $x; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $UserName; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $empid; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d3 ; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d2; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d1; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $d2UsersUnderD1ValueFromInput; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20" align = "center"><?php echo $location ?></div></td>
									  
									</tr>
									
								
									
						<?php 	
									
									$x++;	} //checking active user 
							
							
							
						?>
                    </tbody>
                  </table>
				   <?php 
				  $serialize_user_arr1 = serialize($user_arr1);
            $serialize_user_arr = serialize($user_arr);
            ?>
            <textarea name='export_data1' style='display: none;'><?php echo $serialize_user_arr1; ?></textarea>
			<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
            </form>
				  
				  <?php 
				  
				  $grandTotalTalkTimeForUser = gmdate("H:i:s", array_sum($grandTotalCalls));
				 $sumOfTotalSec =  array_sum($grandTotalCalls);
				 $totalLoginUserSum = count($loginUserArray);
				 $TRDcount = $sumOfTotalSec/$totalLoginUserSum;
				 $TRDValues = gmdate("H:i:s", $TRDcount);
				  
						echo "**".count($TeamCount);
						echo "**".count($loginUserArray);
						echo "**".count($NotLoginUser);
						echo "**".array_sum($totalCallsArray);
						echo "**".count($OnCalls);
						echo "**".count($AvailableForCalls);
						echo "**".$grandTotalTalkTimeForUser;
						echo "**";		
							
						//D3 users 
						$D3UserCountForLogined = array_count_values($D3UserUnderD2Logined);		
						$ArrayToString1 = implode("**",array_unique($D3UserUnderD2Logined));
						$D3UsersUnderD1123 = explode("**",$ArrayToString1);
				  
				  ?>
				 
				  <?php echo "**"; 
				  //D3 users 
						$D3UserCountForNotLogined = array_count_values($D3UserUnderD2NotLogined);		
						$ArrayToString4 = implode("**",array_unique($D3UserUnderD2NotLogined));
						$D3UsersUnderD1123NotLogined = explode("**",$ArrayToString4);	
				  ?>
				  
				  
				  <?php echo "**"; 
				  //D3 users 
						$D3UserCountForAvailableUser = array_count_values($D3UserUnderD2Available);		
						$ArrayToString4 = implode("**",array_unique($D3UserUnderD2Available));
						$D3UsersUnderD1123AvailableUser = explode("**",$ArrayToString4);
				  ?>
				 
				 
				  <?php echo "**".$TRDValues; 
						echo "**".$totalInBoundAndOutBoundConnectedCalls;
						
					
						
						
	}
	
	 
	?>
	