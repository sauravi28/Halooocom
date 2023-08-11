<?php						
							$colorCode = $_REQUEST['colorCode'];
							
							$D1userUderSuperAdmin = $_REQUEST['D1userUderSuperAdmin'];
							if($D1userUderSuperAdmin == 'None'){
								$D1userUderSuperAdmin = '';
							}
							$loginUserD1 = $_REQUEST['loginUserD1'];
							$VerticalNameD1ToD2 = $_REQUEST['VerticalNameD1ToD2'];
							
							
								$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
									$bulk = new MongoDB\Driver\BulkWrite;
									$filter = ['d1_level' => "$D1userUderSuperAdmin","vertical"=>"$VerticalNameD1ToD2"]; //,'d1_level' => "$loginUserD1"							
									$options = [  ];  
									$qry = new MongoDB\Driver\Query($filter,$options);
									$rowsD2_User = $mongo->executeQuery("admin.user_assign", $qry); 									
									$D2UsersUnderD1Value = array();
									foreach( $rowsD2_User as $rowD2){
										$UserName = $rowD2->d4_level;
										$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
										$bulk = new MongoDB\Driver\BulkWrite;
										$filter = ["user_name" => "$UserName","status"=>"Active"];							
										$options = [  ]; 
										$qry_emplId = new MongoDB\Driver\Query($filter,$options);
										$rows_empId = $mongo->executeQuery("admin.users", $qry_emplId);
										$exD_User_empId =  $rows_empId->toArray();
										$D1_UserArray_emplyeeId = json_decode(json_encode($exD_User_empId), true);
										
										if($D1_UserArray_emplyeeId[0]['id'] != ''){	
										array_push($D2UsersUnderD1Value,$rowD2->d2_level);		
										}
									}
									$ArrayToString = implode(" ",array_unique($D2UsersUnderD1Value));
									$D2UsersUnderD1AndSuperAdmin = explode(" ",$ArrayToString);
									
									$D2_count = count($D2UsersUnderD1AndSuperAdmin);
			//$color = array('#3d9970','#36457E','#82f5aa','#ff851b','#007bff','#adfc03','#03b5fc','#9d03fc','#fc037b');
		for($n=0;$n<count($D2UsersUnderD1AndSuperAdmin);$n++){
			if($D2UsersUnderD1AndSuperAdmin[$n] != ''){	?>	
		  <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $colorCode; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><b>D2</b></span> 
                <span class="" style = "cursor:pointer " id = "D2User_<?php echo $n?>" onclick = "D2UsersUnderD1AndSuperAdmin('<?php echo $D2UsersUnderD1AndSuperAdmin[$n]; ?>','<?php echo $colorCode; ?>','<?php echo $n; ?>','<?php echo $D2_count; ?>','<?php echo $VerticalNameD1ToD2; ?>');"><?php echo $D2UsersUnderD1AndSuperAdmin[$n]; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
			<?php }else{
				?>
			 <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $colorCode; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><b>D2</b></span> 
                <span class="" style = "cursor:pointer " id = "D2User_<?php echo $n?>" onclick = "D2UsersUnderD1AndSuperAdmin('None','<?php echo $colorCode; ?>','<?php echo $n; ?>','<?php echo $D2_count; ?>','<?php echo $VerticalNameD1ToD2; ?>');">None</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
			
		<?php }
		} ?>