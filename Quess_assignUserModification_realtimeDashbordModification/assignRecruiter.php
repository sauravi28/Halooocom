<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();

$loggedInuserName  = $_SESSION['username'] ;
$loggedInPassword  = $_SESSION['pass'];
$loggedInUserLevel = $_SESSION['user_level'];

/*echo "<br><br>";
echo "====>".$_SESSION['user_level'];
echo "<br><br>";
echo "====>".$_SESSION['username'];
echo "<br><br>";
echo "====>".$_SESSION['pass'];
die;
*/
if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/quessAdmin/pages/Agent/quessLogin/index.php');
}

	if($_POST['allLevelData']){ 
	
			$D1Lebel = $_POST['D1Lebel'];
			$D2Lebel = $_POST['D2Lebel'];
			$D3Lebel = $_POST['D3Lebel'];
			$D4Lebel = $_POST['D4Lebel']; //extensionIdPhone
			$VerticalLebel = $_POST['VerticalLebel'];
			
			
			if($D1Lebel != '' && $D3Lebel !='' && $VerticalLebel !="" ){
				
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['vertical' => "$VerticalLebel"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$duplicateVertical = $mongo->executeQuery("admin.user_assign", $qry);
							$checkingDuplicateVertical1 = $duplicateVertical->toArray();
							$checkingDuplicateVertical = json_decode(json_encode($checkingDuplicateVertical1), true);
							
							$D1LebelAddedTable = $checkingDuplicateVertical[0]['d1_level'];
							$D3LebelAddedTable = $checkingDuplicateVertical[0]['d3_level'];
							$D1vertical = $checkingDuplicateVertical[0]['vertical'];
							
							
					/*echo "===========================================>".$D1LebelAddedTable;
						echo "<br>";							
					echo "=============================================>".$verticalCount = count($checkingDuplicateVertical);
						echo "<br>";
						echo "==========================================>".$D1vertical;
						echo "<br>";
						echo "===============================================>".$D1Lebel;*/
										

							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['d3_level' => "$D3Lebel"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$duplicateVerticalVal = $mongo->executeQuery("admin.user_assign", $qry);
							$checkingDuplicateVerticalVal1 = $duplicateVerticalVal->toArray();
							$checkingDuplicateVerticalVal = json_decode(json_encode($checkingDuplicateVerticalVal1), true);
							
							$D3LebelAddedTable = $checkingDuplicateVerticalVal[0]['d3_level'];
							$D3verticalAddTable = $checkingDuplicateVerticalVal[0]['vertical'];
							
							if($D3verticalAddTable !=""){
								
								$countOfD3Vertical = count($checkingDuplicateVerticalVal);
								
							}
							
							
							
					/*echo "===========================================>".$D3LebelAddedTable;
						echo "<br>";							
					echo "=============================================>".$D3LebelAddedTable = count($checkingDuplicateVerticalVal);
						echo "<br>";
						echo "==========================================>".$D3verticalAddTable;
						echo "<br>";
						echo "===============================================>".$D3Lebel;*/
															
								
			}	
			else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel == "" && $VerticalLebel !=""){
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1 = json_decode(json_encode($ex), true);
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel == "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD2 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel == "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'d2_level' => "$D2Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D2 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD2D3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D2D3 = json_decode(json_encode($ex), true);
							
			}			
			if(	$D1Lebel == $extensionIDArrayD1[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D1.');</script>";
			}else if(	$D2Lebel == $extensionIDArrayD2[0]['d2_level']  && $D4Lebel == $extensionIDArrayD2[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D2.');</script>";
			}else if(	$D3Lebel == $extensionIDArrayD3[0]['d3_level']  && $D4Lebel == $extensionIDArrayD3[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D3.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D2[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1D2[0]['d4_level'] && $D2Lebel == $extensionIDArrayD1D2[0]['d2_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D2.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D3[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD1D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D3.');</script>";
			}else if(	$D2Lebel == $extensionIDArrayD2D3[0]['d2_level']  && $D4Lebel == $extensionIDArrayD2D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD2D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D2 or D3.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D2D3[0]['d1_level'] && $D2Lebel == $extensionIDArrayD1D2D3[0]['d2_level']  && $D4Lebel == $extensionIDArrayD1D2D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD1D2D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D2 or D3.');</script>";
			}else if(count($checkingDuplicateVertical) > 0  &&  $D1LebelAddedTable != $D1Lebel){
								echo "<script>alert('you are already assign this vertical to D1.');</script>";		 		
			}else if($countOfD3Vertical > 0  &&  $D3verticalAddTable != $VerticalLebel){
								echo "<script>alert('you are already assign this vertical to D3.');</script>";		 		
							}
			else{
					date_default_timezone_set('asia/kolkata');
								$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
								$bulk = new MongoDB\Driver\BulkWrite;
								$qry = new MongoDB\Driver\Query([]);
								$date = date("Y-m-d H:i:s");
								$doc = array(
								 //   'id'      => new MongoDB\BSON\ObjectID,     #Generate MongoID
									"id"                        => strval(rand()),
									"created_date"              => $date,
									"d1_level"                  => $D1Lebel,
									"d2_level"				    => $D2Lebel,
									"d3_level"			        => $D3Lebel,	
									"d4_level"	                => $D4Lebel,
									"vertical"                  => $VerticalLebel,
								);
								$bulk->insert($doc);
								$mongo->executeBulkWrite('admin.user_assign', $bulk);    # 'schooldb' is database and 'student' is collection.exit
				
			}
								
	}
	//txt_User_id txt_User_name txt_User_level txt_User_status txt_id
	if($_POST['editUser']){   
			$txt_id = $_POST['txt_id'];
			$D1Lebel = $_POST['txt_D1'];
			$D2Lebel = $_POST['txt_D2'];
			$D3Lebel = $_POST['txt_D3'];
			$D4Lebel = $_POST['txt_D4']; 
			$VerticalLebel = $_POST['txt_Vertical']; 
			
			if($D1Lebel != '' && $D3Lebel !='' && $VerticalLebel !="" ){
				
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['vertical' => "$VerticalLebel"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$duplicateVertical = $mongo->executeQuery("admin.user_assign", $qry);
							$checkingDuplicateVertical1 = $duplicateVertical->toArray();
							$checkingDuplicateVertical = json_decode(json_encode($checkingDuplicateVertical1), true);
							
							$D1LebelAddedTable = $checkingDuplicateVertical[0]['d1_level'];
							
						//echo "===========================================>".$D1LebelAddedTable;
						//echo "<br>";							
						//echo "=============================================>".$verticalCount = count($checkingDuplicateVertical);
						//echo "<br>";
						//echo "===============================================>".$D1Lebel;
								//die;	
								
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['d3_level' => "$D3Lebel"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$duplicateVerticalVal = $mongo->executeQuery("admin.user_assign", $qry);
							$checkingDuplicateVerticalVal1 = $duplicateVerticalVal->toArray();
							$checkingDuplicateVerticalVal = json_decode(json_encode($checkingDuplicateVerticalVal1), true);
							
							$D3LebelAddedTable = $checkingDuplicateVerticalVal[0]['d3_level'];
							$D3verticalAddTable = $checkingDuplicateVerticalVal[0]['vertical'];
							
							if($D3verticalAddTable !=""){
								
								$countOfD3Vertical = count($checkingDuplicateVerticalVal);
								
							}
							
							
							
					echo "===========================================>".$D3LebelAddedTable;
						echo "<br>";							
					echo "=============================================>".$D3LebelAddedTable = count($checkingDuplicateVerticalVal);
						echo "<br>";
						echo "==========================================>".$D3verticalAddTable;
						echo "<br>";
						echo "===============================================>".$D3Lebel;
							
								
			}	
			else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel == "" && $VerticalLebel !=""){
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1 = json_decode(json_encode($ex), true);
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel == "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD2 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel == ""  && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'d2_level' => "$D2Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D2 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel == "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d1_level' => "$D1Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel == '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD2D3 = json_decode(json_encode($ex), true);
							
			}else if($D1Lebel != '' && $D4Lebel != "" && $D2Lebel != "" && $D3Lebel != "" && $VerticalLebel !=""){		
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'd4_level' => "$D4Lebel" ,'d2_level' => "$D2Lebel",'d3_level' => "$D3Lebel",'vertical' => "$VerticalLebel"];							
							$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.user_assign", $qry); 
							$ex =  $rowsExtension->toArray();
							$extensionIDArrayD1D2D3 = json_decode(json_encode($ex), true);
							
			}		
			if(	$D1Lebel == $extensionIDArrayD1[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D1.');</script>";
			}else if(	$D2Lebel == $extensionIDArrayD2[0]['d2_level']  && $D4Lebel == $extensionIDArrayD2[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D2.');</script>";
			}else if(	$D3Lebel == $extensionIDArrayD3[0]['d3_level']  && $D4Lebel == $extensionIDArrayD3[0]['d4_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign the user to D3.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D2[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1D2[0]['d4_level'] && $D2Lebel == $extensionIDArrayD1D2[0]['d2_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D2.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D3[0]['d1_level']  && $D4Lebel == $extensionIDArrayD1D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD1D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D3.');</script>";
			}else if(	$D2Lebel == $extensionIDArrayD2D3[0]['d2_level']  && $D4Lebel == $extensionIDArrayD2D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD2D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D2 or D3.');</script>";
			}else if(	$D1Lebel == $extensionIDArrayD1D2D3[0]['d1_level'] && $D2Lebel == $extensionIDArrayD1D2D3[0]['d2_level']  && $D4Lebel == $extensionIDArrayD1D2D3[0]['d4_level'] && $D3Lebel == $extensionIDArrayD1D2D3[0]['d3_level'] ){			//|| $D2Lebel == "" || $D3Lebel == "" ||
								echo "<script>alert('you are already assign this  user to D1 or D2 or D3.');</script>";
			}else if(count($checkingDuplicateVertical) > 0  &&  $D1LebelAddedTable != $D1Lebel){
					echo "<script>alert('you are already assign this vertical to D1.');</script>";
			}else if($countOfD3Vertical > 0  &&  $D3verticalAddTable != $VerticalLebel){
								echo "<script>alert('you are already assign this vertical to D3.');</script>";		 		
							}
			else{
				$bulk = new MongoDB\Driver\BulkWrite;
				$bulk->update(
					['id' => $txt_id],
					['$set' => 	
						["d1_level"                => $D1Lebel,			
						 "d2_level"                => $D2Lebel,
						 "d3_level"                => $D3Lebel,
						 "d4_level"                => $D4Lebel,
						 "vertical"                => $VerticalLebel
						 
						 ]	
					],
					['multi' => false, 'upsert' => false]
				);

				$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
				$result = $manager->executeBulkWrite('admin.user_assign', $bulk);	
			}			
	}
	if(isset($_POST['deleteUser'])){ 
			$deleteUserId = $_POST['txt_deleteUser_id'];	
		 $deleteUserId = strval(trim($deleteUserId));		
			$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete(['id' => $deleteUserId], ['limit' => 1]);
		$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
		$result = $manager->executeBulkWrite('admin.user_assign', $bulk);
	
	}


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <style>
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
	
	.dt-button-collection {
		    margin-top: 2.5px !important;
			margin-bottom: 5px !important;
	}
  </style>
   <script>
  $(document).ready(function(){
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
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index.php" class="nav-link">Home</a>
      </li>
   
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item" style="display:none;">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
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

      <!-- Notifications Dropdown Menu -->
  
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
	   <li class="nav-item">
	    <a class="nav-link" href="../../logout.php" title="Signout" role="button"><i class="fas fa-sign-out-alt" style="color:red;"></i></a>
       </li>
      <li class="nav-item" style="display:none;">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link">
      <img src="../../dist/img/logo.png" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Quess</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $loggedInuserName; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline" style="display:none;">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../../index.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Overview</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../index3.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Graphical</p>
                </a>
              </li>
             
            </ul>
          </li>
		   <li class="nav-item">
            <a href="../../DID_DashBoard.php" class="nav-link">
			  <i class="fas fa-tty"></i>
              <p>
                DID Dashboard
                <!--<i class="right fas fa-angle-left"></i>-->
              </p>
            </a>
           
          </li>
       
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Modules
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="Campaign.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Campaign</p>
                </a>
              </li>
			  
			   <li class="nav-item">
                <a href="Ingroup.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ingroup</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="DID_Create.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Location & DID</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="User.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
			    <li class="nav-item">
                <a href="User_active_inactive.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active & Inactive User Count</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="Phone.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Phone</p>
                </a>
              </li>
			  
			    <li class="nav-item">
                <a href="Statuses.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Statuses</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="assignRecruiter.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign User</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Report</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="addBanner.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Banner</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="verticalHead.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vertical</p>
                </a>
              </li>
			    <?php if($loggedInUserLevel =="SuperAdmin"){?>
			   <li class="nav-item">
                <a href="holiday_list.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Holiday List</p>
                </a>
              </li>
			  <?php }?>
         
         
            </ul>
          </li>
   
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
         
          </div>
          <div class="col-sm-6" style = "display:none">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
		  <div class="card-footer">
		   <?php if($loggedInUserLevel =="SuperAdmin"){?>
			<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display:none">ADD USER</button>
			<?php } ?>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Assign Level</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" style="display:none;" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
		  
            <div class="row">
              <div class="col-md-12">
                 <div class="card-body p-0">
				 <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
             <table class="table table-striped projects table-bordered" id="">
				<tr>
					<td><label>Verticals</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select id = "VerticalLebel" name = "VerticalLebel" >
							<option value=""> Select Vertical </option>
							<?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.vertical", $qry); 
							foreach($rowsExtension as $row){
							?>
							<option value = "<?php  echo $row->vertical_name; ?>" ><?php echo $row->vertical_name;?></option>
							<?php } ?>
						</select>
					</td>
					<td><label>A1</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select id = "D1Lebel" name = "D1Lebel" >
							<option value=""> Select A1  </option>
							<?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D1",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){
							?>
							<option value = "<?php  echo $row->user_name; ?>" ><?php echo $row->user_name;?></option>
							<?php } ?>
						</select>
					</td>
					<td><label>D2</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select id = "D2Lebel" name = "D2Lebel" >
							<option value=""> Select D2  </option>
							<?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D2",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){
							?>
							<option value = "<?php  echo $row->user_name; ?>" ><?php echo $row->user_name;?></option>
							<?php } ?>
						</select>
					</td>
					<td><label>D3</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select id = "D3Lebel" name = "D3Lebel" >
							<option value=""> Select D3  </option>
							<?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D3",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){
							?>
							<option value = "<?php  echo $row->user_name; ?>" ><?php echo $row->user_name;?></option>
							<?php } ?>
						</select>
					</td>
					<td><label>D4</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select id = "D4Lebel" name = "D4Lebel" >
							<option value=""> Select D4  </option>
							<?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D4",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){
							?>
							<option value = "<?php  echo $row->user_name; ?>" ><?php echo $row->user_name;?></option>
							<?php } ?>
						</select>
					</td>
					<td>
					 <?php if($loggedInUserLevel =="SuperAdmin"){?>
						<input type = "submit" name = "allLevelData" id = "allLevelData" value= "ADD" class="btn btn-primary">
					 <?php }?>
					</td>
				</tr>
          </table>
		  
		  
		  </form>
        </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        
        </div>
        <!-- /.card -->
		
		
		  <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
		  <div class="card-footer">
		   <?php if($loggedInUserLevel =="SuperAdmin"){?>
			<button type="submit" class="btn btn-primary" style = "display:none" data-toggle="modal" data-target="#myModal">ADD USER</button>
			<?php } ?>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Assigned Users</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" style="display:none;" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
		  
            <div class="row">
              <div class="col-md-12">
                 <div class="card-body p-0">
             <table class="table table-striped projects table-bordered" id="table_camp">
              <thead>
                  <tr>
                      <th>
                         Sr.No.
                      </th>
					  <th>
                        Vertical Name
                      </th>
                      <th>
                          A1
                      </th>
                      <th>
                          D2
                      </th>
					  <th>
                          D3
                      </th>
					   <th>
                          D4
                      </th>
					
					  
					   <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <th>
					  ACTION
                      </th>
					   <?php }?>
                  </tr>
              </thead>
              <tbody>
			  <?php 
							date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [ 'sort' => ['created_date' => -1] ]; //
							$qry = new MongoDB\Driver\Query($filter,$options);  //
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.user_assign", $qry); // user_id user_name user_level
							$x = 1;
			  foreach ($rows as $row) { 
			   $UserName = $row->d4_level;			
			$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
			$bulk = new MongoDB\Driver\BulkWrite;
			$filter = [ "status"=>"Active","user_name"=> "$UserName","user_level"=>"D4"];							
			$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
			$qry = new MongoDB\Driver\Query($filter,$options);
			$rowsD4_User_active = $mongo->executeQuery("admin.users", $qry); 
			$exD4_User_active =  $rowsD4_User_active->toArray();
			$D2_UserArray_active = json_decode(json_encode($exD4_User_active), true);	
		
			if($D2_UserArray_active[0]['user_name'] != ''){
			  
			  ?>
                  <tr>
                      <td>
                          <?php echo $x ; ?>
                      </td>
					   <td>
                         <?php echo $row->vertical; ?>
                      </td>
                      <td>
                          <a>
                            <?php echo $row->d1_level; ?>
                          </a>
                      </td>
                      <td>
                         <?php echo $row->d2_level; ?>
                      </td>
                      <td>
                        <?php echo $row->d3_level; ?>
                      </td>
					     <td>
                        <?php echo $row->d4_level; ?>
                      </td>
					 
					   <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <td class="project-actions text-right">
                         <!-- <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal_edit"> -->
						 <span class="btn btn-info btn-sm"  onclick = "document.getElementById('myModal_edit').style.display='block'; userEditFun('<?php echo $row->id; ?>')">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
							  </span>
                      
						<!--<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST"> 
								<input type = "hidden" value =" <?php echo $row->id; ?>" name  = "deleteUserId" id  = "deleteUserId"  >
								<input type = "hidden" value = "delete_User" name = "deleteUser">					
								<span class="btn btn-danger btn-sm">
								  <button type = "submit" class ="btn" title="delete"><i class="fas fa-trash"></i></button>					   
								  Delete  
							    </span>
                       
					   </form>-->
					   
					    <span class="btn btn-danger btn-sm"  onclick = "document.getElementById('myModal_delete').style.display='block'; userDeleteFun('<?php echo $row->id; ?>','<?php echo $row->user_id; ?>','<?php echo $row->user_name; ?>')">
                              <i class="fas fa-trash">
                              </i>
                              Delete
							  </span>
							
                      </td>
					  <?php }?>
                  </tr>
               <?php $x++; }
			  }?>
                 
                 
              </tbody>
          </table>
        </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        
        </div>
        <!-- /.card -->
		
		<!-- The Modal Edit-->
  <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 16px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">EDIT USER</h4>
          <button type="button" class="close1" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
                <div class="card-body">
					
					<div class="form-group">
					  <label> Verticals </label>
					  <select class="form-control select2" style="width: 100%;" id="txt_Vertical" name  = "txt_Vertical">
						<option value="">Select</option>
						<?php 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.vertical", $qry); 
							foreach($rowsExtension as $row){					
						?>
						<option value="<?php echo $row->vertical_name; ?>"> <?php echo $row->vertical_name; ?></option>
						<?php } ?>
					  </select>
					</div>
					
					<div class="form-group">
					  <label> A1</label>
					  <select class="form-control select2" style="width: 100%;" id="txt_D1" name  = "txt_D1">
						<option value="">Select</option>
						<?php 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D1",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){					
						?>
						<option value="<?php echo $row->user_name; ?>"> <?php echo $row->user_name; ?></option>
						<?php } ?>
					  </select>
					</div>
					
					<div class="form-group">
					  <label> D2</label>
					  <select class="form-control select2" style="width: 100%;" id="txt_D2" name = "txt_D2">
						<option value="">Select</option>
						<?php 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D2",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){					
						?>
						<option value="<?php echo $row->user_name; ?>"> <?php echo $row->user_name; ?></option>
						<?php } ?>
						
					  </select>
					</div>
				
					
					<div class="form-group">
					  <label> D3</label>
					   <select class="form-control select2" style="width: 100%;" id="txt_D3" name = "txt_D3">
						<option value="">Select</option>
						<?php 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D3",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){					
						?>
						<option value="<?php echo $row->user_name; ?>"> <?php echo $row->user_name; ?></option>
						<?php } ?>
					  </select>
					</div>
					<div class="form-group">
					  <label> D4</label>
					   <select class="form-control select2" style="width: 100%;" id="txt_D4" name = "txt_D4">
						<option value="">Select</option>
						<?php 
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ 'user_level' => "D4",'status' => 'Active' ];							
							$options = [ ];  //'sort' => ['created_date' => -1],'limit'=> 1 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qry); 
							foreach($rowsExtension as $row){					
						?>
						<option value="<?php echo $row->user_name; ?>"> <?php echo $row->user_name; ?></option>
						<?php } ?>
					  </select>
					</div>
					
				
					 
					
                </div>
				<input type = "hidden" value = "" id = "txt_id" name = "txt_id">
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value = "Modify" name = "editUser">
                </div>
              </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>

        <!-- /.row -->
		
		<!-- The Modal Add -->
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
         <form action = "" method = "POST">
                <div class="card-body">
					<div class="form-group">
						<label for="UserID">User ID</label> 
						<input type="text" class="form-control" id="User_id" name = "User_id"  placeholder="Enter User ID">
					</div>
					<div class="form-group">
						<label for="UserName">User Name</label>
						<input type="text" class="form-control" id="User_name" name = "User_name" placeholder="Enter User Name">
					</div>
					<div class="form-group">
						<label for="UserName">User Password</label>
						<input type="text" class="form-control" id="User_password" name = "User_password" placeholder="Enter Password">
					</div>
					<div class="form-group">
					  <label> USER LEVEL</label>
					  <select class="form-control select2" style="width: 100%;" id="User_level" name = "User_level">
						<option value="">Select</option>
						<option value="SuperAdmin"> Super Admin</option>
						<option value="D1">D1 	 </option>
						<option value="D2">D2     </option>
						<option value="D3">D3     </option>
						<option value="D4">D4     - Recruiters</option>
						
					  </select>
					</div>
					<div class="form-group">
					  <label> USER STATUS</label>
					  <select class="form-control select2" style="width: 100%;" id="User_status" name = "User_status">
						<option value="">Select</option>
						<option>Active</option>
						<option>Inactive</option>
						
					  </select>
					</div>
					<div class="form-group">
					  <label> EXTENSION ID</label>
					  <select class="form-control select2" style="width: 100%;" id="extensionIdPhone" name = "extensionIdPhone">
						<option value="">Select</option>
			<?php			date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qryExtension = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.phones", $qryExtension); // user_id user_name user_level
							$x = 1;
							$extenionArrayUser = array();
							$extenionArrayPhone = array();
			  foreach ($rowsExtension as $row) { 
					array_push($extenionArrayPhone,$row->phone_id);
			  }
				$extensionIdFromPhone = $row->phone_id;
			  //checking from added extension 
				$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ //'$nor' => ["extension_id" => array('$regex' => "$extensionIdFromPhone")] 
										];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qryExtension = new MongoDB\Driver\Query($filter,$options);  //
							$date = date("Y-m-d H:i:s");
							$rowsExtension = $mongo->executeQuery("admin.users", $qryExtension); // user_id user_name user_level
							 foreach ($rowsExtension as $rowsExtensions) {
								array_push($extenionArrayUser,$rowsExtensions->extension_id);
							 }
							 $resultArray =array_diff($extenionArrayPhone,$extenionArrayUser);
							 $strvalue = implode(",",$resultArray);
							$arrayValue = explode(",",$strvalue);
							 print_r($arrayValue);
							 for($i=0;$i<count($arrayValue);$i++){
								 ?>
								 
									<option value = "<?php echo $arrayValue[$i] ; ?>" ><?php echo $arrayValue[$i]; ?></option> 
							<?php  }
							
							
									    ?>
						
					  </select>
					</div>
					
					<div class="form-group">
					  <label> LOCATION</label>
					  <select class="form-control select2" style="width: 100%;" id="location" name = "location">
						<option value="">Select</option>
						<option value="Chennai">Chennai</option>
						<option value="Hyderabad">Hyderabad</option>
						<option value="Bangalore">Bangalore</option>
					  </select>
					</div>
					
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value = "Submit" name = "addUser">
				  </div>
              </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>



	<!-- The Modal Delete-->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">DELETE USER</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "" method = "POST">
                <div class="card-body">
                  <div class="form-group">
						<label for="UserID">User ID</label>
						<input type="text" class="form-control" id="txt_deleteUser_id" name="txt_deleteUser_id" >
					</div>
					
					
                </div>
				<input type = "hidden" value = "" id = "deleteUserID" name = "deleteUserID">
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value = "Delete" name = "deleteUser">
                </div>
              </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div>

        <!-- /.row -->
       
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0-rc
    </div>
    <strong>Copyright &copy; 2021-2023 All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../../plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="../../dist/js/demo.js"></script> -->
<!-- Page specific script -->
<script src = "https://code.jquery.com/jquery-3.5.1.js"></script> 
<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script>
  $(function () {
  
  $('#table_camp').dataTable();
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
  
 
  
  
  function userEditFun(val)
	{
		//alert(val);
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
			
		
			document.getElementById('txt_D1').value=res[0];
			document.getElementById('txt_D2').value=res[1];
			document.getElementById('txt_D3').value=res[2];
			document.getElementById('txt_D4').value=res[3];			
			document.getElementById('txt_id').value=res[4];
			document.getElementById('txt_Vertical').value=res[5];
			
		    }
        }; 
		xhttp.open("GET", "/quessAdmin_Sauravi/pages/Agent_mongodb/ajax/getUserLevelDataEdit.php?id="+val, true);
		xhttp.send();	  
		// Get the modal
		var modal1 = document.getElementById('myModal_edit');
		// Get the button that opens the modal
		var btn1 = document.getElementById("myBtngrpedit");
		// Get the <span> element that closes the modal
		var span1 = document.getElementsByClassName("close1")[0];
		// When the user clicks the edit button, open the modal 
		modal1.style.display = "block";
		// When the user clicks on <span> (x), close the modal
		span1.onclick = function() {
			
		modal1.style.display = "none";
								   }
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		if (event.target == modal1) {
			modal1.style.display = "none";
									}
										 }

	}
	
	
	function userDeleteFun(val_grpd,UserIDVal,UserNameVal)
	{
		//alert(val_grpd);
		document.getElementById('txt_deleteUser_id').value=val_grpd;
		//document.getElementById('txt_deleteUser_id').value=UserIDVal;
		//document.getElementById('txt_deleteUser_name').value=UserNameVal;
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

  
</script>
</body>
</html>
