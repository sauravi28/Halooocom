
<?php 
						
error_reporting(E_ERROR | E_PARSE);
	
session_start();

$loggedInuserName = $_SESSION['username'] ;
$loggedInPassword = $_SESSION['pass'];

//echo "<br><br>";
//echo "====>".$_SESSION['user'];
if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/quessAdmin/pages/Agent/quessLogin/index.php');
}

								
$identification = $_REQUEST['identification'];
if($identification == "clickTocall"){
	
$phoneNumber = $_REQUEST['phoneNumber']; 
$candidateRecirdId = $_REQUEST['candidateRecirdId'];

			//get location and work type
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user" => "$loggedInuserName" ];							
							$options = [  ]; //$loggedInuserName
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsUserlocation = $mongo->executeQuery("candidateinfo.user_live", $qry); 
							$exlocationWorkType =  $rowsUserlocation->toArray();
							$locationWorkTyopeArray = json_decode(json_encode($exlocationWorkType), true);
							
							//get extension
							
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user_name" => "$loggedInuserName" ];							
							$options = [  ]; //$loggedInuserName
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsUserExtension = $mongo->executeQuery("admin.users", $qry); 
							$exExtention =  $rowsUserExtension->toArray();
							$extensionIDArray = json_decode(json_encode($exExtention), true);
							$userExtensionVar =  $extensionIDArray[0]['extension_id'];  // user Extension  location
							$userLocationVar =  $extensionIDArray[0]['location'];  //location
							
							$did           = $extensionIDArray[0]['DID'];//DID 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "phone_id" => "$userExtensionVar" ];							
							$options = [  ]; //$loggedInuserName
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsUserExtensionPhone = $mongo->executeQuery("admin.phones", $qry); 
							$exExtentionPhone =  $rowsUserExtensionPhone->toArray();
							$extensionPhoneIDArray = json_decode(json_encode($exExtentionPhone), true);
							
							
							$userPhoneNumberVar =  $extensionPhoneIDArray[0]['wfh_phoneNo'];	 //phone Number						
							//$userLocationVar = $locationWorkTyopeArray[0]['user_location'];  // user location
							$userWorkTypeVar =  $locationWorkTyopeArray[0]['work_type'];  // work type 
													
				if(($userWorkTypeVar == 'WFH') && ($userLocationVar == 'Chennai')){  
					$userPHONENUMBER =  $userPhoneNumberVar;
					$internalPhoneline = "11".$userPHONENUMBER; //"1001";
					$context = "default_wfh";
					$target = "22".$phoneNumber;	
				}
				else if(($userWorkTypeVar == 'WFH') && ($userLocationVar == 'Hyderabad')){  
					$userPHONENUMBER =  $userPhoneNumberVar;
					$internalPhoneline = "33".$userPHONENUMBER; //"1001";
					$context = "default_wfh";
					$target = "44".$phoneNumber;	
				}
				else if(($userWorkTypeVar == 'WFH') && ($userLocationVar == 'Bangalore')){  
					$userPHONENUMBER =  $userPhoneNumberVar;
					$internalPhoneline = "77".$userPHONENUMBER; //"1001";
					$context = "default_wfh";
					$target = "66".$phoneNumber;	
				}
				else if(($userWorkTypeVar == 'WFO') && ($userLocationVar == 'Hyderabad')){
					$userPHONENUMBER = 	$userExtensionVar;
					$internalPhoneline = $userPHONENUMBER; //"1001";
					$context = "default_wfo";
					$target = "65".$phoneNumber;
				}
				else if(($userWorkTypeVar == 'WFO') && ($userLocationVar == 'Chennai')){
					$userPHONENUMBER = 	$userExtensionVar;
					$internalPhoneline = $userPHONENUMBER; //"1001";
					$context = "default_wfo";
					$target = $phoneNumber;
				}
				else if(($userWorkTypeVar == 'WFO') && ($userLocationVar == 'Bangalore')){
					$userPHONENUMBER = 	$userExtensionVar;
					$internalPhoneline = $userPHONENUMBER; //"1001";
					$context = "default_wfo";
					$target = "55".$phoneNumber;
				}

//echo $userExtensionVar;
//echo $userPhoneNumberVar;
//echo $userWorkTypeVar;
//echo $userLocationVar;

//exit;


	
	$port = 5038;
$username = "clickadmin";
$password = "admin1234";

$socket = stream_socket_client("tcp://127.0.0.1:$port");
if($socket)
{
    echo "Connected to socket, sending authentication request.\n";

    // Prepare authentication request
    $authenticationRequest = "Action: Login\r\n";
    $authenticationRequest .= "Username: $username\r\n";
    $authenticationRequest .= "Secret: $password\r\n";
    $authenticationRequest .= "Events: off\r\n\r\n";

    // Send authentication request
    $authenticate = stream_socket_sendto($socket, $authenticationRequest);
    if($authenticate > 0)
    {
        // Wait for server response
        usleep(200000);

        // Read server response
        $authenticateResponse = fread($socket, 4096);

        // Check if authentication was successful
        if(strpos($authenticateResponse, 'Success') !== false)
        {
            echo "Authenticated to Asterisk Manager Inteface. Initiating call.\n";
					 
													
					################## Drop call update 								
					date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'customerno' => "$phoneNumber", "miss_update"               => "0"];							
							$options = [ ];  //
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExt = $mongo->executeQuery("candidateinfo.inbound", $qry); 
							foreach($rowsExt as $rowsph){
								$userPhoneid = $rowsph->id;
											
									$bulk = new MongoDB\Driver\BulkWrite;  //updating as call  rinning status
                                        $bulk->update(
                                                ["id" => "$userPhoneid"],
                                                ['$set' =>
                                                        ["miss_update"               => "1"
														
														]
                                                ],
                                                ['multi' => false, 'upsert' => false]
                                        );
                                        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
                                        $result = $manager->executeBulkWrite('candidateinfo.inbound', $bulk);
							}
						############################# callback update
					
						date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ["callback_update"               => "0",
							'$or' => [
								[ 'customerno' => "$phoneNumber"],
								[ 'customerno' => "65$phoneNumber"],  //"dialstatus" => "DROP"
								
								]
								];							
							$options = [ ];  //
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExt = $mongo->executeQuery("candidateinfo.outbound", $qry); 
							foreach($rowsExt as $rowsph){
								$userPhoneid = $rowsph->id;
											
									$bulk = new MongoDB\Driver\BulkWrite;  //updating as call  rinning status
                                        $bulk->update(
                                                ["id" => "$userPhoneid"],
                                                ['$set' =>
                                                        ["callback_update"               => "1"
														
														]
                                                ],
                                                ['multi' => false, 'upsert' => false]
                                        );
                                        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
                                        $result = $manager->executeBulkWrite('candidateinfo.outbound', $bulk);
							}
						$todayDateForModify = date("Y-m-d H:i:s");
							################# User Update 		
							$bulk = new MongoDB\Driver\BulkWrite;  //updating as call  rinning status
                                        $bulk->update(
                                                ["phone_number" => "$phoneNumber","archival" => 0],
                                                ['$set' =>
                                                        ["user"               => "$loggedInuserName",
														"modified_date"       => "$todayDateForModify"
														
														]
                                                ],
                                                ['multi' => false, 'upsert' => false]
                                        );
                                        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
                                        $result = $manager->executeBulkWrite('candidateinfo.candidate_Information', $bulk);
							
            // Prepare originate request
            $originateRequest = "Action: Originate\r\n";
            if($userWorkTypeVar == 'WFH'){
			$originateRequest .= "Channel:  Local/$internalPhoneline@default_wfh\r\n";
			echo "=============".$originateRequest;
			}
			else if($userWorkTypeVar == 'WFO'){
			 $originateRequest .= "Channel:  Local/$internalPhoneline@default_wfo\r\n";
			 //$originateRequest .= "Variable: record_ext=$internalPhoneline\r\n\r\n";
			}
            $originateRequest .= "Callerid: Click 2 Call\r\n";
            $originateRequest .= "Exten: $target\r\n";
            $originateRequest .= "Context: $context\r\n";
            $originateRequest .= "Priority: 1\r\n";
            $originateRequest .= "Async: yes\r\n\r\n";
	    $originateRequest .= "Action: Setvar\r\n";
            $originateRequest .= "Variable: record_ext\r\n";
            $originateRequest .= "Value: $internalPhoneline\r\n\r\n";
          
	    if($did == '8045152800')
	    {
		$dida = '590';
		$didm = '560';
	    }
	    elseif($did == '8045152500')
	    {
	     $dida = '500';   
	     $didm = '510';
	    }
	  
          else
	 {
	  $dida = '590';
          $didm = '560';
	 }
            $originateRequest .= "Async: yes\r\n\r\n";
            $originateRequest .= "Action: Setvar\r\n";
            $originateRequest .= "Variable: dida\r\n";
            $originateRequest .= "Value: $dida\r\n\r\n";

            $originateRequest .= "Async: yes\r\n\r\n";
            $originateRequest .= "Action: Setvar\r\n";
            $originateRequest .= "Variable: didm\r\n";
            $originateRequest .= "Value: $didm\r\n\r\n";

            // Send originate request
            $originate = stream_socket_sendto($socket, $originateRequest);
            if($originate > 0)
            {
                // Wait for server response
                usleep(200000);

                // Read server response
                $originateResponse = fread($socket, 4096);

                // Check if originate was successful
                if(strpos($originateResponse, 'Success') !== false)
                {
                    //echo "Call initiated, dialing.";
					
					
					
					
					
					
					
					
                } else {
                    echo $originateRequest."Could not initiate call.\n".$originateResponse;
                }
            } else {
                echo "Could not write call initiation request to socket.\n";
            }
        } else {
            echo "Could not authenticate to Asterisk Manager Interface.\n";
        }
    } else {
        echo "Could not write authentication request to socket.\n";
    }
} else {
    echo "Unable to connect to socket.";
}




exit;

}
$login_errorr_cnt=1;
    $login_errorr="";
//Candidate_firstname,Candidate_lastname,mobile_no,skill_set,additional_skill_set,current_emp,total_experience,current_annual_CTC,expected_annual_CTC,notice_period,
//location,gender,relocation,employment_type,Linked_in_url,ATS_url
	if($_POST['addCandidateData'] == 'submit'){
		$Candidate_firstname = $_POST['Candidate_firstname'];
		$Candidate_lastname = $_POST['Candidate_lastname'];
		$mobile_no = $_POST['mobile_no'];
		$lead_source = $_POST['lead_source'];
		$skill_set = $_POST['skill_set'];
		$additional_skill_set = $_POST['additional_skill_set'];
		$current_emp = $_POST['current_emp'];
		$total_experience = $_POST['total_experience'];
		$current_annual_CTC = $_POST['current_annual_CTC'];
		$expected_annual_CTC = $_POST['expected_annual_CTC'];
		$notice_period = $_POST['notice_period'];
		$location = $_POST['location'];
		$gender = $_POST['gender'];
		$relocation = $_POST['relocation'];
		$employment_type = $_POST['employment_type'];
		$consentshareCV = $_POST['consentshareCV'];
		$Linked_in_url = $_POST['Linked_in_url'];
		$ATS_url = $_POST['ATS_url'];
		$user = $loggedInuserName;
		
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "phone_number" =>  "$mobile_no","archival"  => 0
										//'$or' => [											
										//			["phone_number" => array('$regex' => "$mobile_no")],
										//			["archival" => array('$regex' => 1)]												
										//		]						
							];							
							$options = [ 'sort' => ['created_date' => -1] ];
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$duplicatePhoneNumber = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
							$checkingDuplicatePhoneNumber1 = $duplicatePhoneNumber->toArray();
							$checkingDuplicatePhoneNumber = json_decode(json_encode($checkingDuplicatePhoneNumber1), true);	
						
							if(count($checkingDuplicatePhoneNumber) == 0 ){	
									date_default_timezone_set('asia/kolkata');		
									$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");   	
									$bulk = new MongoDB\Driver\BulkWrite;
									$qry = new MongoDB\Driver\Query([]);
									$date = date("Y-m-d H:i:s");
									$doc = array(
									 //   'id'      => new MongoDB\BSON\ObjectID,     #Generate MongoID
										"id"                       => strval(rand()),
										"created_date"             => $date,
										"modified_date"            => $date,
										"firstname"                => $Candidate_firstname,
										"lastname"				   => $Candidate_lastname,	
										"phone_number"			   => $mobile_no,	
										"user"	                   => $user,
										"skill_set"                => $skill_set,
										"additional_skill_set"     => $additional_skill_set,
										"total_experience"         => $total_experience,
										"current_annual_ctc"	   => $current_annual_CTC,
										"current_employer"         => $current_emp,
										"expected_annual_ctc"	   => $expected_annual_CTC,
										"notice_period"            => $notice_period,
										"location"                 => $location,
										"employement_type"         => $employment_type,
										"profileImg"               => "",
										"gender"                   => $gender,
										"relocation"               => $relocation,
										"consentshareCV"           => $consentshareCV,
										"linked_url"               => $Linked_in_url,
										"ATS_url"                  => $ATS_url,
										"archival"                 =>  0,
										"lead_source"              => $lead_source,
										"ownerOfTheLead"           => $user
										
									);
									$bulk->insert($doc);
									$mongo->executeBulkWrite('candidateinfo.candidate_Information', $bulk);    # 'schooldb' is database and 'student' is collection.exit
							}else{
								/*date_default_timezone_set('asia/kolkata');
								$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
								$bulk = new MongoDB\Driver\BulkWrite;
								$filter = [ "phone_number" =>  "$mobile_no","archival"  => 1
											//'$or' => [											
											//			["phone_number" => array('$regex' => "$mobile_no")],
											//			["archival" => array('$regex' => 1)]												
											//		]						
								];							
								$options = [ 'sort' => ['created_date' => -1] ];
								$qry = new MongoDB\Driver\Query($filter,$options);
								$date = date("Y-m-d H:i:s");
								$duplicatePhoneNumber = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
								$checkingDuplicatePhoneNumber1 = $duplicatePhoneNumber->toArray();
								$checkingDuplicatePhoneNumber = json_decode(json_encode($checkingDuplicatePhoneNumber1), true);	
							
								if(count($checkingDuplicatePhoneNumber) == 0 ){
								}else{*/
									$login_errorr_cnt=1;
									$login_errorr="Phone Number Already Existing.";
							//	}
							}
	}
	
	
   
	if($_POST['submitArchival']){
		
		$archivalId = $_POST['archivalId'];
		
		date_default_timezone_set('asia/kolkata');
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
		$bulk = new MongoDB\Driver\BulkWrite;
		$filter = [ 'id' => $archivalId ];							
		$options = [  ];
		$qry = new MongoDB\Driver\Query($filter,$options);
		$date = date("Y-m-d H:i:s");
		$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
		$candidateInfoCreatedDate = $rows->toArray();
		$createdDate = json_decode(json_encode($candidateInfoCreatedDate), true);	
		$createdDateinfo =$createdDate[0]['created_date'];
		if(time() - strtotime($createdDateinfo) < 3601){
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->update(
				['id' => $archivalId],
				['$set' => 	
					["archival"               => 1 ]	
				],
				['multi' => false, 'upsert' => false]
			);

			$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
			$result = $manager->executeBulkWrite('candidateinfo.candidate_Information', $bulk);	 
		}else{
            
			      $login_errorr_cnt=1;
                  $login_errorr="<span style = 'font-size: 17px;font-family: monospace;'><b>Archival of the candidate is allowed only before an hour of candidate creation</b></span>";
		//	echo '<script type="text/javascript"> alert("You Can\'t Do This Action Because It\'s Been More Then One Hour.")</script>';
			
		}
		
		
	}
	
	if($_POST['submitDelete']){
		
		$deleteUserId = $_POST['deleteId'];
		$deleteUserId = strval(trim($deleteUserId));		
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete(['id' => $deleteUserId], ['limit' => 1]);
		$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
		$result = $manager->executeBulkWrite('candidateinfo.candidate_Information', $bulk);
		
		
	}

   /* if(isset($_POST['btn_submit'])){
		
		$entry_date = date('Y-m-d H:i:s');
		$user = 'admin';
		$comments = $_POST['txt_comments'];
		$candidate_phone_number = $_POST['candidate_phone_number'];
		$candidate_id = $_POST['candidate_id']; // candidate_Information table id field..
		
		date_default_timezone_set('asia/kolkata');
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
		$bulk = new MongoDB\Driver\BulkWrite;
		$qry = new MongoDB\Driver\Query([]);
		
		$doc = array(
		 //   'id'      => new MongoDB\BSON\ObjectID,     #Generate MongoID
			"id"                       => strval(rand()),
			"entry_date"               => $entry_date,
			"user"	                   => $user,
			"comments"				   => $comments,	
			"phone_number"			   => $candidate_phone_number,	
			"candidate_id"             => $candidate_id
		);
		$bulk->insert($doc);
		$mongo->executeBulkWrite('candidateinfo.userprofile_comment', $bulk);    # 'schooldb' is database and 'student' is collection.exit

		}*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

 <style>
  .btnaa {
	  background-color: white; /* Green */
	  border: 1px solid #59BBE3 ;
	  color:  #59BBE3 ;
	  padding: 5px;
	  text-align: center;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 18px;
	  margin-top:1px
	  cursor: pointer; 
	  border-radius: 8px;
	  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
	  transition: all 0.3s ease 0s;
	  cursor: pointer;
	  outline: 1;
	}
	
	/* Darker background on mouse-over */
	.btnaa:hover {
		background-color: #2EE59D;
		box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
		color: #fff;
		transform: translateY(-7px);
	 }
	 
	 .btnaa1{
    background-color: #fff;
    border: 1px solid #59BBE3;
    padding: 5px 10px 5px 10px;
    color: #59BBE3;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 8px;
    /* box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1); */
    transition: all 0.3s ease 0s;
 }
 .btnaa1:hover {
	background-color: #2EE59D;
    box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
    color: #fff;
    transform: translateY(-7px);
 }
 
    tfoot {
		display: table-header-group;
		}
		
	thead th, table.dataTable thead td {
		padding: 15px 18px !important;
		border-bottom: 1px solid #dee2e6 !important;
	}
	table.dataTable.no-footer {
       border-bottom: 1px solid #dee2e6 !important;
    }
   .dataTables_wrapper .dataTables_info {
		clear: both;
		float: left;
		padding: 10px !important;
		font-size: smaller !important;
	}
	.dataTables_wrapper .dataTables_paginate {
		float: right;
		text-align: right;
		padding: 10px !important;
		font-size: smaller !important;
	}
	.dataTables_wrapper .dataTables_length {
		float: left;
		padding: 10px !important;
		font-size: smaller !important;
	}
	.dataTables_wrapper .dataTables_filter {
		float: right;
		text-align: right;
		padding: 10px !important;
		font-size: smaller !important;
	}
	.dataTables_wrapper .dataTables_filter input {
		border: 1px solid #dee2e6 !important;
	}
	.modal-dialog {
		max-width: 750px;
		margin: 1.75rem auto;
	}
	
	@import url('https://rsms.me/inter/inter.css');
 :root {
	 --color-light: white;
	 --color-dark: #212121;
	 --color-signal: #fab700;
	 --color-background: var(--color-light);
	 --color-text: var(--color-dark);
	 --color-accent: var(--color-signal);
	 --size-bezel: 0.5rem;
	 --size-radius: 4px;
	 line-height: 1.4;
	 font-family: 'Inter', sans-serif;
	 font-size: calc(.6rem + .4vw);
	 color: var(--color-text);
	 background: var(--color-background);
	 font-weight: 300;
	 padding: 0 calc(var(--size-bezel) * 3);
}

 .input {
	 position: relative;
}
 .input__label {
	 position: absolute;
	 left: 0;
	 top: 0;
	 padding: calc(var(--size-bezel) * 0.75) calc(var(--size-bezel) * .5);
	 margin: calc(var(--size-bezel) * 0.75 + 3px) calc(var(--size-bezel) * .5);
	 background: pink;
	 white-space: nowrap;
	 transform: translate(0, 0);
	 transform-origin: 0 0;
	 background: var(--color-background);
	 transition: transform 120ms ease-in;
	 line-height: 0.2;
}

 .input__field:focus + .input__label, .input__field:not(:placeholder-shown) + .input__label {
	 transform: translate(0.25rem, -65%) scale(0.8);
	 color: var(--color-accent);
}

label:not(.form-check-label):not(.custom-file-label) {
    font-weight: 400 !important;
	font-size: 15px !important;
}
label {
    display: block;  
    margin-bottom: 0.5rem;
}

.element {
  display: inline-flex;
  align-items: center;
}
i.fa-camera {
  margin: 10px;
  cursor: pointer;
  font-size:20px;
}

.inputFile {
  display: none;
}

fa-upload{
  margin: 10px;
  cursor: pointer;
  font-size:20px;
}

.container_comment {
    background-color: #eef2f5;
    width: 485px
}
.mt-5, .my-5 {
    margin-top: 0rem!important;
}
.second {
    width: 380px;
    background-color: white;
    border-radius: 4px;
    box-shadow: 10px 10px 5px #aaaaaa
}

.text1 {
    font-size: 15px;
    font-weight: 600;
    color: #56575b
}

.text2 {
    font-size: 13px;
    font-weight: 500;
    margin-left: 6px;
    color: #56575b
}

.text3 {
    font-size: 13px;
    font-weight: 500;
    margin-right: 4px;
    color: #828386
}

.text3o {
    color: #00a5f4
}

.text4 {
    font-size: 13px;
    font-weight: 500;
    color: #828386
}

.text4i {
    color: #00a5f4
}

.text4o {
    color: white
}

.scrollable {
  height: 230px;
  overflow-y: scroll;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.navbar-dark {
    background-color:#ffffff !important;
    border-color: #4b545c !important;
	box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);
}

.content-wrapper {
    background-color: #fff !important;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

 .btnaaInput:hover {
		box-shadow: 0px 1px 3px rgb(192, 185, 185);
		transform: translateY(-4px);
		cursor: pointer;
	 }
	 
.btnaaInput1{
    background-color: #fff;
    border: 1px solid #8bc541;
    padding: 2px 10px 2px 10px;
    color: #1a2b6d;   //#59BBE3;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 8px;
   box-shadow: 0px 8px 15px rgb(0 0 0 / 10%);
   transition: all 0.3s ease 0s;
 }
 
 .btnaaInput1:hover {
		background-color: #2EE59D;
		box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
		color: #fff;
		transform: translateY(-7px);
	 }
	 
	 
.btnaaInput2{
    background-color: #fff;
    border: 1px solid #8bc541;
    padding: 2px 10px 2px 10px;
    color: red;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 8px;
    box-shadow: 0px 8px 15px rgb(0 0 0 / 10%);
	transition: all 0.3s ease 0s;
 }
 
 .btnaaInput2:hover {
		background-color: #2EE59D;
		box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
		color: #fff;
		transform: translateY(-7px);
	 }

	 
	.blink {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {
	  50% {
		opacity: 0;
	  }
	}
	
.btnab {
  background-color: white; /* Green */
  border: none;
  color:  #59BBE3;
  padding: 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 18px;
  margin-top:1px
  cursor: pointer; 
  border-radius: 8px;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: 1;
  width: 245px;
}
.btnab:hover {
	background-color: #2EE59D;
    box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
    color: #fff;
    transform: translateY(-7px);
}

.modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1040 !important;
    width: 1vw !important;
    height: 0vh !important;
    
}


.tableFixHead {
     width: 102% !important;
    }
    .tableFixHead tbody {
      display: block !important;
      width: 100% !important;
      overflow: auto !important;
      height: 430px !important;
    }
    .tableFixHead thead tr {
      display: block !important;
    }
	 .tableFixHead tfoot tr {
		 width: 100% !important;
      display: block !important;
    }
	
    .tableFixHead th,
    .tableFixHead td {
      width: 200px !important;
    }


.wrap {
    width: 100%;
}

.wrap table {
    width: 100%;
    table-layout: fixed;
}

table.tablebody tr td {
    padding: 5px;
    width: 100px;
    word-wrap: break-word;
	border-bottom: 1px solid #dee2e6 !important;
}

table.head tr td {
   background-color:#59BBE3 !important;color:#ffff;
   padding: 15px 18px !important;
   border-bottom: 1px solid #dee2e6 !important;
   font-weight: 700 !important;
   
}

.inner_table {
    height: 430px;
    overflow-y: auto;
}

.pagination {
    display: inline-block;
    padding-left: 0;
    margin: 20px 0;
    border-radius: 4px;
}

.pagination>li {
    display: inline;
}

.pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover {
    z-index: 2;
    color: #23527c;
    background-color: #eee;
    border-color: #ddd;
}
.pagination>li>a, .pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}



	.globalSearchInputBox{
 
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  background-color: white;
  background-image: url('searchicon.png');
  background-position: 10px 10px; 
  background-repeat: no-repeat;
  padding: 0px 5px 5px 5px;
}


[data-title]:hover:after {
        opacity: 1;
        transition: all 0.1s ease 0.5s;
        visibility: visible;
      }
      [data-title]:after {
        content: attr(data-title);
        position: absolute;
        bottom: -1.6em;
        left: 100%;
        padding: 4px 4px 4px 8px;
        color: #666;
        white-space: nowrap;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-box-shadow: 0px 0px 4px #666;
        -webkit-box-shadow: 0px 0px 4px #666;
        box-shadow: 0px 0px 4px #666;
        background-image: -moz-linear-gradient(top, #f0eded, #bfbdbd);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #f0eded), color-stop(1, #bfbdbd));
        background-image: -webkit-linear-gradient(top, #f0eded, #bfbdbd);
        background-image: -moz-linear-gradient(top, #f0eded, #bfbdbd);
        background-image: -ms-linear-gradient(top, #f0eded, #bfbdbd);
        background-image: -o-linear-gradient(top, #f0eded, #bfbdbd);
        opacity: 0;
        z-index: 99999;
        visibility: hidden;
      }
      [data-title] {
        position: relative;
      }
</style>
			
			
			

		<!--<link rel="stylesheet" href = "https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
	<script src = "https://code.jquery.com/jquery-1.12.3.js"></script>  
		<script  src = "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>-->
		
	<script src = "https://code.jquery.com/jquery-3.5.1.js"></script> 
		<script src = "js/jquery.dataTables.min.js"></script>  <!-- <script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>-->
		<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css"> 

<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.5/js/dataTables.scroller.min.js"></script>
	
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css"> -->

<!--Pagenation  -->

<!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->



<script>
$(document).ready(function(){
	
   
   
   	 $(".globalSearchclass").change(function(){
		 
		 
		var globalSearchValue = document.getElementById("globalSearch").value;
		var selectedRows = document.getElementById("selectedRows").value;
			if(globalSearchValue){
				//alert(selectedRows);
				var LoginUserNameForFile = '<?php echo $loggedInuserName; ?>';
				var LoginUserName = '<?php echo $loggedInuserName; ?>';
				//alert(LoginUserName);
				    var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							if(this.responseText != ''){
									var data = this.responseText;
									var candidateTable = data.split('***************************');
								document.getElementById("candidateDateTable").innerHTML =candidateTable[0];
								document.getElementById("TotalRecords").innerHTML =candidateTable[1];
								document.getElementById("PrevPagePagination").innerHTML =candidateTable[2];  // 
								document.getElementById("NextPagePagination").innerHTML =candidateTable[3]; 
								document.getElementById("LastPagePagination").innerHTML =candidateTable[4];  
								document.getElementById("FirstPagePagination").innerHTML =candidateTable[5];
								document.getElementById('rowSelectionFormId').action = 'home.php?searchValue=' + globalSearchValue;
							}else{
								alert(this.responseText);
							}
					   }
					};
					xhttp.open("GET",  "candidateDateTable_search.php?LoginUserName="+LoginUserName+'&globalSearchValue='+globalSearchValue+"&selectedRows="+selectedRows, true);
					xhttp.send();
			}else{
				document.getElementById("refreshFormID").submit();
			//	location.reload();
				
			}
			/*	var xhttp = new XMLHttpRequest();
			    xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					alert("sandeep");
					alert(this.responseText);
					$("#candidateDateTable").html(this.responseText);
				 /*  $('#CandidateData').DataTable( {
					"ajax": 'dataFiles/arrays_'+LoginUserNameForFile+'.txt',
					columnDefs: [{
						"defaultContent": "-",
						"targets": "_all",
						orderable: false
					  }],
					  "order": [[ 0, "desc" ]]				
				} );
				} */
	/*		  }
			  xhttp.open("GET", "candidateDateTable.php?LoginUserName="+LoginUserName+'&LoginUserNameForFile='+LoginUserNameForFile, true);
			  xhttp.send()
   }; */
});

  $("#img_id").click(function () {
		//$("input[type='file']").trigger('click');
		$($('input[type=file]').get(0)).trigger('click');
	});
	
});

$(document).ready(function (e) {

	$("#uploadForm").on('submit',(function(e) {
		
	var leadId = document.getElementById("leadId").value;
	//alert(leadId);
	//return true;

		e.preventDefault();
		$.ajax({
        	url: "upload.php?leadId="+leadId,    //+"&firstName="+firstName			
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
		    {
			//alert(data);
			//location.reload();
			$("#targetLayer").html(data);
			
		    },
		  	error: function() 
	    	{
	    	} 
	        
	   });
	   //alert("test");
	  //location.reload();
	}));
});
</script>


<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />-->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

<style>
.right-container {
        position: relative;
}
#icon-chat {
     font-size: 15px;
}
.right-container .right-container-button {
    width: 25px;
    height: 25px;
    border: none;
    background-color: #52c343;
    transition: all 300ms;
    cursor: pointer;
    /* padding: 10px; */
    color: white;
    font-family: roboto, sans-serif;
    border-radius: 55px;
}
.right-container .right-container-button span {
     color: white;
     position: absolute;
     left: 5px;
     top: 6px;
     line-height: 28px;
}
.right-container .right-container-button:hover {
     transition: all 400ms cubic-bezier(0.62, 0.1, 0.5, 1);
     width: 54px;
     border-top-right-radius: 55px;
     border-bottom-right-radius: 55px;
     
}
.right-container .right-container-button .long-text {
     transition: opacity 1000ms;
     opacity: 0;
     color: white;
     white-space: nowrap;
     font-size: 0;
     width: 0;
     margin: 0;
}
.right-container .right-container-button .long-text.show-long-text {
     transition: opacity 700ms, width 1ms linear 270ms,
          font-size 1ms linear 270ms;
     opacity: 1;
     margin-left: 20px;
     font-size: 14px;
     width: auto;
}

</style>

<script>
$(document).ready(function(){

$(".right-container-button").hover(function() {
    $(".long-text").addClass("show-long-text");
},
function () {
    $(".long-text").removeClass("show-long-text");
});

 sampleDiv_zoom.style.zoom='80%';
	var scale = 'scale(1)';
	document.body.style.webkitTransform = scale;  // Chrome, Opera, Safari
	document.body.style.msTransform = scale;     // IE 9
	document.body.style.transform = scale;     // General

});
</script>

</head>
<body class="hold-transition sidebar-mini" id="sampleDiv_zoom">
	
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header1 navbar navbar-expand navbar-dark navbar-dark"> <!-- class name we made it wrong to avoid side bar-->
    <!-- Left navbar links -->
 <ul class="navbar-nav">  
	<li class="nav-item">
		 <img src="../../dist/img/logo2.png" alt="Admin Logo" class="" style="opacity: .8" height="70" width="160">
      </li>
      <!-- Top manu bar <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>-->
    </ul> 

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item" style="display:none;">
	  
        <a class="nav-link" data-widget="navbar-search" href="#" role="button" id = "SearchId">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline" align = "right" width="800px" action = "#" method = "POST"> 
            <div class="input-group input-group-sm" style = "margin-left:200px">
              <input class="form-control form-control-navbar" name = "GlobalSearchValue" id = "GlobalSearchValue" type="search" placeholder="First Name,Phone Number,Skill,Location" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit" value = "submit" name = "GlobalSearchButton">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
           </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown" style="display:none;">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown" style="display:none;">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
	  
	  <li class="nav-item">
         <img src="../../dist/img/quess_logo2.png" alt="Admin Logo" class="" style="opacity: .8" height="70" width="160">
      </li>
      <li class="nav-item" style = "display:none">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt" style="color: black;"></i>
        </a>
      </li>
      <li class="nav-item" style="display:none;">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->




 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style = "margin-left:0px !important;">
    <!-- Content Header (Page header) -->
   <br>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-2">   <!-- col-md-3 changed to col-md-2 -->

         
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary" style = "display:none;">
              <div class="card-header">
                <h3 class="card-title">Recruiter Info</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <!--  <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
                  B.S. in Computer Science from the University of Tennessee at Knoxville
                </p>

                <hr> -->

                <strong><i class="fa fa-user"></i> Recruiters</strong>

                <p class="text-muted"><span id = "lastRecruiterName"></span></p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Last Remarks</strong>

                <p class="text-muted">
                  <span class="tag tag-danger" id = "recruiterLastRemark"></span>
                
                </p>

                <hr>

                <!--<strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>-->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-12">  <!-- col-md-9 changed to col-md-10 -->
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><span><button type="button" id="userStatus" class="btn btn-danger">Status</button></span></li>
				   <div align="center" style ="position: absolute; right: 274px;" id = "customerTalkTime"></div>
				  <input type="hidden" id="userStatusHiddenValue" value="">
				  <div class="col-sm-2" align = "right">
								&nbsp;&nbsp; <?php echo date("d-m-Y");?> : <span id="hours">00:</span>
										   <span id="mins">00:</span>
										   <span id="seconds">00</span>  
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo $loggedInuserName; ?>)</span>
											<!--<span id = "incomingAndOutGoingUpdate"></span>-->
				   </div>
				     <div class="col-sm-4" align = "center">
						<span id = "incomingAndOutGoingUpdate"></span>
					 </div>
               <!--   <li class="nav-item"  style = "cursor: default;"><span class="nav-link " id = "detailedInfoheader" onclick = "displaySettingOfDetailedInfo();" data-toggle="tab" >DETAILED INFO</span></li> -->
				<!--  <li class="nav-item"  style = "cursor: default;"><a class="nav-link active" id = "detailedAssignCandidateheader" onclick="displaySettingAgentInfo()" data-toggle="tab"> ASSIGN CANDIDATES</a></li> -->
					<span class="form-group" style="margin-top: 15px !important;margin-bottom: -9px !important;margin-left: 20px  !important;">
						  <font color="red" size = "3">
						   <?php 
						   if($login_errorr_cnt =='1')
						   echo $login_errorr;
						   else
						   echo $login_errorr;
						   ?>
						  </font>
				    </span>
								
							  									
					 <!-- /.container-fluid -->
					 
			   </ul>
			   
			   	<input type = "hidden" value = "<?php echo $loggedInuserName; ?>" id = "loggedInUserForUserStatus">
			
			<div class="row" align="center">
			<div class="col-sm-2">
			</div>
			<div class="col-sm-2">
			</div>
			    
			      <div class="col-sm-1" style="max-width: fit-content !important;">
				   	<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="submit" class="btnaaInput1" title="Pause" id="btn_pause" name="btn_pause" onclick="open_pauseCodeData()" ><i class="fas fa-pause" style="font-size:28px"></i></button>	<!-- pauseMode();-->
                  </div>
				  
				  <div class="col-sm-1"style="max-width: fit-content !important;">
				   	<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
					<button type="submit" class="btnaaInput2" title="Active" id="btn_active" name="btn_active" onclick="activeMode();" disabled><i class="fas fa-play" style="font-size:28px"></i></button>					
				  </div>
				  
			     <div class="col-sm-1" style="max-width: fit-content !important;">
				<!--   <form action="<?php echo $_SERVER['PHP_SELF']?>" method = "POST"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="submit" class="btnaaInput1" title="Drop Call" onclick="updateDropCall_View();">  <!--onclick = "dropCallPop()" -->
						<i class="fas fa-phone-slash" style="font-size:28px"><span class='blink' style="padding:0px 7px;border-radius: 50%;color:white;" id='dropcallNotification'></i>
						</button>
			<!--		</form>		-->			
				  </div>
				  <div class="col-sm-1" style="max-width: fit-content !important;">
                        <input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="button" class="btnaaInput2" id="btn_hangup" onclick="callHangupFun('answered','<?php echo $loggedInuserName; ?>')" title="Hang Up" disabled><i class="fa fa-square" style="font-size:28px"></i></button> <!-- open_HangupCodeData(); -->
				  </div>
				  
				  <div class="col-sm-1" style="max-width: fit-content !important;">
					<button type="button" class="btnaaInput1" data-toggle="modal" data-target="#myModal" title="Add User">
					   <i class="fas fa-user-plus" style="font-size:28px"></i>
					</button>
			      </div>
			
			     <div class="col-sm-1" style="max-width: fit-content !important;" onclick="updateIndividual_messageView();" >
					<button type="button" class="btnaaInput1" data-toggle="modal" data-target="#myModal_notification" title="Notification" >
					   <i class="fas fa-bell" style="font-size:28px"><span class='blink' style="padding:0px 7px;border-radius: 50%;color:white;" id='notification-latestindividualChat'></span></i> 
					</button>
			      </div>
				  
				  <div class="col-sm-1" style="max-width: fit-content !important;display:none" >
				<!--   <form action="<?php// echo $_SERVER['PHP_SELF']?>" method = "POST"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="submit" class="btnaaInput1" title="Callback" onclick = "callBackPop()" style = "display:none"><img src="callback.jpg" alt="Trulli" width="25" height="25"><span class='blink' style="padding:0px 7px;border-radius: 50%;color:white;" id='callbackNotification'></span></button>    <!--<i class="fas fa-undo"></i> -->
				<!--	</form>	-->				
				  </div>
				  <div class="col-sm-1"style="max-width: fit-content !important;">
				<!--   <form action="<?php //echo $_SERVER['PHP_SELF']?>" method = "POST"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="submit" class="btnaaInput1" title="CallLog" data-toggle="modal" data-target="#myModal_RecruiterCallLog"><i class="fas fa-id-card" style="font-size:28px"></i></button>
					<!--</form>		-->			
				  </div> 	
				   <div class="col-sm-1"style="max-width: fit-content !important;">
				<!--   <form action="<?php //echo $_SERVER['PHP_SELF']?>" method = "POST"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "" name = "" id = "" >
						<button type="submit" class="btnaaInput1" title="LOGOUT"><a href="logout.php" title="Signout"><i class="fas fa-sign-out-alt" style="color:red;font-size:28px"></i></a></button>
					<!--</form>		-->			
				  </div> 
				  
			<div class="col-sm-2">
			</div>
			<div class="col-sm-2">
			</div>
			    
			</div> 
			
			 <marquee id = "recruiterBanner"></marquee> 
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity" style="display:none;">
                    <!-- Post -->
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                        <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="float-right btn-tool" ><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Shared publicly - 7:30 PM today</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Lorem ipsum represents a long-held tradition for designers,
                        typographers and the like. Some people hate it and argue for
                        its demise, but others ignore the hate as they create awesome
                        tools to help create filler text for everyone from bacon lovers
                        to Charlie Sheen fans.
                      </p>

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                      </p>

                      <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post clearfix">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Sent you a message - 3 days ago</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Lorem ipsum represents a long-held tradition for designers,
                        typographers and the like. Some people hate it and argue for
                        its demise, but others ignore the hate as they create awesome
                        tools to help create filler text for everyone from bacon lovers
                        to Charlie Sheen fans.
                      </p>

                     <!--        <form class="form-horizontal"> -->
                        <div class="input-group input-group-sm mb-0">
                          <input class="form-control form-control-sm" placeholder="Response">
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-danger">Send</button>
                          </div>
                        </div>
                      <!--      </form> -->
                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Adam Jones</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Posted 5 photos - 5 days ago</span>
                      </div>
                      <!-- /.user-block -->
                      <div class="row mb-3">
                        <div class="col-sm-6">
                          <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-6">
                              <img class="img-fluid mb-3" src="../../dist/img/photo2.png" alt="Photo">
                              <img class="img-fluid" src="../../dist/img/photo3.jpg" alt="Photo">
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                              <img class="img-fluid mb-3" src="../../dist/img/photo4.jpg" alt="Photo">
                              <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                      </p>

                      <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                    </div>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="active tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header" style="display:none;">
          <h3 class="card-title"> </h3>
	
        </div>
		   <br>
        <div class="card-body p-0">
		<div class="table-responsive">
		
		     
			   <div class="row">
			      <div class="col-sm-1">
					<?php 
						$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
					$bulk = new MongoDB\Driver\BulkWrite;
					$filter = [ "user" => "$loggedInuserName"];							
					$options = [ 'sort' => ['created_date' => -1],'limit' => 1];
					$qry = new MongoDB\Driver\Query($filter,$options);
					$date = date("Y-m-d H:i:s");
					$rowsget_User = $mongo->executeQuery("candidateinfo.userDataSelection", $qry); 	
					$exget_User =  $rowsget_User->toArray();
					$get_UserArray = json_decode(json_encode($exget_User), true);
					$seleData = $get_UserArray[0]['limitOfSelectedRows'];
					

					if($seleData == ''){					
							 $seleData = 10;						
					}else{
						 if(isset($_POST['selectedRows'])){
							 $seleData = $_POST['selectedRows'];
							 $searchValueTODisplay = $_GET['searchValue'];
							
						 }else{
							$seleData = $get_UserArray[0]['limitOfSelectedRows']; 
						 }
					}
					
						if (isset($_GET['pageno'])) {
							$searchValueTODisplay = $_GET['searchValue'];
						}
					?>  
					<form action= "<?php echo $_SERVER['PHP_SELF']."?searchValue=".$searchValueTODisplay?>" method = "POST" id = "rowSelectionFormId">
						<select name='selectedRows' id = "selectedRows" onchange='this.form.submit()'>
							<option selected><?php echo $seleData  ?></option>
							<option value = 10>10</option>
							<option value = 25>25</option>
							<option value = 50>50</option>
							<option value = 100>100</option>
						</select>
						<noscript><input type="submit" value="Submit"></noscript>
					</form>
				  </div>
				   <div class="col-sm-1">
				  </div>
				   <div class="col-sm-1">
				  </div>
				   <div class="col-sm-1">
				  </div>
				   <div class="col-sm-1">
				  </div>
		
				  <br>
			     <div class="col-sm-0">
				   <form action="<?php echo $_SERVER['PHP_SELF']?>" method = "POST" id="refreshFormID"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "RefreshData" name = "RefreshData" id = "RefreshData" >
						<button type="submit" class="btn" title="Refresh"><i class="fas fa-sync-alt btnaaInput" style="color:red; font-size:25px"></i></button> 
					</form>
				  </div>
			    
		  <?php 
		  
		 
			    if (isset($_GET['pageno'])) {
							$pageno = $_GET['pageno'];
							$lastCreatedDate = $_GET['lastCreatedDate'];
							$firstCreatedDate = $_GET['firstCreatedDate']; 
							$EndCreatedDate = $_GET['EndCreatedDate'];
							$firstCreatedDateToDisplay = $_GET['firstCreatedDateToDisplay'];
						} else {
							$pageno = 0;
						}
						 
						$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
					$bulk = new MongoDB\Driver\BulkWrite;
					$filter = [ "user" => "$loggedInuserName"];							
					$options = [ 'sort' => ['created_date' => -1],'limit' => 1];
					$qry = new MongoDB\Driver\Query($filter,$options);
					$date = date("Y-m-d H:i:s");
					$rowsget_User = $mongo->executeQuery("candidateinfo.userDataSelection", $qry); 	
					$exget_User =  $rowsget_User->toArray();
					$get_UserArray = json_decode(json_encode($exget_User), true);
					if($get_UserArray[0]['id'] > 0){
						 $SelectedcandidateOption = $get_UserArray[0]['id'];
						$limitOfSelectedRowsselectedRows	=	$get_UserArray[0]['limitOfSelectedRows'];
					/*	$limitOfSelectedRows =  intval($_POST['selectedRows']);
						$bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[
										  "limitOfSelectedRows" => 10
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk); */
					}else{
						date_default_timezone_set('asia/kolkata');		
									$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");   	
									$bulk = new MongoDB\Driver\BulkWrite;
									$qry = new MongoDB\Driver\Query([]);
									$date = date("Y-m-d H:i:s");
									$doc = array(								
										"id"                       => strval(rand()),
										"created_date"             => $date,
										"user"         			   => $loggedInuserName,
										"seletedOption"            => "",
										"limitOfSelectedRows"	  => 10
										
									);
									$bulk->insert($doc);
									$mongo->executeBulkWrite('candidateinfo.userDataSelection', $bulk);
					}
					
					 if(isset($_POST['selectedRows'])){
						 $SelectedcandidateOption = $get_UserArray[0]['id'];
						$limitOfSelectedRows =  intval($_POST['selectedRows']);
						$bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[
										  "limitOfSelectedRows" => $limitOfSelectedRows
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk);
						  }/*else{
							  $SelectedcandidateOption = $get_UserArray[0]['id'];
							 $limitOfSelectedRows = 10; 
							 $bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[
										  "limitOfSelectedRows" => $limitOfSelectedRows
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk);
						  }*/
					
						if( $_POST['getAllData']== 'ALL'){
							$seletedOptionAll = $_POST['getAllData'];
							$SelectedcandidateOption = $get_UserArray[0]['id'];
							$bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[									
										"created_date"             => $date,
										"user"         			   => $loggedInuserName,
										"seletedOption"            => $seletedOptionAll
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk);

						}else if($_POST['RefreshData'] == 'RefreshData'){
							$RefreshData = $_POST['RefreshData'];
									$SelectedcandidateOption = $get_UserArray[0]['id'];
							$bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[									
										"created_date"             => $date,
										"user"         			   => $loggedInuserName,
										"seletedOption"            => $RefreshData
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk);
							
						}else if($_POST['singleUserData'] == 'singleUserData'){
								$singleUserData = $_POST['singleUserData'];
									$SelectedcandidateOption = $get_UserArray[0]['id'];
							$bulk = new MongoDB\Driver\BulkWrite;
							$bulk->update(
											['id' => $SelectedcandidateOption ],
											['$set' => 	
										[									
										"created_date"             => $date,
										"user"         			   => $loggedInuserName,
										"seletedOption"            => $singleUserData
										]	
										],
									['multi' => false, 'upsert' => false]
								);
								$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
								$result = $manager->executeBulkWrite('candidateinfo.userDataSelection', $bulk);
						}
		  
		   
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
					$bulk = new MongoDB\Driver\BulkWrite;
					$filter = [ "user" => "$loggedInuserName"];							
					$options = [ 'sort' => ['created_date' => -1],'limit' => 1];
					$qry = new MongoDB\Driver\Query($filter,$options);
					$date = date("Y-m-d H:i:s");
					$rowsget_User = $mongo->executeQuery("candidateinfo.userDataSelection", $qry); 	
					$exget_User =  $rowsget_User->toArray();
					$get_UserArray = json_decode(json_encode($exget_User), true);
						
						$limitOfSelectedRowsFromDB = $get_UserArray[0]['limitOfSelectedRows'];
		        	  		$colorForSelected = '';	
							$colorForSelectedSingle = '';		
					  if($get_UserArray[0]['seletedOption'] == 'ALL'){ 
							$colorForSelected = "color:green";
							
					  }else if($get_UserArray[0]['seletedOption'] == 'singleUserData'){
						  $colorForSelectedSingle = "color:green";
						 
					  }else{
						  $colorForSelectedSingle = "color:green";
					  }
					
					  ?>
					  
					<div class="col-sm-0">
					<form action="<?php echo $_SERVER['PHP_SELF']?>" method = "POST" id="allForm"  > <!--style="position: absolute;right: 120px;"-->
						<input  type="hidden" class="btn btn-tool" value = "ALL" name = "getAllData" id = "getAllData" >
						<button type="submit" id="testingcolor" class="btn" title="ALL"><i class="fa fa-users btnaaInput" style="font-size:25px;<?php echo empty($colorForSelected) ? "color:#59BBE3": $colorForSelected;?>"></i></button>
					</form>
					</div>
			      <div class="col-sm-0">
				   <form action="<?php echo $_SERVER['PHP_SELF']?>" method = "POST" id="form1ForAllData"> <!--style="position: absolute;right: 160px;"-->
						<input  type="hidden" class="btn btn-tool" value = "singleUserData" name = "singleUserData" id = "singleUserData" >
						<button type="submit" class="btn" title="<?php echo $loggedInuserName; ?>"><i class="fa fa-user btnaaInput" style="font-size:25px;<?php echo empty($colorForSelectedSingle) ? "color:#59BBE3": $colorForSelectedSingle;?>"></i></button>
					</form>
				  </div>
					<div class="col-sm-0">
					<button type="button" class="btn" data-toggle="modal" data-target="#myModal_Archival" title="Archive">
						<i class="fas fa-archive btnaaInput" style="color: #59BBE3;font-size:25px"></i>
					</button>
					</div>
				
					<div class="col-sm-0">
					<div align = "right">
						<table>
						<tr><td><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php 
						
					
						?>
						<b>Search:&nbsp;&nbsp;</b></label></td><td><input type = "input" name = "globalSearch"  class = "globalSearchInputBox globalSearchclass" value = "<?php echo $searchValueTODisplay; ?>"id = "globalSearch" >&nbsp;&nbsp;&nbsp;&nbsp;<strong class = "float-right d-none d-sm-block"><button type="button" class="btn btn-sm calldisbale globalSearchclass" style="width: 66px;height:23px;border: none;cursor: pointer;padding:0px;color: white;font-family: roboto, sans-serif;border-radius: 55px;background-color: #52c343;" title="Call" >SUBMIT</button> </strong></td></tr>
						</table>
					</div>
					</div>		
			  
		  </div>
		  <div id = "candidateDateTable">
		  <div class="wrap">
		    <table class="table m-0 head" name="item" id="CandidateData"  cellspacing="0"> <!-- tableFixHead-->
                  <tr style = "font-size:15px;">
                    <!--  <th style = "width:20.5833px !important;">#</th> -->
					  <td style = "display:none">create date</td>
                      <td>Name</td>
					  <td>Employer</td>
					  <td>Skills</td>
					  <td>Add. Skills</td>
					  <td>Experience</td>
					  <td>Current CTC</td>
					  <td>Expected CTC</td>
					  <td>Notice Period</td>
					  <td>Employment Type</td>					  
                      <td>Location </td>
      				  <td>Recruiters</td>
					  <td>Last Remarks</td>
					  <td>Phone Number</td>
					  <td>Action</td>
                  <!--    <th>Action</th> -->
                  </tr>
			</table> 
		    
          <!--<table class="table m-0 tableFixHead" name="item" id="CandidateData"  cellspacing="0"> 
              <thead style = "background-color:#59BBE3 !important;color:#ffff;">
                  <tr style = "font-size:15px;">
					<th style = "width:25px;display:none">create date</th>
                      <th style = "width:25px;">Name</th>
					  <th style = "width:25px">Employer</th>
					  <th style = "width:25px">Skills</th>
					  <th style = "width:25px">Add. Skills</th>
					  <th style = "width:25px">Experience</th>
					  <th style = "width:25px">Current CTC</th>
					  <th style = "width:25px">Expected CTC</th>
					  <th style = "width:25px">Notice Period</th>
					  <th style = "width:25px">Employment Type</th>					  
                      <th style = "width:25px">Location </th>
      				  <th style = "width:25px">Recruiters</th>
					  <th style = "width:25px">Last Remarks</th>
					  <th style = "width:25px">Phone Number</th>
					  <th style = "width:57px">Action</th>
                  
                  </tr>
              </thead>
			  <tfoot>
				   
			  </tfoot>
              <tbody>-->
			  
			   <div class="inner_table">
               <table class="tablebody">
                  <tr>
					  <?php 
					 
						
					$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
					$bulk = new MongoDB\Driver\BulkWrite;
					$filter = [ "user" => "$loggedInuserName"];							
					$options = [ 'sort' => ['created_date' => -1],'limit' => 1];
					$qry = new MongoDB\Driver\Query($filter,$options);
					$date = date("Y-m-d H:i:s");
					$rowsget_User = $mongo->executeQuery("candidateinfo.userDataSelection", $qry); 	
					$exget_User =  $rowsget_User->toArray();
					$get_UserArray = json_decode(json_encode($exget_User), true);
						
						$limitOfSelectedRowsFromDB = $get_UserArray[0]['limitOfSelectedRows'];
				
					  						
					  if($get_UserArray[0]['seletedOption'] == 'ALL'){ 
							$CrossCheckingData = "ALL";
							
					        
						
								if($pageno > 0){
								
									
									 if($lastCreatedDate != ''){
											 if($searchValueTODisplay != '' && $lastCreatedDate != ''){
													date_default_timezone_set('asia/kolkata');
													$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
													$bulk = new MongoDB\Driver\BulkWrite;
													$filter = [ "archival" => 0,
														'$or' => [
															["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
															["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
														],
														'$and' => [
																	['created_date' => ['$lt' => "$lastCreatedDate"]]										
																]
													];							
													$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
													$qry = new MongoDB\Driver\Query($filter,$options);
													$date = date("Y-m-d H:i:s");
													$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
												}else{
													date_default_timezone_set('asia/kolkata');
													$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
													$bulk = new MongoDB\Driver\BulkWrite;
													$filter = [ "archival" => 0,
														'$and' => [
																	['created_date' => ['$lt' => "$lastCreatedDate"]]										
																]
													];							
													$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
													$qry = new MongoDB\Driver\Query($filter,$options);
													$date = date("Y-m-d H:i:s");
													$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
												}
											 	
									  }elseif($firstCreatedDate != ''){
										   if($searchValueTODisplay != '' && $firstCreatedDate != ''){
											
											   $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
													'$or' => [
															["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
															["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
														],
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDate"]]										
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);											
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										   }else{
												
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDate"]]										
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);											
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										   }
										   
										  
									  }elseif($EndCreatedDate != ''){ 
											if($searchValueTODisplay !='' && $EndCreatedDate != ''){
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
													'$and' => [
																['created_date' => ['$lt' => "$EndCreatedDate"]]										
															],
													'$or' => [
															["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
															["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
														]
															
												];							
												$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
												
											}else{
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
													'$and' => [
																['created_date' => ['$lt' => "$EndCreatedDate"]]										
															]
												];							
												$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
											}
										    
									  }elseif($firstCreatedDateToDisplay != ''){ 
											if($firstCreatedDateToDisplay != '' && $searchValueTODisplay != ''){
												
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
														'$or' => [
															["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
															["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
														],
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDateToDisplay"]]										
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
											}else{
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0,
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDateToDisplay"]]										
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
											}
										   
									  }
												 
								}else{
								//echo "page 0 and all data";
									if($searchValueTODisplay != ''){
										date_default_timezone_set('asia/kolkata');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
										$bulk = new MongoDB\Driver\BulkWrite;
										$filter = [ "archival" => 0,
											'$or' => [
															["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
															["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
															["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
														]
										];							
										$options = [ 'sort' => ['modified_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
										$qry = new MongoDB\Driver\Query($filter,$options);
										$date = date("Y-m-d H:i:s");
										$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
									}else{
										date_default_timezone_set('asia/kolkata');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
										$bulk = new MongoDB\Driver\BulkWrite;
										$filter = [ "archival" => 0	];							
										$options = [ 'sort' => ['modified_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
										$qry = new MongoDB\Driver\Query($filter,$options);
										$date = date("Y-m-d H:i:s");
										$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 	
									}
								}
					
							
						}else{							
							$CrossCheckingData = "SingleUserValue";
							
								if($pageno > 1){
									  if($lastCreatedDate != ''){
										  if($lastCreatedDate != '' && $searchValueTODisplay != ''){
												 date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0, "user" => "$loggedInuserName",
													'$and' => [
																['created_date' => ['$lt' => "$lastCreatedDate"]]
															
															],
														'$or' => [
																["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
																["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
															]				
												];							
												$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										  }else{
											    date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0, "user" => "$loggedInuserName",
													'$and' => [
																['created_date' => ['$lt' => "$lastCreatedDate"]]
															
															] 
												];							
												$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										  }
										 	
									 }elseif($firstCreatedDate != ''){
										 if($firstCreatedDate != '' && $searchValueTODisplay != ''){
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0, "user" => "$loggedInuserName",
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDate"]]
															
															],
															'$or' => [
																["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
																["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										 }else{
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0, "user" => "$loggedInuserName",
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDate"]]
															
															] 
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										 }
										 	 
									 }elseif($EndCreatedDate != ''){ 
									  if($EndCreatedDate != '' && $searchValueTODisplay != ''){
											date_default_timezone_set('asia/kolkata');
											$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
											$bulk = new MongoDB\Driver\BulkWrite;
											$filter = [ "archival" => 0, "user" => "$loggedInuserName",
												'$and' => [
															['created_date' => ['$lt' => "$EndCreatedDate"]]										
														],
														'$or' => [
																["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
																["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
															]
											];							
											$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
											$qry = new MongoDB\Driver\Query($filter,$options);
											$date = date("Y-m-d H:i:s");
											$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
									  }else{
										    date_default_timezone_set('asia/kolkata');
											$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
											$bulk = new MongoDB\Driver\BulkWrite;
											$filter = [ "archival" => 0, "user" => "$loggedInuserName",
												'$and' => [
															['created_date' => ['$lt' => "$EndCreatedDate"]]										
														]
											];							
											$options = [ 'sort' => ['created_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
											$qry = new MongoDB\Driver\Query($filter,$options);
											$date = date("Y-m-d H:i:s");
											$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
									  }
										   
									  }elseif($firstCreatedDateToDisplay != ''){ 
										 if($firstCreatedDateToDisplay != '' && $searchValueTODisplay != ''){
												 date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "archival" => 0, "user" => "$loggedInuserName",
													'$and' => [
																['created_date' => ['$gt' => "$firstCreatedDateToDisplay"]]										
															],
														'$or' => [
																["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
																["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
															]
												];							
												$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
												$qry = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
										 }else{
											date_default_timezone_set('asia/kolkata');
											$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
											$bulk = new MongoDB\Driver\BulkWrite;
											$filter = [ "archival" => 0, "user" => "$loggedInuserName",
												'$and' => [
															['created_date' => ['$gt' => "$firstCreatedDateToDisplay"]]										
														]
											];							
											$options = [ 'sort' => ['created_date' => 1],'limit' => $limitOfSelectedRowsFromDB ];
											$qry = new MongoDB\Driver\Query($filter,$options);
											$date = date("Y-m-d H:i:s");
											$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);	
										 }
										
									  }
													 
								}else{
									//echo "page 0 and signle data";
									if($searchValueTODisplay != ''){
										date_default_timezone_set('asia/kolkata');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
										$bulk = new MongoDB\Driver\BulkWrite;
										$filter = [ "archival" => 0, "user" => "$loggedInuserName",
										'$or' => [
																["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
																["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
																["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
															]
												];							
										$options = [ 'sort' => ['modified_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
										$qry = new MongoDB\Driver\Query($filter,$options);
										$date = date("Y-m-d H:i:s");
										$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry);
									}else{
										date_default_timezone_set('asia/kolkata');
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
										$bulk = new MongoDB\Driver\BulkWrite;
										$filter = [ "archival" => 0, "user" => "$loggedInuserName"];							
										$options = [ 'sort' => ['modified_date' => -1],'limit' => $limitOfSelectedRowsFromDB ];
										$qry = new MongoDB\Driver\Query($filter,$options);
										$date = date("Y-m-d H:i:s");
										$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
									}
										
								}
						}
						$m = 1;
						
						$firstCreatedDate = '';
						$lastCreatedDate = '';
					 foreach ($rows as $row) {					 
					  ?>
						<!--  <td><input type = "radio" onchange = "viewCadidateDetails('<?php //echo nl2br($row->id); ?>');" id="html1" name="fav_language" value="HTML1" ></td> -->
                          <td style = "display:none"><?php echo $row->created_date;  ?><?php if($m==1){ $firstCreatedDate = $row->created_date; } if($m==$limitOfSelectedRowsFromDB){ $lastCreatedDate = $row->created_date; } ?></td>
						  <td style="text-align: center;"> <a onclick = window.open("https://ucp.quesscorp.com/quessAdmin/pages/Agent_mongodb/userProfile.php?id=<?php echo nl2br($row->id); ?>&user=<?php echo $loggedInuserName; ?>&phno=<?php echo $row->phone_number; ?>") target = "_black" data-title="<?php echo $row->firstname." ".$row->lastname;?>" style="cursor: pointer;"> <?php echo $row->firstname; ?></a></td>
						   <td style="text-align: center;"> <?php echo $row->current_employer; ?></td>
						   <td style="text-align: center;"> <?php echo $row->skill_set; ?></td>
						   <td style="text-align: center;"><?php echo $row->additional_skill_set; ?></td>
						   <td style="text-align: center;"> <?php echo $row->total_experience; ?></td>
						   <td style="text-align: center;"><?php echo $row->current_annual_ctc; ?></td>
						   <td style="text-align: center;"><?php echo $row->expected_annual_ctc; ?></td>
						   <td style="text-align: center;"> <?php echo $row->notice_period; ?> </td>
						   <td style="text-align: center;"><?php echo $row->employement_type; ?></td>
						   <td style="text-align: center;"><?php echo $row->location; ?></td>	
						   <td style="text-align: center;"><?php echo $row->user; ?></td>						  
						  <td style="text-align: center;"><?php if($row->last_comments != ''){echo $row->last_comments." (".$row->lastCommentAddedUser.")";} ?></td>
						  <td style="text-align: center;"><?php echo $row->phone_number; ?></td> 
						  
						  <td style="text-align: center;"><button type="button" class="btn btn-sm calldisbale" style="width: 23px;height:23px;border: none;cursor: pointer;padding:0px;color: white;font-family: roboto, sans-serif;border-radius: 55px;background-color: #52c343;" title="Call" onclick = "enableDisableCallButton('<?php echo $row->id; ?>','<?php echo $loggedInuserName; ?>','<?php echo $row->phone_number;?>');"> <!--window.open("http://15.207.227.199/quessAdmin/pages/Agent_mongodb/userProfile1.php?id=<?php //echo nl2br($row->id); ?>&user=<?php //echo $loggedInuserName; ?>")-->
                           <i class="fas fa-phone-alt" aria-hidden="true" style="font-size: 12px;"></i></button> <!--<span onclick = "enableHangUpButton();"></span>--> <!--onclick="popInCall('<?php //echo $row->phone_number; ?>');"--> 
									  <?php	
									$createdDateinfo =$row->created_date;;
							if(time() - strtotime($createdDateinfo) < 3601){

								$ownerOfTheLead =$row->ownerOfTheLead;
								if( $loggedInuserName == $ownerOfTheLead ){

								?>
							<span class="btn btn-sm" title="Archival" onclick = "document.getElementById('myModal_delete').style.display='block'; archivalVerifyData('<?php echo $row->id; ?>','<?php echo $row->phone_number; ?>','<?php echo $row->firstname; ?>')">
							  <i class="fa fa-archive" aria-hidden="true" style="color: darkgrey;"></i></span>
						  
								<?php  } }?>
						  
						  <div class="row">
						     <div class="col-sm-0">
						    
							  </div>
						  
							  </div>	
						  </td>		
                  </tr>
				  <?php $m++;}?>                  
              </tbody>
          </table>	
		  </div>
		  </div>
		  </div>
		  <div id="paginationID">
		  <?php 
				if($CrossCheckingData == "ALL"){
					
		  	        /*  $no_of_records_per_page = $limitOfSelectedRowsFromDB;
							$offset = ($pageno-1) * $no_of_records_per_page;

							$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
							$filter = ['archival' => 0];  		
							$options = [ 'sort' => ['created_date' => -1]]; 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$collection = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
						   $exD1_User =  $collection->toArray();
							$D1_UserArray = json_decode(json_encode($exD1_User), true);	
							$EndCreatedDate = $D1_UserArray[0]['created_date'];
							$total_rows =count($D1_UserArray);		
							$total_pages = ceil($total_rows / $no_of_records_per_page);*/
							$no_of_records_per_page = $limitOfSelectedRowsFromDB;
								$offset = ($pageno-1) * $no_of_records_per_page;
									$totalNumberOfRecords =array();
							if($searchValueTODisplay != ''){
								
								/*$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = [ 'archival' => 0,
									'$or' => [
											["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
											["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
										]
									];  		
								$options = ['sort' => ['modified_date' => -1]]; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
								$totalNumberOfRecords =array();
								foreach($collection as $row){
									array_push($totalNumberOfRecords,$row->id);
								} */
								
								$filter = [ "archival" => 0,    //, "user" => "$loggedInuserName"
									'$or' => [
													["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["lastname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
													["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]
																								
												]
									];	                                    									
									$options = [ 'sort' => ['modified_date' => -1]];
									$qry = new MongoDB\Driver\Query($filter,$options);								
									$rowsnoOfRecords = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
									foreach($rowsnoOfRecords as $rowsnoOfRecordsForpagination){
										array_push($totalNumberOfRecords,$rowsnoOfRecordsForpagination->id);
									}
								
							}else{
								$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = ['archival' => 0];  		
								$options = []; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
							
									foreach($collection as $row){
											array_push($totalNumberOfRecords,$row->id);
										}
							}
															
							
							
							//$exD1_User =  $collection->toArray();
						//	$D1_UserArray = json_decode(json_encode($exD1_User), true);	
						//	$EndCreatedDate = $D1_UserArray[0]['created_date'];
							$total_rows =count($totalNumberOfRecords);	
							
							$total_pages = ceil($total_rows / $no_of_records_per_page);
							
							if($searchValueTODisplay != ''){
								//last created date
								$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = ['archival' => 0,
									'$or' => [
											["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
											["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
										]
								];						 		
								$options = [ 'sort' => ['created_date' => -1],'limit'=>1]; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection_lastCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
							    $lastCreatedDateOfUser =  $collection_lastCreatedDate->toArray();
								$UserArrayLastCreatedDate = json_decode(json_encode($lastCreatedDateOfUser), true);	
								$EndCreatedDate = $UserArrayLastCreatedDate[0]['created_date'];
							}else{
								//last created date
								$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = ['archival' => 0];  		
								$options = [ 'sort' => ['created_date' => -1],'limit'=>1]; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection_lastCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
							    $lastCreatedDateOfUser =  $collection_lastCreatedDate->toArray();
								$UserArrayLastCreatedDate = json_decode(json_encode($lastCreatedDateOfUser), true);	
								$EndCreatedDate = $UserArrayLastCreatedDate[0]['created_date'];
							}
							
							
							
							if($searchValueTODisplay != ''){
									//first created date
								$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = ['archival' => 0,
								'$or' => [
											["firstname" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["phone_number" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["user" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["additional_skill_set" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["total_experience" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_annual_ctc" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["current_employer" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["expected_annual_ctc" => array('$regex' => "$searchValueTODisplay")],
											["notice_period" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["location" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')],
											["employement_type" => array('$regex' => "$searchValueTODisplay",'$options'=>'i')]											
										]
								];  		
								$options = [ 'sort' => ['created_date' => 1],'limit' => 1]; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection_firstCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
							   $firstcreatedDate_User =  $collection_firstCreatedDate->toArray();
								$firstCreatedDateFromDB = json_decode(json_encode($firstcreatedDate_User), true);	
								$firstCreatedDateToDisplay = $firstCreatedDateFromDB[0]['created_date'];
							}else{
								//first created date
								$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
								$filter = ['archival' => 0];  		
								$options = [ 'sort' => ['created_date' => 1],'limit' => 1]; 
								$qry = new MongoDB\Driver\Query($filter,$options);
								$collection_firstCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
							   $firstcreatedDate_User =  $collection_firstCreatedDate->toArray();
								$firstCreatedDateFromDB = json_decode(json_encode($firstcreatedDate_User), true);	
								$firstCreatedDateToDisplay = $firstCreatedDateFromDB[0]['created_date'];
							}
							
				}elseif($CrossCheckingData == 'SingleUserValue'){
							$no_of_records_per_page = $limitOfSelectedRowsFromDB;
							$offset = ($pageno-1) * $no_of_records_per_page;

							$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
							$filter = ['archival' => 0, "user" => "$loggedInuserName"];  		
							$options = [ 'sort' => ['created_date' => -1]]; 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$collection = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
						   $exD1_User =  $collection->toArray();
							$D1_UserArray = json_decode(json_encode($exD1_User), true);	
							$EndCreatedDate = $D1_UserArray[0]['created_date'];
							$total_rows =count($D1_UserArray);		
							$total_pages = ceil($total_rows / $no_of_records_per_page);
							
							//last created date
							$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
							$filter = ['archival' => 0, "user" => "$loggedInuserName"];  		
							$options = [ 'sort' => ['created_date' => -1],'limit'=>1]; 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$collection_lastCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
						   $lastCreatedDateOfUser =  $collection_lastCreatedDate->toArray();
							$UserArrayLastCreatedDate = json_decode(json_encode($lastCreatedDateOfUser), true);	
							$EndCreatedDate = $UserArrayLastCreatedDate[0]['created_date'];
							
							
							//first created date
							$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
							$filter = ['archival' => 0, "user" => "$loggedInuserName"];  		
							$options = [ 'sort' => ['created_date' => 1],'limit' => 1]; 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$collection_firstCreatedDate = $mng->executeQuery("candidateinfo.candidate_Information", $qry);
						   $firstcreatedDate_User =  $collection_firstCreatedDate->toArray();
							$firstCreatedDateFromDB = json_decode(json_encode($firstcreatedDate_User), true);	
							$firstCreatedDateToDisplay = $firstCreatedDateFromDB[0]['created_date'];
				}
		  
		  
		  ?>
    <ul class="pagination">
        <li id = "FirstPagePagination"><a href="?pageno=1&EndCreatedDate=<?php echo  $EndCreatedDate."&searchValue=".$searchValueTODisplay; ?>">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>" id = "PrevPagePagination">  
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1)."&firstCreatedDate=".$firstCreatedDate."&searchValue=".$searchValueTODisplay; } ?>">Prev</a> 
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>" id = "NextPagePagination">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1)."&lastCreatedDate=".$lastCreatedDate."&searchValue=".$searchValueTODisplay; } ?>">Next</a>
        </li>
        <li id = "LastPagePagination"><a href="?pageno=<?php echo $total_pages."&firstCreatedDateToDisplay=".$firstCreatedDateToDisplay."&searchValue=".$searchValueTODisplay; ?>">Last</a></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b id = "TotalRecords">Total Records: <?php echo $total_rows; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page Number:<?php echo $pageno; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Number Of Pages: <?php echo $total_pages; ?> </b> </li>
    </ul>
	</div>  
		  </div>
        </div>
        <!-- /.card-body -->
      </div>
	  
	  	  		<!-- The Modal -->
  <div class="modal fade" id="myModal_notification">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">MESSAGE</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
			    <div id ='individualChat_script'></div> 

                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 
	 <!-- The Modal -->
  <div class="modal fade" id="myHangup" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">SELECT CALL DISPOSITION</h4>
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
			    <?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.status", $qry); // user_id user_name user_level				
							$x = 1;	
                            $dispoStatus = array();
							  foreach ($rows as $row) {
								 
								  $statuses = $row->status; 
								  $statusnames = $row->status_description;
								   array_push($dispoStatus,$statuses." - ".$statusnames);
							  }
							
							  for($x=0;$x<count($dispoStatus);$x++){
								  
								  
								  ?>
								  
								  <div class="form-row">
										<div class="form-group col-md-6">
										  <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab" onclick="callHangupFun('<?php echo $dispoStatus[$x];?>','<?php echo $loggedInuserName;?>');"><?php echo $dispoStatus[$x]; ?></button></b></font>
										</div>
									<?php $x++;if($dispoStatus[$x] != ''){ ?>
										<div class="form-group col-md-6">
										   <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab" onclick="callHangupFun('<?php echo $dispoStatus[$x];?>','<?php echo $loggedInuserName;?>');"><?php  echo $dispoStatus[$x]; ?></button></b></font>
										</div>	
									<?php } ?>
										
								</div>
								<?php }?>
                            
                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 
	 
	 
	 	<!-- The Modal -->
  <div class="modal fade" id="myHangupCallback" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">SELECT CALLBACK DATE</h4>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
			    
								
								<div class="form-row">
										<div class="form-group col-md-6">
										  <label for="birthdaytime">Date and Time:</label>
											  <input type="datetime-local" id="callback_datetime" name="callback_datetime" value="">
											  <input type="submit" onclick = "callBackHangupFun();">
										</div>
										<div class="form-group col-md-6">
										</div>	
								</div>
								
								

                            
                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	
	
	<!-- The Modal -->
  <div class="modal fade" id="callBackPop" style="opacity: 3;top : 104px !important" style = "width:500px;">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">CALLBACK DATA</h4>
          <button type="button" class="close1234578" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
						<div id = "callBackData"></div>

                            
                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 
	 
	<!-- The Modal -->
  <div class="modal fade" id="dropCallPop" style="opacity: 3;top : 104px !important" style = "width:500px;">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">DROP CALLS</h4>
          <button type="button" class="close123457" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
						<div id = "dropCallData"></div>

                            
                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	
	 <!-- The Modal -->
  <div class="modal fade" id="myPauseCode" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">SELECT PAUSE CODE</h4>
          <button type="button" class="close12345" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
              
                <div class="card-body">
				
			    <?php 
							/*date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.status", $qry); // user_id user_name user_level				
							$x = 1;	
                            $dispoStatus = array();
							  foreach ($rows as $row) {
								 
								  $statuses = $row->status; 
								  $statusnames = $row->status_description;
								   array_push($dispoStatus,$statuses." - ".$statusnames);
							  }
							
							  for($x=0;$x<count($dispoStatus);$x++){*/
								  ?>
								  
								    <!--<div class="form-row">
										<div class="form-group col-md-6">
										  <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab"><?php //echo $dispoStatus[$x]; ?></button></b></font>
										</div>
									<?php //$x++;if($dispoStatus[$x] != ''){ ?>
										<div class="form-group col-md-6">
										   <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab"><?php  //echo $dispoStatus[$x]; ?></button></b></font>
										</div>	
									<?php //} ?>
										
								    </div>-->
								<?php //}?>
								
								<div class="form-row">
										<div class="form-group col-md-6">
										  <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab" onclick="activeToPause('LNB')" >LNB - LUNCH BREAK</button></b></font>
										</div>
										<div class="form-group col-md-6">
										   <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab"  onclick="activeToPause('TEA')">TEA - TEA BREAK</button></b></font>
										</div>	
								</div>
								
								<div class="form-row">
										<div class="form-group col-md-6">
										  <font size="2" face="Arial, Helvetica, sans-serif" style="BACKGROUND-COLOR: #FFFFCC">
										  <b><button class="btnab"  onclick="activeToPause('BIO')">BIO - BIO BREAK</button></b></font>
										</div>
								</div>


                            
                </div>
                <!-- /.card-body -->

              
             
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	
	
	
	 <!-- The Modal -->
  <div class="modal fade" id="myModal_popupInCall" style="opacity: 3;top : 104px !important">
  
  </div>
	 <!-- The End Modal --> 
	 
	  		<!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ADD A NEW USER</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
               <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" onsubmit="return validateForm()">
                <div class="card-body">
				
				<div class="form-row">
				        <div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="Candidate_firstname" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;" name="Candidate_firstname" placeholder=" " />
							  <span class="input__label">Candidate First Name <span style = "color:red">*</span></span>
							</label>
						</div>
						<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="Candidate_lastname" name="Candidate_lastname" placeholder=" " />
							  <span class="input__label">Candidate Last Name</span>
							</label>
						</div>
                </div>
					
				<div class="form-row">
				       <div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="number" min = "0"   id="mobile_no" name="mobile_no" placeholder=" " /> <!-- onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;"-->
							  <span class="input__label">Mobile Number <span style = "color:red">*</span></span>
							</label>
						</div>
						 <div class="form-group col-md-6">							
							<select class="form-control select2 form-control-sm" style="width: 100%;"  id="lead_source" name="lead_source">
								<option value="">--Select lead Source-- <span style = "color:red">*</span></option>
								<?php 
									$leadSourceArray = array("Naukri","Monster","LinkedIn","Referral","Others","SubVendor","Shine","COE","VMO");	


  				
									shuffle($leadSourceArray);						
									for($x=0;$x<count($leadSourceArray);$x++){				
									?>
									<option value = "<?php echo $leadSourceArray[$x]; ?>" ><?php echo $leadSourceArray[$x]; ?></option>
									<?php } ?>
							</select>
							
							
						</div>
						
                </div>
					
					
                <div class="form-row">
				       
						
                </div>
					
                <div class="form-row">
				<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="skill_set" name="skill_set" placeholder=" " />
							  <span class="input__label">Skill Set</span>
							</label>
						</div>
				       <div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field"  type="text" id="current_emp" name="current_emp" placeholder=" " />   <!-- onkeypress="return /[a-z]/i.test(event.key)" -->
							  <span class="input__label">Current Employer</span>
							</label>
						</div>
						
                </div>
			
					
				<div class="form-row">
				<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="additional_skill_set" name="additional_skill_set" placeholder=" " />
							  <span class="input__label">Additional Skill Set</span>
							</label>
						</div>
				<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="total_experience" name="total_experience" placeholder=" " />
							  <span class="input__label">Total Experience</span>
							</label>
						</div>
				<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="current_annual_CTC" name="current_annual_CTC" placeholder=" " />
							  <span class="input__label">Current Annual CTC</span>
							</label>
						</div>
				       <div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="number" onkeypress="return /[0-9,./]/i.test(event.key)" id="expected_annual_CTC" name="expected_annual_CTC" placeholder=" " />
							  <span class="input__label">Expected Annual CTC</span>
							</label>
						</div>
						
                </div>	
				
				
				<div class="form-row">
				<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field noticePeriodClass" type="number"  pattern="/^+-?\d\.?\d*$/" onKeyPress="if(this.value.length==2) return false;" min = 0 id="notice_period" name="notice_period" onchange = "noticePeriodClass();" placeholder=" " />
							  <span class="input__label">Notice Period(No.Of Days)</span>
							</label>
						</div>
				       <div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" onkeypress="return /[a-z]/i.test(event.key)"  type="text" id="location" name="location" placeholder=" " />
							  <span class="input__label">Location</span>
							</label>
						</div>
						
                </div>	
	
					
				    <div class="form-row">
					<div class="form-group col-md-6">
						   <!--<label>Gender</label>-->
							  <select class="form-control select2 form-control-sm" style="width: 100%;" id="gender" name="gender">
								<option value="">--Select Gender--</option>
								<option>Male</option>
								<option>Female</option>
							  </select>
						</div>
						<div class="form-group col-md-6">
						   <!--<label>Relocation</label>-->
							  <select class="form-control select2 form-control-sm" style="width: 100%;" id="relocation" name="relocation">
								<option value="">--Select Relocation--</option>
								<option>YES</option>
								<option>NO</option>
							  </select>
						</div>
						
					</div>
					
					<div class="form-row">
					<div class="form-group col-md-6">
						    <!--<label>Employment Type</label>-->
							  <select class="form-control select2 form-control-sm" style="width: 100%;" id="employment_type" name="employment_type">
								<option value="">--Select Employment Type--</option>
								<option>Open to contract</option>
								<option>Permanent</option>
								<option>Both</option>
							  </select>
						</div>
						<div class="form-group col-md-6">
						   <!--<label>Consent to share CV</label>-->
						    <select class="form-control select2 form-control-sm" style="width: 100%;" id="consentshareCV" name="consentshareCV">
								<option value="">--Select Consent to share CV--</option>
								<option>YES</option>
								<option>NO</option>
						    </select>
						</div>
						
					</div>
					
					<div class="form-row">
					<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="Linked_in_url" name="Linked_in_url" placeholder=" " />
							  <span class="input__label">Linked in URL</span>
							</label>
						</div>
						<div class="form-group col-md-6">
						   <label class="input">
							  <input class="form-control form-control-sm input__field" type="text" id="ATS_url" name="ATS_url" placeholder=" " />
							  <span class="input__label">ATS URL</span>
							</label>
						</div>
					</div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name = "addCandidateData" value = "submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 
	 
	 		<!-- The Modal -->
  <div class="modal fade" id="myModal_Archival">
    <div class="modal-dialog" style="max-width: 1332px; margin: 1.75rem auto;">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ARCHIVE USER</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<div class="table-responsive">
          <table class="table m-0" name="itemArchival" id="itemArchival"  cellspacing="0">
              <thead style = "background-color:#59BBE3 !important;color:#ffff;">
                  <tr style = "font-size:15px;">
                     <th style = "width:25px;display:none">createdate</th>
                      <th style = "width:25px;">Candidate Name</th>
                      <th style = "width:25px">Location </th>
                      <th style = "width:25px">Current Employer</th>
                      <th style = "width:25px">Experience</th>
					  <th style = "width:25px">Skills</th>
					  <th style = "width:25px">Additional Skills</th>
					  <th style = "width:25px">Current Annual CTC</th>
					  <th style = "width:25px">Expected Annual CTC</th>
					  <th style = "width:25px">Notice Period</th>
					  <th style = "width:25px">Employment Type</th>
					  <th style = "width:25px">Recruiters</th>
					  <th style = "width:25px">Last Remarks</th>
					  <th style = "width:25px">Phone Number</th>
                  <!--    <th>Action</th> -->
                  </tr>
              </thead>  
			
              <tbody>
                  <tr>
					  <?php 
					  
											
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "archival" => 1,"user" => "$loggedInuserName" ];							
							$options = [ 'sort' => ['created_date' => -1] ];
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("candidateinfo.candidate_Information", $qry); 
						
						
						 foreach ($rows as $row) {
							//echo nl2br("$row->firstname : $row->phone_number\n");
					 
					  ?>
						<!--  <td><input type = "radio" onchange = "viewCadidateDetails('<?php //echo nl2br($row->id); ?>');" id="html1" name="fav_language" value="HTML1" ></td> -->
						<td style = "display:none"><?php echo $row->created_date; ?></td>	
						 <td> <?php echo $row->firstname; ?></td>
						  <td><?php echo $row->location; ?></td>
						  <td> <?php echo $row->current_employer; ?></td>
						  <td> <?php echo $row->total_experience; ?></td>
						  <td> <?php echo $row->skill_set; ?></td>
						  <td><?php echo $row->additional_skill_set; ?></td>
						  <td><?php echo $row->current_annual_ctc; ?></td>
						  <td><?php echo $row->expected_annual_ctc; ?></td>
						  <td> <?php echo $row->notice_period; ?> </td>	
						  <td><?php echo $row->employement_type; ?></td>
						  <td><?php echo $row->user; ?></td>
						  <?php
								$canId = $row->id;
								$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
								$bulk = new MongoDB\Driver\BulkWrite;
								$filter_remarks = [ "candidate_id" => "$canId" ];							
							$options_remarks = [ 'sort' => ['entry_date' => -1],'limit'=>1 ];
							$qry_remarks = new MongoDB\Driver\Query($filter_remarks,$options_remarks);
							$date = date("Y-m-d H:i:s");
							$rows_remarks = $mongo->executeQuery("candidateinfo.userprofile_comment", $qry_remarks);
							$arr = $rows_remarks->toArray();
							$array = json_decode(json_encode($arr), true);							
						  ?>
						  <td><?php echo $array[0]['comments']; ?></td>
						  <td><?php echo $row->phone_number; ?>
						  </td>    
						  	
                  </tr>
				  <?php }?>                  
              </tbody>
          </table>
		  </div>
               
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 
	 
	 <!--Call log start -->
	 	 		<!-- The Modal -->
  <div class="modal fade" id="myModal_RecruiterCallLog">
    <div class="modal-dialog" style="max-width: 1332px; margin: 1.75rem auto;">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">RECRUITER CALL LOG</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<div class="table-responsive">
			<table border="0" cellspacing="5" cellpadding="5">
			<tbody><tr>
				<td>Start Date:</td>
				<td><input type="date" id="min" name="min"></td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td><input type="date" id="max" name="max"></td>
			</tr>  
			<tr>
				<td><input  type= "submit" value = "submit" name = "submit_dateRange" onclick = "submit_dateRange()" ></td>
			   
			</tr>
		   </tbody></table>
		   <div id = "recruiterCallLogDevision">
          <table class="table m-0" name="recruiterCallLog" id="recruiterCallLog"  cellspacing="0">
              <thead style = "background-color:#59BBE3 !important;color:#ffff;">
                  <tr style = "font-size:15px;">
                    <!--  <th style = "width:25px">S.N </th> -->
                      <th style = "width:25px">DATE</th>
					  <th style = "width:25px">NAME</th>
					  <th style = "width:25px">NUMBER</th>
                      <th style = "width:25px">DURATION</th>
					  <th style = "width:25px">CALL TYPE</th>
					  <th style = "width:25px">DIAL</th>
					
                  </tr>
              </thead>  
			
              <tbody>
                  
					  <?php 
					  
										
							date_default_timezone_set('asia/kolkata');
							$dateval =  date("Y-m-d");
							$date =  date("Y-m-d");
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user" => "$loggedInuserName",
							'$and' => [
														['created_date' => ['$gt' => "$date 00:00:00"]],
														['created_date' => ['$lt' => "$date 23:59:59"]]
										]
							
							]; //,"created_date" => array('$regex' => "$dateval")		 ,"customerans" => "Answered"					
							$options = [ ]; // 'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$rows = $mongo->executeQuery("candidateinfo.outbound", $qry); 
						
						$m = 1;
						 foreach ($rows as $row) {
							 $callStatus = $row->dialstatusexten;
							 $customerans = $row->customerans;
							// echo $callStatus."<br>";
							 if($callStatus != 'BUSY'){
								 if($callStatus != 'CANCEL'){
						//	if($customerans != ''){
							//echo nl2br("$row->firstname : $row->phone_number\n");
							if($customerans == 'Answered'){
							$talkTime = $row->anscus;													
							$TotalTalkTime = gmdate("H:i:s", $talkTime);
							}else{
								$TotalTalkTime = "00:00:00";
							}
							/*$str = $row->voice;//"20220209-080814_9738031110_8688787956_bglwfh.gsm";
							$candidatePhoneNumber = explode("_",$str);							
							$PhoneLen = strlen($candidatePhoneNumber[2]);
							//$PhoneLen = strlen($row->customerno);
							
							if($PhoneLen >10){
								$phoneNumberFinal = substr($candidatePhoneNumber[2],2);
							}
							else {
								
								$phoneNumberFinal = $candidatePhoneNumber[2];
							}*/
							$PhoneLen = strlen($row->customerno);
							
							if($PhoneLen >10){
								if($PhoneLen == 11){
									$phoneNumberFinal = substr($row->customerno,1);
								}elseif($PhoneLen == 12){
									$phoneNumberFinal = substr($row->customerno,2);
								}
								
							}
							else {
								
								$phoneNumberFinal = $row->customerno;
							}	
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['phone_number' => $phoneNumberFinal ];							
							$options = [  ];
							$qry_name = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rows_name = $mongo->executeQuery("candidateinfo.candidate_Information", $qry_name); 
							$candidateInfoName = $rows_name->toArray();
							$candidateINFO = json_decode(json_encode($candidateInfoName), true);	
							$firstname =$candidateINFO[0]['firstname'];
							
					  ?>
					  <tr>
					<!--	  <td><?php// echo $m; ?></td> -->
						  <td><?php echo $row->created_date; ?></td>
						  <td><?php echo $firstname; ?></td>
						  <td><?php echo $phoneNumberFinal; ?></td>
						  <td> <?php  if($TotalTalkTime == ""){echo "00:00:00";}else{echo $TotalTalkTime;} ?></td>  
						  <td><?php echo "OUT"; ?></td>
						  <!--<td><?php //echo $phoneNumberFinal; ?></td>-->
						  <td> <!--<button type= "button" onclick ="clickToCall(<?php //echo $phoneNumberFinal; ?>)">Dial</button>-->

                            <button type="button" class="btn btn-sm" style="width: 23px;height:23px;border: none;cursor: pointer;padding:0px;color: white;font-family: roboto, sans-serif;border-radius: 55px;background-color: #52c343;" title="Call" onclick ="clickToCall('<?php echo $phoneNumberFinal; ?>');"> 
                           <i class="fas fa-phone-alt" aria-hidden="true" style="font-size: 12px;"></i></button></td>						  
						     
						  	
                  </tr>
						<?php } } $m++;}
				  ?> 
						  <?php 
					  
											
							date_default_timezone_set('asia/kolkata');
							$dateTODAY = date("Y-m-d");
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user" => "$loggedInuserName",
							'$and' => [
														['created_date' => ['$gt' => "$dateTODAY 00:00:00"]],
														['created_date' => ['$lt' => "$dateTODAY 23:59:59"]]
										]
							
							];  //,"customerans" => "Answered"							
							$options = [ 'sort' => ['created_date' => -1] ];
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("candidateinfo.inbound", $qry); 
						
						//$n = 1;
						 foreach ($rows as $row) {
							//echo nl2br("$row->firstname : $row->phone_number\n");
							$talkTime = $row->answerdtime;													
							$TotalTalkTime = gmdate("H:i:s", $talkTime);
							
							$PhoneLen = strlen($row->customerno);
							
							if($PhoneLen >10){
								$phoneNumberFinal = substr($row->customerno,2);
							}
							else {
								
								$phoneNumberFinal = $row->customerno;
							}
							
					       //$phoneNumberFinal = substr($row->customerno,2);
							
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['phone_number' => $phoneNumberFinal ];							
							$options = [  ];
							$qry_nameIN = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rows_nameIn = $mongo->executeQuery("candidateinfo.candidate_Information", $qry_nameIN); 
							$candidateInfoNameIn = $rows_nameIn->toArray();
							$candidateINFOIn = json_decode(json_encode($candidateInfoNameIn), true);	
							$firstname =$candidateINFOIn[0]['firstname'];
					  ?>
					  <tr>
						<!--  <td><?php // echo $m; ?></td> -->
						  <td> <?php echo $row->created_date; ?></td>
						  <td><?php echo $firstname;?> </td>
						  <td><?php echo $phoneNumberFinal; ?></td>
						  <td> <?php if($TotalTalkTime == ""){echo "00:00:00";}else{echo $TotalTalkTime;} ?></td>
						  <td><?php echo "IN"; ?></td>
						  <!--<td><?php //echo $phoneNumberFinal; ?></td>-->
						  <td> <!--<button type= "button" onclick ="clickToCall(<?php echo $phoneNumberFinal; ?>)">Dial</button>-->
						  
						  <button type="button" class="btn btn-sm" style="width: 23px;height:23px;border: none;cursor: pointer;padding:0px;color: white;font-family: roboto, sans-serif;border-radius: 55px;background-color: #52c343;" title="Call" onclick ="clickToCall('<?php echo $phoneNumberFinal; ?>');"> 
                           <i class="fas fa-phone-alt" aria-hidden="true" style="font-size: 12px;"></i></button>
						  </td>	
						     
						  	
                  </tr>
				  <?php $m++;}?> 
              </tbody>
          </table>
		  </div>
               
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
	 <!-- The End Modal --> 
	 <!--call log end  -->
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
      <!-- /.card -->

    </section>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
    <div class="row">
          <div class="col-md-2" id = "candidateUserProfile" style = "display:none;">
		     <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center" id="targetLayer">
                  <img class="profile-user-img img-fluid img-circle" id="profileImg" src="../../dist/img/userImg.png" alt="User profile picture">
				  
                </div>
				
                <h3 class="profile-username text-center" ><span id = "profileCandidateName">Candidate Name</span></h3>

             <!--     <p class="text-muted text-center">Software Engineer</p> -->

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
				    <form id="uploadForm" action="upload.php" method="post" >
                      <div id="uploadFormLayer" class="element">
					   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-camera btnaa" style="font-size:15px" id="img_id" title="Upload Profile"></i><input name="userImage" type="file" id = "userImage" class="inputFile" /> 
						<button class="btnaa1" type="submit"><i class="fa fa-upload" style="font-size:15px" title="submit"></i></button>
						<input type="hidden" id="leadId" value = "" name="leadId">				  
					  </div>
					</form>
				  </li>
                  <li class="list-group-item"> 
						&nbsp;&nbsp;&nbsp;&nbsp; <!--<a  href="#settings" data-toggle="tab" >--><span onclick = "clickToCall();"><i class="fa fa-phone btnaa " style="font-size:15px"></i></span><!--</a>-->
						<input type="hidden" id = "candidatePhoneNumber1" value = "" name = "candidatePhoneNumber1">				  
						&nbsp;&nbsp;&nbsp;&nbsp;<a href = "chat_api_wtup/whatsApp_chat_ajax1.php" target = "_black"><i class="fab fa-whatsapp btnaa" style="font-size:15px"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
						 <i class="fas fa-video btnaa" style="font-size:15px"></i>
                  </li>
                </ul>

               <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
              </div>
              <!-- /.card-body -->
            </div>
		  </div>
		  
		
          <div class="col-md-6">
                  <div class="tab-pane" id="settings" style = "display:none">
					<div align = "right">
				 <b>EDIT :</b>&nbsp;&nbsp;&nbsp;  <label class="switch">
					
					 <input type="checkbox" class = "editBtn" onchange = "validate();"id = "remember">
					  <span class="slider round"></span>
					</label>
					</div>
                     <!--     <form class="form-horizontal"> -->
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Candidate Name</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputName" readonly>
                        </div>
                      </div>
					  
					  <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Gender</label>
                        <div class="col-sm-8">
						     <select class="form-control select2 editField" style="width: 100%;" id="inputgender" name="inputgender" readonly>
								<option value="">--Select--</option>
								<option>Male</option>
								<option>Female</option>
							  </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputlocation" class="col-sm-4 col-form-label">Location</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputlocation" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputCurrentEmployer" class="col-sm-4 col-form-label">Current Employer</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputCurrentEmployer" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-4 col-form-label">Experience</label>
                        <div class="col-sm-8">
						  <input type="text" class="form-control editField" id="inputExperience" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-4 col-form-label">Skills</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputSkills" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputAdditionalSkills" class="col-sm-4 col-form-label">Additional Skill Set</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputAdditionalSkills" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputCurrentAnnualCTC" class="col-sm-4 col-form-label">Current Annual CTC</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputCurrentAnnualCTC" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputExpectedAnnualCTC" class="col-sm-4 col-form-label">Expected Annual CTC</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputExpectedAnnualCTC" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputPhoneNumber" class="col-sm-4 col-form-label">Phone Number</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputPhoneNumber" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputNoticePeriod" class="col-sm-4 col-form-label">Notice Period (No. Of Days)</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputNoticePeriod" readonly>
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="inputAge" class="col-sm-4 col-form-label">Days Since Profile Is Created</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control editField" id="inputAge" readonly>
                        </div>
                      </div>
					  
					  <div class="form-group row">
                        <label for="inputconsentshareCV" class="col-sm-4 col-form-label">Consent to share CV</label>
                        <div class="col-sm-8">
						     <select class="form-control select2 editField" style="width: 100%;" id="inputconsentshareCV" name="inputconsentshareCV" readonly>
								<option value="">--Select--</option>
								<option>YES</option>
								<option>NO</option>
							  </select>
                        </div>
                       </div>
					   
					  <div class="form-group row">
                        <label for="inputemployment_type" class="col-sm-4 col-form-label">Employment Type</label>
                        <div class="col-sm-8">
						     <select class="form-control select2 editField" style="width: 100%;" id="inputemployment_type" name="inputemployment_type" readonly>
								<option value="">--Select--</option>
								<option>Open to contract</option>
								<option>Permanent</option>
								<option>Both</option>
							  </select>
                        </div>
                      </div>
					  
					   <div class="form-group row">
                        <label for="inputRelocation" class="col-sm-4 col-form-label">Relocation</label>
                        <div class="col-sm-8">
						     <select class="form-control select2 editField" style="width: 100%;" id="inputRelocation" name="inputRelocation" readonly>
								<option value="">--Select--</option>
								<option>YES</option>
								<option>NO</option>
							  </select>
                        </div>
                      </div>
					  
                      <div class="form-group row" style="display:none;">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-success" onclick = "editCandidateInfo();">Submit</button>
                        </div>
                      </div>
              <!--      </form> -->
                  </div>
                  <!-- /.tab-pane -->
		</div> <!-- sandeep div-->	
		
		<div class="col-md-4">
               
				<div class="tab-pane" id="commentsdisplay" style = "display:none">
                     <div class="card h-100">
                    <div class="card-body">
                     <!--<form action="" method="POST">-->
							<?php //if(isset($_POST['btn_submit'])) { ?>
                            <div  id="profile" role="tabpanel" aria-labelledby="profile-tab">
							 <div id="coomentSection"></div>
							
                                <div class="row">
                                    <div class="col-md-12">
                                        <br><h4 align="center">Comment</h4>
										<hr>
										<p style="font-size:18px;"><textarea class="form-control" rows="2" id="txt_comments" name="txt_comments"></textarea></p>
                                        <input type="hidden" id="candidate_id" value = "" name="candidate_id">	
		                                <input type="hidden" id="candidate_phone_number" value = "" name="candidate_phone_number">	
										<button type="submit" class="btn btn-success" onclick = "addComments();">Submit</button>
                                    </div>
                                </div>
                            </div>
							<?php //} ?>
							<!--</form>-->
					  
					  
                    </div>
                  </div>
                  </div>
                  <!-- /.tab-pane -->
		</div> <!-- sandeep div-->	
		
</div>	<!-- sandeep1 div -->	

	<!-- The Modal Delete-->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ARCHIVE DATA</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "" method = "POST">
                <div class="card-body">
                 
				  <div class="form-row">
				        <div class="form-group col-md-6">
						   <label for="UserID"><b>Phone Number</b></label>
						   <input type="text" class="form-control" id="txt_archival_id" name="txt_archival_id" readonly>
						</div>
						<div class="form-group col-md-6">
						   <label for="UserID"><b>Candidate Name</b></label>
						   <input type="text" class="form-control" id="txt_archival_candidatename" name="txt_archival_candidatename" readonly>
						</div>
                  </div>
				
				  <div class="form-group">
					 <b>The selected candidate will be archived and cannot be retrieved.</b>
				  </div>
                </div>
				<input type = "hidden" value = "" id="archivalId" name="archivalId">
                <!-- /.card-body -->

                <div class="card-footer">
				  <input type = "hidden" value = "ADD" name = "submitArchival">
                  <input type="submit" class="btn btn-primary" value = "Confirm">
                </div>
              </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>
		


                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer" style="margin-left: 1px !important;">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 4.1-rc
    </div>
    <strong> Haloocom Copyright &copy;2021-2023</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<!--<script src="../../plugins/jquery/jquery.min.js"></script> -->
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

<script src="js/candidateInfo.js"></script>

</body>
</html>
<script type="text/javascript">

 
 
 
   /* $(document).ready( function () {
		  $.fn.dataTable.ext.search.push(
			function( settings, data, dataIndex ) {
			  // Get the age input
				var val = $('input[name="CurrentAnnualCTC"]').val();			
		
			  if (val) {
				
				// Split at - to get the range
				var minMax = val.split('-');
			  
				var min = parseInt( minMax[0], 10 );
				var max = parseInt( minMax[1], 10 );
			  
				var age = parseFloat( data[7] ) || 0; // use data for the age column
		 
				if ( ( isNaN( min ) && isNaN( max ) ) ||
					 ( isNaN( min ) && age <= max ) ||
					 ( min <= age   && isNaN( max ) ) ||
					 ( min <= age   && age <= max ) )
				{
					return true;
				}
				return false;
			  }		  
			  // No value return true
			  return true;
			}
		);
		
		
		
		
		 $('#item tfoot th').each( function () {
			 var title = $(this).text();
				  if(title != ''){
					 $(this).html( '<input type="text" style = "width:80px;" placeholder="'+title+'" name="'+title+'" />' );
					 }
		 } );

		  var table = $('#item').DataTable({
			
		  });
		  
		 table.columns().every( function () {
		  
			 var that = this;
			 
			 $( 'input', this.footer() ).on( 'keyup change', function () {
			   
			   //. if the age column just use drawa to run the plugin
			   if (that.index() === 7 ) {  //|| that.index() === 8
				 table.draw()
			   } else {
				 
				 if ( that.search() !== this.value ) {
					 // alert(this.value);         
					 that
						 .search( this.value )
						 .draw();
				 }
			   }
			 } );
		 } );
} );
 
 */

	</script>
	<script>
	function filterColumn ( i ) {
			if(i == 3 ){  
				var enterData = $('#Skills').val();
			}else if(i == 4){
				var enterData = $('#AdditionalSkills').val();
			}else if(i == 10){
				var enterData = $('#Location').val();
			}else if(i == 2){
				var enterData = $('#CurrentEmployer').val();
			}
			$('#item').DataTable().column( i ).search( enterData,true,true).draw();
		}
	$(document).ready( function () {	
  $.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
      // Get the age input
      var val = $('input[name="CurrentAnnualCTC"]').val();      
      // Get position
      var positionChecked = $('input[name="ExpectedAnnualCTC"]').val();
	  var Experience = $('input[name="Experience"]').val();
	  var NoticePeriod = $('input[name="NoticePeriod"]').val();
     
      // Keep track of each column
      showAge = true;
      showPosition = true;
	  showExperience = true;
	  showNoticePeriod = true;
      
      // Evaluate Age range
      if (val) {        
        // Split at - to get the range
        var minMax = val.split('-');
      
        var min = parseInt( minMax[0], 10 );
        var max = parseInt( minMax[1], 10 );
      
        var age = parseFloat( data[6] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            showAge = true;
        } else {
          showAge = false;
        }
      }
	
	  
	  if (positionChecked) {
        
        // Split at - to get the range
        var minMax = positionChecked.split('-');
      
        var min = parseInt( minMax[0], 10 );
        var max = parseInt( minMax[1], 10 );
      
        var age = parseFloat( data[7] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) || ( isNaN( min ) && age <= max ) ||  ( min <= age   && isNaN( max ) ) || ( min <= age   && age <= max ) )
        {
            showPosition = true;
        } else {
          showPosition = false;
        }
      }
	  
	   if (Experience) {
        
        // Split at - to get the range
        var minMax = Experience.split('-');
      
        var min = parseInt( minMax[0], 10 );
        var max = parseInt( minMax[1], 10 );
      
        var age = parseFloat( data[5] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            showExperience = true;
        } else {
          showExperience = false;
        }
      }
	 
	    if (NoticePeriod) {        
        // Split at - to get the range
        var minMax = NoticePeriod.split('-');
      
        var min = parseInt( minMax[0], 10 );
        var max = parseInt( minMax[1], 10 );
      
        var age = parseFloat( data[8] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            showNoticePeriod = true;
        } else {
          showNoticePeriod = false;
        }
      }
 
      
      
      // Return combined boolean
      return showAge && showPosition && showExperience && showNoticePeriod;
    }
);
 $('#item tfoot th').each( function () {
     var title = $(this).text();
	//if( title =='Skills'){
		// $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;" class = "column_filter" placeholder="'+title+'" />' );
	// }else{
		if(title == 'Name'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: 0px;"  placeholder="'+title+'" />' );
	 }if(title == 'CurrentEmployer'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -58px;"  placeholder="'+title+'" />' );
	 }if(title == 'Skills'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -72px;"  placeholder="'+title+'" />' );
	 }if(title == 'AdditionalSkills'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -94px;"  placeholder="'+title+'" />' );
	 }if(title == 'Experience'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -73px;"  placeholder="'+title+'" />' );
	 }if(title == 'CurrentAnnualCTC'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -70px;"  placeholder="'+title+'" />' );
	 }if(title == 'ExpectedAnnualCTC'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -69px;"  placeholder="'+title+'" />' );
	 }if(title == 'NoticePeriod'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -96px;"  placeholder="'+title+'" />' );
	 }if(title == 'EmploymentType'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -110px;"  placeholder="'+title+'" />' );
	 }if(title == 'Location'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -113px;"  placeholder="'+title+'" />' );
	 }if(title == 'Recruiters'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -104px;"  placeholder="'+title+'" />' );
	 }if(title == 'LastRemarks'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -88px;"  placeholder="'+title+'" />' );
	 }if(title == 'PhoneNumber'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -117px;"  placeholder="'+title+'" />' );
	 }


		
		/*if(title != 'Name'){
         $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: -30px;"  placeholder="'+title+'" />' );
	 }else{
		 $(this).html( '<input type="text"  name="' + title + '" id = "' + title + '" style = "width:70px;margin-left: 0px;"  placeholder="'+title+'" />' );
	 }*/
 } );

  var table = $('#item').DataTable({
		"order": [[ 0, "desc" ]],
		 "bPaginate": false,
		  "bFilter": false
	  //  scrollY:        "50vh"
	  // scrollY:        true,
	//	fixedHeader: true,
		//scrollY:        "500px"
       // scrollY:        true
        //scrollCollapse: true
        //paging:         false,
	 //fixedColumns:   {
       //     leftColumns: 1,
         //   rightColumns: 1
       //}  */
  });

  
 table.columns().every( function () {
  
     var that = this;
     
     $( 'input', this.footer() ).on( 'keyup change', function () {
       
       //. if the age column just use drawa to run the plugin
       if (that.index() === 6 || that.index() === 7 || that.index() === 5 || that.index() === 8) {
         table.draw()
       } else if(that.index() === 3){
		   filterColumn(3);       //3 skil ,4 add ,10 location ,2 eurrent emp
	   } else if(that.index() === 4){
		   filterColumn(4); 
	   } else if(that.index() === 10){
		   filterColumn(10); 
	   } else if(that.index() === 2){
		   filterColumn(2); 
	   } else{
           //6 currentCTC ,7 exepet ctc,5 experieence,8 notice peried
         if ( that.search() !== this.value ) {
             // alert(this.value);         
             that
                 .search( this.value )
                 .draw();
         }
       }
     } );
	
	
 } );
 
 	
  $('.filter').on('change', function() {
    table.draw();
  });
  
  $('#itemArchival').DataTable({
	  "order": [[ 0, "desc" ]]
  });
  $('#recruiterCallLog').DataTable({
	   "order": [[ 0, "desc" ]]
  });
  
  




} );  


  function highlight() {
	  alert('inside');
	var highlightVal = document.getElementById('getAllData').value; 
	
	 if(highlightVal == "ALL"){
		
      document.getElementById('testingcolor').style.background = "black";
      document.getElementById('testingcolor').style.color = "white";
	 }
	 else{
		 alert('inside else');
	 }
   }
  
//callbackNotification
function callbackNotification(){
	//alert("test");
     var user = '<?php echo $loggedInuserName; ?>';
	// alert(user);
	 
	$.ajax({
    url: "ajax/callbackNotification.php", 
    data:{'user':user},
    success: function(result){
		
//alert(result);
	
	 if(result>0){
      $("#callbackNotification").html(result);
	  $("#callbackNotification").css("background-color","red");
	 }
	 else{
		 $("#callbackNotification").html('');
		 $("#callbackNotification").css("background-color","#fff");
	 }
	 
    }});
}
 
callbackNotification();
setInterval(function(){callbackNotification()},10000);



//Update DropCall View //

 function updateDropCall_View(){
	//alert("inside");
	
	 var user = '<?php echo $loggedInuserName; ?>';
	
	$.ajax({
	type:'POST',	
    url: "ajax/updateDropCall_View.php", 
    data:{'user':user},
    /*success: function(result){
		//alert(result);
	}*/	
    });
	dropCallPop();
	 
}

function dropcallNotification(){
	//alert("test");
     var user = '<?php echo $loggedInuserName; ?>';
	// alert(user);
	 
	$.ajax({
    url: "ajax/dropcallNotification.php", 
    data:{'user':user},
    success: function(result){
		
//alert(result);
	
	 if(result>0){
      $("#dropcallNotification").html(result);
	  $("#dropcallNotification").css("background-color","red");
	 }
	 else{
		 $("#dropcallNotification").html('');
		 $("#dropcallNotification").css("background-color","#fff");
	 }
	 
    }});
}
 
dropcallNotification();
setInterval(function(){dropcallNotification()},10000);
  
  function archivalVerifyData(val_grpd,archivalIDVal,archivalcandidatename)
	{
		//alert(val_grpd);
		document.getElementById('archivalId').value = val_grpd;
		document.getElementById('txt_archival_id').value = archivalIDVal;
		document.getElementById('txt_archival_candidatename').value = archivalcandidatename;
		
		// Get the modal
        var modal2 = document.getElementById('myModal_delete');

		// Get the button that opens the modal
		var btn2 = document.getElementById("myBtn2");

		// Get the <span> element that closes the modal
		var span2 = document.getElementsByClassName("close23")[0];

		// When the user clicks the edit button, open the modal 
		modal2.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span2.onclick = function() {
		modal2.style.display = "none";
		}
		// When the user clicks anywhere outside of the modal, close it
					window.onclick = function(event) {
					if (event.target == modal2) {
					modal2.style.display = "none";
												}
													}	
	
	
	}
 function updateIndividual_messageView(){
	//alert("inside");
	 var user = '<?php echo $loggedInuserName; ?>';
	
	$.ajax({
	type:'POST',	
    url: "ajax/updateIndividual_messageView.php", 
    data:{'user':user},	
    });
}



function activeMode()
{
	document.getElementById("btn_pause").disabled = false;
	document.getElementById('btn_pause').className = "btnaaInput1";
	
	document.getElementById("btn_active").disabled = true;
	document.getElementById('btn_active').className = "btnaaInput2";
	
	var user = '<?php echo $loggedInuserName; ?>';
	
	$.ajax({
	type:'POST',	
    url: "ajax/updateUser_liveStstus.php", 
    data:{'user':user},	
	success: function(result){	
	//alert(result);
    } });
	
	
				
}

function pauseMode()
{
	document.getElementById("btn_active").disabled = false;
	document.getElementById('btn_active').className = "btnaaInput1";
	
	document.getElementById("btn_pause").disabled = true;
	document.getElementById('btn_pause').className = "btnaaInput2";
	
	
	var user = '<?php echo $loggedInuserName; ?>';
	
	$.ajax({
	type:'POST',	
    url: "ajax/updateUser_liveStstus.php", 
    data:{'user':user},	
	success: function(result){	
	//alert(result);
    }
    });
	
	open_pauseCodeData();			
}

function open_pauseCodeData()
	{
			//alert("open_pauseCodeData");
			var modal_new = document.getElementById('myPauseCode');
			var btn1 = document.getElementById("btn");
			var span1 = document.getElementsByClassName("close12345")[0];
			modal_new.style.display = "block";
			span1.onclick = function() {
			modal_new.style.display = "none";
									   }
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			if (event.target == modal_new) {
				modal_new.style.display = "none";
										}
											 }
	    

	}
	
	
	function dropCallPop()
	{
		dropCallData();
		
			//alert("open_pauseCodeData");
			var modal_new = document.getElementById('dropCallPop');
			var btn1 = document.getElementById("btn");
			var span1 = document.getElementsByClassName("close123457")[0];
			modal_new.style.display = "block";
			span1.onclick = function() {
			modal_new.style.display = "none";
									   }
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			if (event.target == modal_new) {
				modal_new.style.display = "none";
										}
											 }
	    

	}
	function dropCallData(){
		var user = '<?php echo $loggedInuserName; ?>';
		
		$.ajax({
			type:'POST',	
			url: "ajax/dropCallData.php", 
			data:{'user':user},	
			success: function(result){	
			//alert(result);
			
			 document.getElementById('dropCallData').innerHTML = result;
			  $('#dropcallData').DataTable();
			}
			});
	}  
	function callBackPop()
	{
		callBackData();
		
			//alert("open_pauseCodeData");
			var modal_new = document.getElementById('callBackPop');
			var btn1 = document.getElementById("btn");
			var span1 = document.getElementsByClassName("close1234578")[0];
			modal_new.style.display = "block";
			span1.onclick = function() {
			modal_new.style.display = "none";
									   }
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			if (event.target == modal_new) {
				modal_new.style.display = "none";
										}
											 }
	    

	}
	function callBackData(){
		var user = '<?php echo $loggedInuserName; ?>';
		
		$.ajax({
			type:'POST',	
			url: "ajax/callBackData.php", 
			data:{'user':user},	
			success: function(result){	
			//alert(result);
			
			 document.getElementById('callBackData').innerHTML = result;
			  $('#callBackDatatable').DataTable();
			  // $('#callBackData').DataTable();
			}
			});
	} 
	
	function activeToPause(code){
		pauseMode();
		//alert(code);
		var activeToPause = "activeToPauseval";
		var PauseCodeval  = code;
		var user = '<?php echo $loggedInuserName; ?>';
		
		$.ajax({
			type:'POST',	
			url: "ajax/updateUser_livePauseStstus.php", 
			data:{'user':user,'activeToPause':activeToPause,'PauseCodeval':PauseCodeval},	
			success: function(result){	
			//alert(result);
			 document.getElementById('myPauseCode').style.display = "none";
			}
			});
	}
	
	
var hours =0;
var mins =0;
var seconds =0;

function startTimer(){
	
  timex = setTimeout(function(){
	  
      seconds++;
    
	
	if(seconds >59){
		
		seconds=0;mins++;
		
       if(mins>59) {
		   
         mins=0;hours++;
		 
         if(hours <10) {$("#hours").text('0'+hours+':')} else $("#hours").text(hours+':');
		 
        }
                       
      if(mins<10){		  
      $("#mins").text('0'+mins+':');
	  }       
       else $("#mins").text(mins+':');
                   
	}    
    if(seconds <10) {
        $("#seconds").text('0'+seconds);} 
	  else {
       $("#seconds").text(seconds);
      }
     
    
      startTimer();
  },1000);
}

//for timer //
function updateTimer(){
	
     var user = '<?php echo $loggedInuserName; ?>';
	$.ajax({
    url: "ajax/updateDateTimer.php", 
    data:{'user':user},
    success: function(result1){
		//alert(result1);
var result = result1.split('**');
	
	 if(result[0] == "starttimer"){
      //  alert(result);
        //reset timer //	
		$("#incomingAndOutGoingUpdate").html(result[1]);
//document.getElementById("incomingAndOutGoingUpdate").innerHTML = result[1];		
		  hours =0;      mins =0;      seconds =0;
		  $('#hours','#mins').html('00:');
		  $('#seconds').html('00');		
	 
	   startTimer();
	  }
	 else if(result[0] == "stoptimer"){
		 
		 $phNo = result[1];
		 
		 //alert($phNo);
		 
		 var minsVal = document.getElementById('mins').innerText;
		 
		 var MinVal = minsVal.replace(":", "");
     
          //alert(MinVal);
		  
		  if(MinVal >= 02){
		  
		   popInCall($phNo);
		  
		  }
		 // alert(result);
		clearTimeout(timex);
		document.getElementById("incomingAndOutGoingUpdate").innerHTML = "";	
		 
	 }
	 
	
	 
	 
    }});
}
 
updateTimer();
setInterval(function(){updateTimer()},3000); 


function popInCall(phone_number){
	
		//alert("insid popup");
			var phnNumber = phone_number;
			//alert(phnNumber);
				phnNumber = phnNumber.trim();
			var phnNumberFor3MinPop = phnNumber.substring(phnNumber.length - 10, phnNumber.length);
				
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				var result = this.responseText;	
				//alert(result);
				document.getElementById('myModal_popupInCall').innerHTML = result;							
				if(result != 'Failed'){	
				//alert("result Not== Failed");
					open_fieldData();		
				}
			}
			xhttp.open("GET", "ajax/popInCall.php?phnNumber="+phnNumberFor3MinPop, true);
			xhttp.send();	
			
	}
	
	function open_fieldData()
	{
		//alert("openfield");
		var modal_new = document.getElementById('myModal_popupInCall');
		var btn1 = document.getElementById("btn");
		var span1 = document.getElementsByClassName("close1234")[0];
		modal_new.style.display = "block";
		span1.onclick = function() {
		modal_new.style.display = "none";
								   }
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		if (event.target == modal_new) {
			modal_new.style.display = "none";
									}
										 }
										 //alert("end OPen field fun");

	}
	
function update3MinData(lead_id,phnNumberVal){
		//alert("Inside");
	var CandidatePhnNumber = phnNumberVal;
	  CandidatePhnNumber = CandidatePhnNumber.trim();
	  var phnNumber = CandidatePhnNumber.substring(CandidatePhnNumber.length - 10, CandidatePhnNumber.length);
	
		var txtfield_1 = document.getElementById('txtfield_1').value;
		//var txtfield_2 = document.getElementById('txtfield_2').value;
		//var txtfield_3 = document.getElementById('txtfield_3').value;
		//var txtfield_4 = document.getElementById('txtfield_4').value;
		var txtfield_5 = document.getElementById('txtfield_5').value;
		
		
			 $.ajax({  
            url:"ajax/timeBoundInsertData.php",  
            method:"POST",  
            data:{txtfield_1:txtfield_1,txtfield_5:txtfield_5,phnNumber:phnNumber,lead_id:lead_id},  //txtfield_2:txtfield_2,txtfield_3:txtfield_3,txtfield_4:txtfield_4,
            success: function(data){
				//alert(data);
			
				alert("You Have Successfully Added");
				
				document.getElementById('myModal_popupInCall').style.display='none';
				document.getElementById('txtfield_1').value = '';
				//document.getElementById('txtfield_2').value = '';
				//document.getElementById('txtfield_3').value = '';
				//document.getElementById('txtfield_4').value = '';
				document.getElementById('txtfield_5').value = '';
				
			}
			 });
}

 		
	
	function open_HangupCodeData()
	{
		clearTimeout(timex);
					var modal_new = document.getElementById('myHangup');
					var btn1 = document.getElementById("btn");
					var span1 = document.getElementsByClassName("close12345")[0];
					modal_new.style.display = "block";
					span1.onclick = function() {
					modal_new.style.display = "none";
											   }
					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function(event) {
					if (event.target == modal_new) {
						modal_new.style.display = "none";
						
												}
													 }
		
	}
	
	function callHangupFun(Disposition,LoginUser){
		
		var user = LoginUser;
		var DispositionVal = Disposition;
		//alert(user);
		//alert(DispositionVal);
		
		if(DispositionVal == "Call Back - Call Back"){
				
				document.getElementById('myHangupCallback').style.display = "block";
				//alert("inside");
				//fun();
		}	
		
		$.ajax({
		type:'POST',	
		url: "ajax/callHangup.php", 
		data:{'user':user,'DispositionVal':DispositionVal},	
		success: function(result){
			//alert(result);
		 document.getElementById("btn_hangup").disabled = true;
	     document.getElementById('btn_hangup').className = "btnaaInput2";
		 document.getElementById('myHangup').style.display = "none"; 
		
		} });
	
	}
	
	
	function callBackHangupFun(){
		//alert("inside_callback");
		var user = '<?php echo $loggedInuserName; ?>';
		var callback_datetimeVal = document.getElementById("callback_datetime").value;
		//alert(user);
		//alert(callback_datetimeVal); 
		
		$.ajax({
			type:'POST',	
			url: "ajax/callBackHangup.php", 
			data:{'user':user,'callback_datetimeVal':callback_datetimeVal},	
			success: function(result){
				//alert(result);
			 document.getElementById("btn_hangup").disabled = true;
			 document.getElementById('btn_hangup').className = "btnaaInput2";
			 document.getElementById('myHangupCallback').style.display = "none"; 
			
			} });
	
	}
	
	
	
	function enableDisableCallButton(Calling_id,LoginUserCallVal,phonoId){
		//alert("Inside");
	    var userStatusHiddenValue = document.getElementById('userStatusHiddenValue').value;	
		//alert(userStatusHiddenValue);
		
		var Callid = Calling_id;
		var LoginUserVal = LoginUserCallVal;
		var phonoId1 = phonoId;
		
		if(userStatusHiddenValue == "READY" || userStatusHiddenValue == "Ready"){
			
			//alert("If condition");
			window.open("https://ucp.quesscorp.com/quessAdmin/pages/Agent_mongodb/userProfile1.php?id="+Callid+"&user="+LoginUserVal+"&phonoId="+phonoId1);
			
		}else{	
			alert("You Are In Pause Mode Please Make It Ready.");
		}
		
	}
	
	
	
	//$(document).ready(function() {
    //$('#dropcallData').DataTable();
//} );

//$(document).ready(function() {
  //  $('#callBackData').DataTable();
//} );
		$(document).ready(function(){
    $("#current_emp").keypress(function(event){
        var inputValue = event.charCode;
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
            event.preventDefault();
        }
    });
});
				document.querySelector(".noticePeriodClass").addEventListener("keypress", function (evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
});$(document).ready(function(){
    $("#current_emp").keypress(function(event){
        var inputValue = event.charCode;
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
            event.preventDefault();
        }
    });  
	$('#expected_annual_CTC').keypress(function(event) {
	if(this.value.length==6) return false;
		if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
				$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	}).on('paste', function(event) {
		event.preventDefault();
	});   
	$('#current_annual_CTC').keypress(function(event) {
	if(this.value.length==6) return false;
		if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
				$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	}).on('paste', function(event) {
		event.preventDefault();
	}); 
	$('#total_experience').keypress(function(event) {
	if(this.value.length==5) return false;
		if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
				$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	}).on('paste', function(event) {
		event.preventDefault();
	}); 
});
		document.querySelector(".noticePeriodClass").addEventListener("keypress", function (evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
});

function noticePeriodClass(){
	var inputNoticePeriod = document.getElementById("notice_period").value;
	if(inputNoticePeriod.length ==2){
		var num = inputNoticePeriod.charAt(0); //0+inputNoticePeriod;
		if(num == 0){
		document.getElementById("notice_period").value=inputNoticePeriod.charAt(1);
		}
	}
	//alert("test");
}
	</script>
<script>
function getindividualChatVal(){
	
	var user = '<?php echo $loggedInuserName; ?>';
	//alert(user);
	$.ajax({
	type:'POST',	
    url: "ajax/getIndividualChat.php", 
    data:{'user':user},	
    success: function(result){
		if(result != ''){
			//alert(result);
		$("#individualChat_script").html(result);
		} else {
			$("#individualChat_script").html('');
		}
	
    }});
}

getindividualChatVal();
setInterval(function(){getindividualChatVal()},10000);




//for notification for individualChat //
function getNotification_IndividualChat(){
	
     var user = '<?php echo $loggedInuserName; ?>';
	 
	$.ajax({
    url: "ajax/getNotification_IndividualChat.php", 
    data:{'user':user},
    success: function(result){
		
	//alert(result);
	
	 if(result>0){
      $("#notification-latestindividualChat").html(result);
	  $("#notification-latestindividualChat").css("background-color","red");
	 }
	 else{
		 $("#notification-latestindividualChat").html('');
		 $("#notification-latestindividualChat").css("background-color","#fff");
	 }
	 
    }});
}
 
getNotification_IndividualChat();
setInterval(function(){getNotification_IndividualChat()},10000); 

 function submit_dateRange(){
			
			 var min = document.getElementById("min").value;  
	         var max = document.getElementById("max").value;
			 var user = '<?php echo $loggedInuserName; ?>';
			
			//alert(min);
			//alert(max);
			
			 $.ajax({  
						type:"POST",  
						url:"ajax/submit_CallLogdateRange.php?min="+min+"&max="+max+"&user="+user, 
						data:{},  
						success: function(result){
					     //alert(result);				
						 $('#recruiterCallLogDevision').html(result); 
						 $('#recruiterCallLog').dataTable({
						   "order": [[ 0, "desc" ]]
					  });			 
					}
			   });
		}



</script>
