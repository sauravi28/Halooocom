<?php						
							$colorCode = $_REQUEST['colorCode'];
							
							$D2userUderSuperAdmin = $_REQUEST['D2userUderSuperAdmin'];
							if($D2userUderSuperAdmin == 'None'){
								$D2userUderSuperAdmin = '';
							}
							$loginUserD1 = $_REQUEST['loginUserD1'];
							$VerticalNameD2ToD3 = $_REQUEST['VerticalNameD2ToD3'];
							
								$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
									$bulk = new MongoDB\Driver\BulkWrite;
									$filter = ['d2_level' => "$D2userUderSuperAdmin",'d1_level' => "$loginUserD1","vertical"=>"$VerticalNameD2ToD3"]; //							
									$options = [  ];  
									$qry = new MongoDB\Driver\Query($filter,$options);
									$rowsD2_User = $mongo->executeQuery("admin.user_assign", $qry); 									
									$D3UsersUnderD1Value = array();
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
										array_push($D3UsersUnderD1Value,$rowD2->d3_level);		
										}
									}
									$ArrayToString = implode(" ",array_unique($D3UsersUnderD1Value));
									$D3UsersUnderD1AndSuperAdmin = explode(" ",$ArrayToString);
									$D3_count = count($D3UsersUnderD1AndSuperAdmin);
			//$color = array('#3d9970','#36457E','#82f5aa','#ff851b','#007bff','#adfc03','#03b5fc','#9d03fc','#fc037b');
		for($n=0;$n<count($D3UsersUnderD1AndSuperAdmin);$n++){
			if($D3UsersUnderD1AndSuperAdmin[$n] != ''){	?>	
		  <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $colorCode; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><b>D3</b></span> 
                <span class="" style = "cursor:pointer " id = "D3User_<?php echo $n?>" onclick = "D3UsersUnderD2AndD1('<?php echo $D3UsersUnderD1AndSuperAdmin[$n]; ?>',
				'<?php echo $n; ?>','<?php echo $D3_count; ?>');"><?php echo $D3UsersUnderD1AndSuperAdmin[$n]; ?></span>
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
                <span class="info-box-text"><b>D3</b></span> 
                <span class="" style = "cursor:pointer " id = "D3User_<?php echo $n?>" onclick = "D3UsersUnderD2AndD1('None');">None</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
			
		<?php }
		} ?>