<?php						
							$colorCode = $_REQUEST['colorCode'];
							
							$BaseonverticalD1Val = $_REQUEST['BaseonverticalD1Val'];
							if($BaseonverticalD1Val == 'None'){
								$BaseonverticalD1Val = '';
							}
							$loginUserD1 = $_REQUEST['loginUserD1'];
							$D1VerticalName = $_REQUEST['D1VerticalName'];
							//die;
							
								$D1UsersUnderSuperAdminValue = array();	
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ["vertical"=>"$D1VerticalName"];	
	//print_r($filter);
	$options = [  ];  
	$qry = new MongoDB\Driver\Query($filter,$options);
	//echo $qry;
    $rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 
	foreach( $rowsD1_User as $rows){
			$UserName = $rows->d1_level;
			
                                                        		
														$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
															$bulk = new MongoDB\Driver\BulkWrite;
															$filter = [ "status"=>"Active","user_name"=> "$BaseonverticalD1Val","user_level"=>"D1"];						
															$options = [  ]; 
															$qry = new MongoDB\Driver\Query($filter,$options);
															$rowsD1_User_active = $mongo->executeQuery("admin.users", $qry); 
															foreach( $rowsD1_User_active as $rowD1){
			                                               array_push($D1UsersUnderSuperAdminValue,$rowD1->user_name);		
		                                                        }
			                                           $ArrayToString = implode("**",array_unique($D1UsersUnderSuperAdminValue));
			                                               $D1UsersUnderSuperAdmin = explode("**",$ArrayToString);
														   
														   $D1_Count = count($D1UsersUnderSuperAdmin);
				
		}
			
		for($n=0;$n<count($D1UsersUnderSuperAdmin);$n++){
			if($D1UsersUnderSuperAdmin[$n] != ''){
				
				?>	
		  <!-- /.col -->
		  <input type = "hidden" value = "" id = "d3UsersUnderD2ID"> 
		<input type = "hidden" value = "" id = "d2UsersUnderD1ID">
		<input type = "hidden" value = "" id = "d1UsersUnderD2ID">
		
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $colorCode; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
			 
                <span class="info-box-text"><b>A1</b></span>
				
			    <span class="" style = "cursor:pointer " id = "D1User_<?php echo $n?>" onclick = "d1UsersUnderSuperAdmin('<?php echo $D1UsersUnderSuperAdmin[$n]; ?>','<?php echo $colorCode; ?>','<?php echo $n?>','<?php echo $D1_Count; ?>','<?php echo $D1VerticalName;?>');"><?php echo $D1UsersUnderSuperAdmin[$n]; ?></span>
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
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $color[$n]; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><b>A1</b></span> 
                <span class="" style = "cursor:pointer " id = "D1User_<?php echo $n?>" onclick = "d1UsersUnderSuperAdmin('None','<?php echo $color[$n]; ?>');">None</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
			
		<?php }
		} ?>