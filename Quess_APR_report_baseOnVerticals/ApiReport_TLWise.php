<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ERROR | E_PARSE);
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

############################################super admin 

	date_default_timezone_set('asia/kolkata');
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['user_name' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
	$date = date("Y-m-d H:i:s");
    $rowsD1_User = $mongo->executeQuery("admin.users", $qry); 
	$exD1_User =  $rowsD1_User->toArray();
	$D1_UserArray = json_decode(json_encode($exD1_User), true);

	//echo $D1_UserArray[0]['user_level'];exit;
	$superAdminArr = array();
	if($D1_UserArray[0]['user_level'] == 'SuperAdmin' || $D1_UserArray[0]['user_level'] == 'SuperUserAccess'){
			date_default_timezone_set('asia/kolkata');
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
		$bulk = new MongoDB\Driver\BulkWrite;
		$filter = [];							
		$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
		$qry = new MongoDB\Driver\Query($filter,$options);
		$date = date("Y-m-d H:i:s");
		$rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 
		foreach($rowsD1_User as $rows){
			
			$UserName = $rows->d1_level;
			
                                                        		
														$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
															$bulk = new MongoDB\Driver\BulkWrite;
															$filter = [ "status"=>"Active","user_name"=> "$UserName","user_level"=>"D1"];							
															$options = [  ]; 
															$qry = new MongoDB\Driver\Query($filter,$options);
															$rowsD1_User_active = $mongo->executeQuery("admin.users", $qry); 
															foreach( $rowsD1_User_active as $row){
			                                               array_push($superAdminArr,$row->user_name);	
		                                                        }
																
																$superAdminLevel = $D1_UserArray[0]['user_level'];
		                                                       $arr = array_unique($superAdminArr);						
					                                        	$str = implode(",",$arr);						
						                                       $arrayData = explode(",",$str);
			                                         
		}
			
		}
	
	 
						
	################################## Super admin#########################
	
	//exit;
///D1_User	
	date_default_timezone_set('asia/kolkata');
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d1_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
	$date = date("Y-m-d H:i:s");
    $rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 
	$exD1_User =  $rowsD1_User->toArray();
	$D1_UserArray = json_decode(json_encode($exD1_User), true);
	
   //echo "================================ D1_user>".$D1_UserArray[0]['d1_level'];
   if($D1_UserArray[0]['d1_level'] != ''){
	   $D1UserLoggedIn = $D1_UserArray[0]['d1_level'];
   }
   
   ///D2_User	
	date_default_timezone_set('asia/kolkata');
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d2_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
	$date = date("Y-m-d H:i:s");
    $rowsD2_User = $mongo->executeQuery("admin.user_assign", $qry); 
	$exD2_User =  $rowsD2_User->toArray();
	$D2_UserArray = json_decode(json_encode($exD2_User), true);
	
	
	 if($D2_UserArray[0]['d2_level'] != ''){
	   $D2UserLoggedIn = $D2_UserArray[0]['d2_level'];
   }
	//echo $loggedInuserName."<br>";
	 ///D3_User	
	date_default_timezone_set('asia/kolkata');
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d3_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
	$date = date("Y-m-d H:i:s");
    $rowsD3_User = $mongo->executeQuery("admin.user_assign", $qry); 
	$exD3_User =  $rowsD3_User->toArray();
	$D3_UserArray = json_decode(json_encode($exD3_User), true);
	
	
	 if($D3_UserArray[0]['d3_level'] != ''){
	   $D3UserLoggedIn = $D3_UserArray[0]['d3_level'];
   }
	
	//echo $D3UserLoggedIn."Test";


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
	
	.dt-button {
		border: 1px solid #eeee;
        margin-left: 16px;
		}
   
   .btn.btn-xs {
    font-size: 14px !important;
   }
	

    .tableFixHead tbody {
      display: block;
      width: 100%;
      overflow: auto;
      height: 300px;
    }
    .tableFixHead thead tr {
      display: block;
    }
    .tableFixHead th,
    .tableFixHead  td {
      width: 200px;
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
<body class="sidebar-mini sidebar-collapse"  id = "sampleDiv_zoom"> <!-- hold-transition sidebar-mini-->
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
                <a href="User.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
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
                <a href="assignRecruiter.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign User</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="report.php" class="nav-link active">
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
              <li class="breadcrumb-item active">API Report</li>
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
            <h3 class="card-title">Filter Option</h3>

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
			
					<td><label>Start Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="date" id="startDate" name="startDate" >
					</td>
					<td><label>End Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="date" id="endDate" name="endDate" >
					</td>
				<?php echo $D1_UserArray[0]['user_level']; ?>
				
					<td><label>Verticals</label>
						<select name='vertical[]' multiple class='formselect' id="vertical">
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
								if($superAdminLevel == 'SuperAdmin' || $superAdminLevel == 'SuperUserAccess' || $loggedInUserLevel == 'D1'){ 
							?>
							<option value = "<?php  echo $row->vertical_name; ?>" ><?php echo $row->vertical_name;?></option>
								<?php }} ?>
						</select>
					</td>
					
					<td>
						<label for="cars">A1</label> 
						  <select name='D1_User[]' multiple class='formselect' id="D1_User">
						  	<?php 
							if($superAdminLevel == 'SuperAdmin' || $superAdminLevel == 'SuperUserAccess'){ ?>
								<option value="<?php echo $D1UserLoggedIn; ?>"><?php echo $D1UserLoggedIn; ?></option>
							<?php } ?>
						  </select>
					</td>
					<td>
						<label for="cars">D2</label>
						  <select name='D2_User[]' multiple class='formselect' id="D2_User">
						  <?php if($D2UserLoggedIn != ''){?>
							<option value="<?php echo $D2UserLoggedIn; ?>"><?php echo $D2UserLoggedIn; ?></option>
							 <?php } ?>
						  </select>
					</td>
					<td>
						<label for="cars">D3</label>
						 <select name='D3_User[]' multiple class='formselect' id="D3_User">
						 <?php if($D3UserLoggedIn != ''){?>
							<option value="<?php echo $D3UserLoggedIn; ?>"><?php echo $D3UserLoggedIn; ?></option>
						 <?php } ?>
						  </select>
					</td>
					
					<td>
						<label for="cars">D4</label>
						  <select name='D4_User[]' multiple class='formselect' id="D4_User">
							
						  </select>
					</td>
				
					<td>
						<input type = "submit" name="btn_submit" class = "btn btn-primary">
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
            <h3 class="card-title">RECRUITERS PERFORMANCE REPORT</h3>
            <br><br>
			  <?php
				if (isset($_POST["btn_submit"]) == "btn_submit") {
											
							  $start_date = $_POST['startDate']; 
					          $end_date = $_POST['endDate']; 
							  
							  
							  //
							  
									
							  
							/*$start_date_Time = $_POST['min_time']; 
							$end_date_Time = $_POST['max_time']; 
							
							if ($start_date_Time =="") 
							{
								$start_date_TimeVal="00:00:00";
							}
							else
							{
								$start_date_TimeVal = $start_date_Time;
							}
							if ($end_date_Time =="") {
								
								$end_date_TimeVal="23:59:59";
							}
							else
							{
								$end_date_TimeVal=$end_date_Time;
							}*/
							
							
							
				?>
					<label style="font-size: medium;font-weight: 400;">Date Range :&nbsp; &nbsp; <?php echo "$start_date 00:00:00"; ?>&nbsp; &nbsp; to  &nbsp; &nbsp;<?php echo "$end_date 23:59:59"; ?></label>
					
				<?php			
				}	
				?>
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
             <table class="table m-0" id="table_camp" cellspacing="0" style="border: 1px solid #eee;"> <!--tableFixHead-->
              <thead>
                  <tr>
                <!--      <th>Sr.No.</th> -->
					  <th>Emp ID</th>
                      <th>Recruiters</th>
					  <th>D3 Level</th>
					  <th>D2 Level</th>
					  <th>A1 Level</th>
					  <th>Vertical</th>
					  <th>No. of Work Days</th>
                      <th>No.Of Days Login</th>
					  <th>Total Calls</th>
					  <th>Total Call Time</th>
					  <th>Not Connected Calls</th>
					  <th>Connected Calls</th>	
					  <th>Welcome Call Time</th>	
					  <th>Customer Talk time</th>						
					  <th>Total Talk Time</th>
					  <th>Email</th>
					  <th>Dialed Count</th>
					 
                  </tr>
              </thead>
             <tbody>
			  <?php 	
			
						   if (isset($_POST["btn_submit"]) == "btn_submit") {
							   //echo "========================>"."inside";
							   //die;
							   $start_date = $_POST['startDate']; 
							   $end_date = $_POST['endDate']; 
							 if ($start_date !="" && $end_date !="") {


										date_default_timezone_set('asia/kolkata');		
									$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");   	
									$bulk = new MongoDB\Driver\BulkWrite;
									$qry = new MongoDB\Driver\Query([]);
									$date = date("Y-m-d H:i:s");
									$doc = array(
									 //   'id'      => new MongoDB\BSON\ObjectID,     #Generate MongoID
										"id"                       => strval(rand()),
										"created_date"             => $date,										
										"reportName"               => "Recruiters_Performance_Report",
										"user"				       => "$loggedInuserName",
										"user_level"	           => "$loggedInUserLevel"
									);
									$bulk->insert($doc);
									$mongo->executeBulkWrite('candidateinfo.repostStatus', $bulk);
									
									
											$x = 1;	
											//echo "count ".count($_POST['D4_User']);exit;
											foreach ($_POST['D4_User'] as $selected_D4Option) {
											//echo "=============================>".$selected_D4Option."\n";
											//die;
											
											   //============================> USER ID ============================> //
											   $start_date1 = strtotime($start_date);							  
							  $end_date1 =  strtotime($end_date);
							  
											$days_between = ceil(abs($end_date1 - $start_date1) / 86400)+1;
							
							
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["d4_level" => "$selected_D4Option"];												
												$options = []; 
												$qry_userId = new MongoDB\Driver\Query($filter,$options);											
												$rows_userIdLevel = $mongo->executeQuery("admin.user_assign", $qry_userId); 
												$arr_userIdLevel = $rows_userIdLevel->toArray();
												$array_userIdLevel = json_decode(json_encode($arr_userIdLevel), true);
												$D3 = $array_userIdLevel[0]['d3_level'];
												if($D3 == ''){
													$D3 = "D3NoOne";
												}
												if($D2 == ''){
													$D2 = "D2NoOne";
												}
												if($D1 == ''){
													$D1 = "D1NoOne";
												}
												$D2 = $array_userIdLevel[0]['d2_level'];
												$D1 = $array_userIdLevel[0]['d1_level'];
												$VerticalName = $array_userIdLevel[0]['vertical'];
												
											
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user_name" => "$selected_D4Option"];							
												//echo "==>".print_r($filter); 
												$options = []; 
												$qry_userId = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows_userId = $mongo->executeQuery("admin.users", $qry_userId); 
												$arr_userId = $rows_userId->toArray();
												$array_userId = json_decode(json_encode($arr_userId), true);							        
												$UserID = $array_userId[0]['user_id'];
											    $UserID_Id = $array_userId[0]['user_id_new'];  //User_fullname
												$User_fullname = $array_userId[0]['User_fullname'];
												$UserEmail_Id = $array_userId[0]['user_id'];
											  //============================> FIRST LOGIN ============================> //
											  
											    date_default_timezone_set('asia/kolkata');
											        
													// Declare two dates
													$Date1 = $start_date;
													$Date2 = $end_date;
													  
													$datearray = array();
													  
													$Variable1 = strtotime($Date1);
													$Variable2 = strtotime($Date2);
													 
													for ($currentDate = $Variable1; $currentDate <= $Variable2; 
																					$currentDate += (86400)) {
																						  
													$Store = date('Y-m-d', $currentDate);
													$datearray[] = $Store;
													}
													  
													// Display the dates in array format
													//echo "<pre>";
													//print_r($datearray);
													
													$dateCountVal = count($datearray);
											$arrayNoofLogin = array();
											$Count_array_logintime="";
											$NoOfTimeLogin="";	
											   for($i=0; $i<$dateCountVal;$i++){
												   
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;												
												$filter = ["user" => "$selected_D4Option","status" => "LOGIN",
												'$and' => [
																		['created_date' => ['$gt' => "$datearray[$i] 00:00:00"]],
																		['created_date' => ['$lt' => "$datearray[$i] 23:59:59"]]
														]	
												];
											
												$options = ['sort' => ['created_date' => 1], 'limit' => 1]; 
												$qry_logintime = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows_logintime = $mongo->executeQuery("candidateinfo.user_log", $qry_logintime); 
												$arr_logintime  = $rows_logintime->toArray();
												$array_logintime = json_decode(json_encode($arr_logintime), true);	
												//echo "<pre>";
												//print_r($array_logintime[0]);
												
												$Count_array_logintime = count($array_logintime);
												//echo $Count_array_logintime;
												if($Count_array_logintime > 0){
												array_push($arrayNoofLogin,$Count_array_logintime);
												}
												$LoginTime = $array_logintime[0]['created_date'];
												
												$TimeVal = substr("$LoginTime",10);
												
												$DateVal = substr("$LoginTime",0,10);
												
											   }
						                       $NoOfTimeLogin = count($arrayNoofLogin);
												//============================> LAST LOGOUT ============================> //
												
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												//$filter = ["user" => "$UserName","status" => "LOGOUT","created_date" => array('$regex' => "$dateval")];	
											   // $filter = ["user" => "$UserName","status" => "LOGOUT","created_date" => ['$lte' => "$end_date 23:59:59"],"created_date" => ['$gte' => "$start_date 00:00:00"] ];								
												$filter = ["user" => "$selected_D4Option","status" => "LOGOUT",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = ['sort' => ['created_date' => -1], 'limit' => 1]; 
												$qry_logouttime = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows_logouttime = $mongo->executeQuery("candidateinfo.user_log", $qry_logouttime); 
												$arr_logouttime = $rows_logouttime->toArray();
												$array_logouttime = json_decode(json_encode($arr_logouttime), true);							        
												$LogoutTime = $array_logouttime[0]['created_date'];
												
												//============================>TOTAL CALLS ============================> //
												//Out bound calls 
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option",
												'$and' => [				['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
															]	
												];
												$options = ['sort' => ['created_date' => -1]]; 
												$qry_Calls = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows_Calls = $mongo->executeQuery("candidateinfo.outbound", $qry_Calls); 
												$arr_Calls = $rows_Calls->toArray();
												$array_Calls = json_decode(json_encode($arr_Calls), true);	
												//inbound calls 
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option",
												'$and' => [				['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
															],
												'$or' => [
															["dialstatus" => array('$regex' => "CANCEL")],
															["dialstatus" => array('$regex' => "BUSY")],  //"dialstatus" => "DROP"
															["dialstatus" => array('$regex' => "DROP")],
															["dialstatus" => array('$regex' => "ANSWER")]
															]
												];
												$options = []; 
												$qry_Calls = new MongoDB\Driver\Query($filter,$options);
												$date = date("Y-m-d H:i:s");
												$rows_Calls_inbound = $mongo->executeQuery("candidateinfo.inbound", $qry_Calls); 
												$arr_Calls_inbound = $rows_Calls_inbound->toArray();
												$array_Calls_inbound = json_decode(json_encode($arr_Calls_inbound), true);
												
												$TotalCalls = count($array_Calls)+count($array_Calls_inbound);
												
												//============================>TOTAL CONNECTED CALLS ============================> //
													
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","customerans" => "Answered",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_ConnectedCalls = new MongoDB\Driver\Query($filter,$options);
												$rows_ConnectedCalls = $mongo->executeQuery("candidateinfo.outbound", $qry_ConnectedCalls); 
												$arr_ConnectedCalls = $rows_ConnectedCalls->toArray();
												$array_ConnectedCalls_outbound = json_decode(json_encode($arr_ConnectedCalls), true);	


												//inbound 
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","dialstatus" => "ANSWER",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_ConnectedCalls = new MongoDB\Driver\Query($filter,$options);
												$rows_ConnectedCalls_Inbound = $mongo->executeQuery("candidateinfo.inbound", $qry_ConnectedCalls); 
												$arr_ConnectedCalls_Inbound = $rows_ConnectedCalls_Inbound->toArray();
												$array_ConnectedCalls_Inbound = json_decode(json_encode($arr_ConnectedCalls_Inbound), true);
												
												$ConnectedTotalCalls = count($array_ConnectedCalls_Inbound)+count($array_ConnectedCalls_outbound);
												
												
												//============================>TOTAL NOTCONNECTED CALLS ============================> //
													
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","customerans" => "",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_NotConnectedCalls = new MongoDB\Driver\Query($filter,$options);
												$rows_NotConnectedCalls = $mongo->executeQuery("candidateinfo.outbound", $qry_NotConnectedCalls); 
												$arr_NotConnectedCalls = $rows_NotConnectedCalls->toArray();
												$array_NotConnectedCalls = json_decode(json_encode($arr_NotConnectedCalls), true);	
												//Inbound table 
												
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														],
												'$or' => [
															["dialstatus" => array('$regex' => "CANCEL")],
															["dialstatus" => array('$regex' => "BUSY")],  //"dialstatus" => "DROP"
															["dialstatus" => array('$regex' => "DROP")]
															
															]			
												];
												$options = []; 
												$qry_NotConnectedCalls = new MongoDB\Driver\Query($filter,$options);
												$rows_NotConnectedCalls_inbound = $mongo->executeQuery("candidateinfo.inbound", $qry_NotConnectedCalls); 
												$arr_NotConnectedCalls_inbound = $rows_NotConnectedCalls_inbound->toArray();
												$array_NotConnectedCalls_inbound = json_decode(json_encode($arr_NotConnectedCalls_inbound), true);
	
													
												$NotConnectedTotalCalls = count($array_NotConnectedCalls)+count($array_NotConnectedCalls_inbound);
												
												
												//============================>TOTAL Talk Time ============================> //
												//connected calls 
													$talkTimeArray_Out = array();
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","customerans" => "Answered",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_talkTIme = new MongoDB\Driver\Query($filter,$options);
												$rows_talkTIme = $mongo->executeQuery("candidateinfo.outbound", $qry_talkTIme); 
												foreach($rows_talkTIme as $rows){
													array_push($talkTimeArray_Out,$rows->anscus);
												}
												$sumArray_Out = array_sum($talkTimeArray_Out);												
												//inbound
												$talkTimeArray_IN = array();
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","dialstatus" => "ANSWER",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_talkTIme = new MongoDB\Driver\Query($filter,$options);
												$rows_talkTIme = $mongo->executeQuery("candidateinfo.inbound", $qry_talkTIme); 
												foreach($rows_talkTIme as $rows){
													array_push($talkTimeArray_IN,$rows->answerdtime);
												}
												$sumArray_IN = array_sum($talkTimeArray_IN);
												$sumArray = $sumArray_IN + $sumArray_Out;
												$TotalTalkTime = gmdate("H:i:s", $sumArray);
												
												$avgVariable = $sumArray/$TotalCalls;
												$AvgTalkTIme = gmdate("H:i:s", $avgVariable);
												
												
												
												
														//============================>Total Calls ============================> //
												// dialed calls 
													$TotalCallsTimeArray = array();
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","extensionans" => "Answered",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$qry_talkTIme = new MongoDB\Driver\Query($filter,$options);
												$rows_talkTIme = $mongo->executeQuery("candidateinfo.outbound", $qry_talkTIme); 
												foreach($rows_talkTIme as $rows){
													array_push($TotalCallsTimeArray,$rows->anscus);
												}
												//inbound also has to bring 
												
												$sumArrayTotalcalls_OUTBOUNDCALLS = array_sum($TotalCallsTimeArray);
												$sumArrayTotalcalls = $sumArray_IN + $sumArrayTotalcalls_OUTBOUNDCALLS;
												$TotalCallsTime = gmdate("H:i:s", $sumArrayTotalcalls);
												
												
												
												
												//======================== wel come call time ============================
													
													$welcomeCallTalkTime = array();
												date_default_timezone_set('asia/kolkata');
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = ["user" => "$selected_D4Option","extensionans" => "Answered",
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]	
												];
												$options = []; 
												$welcomeCall = new MongoDB\Driver\Query($filter,$options);
												$rows_welcomeCall = $mongo->executeQuery("candidateinfo.outbound", $welcomeCall); 
												foreach($rows_welcomeCall as $rows_welcomeCALL){
													$EXTENSIONANSSTRAT = $rows_welcomeCALL->extensionans_start;
													$EXTENSIONANSEND = $rows_welcomeCALL->extensionans_end;
													if($EXTENSIONANSEND != '' && $EXTENSIONANSSTRAT != ''){
														$date1 = new DateTime($EXTENSIONANSSTRAT, new DateTimeZone('asia/kolkata'));
														$date2 = new DateTime($EXTENSIONANSEND,new DateTimeZone('asia/kolkata'));
														$interval = date_diff($date1, $date2);
														//$welcomeTIME = $interval->format("%H:%I:%S");
														$str_time = $interval->format("%H:%I:%S");
														
														$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
														sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
														$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
														
														array_push($welcomeCallTalkTime,$time_seconds);
													}
												}
												//inbound also has to bring 
											//	print_r($welcomeCallTalkTime);
												$welcomeCALLTIMESECONDS = array_sum($welcomeCallTalkTime);
												$welcomeCALLTIME = gmdate("H:i:s", $welcomeCALLTIMESECONDS);
												
												//=================== customer talk time ===============
												$customerTALKTIMESECODS =   $sumArray_Out-$welcomeCALLTIMESECONDS;
												$customerTALKTIME = gmdate("H:i:s", $customerTALKTIMESECODS);
												
												//=================================================//
												
												date_default_timezone_set('asia/kolkata');
												$callNumber = array();
												$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
												$bulk = new MongoDB\Driver\BulkWrite;
												$filter = [ "extensionans" => "Answered",
												     
													       "user" => "$selected_D4Option",	
													
												'$and' => [
																		['created_date' => ['$gt' => "$start_date 00:00:00"]],
																		['created_date' => ['$lt' => "$end_date 23:59:59"]]
														]
													];				 //	
													
												$options = [ ]; //'sort' => ['entry_date' => -1],"limit"=> 1
												$qry = new MongoDB\Driver\Query($filter,$options);						
												$duplicatePhoneNumber = $mongo->executeQuery("candidateinfo.outbound", $qry);
										
												foreach($duplicatePhoneNumber  as $numb){
													//if($numb->customerno !=""){
													//array_push($callNumber,str_replace(" ","",$numb->customerno));
													array_push($callNumber, $numb->customerno);
													//}
												}
											
											
												//echo "<pre>";
												//print_r($callNumber);
												//echo "TOTAL LEAD COUNT : ".count($callNumber)."<br>";
											/*  $str = implode("**",array_unique($callNumber));
	                                 		$uniqueNumbers = explode("**",$str);	*/
												$arr = array_unique($callNumber);	
                                           		$str = implode(",",$arr);						
												$uniqueNumbers = explode(",",trim($str));
												$uniquecount = count($uniqueNumbers) -1 ;
												
												//print_r($uniqueNumbers);
												
												//echo "TOTAL UNIQUE LEAD COUNT : ".count($uniqueNumbers)."<br>"; 
												/*	echo"	<div class='row' >
														 <div class='col-12 col-sm-6 col-md-2'>
                                                          <div class='info-box mb-3'>
             
                                                         <div class='info-box-content'>
                                                        <span class='info-box-text'><b>$selected_D4Option</b></span> 
                                                        <span >TOTAL LEAD COUNT : ".count($callNumber)." <br>
														TOTAL UNIQUE LEAD COUNT : ".count($uniqueNumbers)."
														</span>
                                                               </div>
              
                                                                 </div>
           
                                                               </div>
														</div> ";  */
												
											
												//echo "<br>";
												
											for($n=0;$n<count($uniqueNumbers);$n++){
												$phoneNumberCountArray = array();	
													//echo "phoneNumber===>".$uniqueNumbers[$n];
													//echo "<br>";
											
													$customerno = $uniqueNumbers[$n];
											
													    if(strlen($customerno) > 10){
															$phoneNumber = substr($customerno, 2);
														}else{  
															$phoneNumber =$customerno;
														} 
														
														##################### call count 
														$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
														$bulk = new MongoDB\Driver\BulkWrite;
														$filter = [
														            "extensionans" => "Answered",
														            "customerno" => "$customerno",
														'$and' => [
																				['created_date' => ['$gt' => "$start_date 00:00:00"]],
																				['created_date' => ['$lt' => "$end_date 23:59:59"]]
																]	
														
														
														
														];			 
														$options = []; 
														$qry_numberCount = new MongoDB\Driver\Query($filter,$options);											
														$rows_numberCount = $mongo->executeQuery("candidateinfo.outbound", $qry_numberCount); 
														//foreach($rows_numberCount as $rowp){
															//array_push($phoneNumberCountArray,$rowp->customerno);
															//echo $rowp->customerno;
															//echo "<br>";
														//}
													//	$phoneNumberCount = count($phoneNumberCountArray); 
													
													foreach($rows_numberCount as $rowp){
													if($rowp->customerno !=""){
													array_push($phoneNumberCountArray,str_replace(" ","",$rowp->customerno));
													}
												}
												$phoneNumberCount = count($phoneNumberCountArray); 
											
														
											}	
				                     //   if($NoOfTimeLogin!=0){
									  ?>
										   <tr>
											<!--  <td style="width: 5%;"><?php// echo $x ; ?></td> -->
											  <td style="text-align: center1;font-size: smaller;"><?php echo $UserID_Id; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $User_fullname; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $D3; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $D2; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $D1; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $VerticalName; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $days_between; ?></td>											
											  <td style="text-align: center1;font-size: smaller;"><?php echo $NoOfTimeLogin; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $TotalCalls; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $TotalCallsTime; ?></td>	
											  <td style="text-align: center1;font-size: smaller;"><?php echo $NotConnectedTotalCalls; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $ConnectedTotalCalls; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $welcomeCALLTIME; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $customerTALKTIME; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $TotalTalkTime; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $UserEmail_Id; ?></td>
											  <td style="text-align: center1;font-size: smaller;"><?php echo $uniquecount; ?></td>
											  
											  

											<!--  <td style="text-align: center;"><?php// echo $AvgTalkTIme; ?></td> -->
											 </tr>
											<?php $x++;/*}*/ }} else { ?> <label style="color:red;font-size:medium;font-weight:700;text-align:center;">Please Select Date Range And Recruiters..</label> <br>
									 
									<?php }}?>
								 
                 
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
		
		

        <!-- /.row -->
       
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer" style="margin-left: 12px;">
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
<!--<script src = "https://code.jquery.com/jquery-3.5.1.js"></script> 
<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">-->

    <script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">


<script>
  $(function () {
	  
 //D1_User//
 
 
var globle_valVertical;
jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  
  jQuery("#vertical").change(function() {
    var ids = $(this).val();
	 globle_valVertical = ids;
    jQuery.ajax({
      url: "/quessAdmin_Sauravi/pages/Agent_mongodb/ajax/get_D1_User_BaseOnVertical_list.php",
      type: "POST",
      data:{'login_user':login_user,'vertical_user_id':ids},
      success: function(result) {
		   //alert(result);
         var userData = result.split('**');
        jQuery("#D1_User").html(userData[0]);
		
      }
    });
  });
});
	 
	  
//D2_User//

/*
jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  jQuery("#D1_User").change(function() {
	  //alert("inside");
    var D1_User_id= document.getElementById('D1_User').value;
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D2_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D1_User_id':D1_User_id},
      success: function(result) {
		// alert(result);
		 var userData = result.split('**');
        jQuery("#D2_User").html(userData[0]);
		jQuery("#D4_User").html(userData[1]);
      }
    });
  });
});
*/

var globle_valD1;
jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  
  jQuery("#D1_User").change(function() {
    var ids = $(this).val();
	 globle_valD1 = ids;
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D2_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D1_User_id':ids},
      success: function(result) {
		   //alert(result);
         var userData = result.split('**');
        jQuery("#D2_User").html(userData[0]);
		jQuery("#D4_User").html(userData[1]);
		jQuery("#D3_User").html(userData[2]);
      }
    });
  });
});


//D3_User//

/*jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  jQuery("#D2_User").change(function() {
	  //alert("inside");
    var D2_User_id= document.getElementById('D2_User').value;
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D3_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D2_User_id':D2_User_id},
      success: function(result) {
		 alert(result);
        jQuery("#D3_User").html(result);
      }
    });
  });
});*/
 
var globle_valD2;
jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  jQuery("#D2_User").change(function() {
	  var ids = $(this).val();
	globle_valD2 = ids; 
	  var D2SelectedUser = globle_valD1;
	 // alert(D2SelectedUser);
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D3_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D2_User_id':ids,'D2SelectedUser':D2SelectedUser},
      success: function(result1) {
		//alert(result1);
		  var result = result1.split("**");		
        jQuery("#D3_User").html(result[0]);
		jQuery("#D4_User").html(result[1]);
      }
    });
  });
});

 
//D4_User//

/*jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  jQuery("#D3_User").change(function() {
	  //alert("inside");
	  var D1_User_id= document.getElementById('D1_User').value;
	 // alert(D1_User_id);
    var D3_User_id= document.getElementById('D3_User').value;
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D4_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D3_User_id':D3_User_id,'D1_User_id':D1_User_id},
      success: function(result) {
		 //alert(result);
        jQuery("#D4_User").append(result);
      }
    });
  });
});*/
  
jQuery(function() {
  var login_user = "<?php echo $loggedInuserName; ?>";
  jQuery("#D3_User").change(function() {
	 var ids = $(this).val();
	// alert(ids);
	 //alert(globle_valD1);  
	 //alert(globle_valD2);
console.log(ids);
    jQuery.ajax({
      url: "/quessAdmin/pages/Agent_mongodb/ajax/get_D4_User_list.php",
      type: "POST",
      data:{'login_user':login_user,'D3_User_id':ids,'globle_valD1':globle_valD1,'globle_valD2':globle_valD2},
      success: function(result) {
	//	 alert(result);
        jQuery("#D4_User").html(result);
      }
    });
  });
}); 
  
  
  //$('#table_camp').dataTable();
  
   var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#table_camp').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'csv',
				title:'Recruiters_Performance_Report_Report_'+today
                
            }

		 
        ] 
    } );
  
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
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
			
		
			document.getElementById('txt_User_id').value=res[0];
			document.getElementById('txt_User_name').value=res[1];
			document.getElementById('txt_User_level').value=res[2];
			document.getElementById('txt_User_status').value=res[3];
			document.getElementById('txt_User_password').value=res[4];  
			document.getElementById('txt_extension_id').value=res[5];  
			document.getElementById('txt_location').value=res[6];
			document.getElementById('txt_id').value=res[7];
		    }
        }; 
		xhttp.open("GET", "/quessAdmin/pages/Agent_mongodb/ajax/get_useredit_data.php?id="+val, true);
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
		alert(val_grpd);
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
