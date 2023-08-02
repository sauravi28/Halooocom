<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
 $login_user = $_POST["login_user"];
 $vertical_user_id =  $_POST["vertical_user_id"];  //  // array('TambeRonak'); //array("Quess","QuessD1","AdeebaRitesh","TambeRonak","Yuvarani","BNVRaghavanand","RaghuramS","PrithivKumar ","NirmalVyas "); //


        
		$D1UsersArray = array();
			
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
		$bulk = new MongoDB\Driver\BulkWrite;
		
		foreach($vertical_user_id as $val){			
				$MultiSelsectD1_User = $val;
				$filter = ['vertical_name' => [ '$in' => [ $MultiSelsectD1_User ] ]];
				echo "<pre>";
				print_r($filter);				
				$options = []; 
				$qry_multi = new MongoDB\Driver\Query($filter,$options);
				$rows_multi = $mongo->executeQuery("admin.vertical", $qry_multi);  
		 
				
					
					foreach ($rows_multi as $row) {

							$UserName = $row->vertical_headname ; 
							//echo $UserName;							
																	
										array_push($D1UsersArray,$UserName);									
									}
						
								$arr = array_unique($D1UsersArray);						
								$str = implode(",",$arr);						
								$arrayData = explode(",",$str);
								
						
			}
			for($i=0;$i<count($arrayData);$i++){	?>
			<option value="<?php echo $arrayData[$i]; ?>"><?php echo $arrayData[$i]; ?></option>
			<?php
			} ?>  
		