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

	if($_POST['addUser']){
		
		    $User_id = $_POST['User_id'];
			$User_id_new = $_POST['User_id_new'];
			$User_fullname = $_POST['User_fullname'];
			$User_name = $_POST['User_name'];
			$User_level = $_POST['User_level'];
			$User_status = $_POST['User_status']; //extensionIdPhone
			$User_password = $_POST['User_password'];
			$extensionIdPhone = $_POST['extensionIdPhone']; 
			$location     = $_POST['location']; 
			$addDID     = $_POST['addDID'];
			
	   if($User_level == "D4"){
		   
		///Checking D4 User Count //

			date_default_timezone_set('asia/kolkata');
			$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
			$bulk = new MongoDB\Driver\BulkWrite;
			$filter = [ "status" =>  "Active", "user_level" => "D4"
						//'$or' => [											
						//			["phone_number" => array('$regex' => "$mobile_no")],
						//			["archival" => array('$regex' => 1)]												
						//		]						
					  ];							
			$options = [];
			$qry_addNewUser = new MongoDB\Driver\Query($filter,$options);
			$date = date("Y-m-d H:i:s");
			$addNewUser = $mongo->executeQuery("admin.users", $qry_addNewUser);
			$UserCount = $addNewUser->toArray();
			$totalUserCount = json_decode(json_encode($UserCount), true);	
			$UserCountVal =  count($totalUserCount);
			//echo "===>Inside D4".$UserCountVal;
			//die;
		        if($UserCountVal >= 500 ){
					
					echo "<script>alert('User Limit crossed.');</script>";
					
				}else {
					
					date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user_name" =>  "$User_name"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$duplicatePhoneNumber = $mongo->executeQuery("admin.users", $qry);
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
										"user_id"                  => $User_id,
										"user_name"				   => $User_name,
										"user_level"			   => $User_level,	
										"status"	               => $User_status,
										"user_password"	           => $User_password,	
										"user_login_status"	       => "0",	
										"extension_id"	           => $extensionIdPhone,
										"location"				   => $location,
										"user_id_new"              => $User_id_new,
										"User_fullname"            => $User_fullname,
										"DID"                      => $addDID
										
									);
									$bulk->insert($doc);
									$mongo->executeBulkWrite('admin.users', $bulk);    # 'schooldb' is database and 'student' is collection.exit
							}else{
								echo "<script>alert('User Already Exist.');</script>";
							}
					
				}
			        
			
		}else{
	   
				        date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "user_name" =>  "$User_name"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);
							$date = date("Y-m-d H:i:s");
							$duplicatePhoneNumber = $mongo->executeQuery("admin.users", $qry);
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
										"user_id"                  => $User_id,
										"user_name"				   => $User_name,
										"user_level"			   => $User_level,	
										"status"	               => $User_status,
										"user_password"	           => $User_password,	
										"user_login_status"	       => "0",	
										"extension_id"	           => $extensionIdPhone,
										"location"				   => $location,
										"user_id_new"              => $User_id_new,
										"User_fullname"            => $User_fullname
										
									);
									$bulk->insert($doc);
									$mongo->executeBulkWrite('admin.users', $bulk);    # 'schooldb' is database and 'student' is collection.exit
							}else{
								echo "<script>alert('User Already Exist.');</script>";
							}
	        }
	}
	//txt_User_id txt_User_name txt_User_level txt_User_status txt_id
	if($_POST['editUser']){  
			$txt_User_id = $_POST['txt_User_id'];
			$txt_User_name = $_POST['txt_User_name'];
			$txt_User_level = $_POST['txt_User_level'];
			$txt_User_status = $_POST['txt_User_status'];
			$txt_User_password = $_POST['txt_User_password']; 
			$txt_extension_id = $_POST['txt_extension_id'];
			$txt_id = $_POST['txt_id'];		
			$txt_location = $_POST['txt_location'];	
			$txt_User_id_new = $_POST['txt_User_id_new'];	
			$txt_User_fullname = $_POST['txt_User_fullname'];	
			$txt_editDID = $_POST['txt_editDID'];
			
			
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->update(
				['id' => $txt_id],
				['$set' => 	
					["user_id"               => $txt_User_id,			
					 "user_name"             => $txt_User_name,
					 "user_level"            => $txt_User_level,
					 "status"                => $txt_User_status,
					 "user_password"	     => $txt_User_password,
					 "extension_id"          => $txt_extension_id,
					 "location"              => $txt_location,
					 "user_id_new"           => $txt_User_id_new,
					 "User_fullname"         => $txt_User_fullname,
					 "DID"                   => $txt_editDID
					 ]	
				],
				['multi' => false, 'upsert' => false]
			);

			$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
			$result = $manager->executeBulkWrite('admin.users', $bulk);		
	}
	if(isset($_POST['deleteUser'])){ 
			$deleteUserId = $_POST['deleteUserID'];	
		echo $deleteUserId = strval(trim($deleteUserId));		
			$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete(['id' => $deleteUserId], ['limit' => 1]);
		$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
		$result = $manager->executeBulkWrite('admin.users', $bulk);
	
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
  <style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 25%;
  padding: 10px;
  height: 50px; /* Should be removed. Only for demonstration */
  color: white;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

.fade {
  background-color: none;
 // width: 110px;
  height: 660px;
  overflow: scroll;
}
</style>
</head>
<body class="hold-transition sidebar-mini" id = "sampleDiv_zoom">
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
                <a href="User.php" class="nav-link active">
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
                <a href="assignRecruiter.php" class="nav-link">
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
    <section class="content-header" style = "display:none">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
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
			<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD USER</button>
			<?php } ?>
		   </div>
		   
		   <?php 
			$totalD1Users = array();
			$ActiveD1Users = array();
			$InActiveD1Users = array();
			$totalD2Users = array();
			$ActiveD2Users = array();
			$InActiveD2Users = array();
			$totalD3Users = array();
			$ActiveD3Users = array();
			$InActiveD3Users = array();
			$totalD4Users = array();
			$ActiveD4Users = array();
			$InActiveD4Users = array();
			$Hyderabad = array();  
			$chennai = array();
			$bangalore = array();
			$BangaloreActive = array();
			$BangaloreInActive = array();
			$ChennaiActive = array();
			$ChennnaiInActive = array();
			$HydActive = array();
			$HydInActive = array();
			$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
			$bulk = new MongoDB\Driver\BulkWrite;
			$filter = [ ];							
			$options = [ 'sort' => ['created_date' => -1] ]; //'sort' => ['created_date' => -1]
			$qry = new MongoDB\Driver\Query([]);  //$filter,$options
			$rows = $mongo->executeQuery("admin.users", $qry); // user_id user_name user_level
			$x = 1;
			  foreach ($rows as $row) { 
					if($row->user_level == "D1"){
						array_push($totalD1Users,$row->id);
						if($row->status == "Active"){
							array_push($ActiveD1Users,$row->id);
						}
						if($row->status == "Inactive"){
							array_push($InActiveD1Users,$row->id);
						}
					}
					if($row->user_level == "D2"){
						array_push($totalD2Users,$row->id);
						if($row->status == "Active"){
							array_push($ActiveD2Users,$row->id);
						}
						if($row->status == "Inactive"){
							array_push($InActiveD2Users,$row->id);
						}
					}
					if($row->user_level == "D3"){
						array_push($totalD3Users,$row->id);
						if($row->status == "Active"){
							array_push($ActiveD3Users,$row->id);
						}
						if($row->status == "Inactive"){
							array_push($InActiveD3Users,$row->id);
						}
					}
					if($row->user_level == "D4"){
						array_push($totalD4Users,$row->id);
						if($row->status == "Active"){
							array_push($ActiveD4Users,$row->id);
						}
						if($row->status == "Inactive"){
							array_push($InActiveD4Users,$row->id);
						}
						
						if($row->location == "Bangalore"){
							if($row->status == "Active"){
								array_push($BangaloreActive,$row->location);
							}elseif($row->status == "Inactive"){
								array_push($BangaloreInActive,$row->location);
							}
							array_push($bangalore,$row->location);
						}
						if($row->location == "Hyderabad"){
							if($row->status == "Active"){
								array_push($HydActive,$row->location);
							}elseif($row->status == "Inactive"){
								array_push($HydInActive,$row->location);
							}
							array_push($Hyderabad,$row->location);
						}
						if($row->location == "Chennai"){
							if($row->status == "Active"){
								array_push($ChennaiActive,$row->location);
							}elseif($row->status == "Inactive"){
								array_push($ChennnaiInActive,$row->location);
							}
							array_push($chennai,$row->location);
						}
					}
			  
			  }
		
		   
		   ?>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">		
          <div class="card-header">
		 <!-- <div class="row">
			 <div class="column" style="background-color:#bbb;">
				<h5><span><strong>D1 User Details  </strong></span></h5>			
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total D1 Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($totalD1Users); ?></h6>		
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>D1 Active Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ActiveD1Users); ?></h6>			
			  </div>
			   <div class="column" style="background-color:#aaa;">
				<h6>D1 Inactive Users :  &nbsp;&nbsp;&nbsp;&nbsp; <?php echo count($InActiveD1Users); ?></h6>			
			  </div>
			  
		 </div>
		 <div class="row">
			<div class="column" style="background-color:#bbb;">
				<h5><span><strong>D2 User Details  </strong></span></h5>				
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total D2 Users  : &nbsp;&nbsp;&nbsp; <?php echo count($totalD2Users); ?></h6>		
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>D2 Active Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ActiveD2Users); ?></h6>			
			  </div>
			   <div class="column" style="background-color:#aaa;">
				<h6>D2 Inactive Users : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($InActiveD2Users); ?> </h6>			
			  </div>
			  
		 </div>
		 <div class="row">
			<div class="column" style="background-color:#bbb;">
				<h5><span><strong>D3 User Details  </strong></span></h5>				
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total D3 Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($totalD3Users); ?></h6>		
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>D3 Active Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ActiveD3Users); ?></h6>			
			  </div>
			   <div class="column" style="background-color:#aaa;">
				<h6>D3 Inactive Users : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($InActiveD3Users); ?></h6>			
			  </div>
			   
		 </div>
		 <div class="row">
			<div class="column" style="background-color:#bbb;">
				<h5><span><strong>D4 User Details  </strong></span></h5>				
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total D4 Users  : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($totalD4Users); ?></h6>		
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>D4 Active Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ActiveD4Users); ?></h6>			
			  </div>
			   <div class="column" style="background-color:#aaa;">
				<h6>D4 Inactive Users : &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($InActiveD4Users); ?></h6>			
			  </div>
			  
		 </div>
		  <div class="row"> 
				<div class="column" style="background-color:#bbb;">
				<h5><span><strong>Hyderabad Details  </strong></span></h5>				
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total Hyderabad  : <?php echo count($Hyderabad); ?></h6>		
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>Hyderabad Active: &nbsp;&nbsp;<?php echo count($HydActive); ?></h6>		
			  </div>
			  
			  <div class="column" style="background-color:#aaa;">
				<h6>Hyderabad InActive: &nbsp;&nbsp;&nbsp;<?php echo count($HydInActive); ?></h6>		
			  </div>			   
			   
		 </div>
		 <div class="row"> 
				
			   <div class="column" style="background-color:#bbb;">
				<h5><span><strong>Bangalore Details </strong></span></h5>		
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total Bangalore:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($bangalore); ?></h6>			
			  </div>
			  <div class="column" style="background-color:#bbb;">
				<h6>Bangalore Active: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($BangaloreActive); ?></h6>			
			  </div>
			    <div class="column" style="background-color:#aaa;">
				<h6>Bangalore InActive: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($BangaloreInActive); ?></h6>			
			  </div>
			  
		 </div>
		 
		
			
		 <div class="row"> 
			<div class="column" style="background-color:#bbb;">
				<h5><span><strong>Chennai Details </strong></span></h5>			
			  </div>
			  <div class="column" style="background-color:#aaa;">
				<h6>Total Chennai : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($chennai); ?></h6>			
			  </div>
			 <div class="column" style="background-color:#bbb;">
				<h6>Chennai Active: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ChennaiActive); ?></h6>			
			  </div>
			   <div class="column" style="background-color:#aaa;">
				<h6>Chennai InActive: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo count($ChennnaiInActive); ?></h6>			
			  </div>
			   
		 </div> -->
		 
            <h3 class="card-title">USER LISTINGS</h3>

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
                  <tr><th style="display:none;">
                         Date
                      </th>
                      <th>
                         Sr.No.
                      </th>
                      <th>
                          EMAIL ID
                      </th>
					  <th>
                          EMPLOYEE ID
                      </th>
                      <th>
                         RECRUITER ID
                      </th>
					  <th>
                          USER LEVEL
                      </th>
					   <th>
                          USER STATUS
                      </th>
					  <th>
                          EXTENSION ID
                      </th>
					  <th>
                          DID
                      </th>
					  <th>
                          LOCATION  
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
							$options = [ 'sort' => ['created_date' => -1] ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.users", $qry); // user_id user_name user_level
							$x = 1;
			  foreach ($rows as $row) { 
			  
			  
			  ?>
                  <tr>
				      <td style="display:none;">
                          <?php echo $row->created_date ; ?>
                      </td>
                      <td>
                          <?php echo $x ; ?>
                      </td>
                      <td>
                          <a>
                            <?php echo $row->user_id; ?>
                          </a>
                      </td>
					  <td><?php echo $row->user_id_new; ?></td>
                      <td>
                         <?php echo $row->user_name; ?>
                      </td>
                      <td>
                        <?php echo $row->user_level; ?>
                      </td>
					     <td>
                        <?php echo $row->status; ?>
                      </td>
					  <td>
                        <?php echo $row->extension_id; ?>
                      </td>
					  <td>
                        <?php  echo $row->DID; ?>  
                      </td>
					   <td>
                        <?php echo $row->location; ?>
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
               <?php $x++; }?>
                 
                 
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
						<label for="UserID">Email ID</label>
						<input type="text" class="form-control" id="txt_User_id" name="txt_User_id" >
					</div>
					<div class="form-group">
						<label for="UserID">Employee ID</label>
						<input type="text" class="form-control" id="txt_User_id_new" name="txt_User_id_new" >
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter Fullname</label>
						<input type="text" class="form-control" id="txt_User_fullname" name="txt_User_fullname">
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter ID</label>
						<input type="text" class="form-control" id="txt_User_name" name="txt_User_name">
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter Password</label>
						<input type="text" class="form-control" id="txt_User_password" name="txt_User_password">
					</div>
					<div class="form-group">
					  <label> USER LEVEL</label>
					  <select class="form-control select2" style="width: 100%;" id="txt_User_level" name  = "txt_User_level">
						<option value="">Select</option>
						<option value="SuperAdmin"> Super Admin</option>
						<option value="SuperUserAccess"> Super User Access</option>
						<option value="D1">A1 	 </option>
						<option value="D2">D2     </option>
						<option value="D3">D3     </option>
						<option value="D4">D4     - Recruiters</option> 
					  </select>
					</div>
					
					<div class="form-group">
					  <label> USER STATUS</label>
					  <select class="form-control select2" style="width: 100%;" id="txt_User_status" name = "txt_User_status">
						<option value="">Select</option>
						<option value="Active">Active</option>
						<option value="Inactive">Inactive</option>
						
					  </select>
					</div>
					<div class="form-group">
					  <label> EXTENSION ID</label>
					  <select class="form-control select2" style="width: 100%;" id="txt_extension_id" name = "txt_extension_id">
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
							  foreach ($rowsExtension as $row) {
							  ?>
						<option value = "<?php echo $row->phone_id; ?>" ><?php echo $row->phone_id; ?></option>
			  <?php } ?>
						
					  </select>
					</div>
					
					<div class="form-group">
					  <label> LOCATION</label>
					   <select class="form-control select2" style="width: 100%;" id="txt_location" name = "txt_location">
						<option value="">Select Location</option>
                              <?php
                               date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.Location_DID", $qry); // user_id user_name user_level
							$arr_value= $rows->toArray();
							$array_userId = json_decode(json_encode($arr_value), true);
							$UserLoaction = $array_userId[0]['location'];

							$x = 1;
			  foreach(array_unique(array_column($array_userId, 'location')) AS $arr)
                                   {
                                 ?>
                              <option value="<?php echo "$arr"; ?>"><?php echo "$arr"; ?></option>
                              <?php
								   $x++; }
                                 ?>
					  </select>
					</div>
					
					<div class="form-group">
					
					
					  <label> DID</label>
				
					 <select class="form-control select2" style="width: 100%;" id="txt_editDID" name = "txt_editDID">
					 
					  <option value="">Select DID</option>
					  <?php
					 
                 //$location = $row->location;
 date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [ "location" =>  "$location" ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query($filter,$options);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.Location_DID", $qry); // user_id user_name user_level
							$x = 1;
			  

foreach ($rows as $row) { 
?>
<option value="<?php echo $row->DID; ?>"><?php echo $row->DID; ?></option>
<?php
$x++; 
}

?>
                      </select> 
					 <!-- <input type="text" class="form-control" id="txt_editDID" name = "txt_editDID"> <?php echo $_POST['subject']; ?> -->
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
						<label for="UserID">Email ID</label> 
						<input type="text" class="form-control" id="User_id" name = "User_id"  placeholder="Enter Email ID">
					</div>
					<div class="form-group">
						<label for="UserID">Employee ID</label> 
						<input type="text" class="form-control" id="User_id_new" name = "User_id_new"  placeholder="Enter Employee ID">
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter Fullname</label>
						<input type="text" class="form-control" id="User_fullname" name = "User_fullname" placeholder="Enter User FullName">
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter ID</label>
						<input type="text" class="form-control" id="User_name" name = "User_name" placeholder="Enter User Name">
					</div>
					<div class="form-group">
						<label for="UserName">Recruiter Password</label>
						<input type="text" class="form-control" id="User_password" name = "User_password" placeholder="Enter Password">
					</div>
					<div class="form-group">
					  <label> USER LEVEL</label>
					  <select class="form-control select2" style="width: 100%;" id="User_level" name = "User_level">
						<option value="">Select</option>
						<option value="SuperAdmin"> Super Admin</option>
						<option value="SuperUserAccess"> Super User Access</option>
						<option value="D1">A1 	 </option>
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
					
					<!--<div class="form-group">
					  <label> LOCATION</label>
					  <select class="form-control select2" style="width: 100%;" id="location" name = "location">
						<option value="">Select</option>
						<option value="Chennai">Chennai</option>
						<option value="Hyderabad">Hyderabad</option>
						<option value="Bangalore">Bangalore</option
						
						
						
					  </select>
					</div>
					
					<div class="form-group">
					  <label> DID</label>
					  <input type = "text" name="addDID" value = "addDID" class="form-control select2">
					 <select class="form-control select2" style="width: 100%;" id="addDID" name = "addDID">
						<option value="">Select</option>
						<option value="1234">1234</option>
						<option value="45678">45678</option>
						<option value="9876">9876</option>
					  </select>
					</div>-->
					
					 
                     
                        <div class="form-group">
                           <label for="location">LOCATION</label>
                           <select class="form-control select2" style="width: 100%;" id="location" name = "location">
                              <option value="">Select Location</option>
                              <?php
                                date_default_timezone_set('asia/kolkata');
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = [  ];							
							$options = [  ]; //'sort' => ['created_date' => -1]
							$qry = new MongoDB\Driver\Query([]);  //$filter,$options
							$date = date("Y-m-d H:i:s");
							$rows = $mongo->executeQuery("admin.Location_DID", $qry); // user_id user_name user_level
							$arr_value= $rows->toArray();
							$array_userId = json_decode(json_encode($arr_value), true);
							$UserLoaction = $array_userId[0]['location'];

							$x = 1;
			  foreach(array_unique(array_column($array_userId, 'location')) AS $arr)
                                   {
                                 ?>
                              <option value="<?php echo "$arr"; ?>"><?php echo "$arr"; ?></option>
                              <?php
                                $x++; }
                                 ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="SUBCATEGORY">DID</label>
                           <select class="form-control select2" style="width: 100%;" id="addDID" name = "addDID">
						   <option value="">Select DID</option>
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
					<div class="form-group">
						<label for="UserName">User Name</label>
						<input type="text" class="form-control" id="txt_deleteUser_name" name="txt_deleteUser_name">
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
         $(document).ready(function() {
    $('#location').on('change', function() {
        var category_id = this.value;
		//alert(category_id);
        $.ajax({
            url: "fetch-subcategory-by-category.php",
            type: "POST",
            data: {
                category_id: category_id
            },
            cache: false,
            success: function(result) {
                $("#addDID").html(result);
				
				
            }
        });
    });
});

</script>

<script>

$(document).ready(function() {
    $('#txt_location').on('change', function() {
        var category_id = this.value;
		//alert(category_id);
        $.ajax({
            url: "fetch-subcategory-by-category.php",
            type: "POST",
            data: {
                category_id: category_id
            },
            cache: false,
            success: function(result) {
				//alert(result);
               $("#txt_editDID").html(result);
				
            }
        });
    });
}); 



 </script>
 
<script>
  $(function () {
  
  $('#table_camp').dataTable({
	    "order": [[ 0, "desc" ]]
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
			document.getElementById('txt_User_id_new').value=res[8];
			document.getElementById('txt_User_fullname').value=res[9];
			//document.getElementById('txt_editDID').value=res[10];
			
			  $.ajax({
            url: "fetch-subcategory-by-category.php",
            type: "POST",
            data: {
                category_id: res[6]
            },
            cache: false,
            success: function(result) {
				//alert(result);
               $("#txt_editDID").html(result);
				
            }
        });
			
			

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
		document.getElementById('deleteUserID').value=val_grpd;
		document.getElementById('txt_deleteUser_id').value=UserIDVal;
		document.getElementById('txt_deleteUser_name').value=UserNameVal;
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
