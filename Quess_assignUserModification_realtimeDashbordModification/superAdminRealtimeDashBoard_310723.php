
<?php    

session_start();

$loggedInuserName  = $_SESSION['username'] ;
$loggedInPassword  = $_SESSION['pass'];
$loggedInUserLevel = $_SESSION['user_level']; 


if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/quessAdmin/pages/Agent/quessLogin/index.php');
}


    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d1_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 	
	$exD1_User =  $rowsD1_User->toArray();
	$D1_UserArray = json_decode(json_encode($exD1_User), true);

   if($D1_UserArray[0]['d1_level'] != ''){
	   $D1UserLoggedIn = $D1_UserArray[0]['d1_level'];  
   }
   $D2UsersUnderD1Value = array();
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d1_level' => "$loggedInuserName" ];							
	$options = [  ];  
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 
	foreach( $rowsD1_User as $rowD2){
			array_push($D2UsersUnderD1Value,$rowD2->d2_level);		
		}
			$ArrayToString = implode("**",array_unique($D2UsersUnderD1Value));
			$D2UsersUnderD1 = explode("**",$ArrayToString);

   ///D2_User	
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d2_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsD2_User = $mongo->executeQuery("admin.user_assign", $qry); 



	$exD2_User =  $rowsD2_User->toArray();
	$D2_UserArray = json_decode(json_encode($exD2_User), true);

	 if($D2_UserArray[0]['d2_level'] != ''){
	   $D2UserLoggedIn = $D2_UserArray[0]['d2_level'];
	    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
		$bulk = new MongoDB\Driver\BulkWrite;
		$filter = ['d2_level' => "$loggedInuserName" ];							
		$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
		$qry = new MongoDB\Driver\Query($filter,$options);
		$rowsD2_User = $mongo->executeQuery("admin.user_assign", $qry); 
		$D3UsersUnderD2Value = array();
		foreach( $rowsD2_User as $rowD3){
			array_push($D3UsersUnderD2Value,$rowD3->d3_level);		
		}
			$ArrayToString = implode(" ",array_unique($D3UsersUnderD2Value));
	$D3UsersUnderD2 = explode(" ",$ArrayToString);
	   
	  // echo "<div align = 'center'><b>D3 Can Only Allow In This Page</b>&nbsp;&nbsp;&nbsp;<a href=\"index.php\" class=\"nav-link\">Home</a></div>";exit;
   }
	
	 ///D3_User	

    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['d3_level' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsD3_User = $mongo->executeQuery("admin.user_assign", $qry); 
	$exD3_User =  $rowsD3_User->toArray();
	$D3_UserArray = json_decode(json_encode($exD3_User), true);
	
	
	 if($D3_UserArray[0]['d3_level'] != ''){
	   $D3UserLoggedIn = $D3_UserArray[0]['d3_level'];
	  // echo "D3 user ".$D3UserLoggedIn;exit;
   }
   
   
   #### super admin checking 
   //echo $loggedInuserName;exit;
   $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = ['user_name' => "$loggedInuserName" ];							
	$options = [  ];  //'sort' => ['created_date' => -1],'limit'=> 1
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsSuper_User = $mongo->executeQuery("admin.users", $qry); 
	$exSuper_User =  $rowsSuper_User->toArray();
	$Super_UserArray = json_decode(json_encode($exSuper_User), true);
   
   if($Super_UserArray[0]['user_level']  == "SuperAdmin"){
	  $superAdminLoginUser = $Super_UserArray[0]['user_name'];
   }
  
    $D1UsersUnderSuperAdminValue = array();	
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
	$bulk = new MongoDB\Driver\BulkWrite;
	$filter = [ ];							
	$options = [  ];  
	$qry = new MongoDB\Driver\Query($filter,$options);
    $rowsD1_User = $mongo->executeQuery("admin.user_assign", $qry); 
	foreach( $rowsD1_User as $rows){
			$UserName = $rows->d1_level;
			
                                                        		
														$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
															$bulk = new MongoDB\Driver\BulkWrite;
															$filter = [ "status"=>"Active","user_name"=> "$UserName","user_level"=>"D1"];							
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
		
	//Vertical Name 	
		$D1UsersVerticalNameValue = array();
															$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
															$bulk = new MongoDB\Driver\BulkWrite;
															$filter = [ ];							
															$options = [  ]; 
															$qry = new MongoDB\Driver\Query($filter,$options);
															$rowsD1_verticalName = $mongo->executeQuery("admin.vertical", $qry); 
															foreach( $rowsD1_verticalName as $rowD1Vertical){
			                                               array_push($D1UsersVerticalNameValue,$rowD1Vertical->vertical_name);		
		                                                        }
			                                           $ArrayToString1 = implode("**",array_unique($D1UsersVerticalNameValue));
			                                               $VerticalNameVal = explode("**",$ArrayToString1);
														   
														   $D1Vertical_Count = count($VerticalNameVal);
					
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  
  
  
  
  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
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
	
	
    .tableFixHead {
      width: 100%;
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

  <style>

.center {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #000;
}
.wave {
  width: 5px;
  height: 100px;
  background: linear-gradient(45deg, cyan, #fff);
  margin: 10px;
  animation: wave 1s linear infinite;
  border-radius: 20px;
}
.wave:nth-child(2) {
  animation-delay: 0.1s;
}
.wave:nth-child(3) {
  animation-delay: 0.2s;
}
.wave:nth-child(4) {
  animation-delay: 0.3s;
}
.wave:nth-child(5) {
  animation-delay: 0.4s;
}
.wave:nth-child(6) {
  animation-delay: 0.5s;
}
.wave:nth-child(7) {
  animation-delay: 0.6s;
}
.wave:nth-child(8) {
  animation-delay: 0.7s;
}
.wave:nth-child(9) {
  animation-delay: 0.8s;
}
.wave:nth-child(10) {
  animation-delay: 0.9s;
}

@keyframes wave {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
</style>
</head>
<body class="hold-transition  sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed"  style = "margin-top: -3pc" id = "sampleDiv_zoom">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="dist/img/logo.png" alt="AdminLogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light" > <!--main-header navbar navbar-expand navbar-dark  style = "top:41px !important"   -->
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
	   
      <li class="nav-item d-none d-sm-inline-block">
		  <a href="index.php" class="nav-link">Home</a>
      </li>
	  <li class="nav-item d-none d-sm-inline-block">
		  <span onclick = "makeItEmptyValue('<?php echo $superAdminLoginUser; ?>');" style = "cursor:pointer;color:#007bff" class="nav-link"><?php echo $superAdminLoginUser; ?></span>
      </li>
	  <li class="nav-item d-none d-sm-inline-block">
		 <div align = "center" class="nav-link" style = "color: skyblue;margin-left: 26pc"><b>SUPER ADMIN REAL TIME DASHBOARD</b> </div>
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

      
          
      </li>
      <!-- Notifications Dropdown Menu -->
    
      <li class="nav-item" style = "display:none">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
	   <li class="nav-item">
	    <a class="nav-link" onclick = "agentLiveData();" title="Refresh" role="button"><i class="fas fa-sync-alt" style="color:green;"></i></a>
      </li>
	  <li class="nav-item">
	    <a class="nav-link" href="logout.php" title="Signout" role="button"><i class="fas fa-sign-out-alt" style="color:red;"></i></a>
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
    <a href="index.php" class="brand-link">
      <img src="dist/img/logo.png" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Quess</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $D1UserLoggedIn; ?></a>
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
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Overview</p>
                </a>
              </li>
          
              <li class="nav-item">
                <a href="./index3.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Graphical</p>
                </a>
              </li>
			  
			  
            </ul>
          </li>
		  
		  
		  		<li class="nav-item">
            <a href="DID_DashBoard.php" class="nav-link">
			  <i class="fas fa-tty"></i>
              <p>
                DID Dashboard
                <!--<i class="right fas fa-angle-left"></i>-->
              </p>
            </a>
           
          </li>
        
       <!--## -->
       
        
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Administration
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/forms/Campaign.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Campaign</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="pages/forms/Ingroup.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ingroup</p>
                </a>
              </li>
			  	<li class="nav-item">
                <a href="pages/forms/DID_Create.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Location & DID</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="pages/forms/User.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
			   <li class="nav-item">
                <a href="pages/forms/User_active_inactive.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active & Inactive User Count</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="pages/forms/Phone.php" class="nav-link">
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
                <a href="pages/forms/assignRecruiter.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign User</p>
                </a>
              </li>
			  
			    <li class="nav-item">
                <a href="pages/forms/report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Report</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="pages/forms/addBanner.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Banner</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="pages/forms/verticalHead.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vertical</p>
                </a>
              </li>
			  <?php if($loggedInUserLevel == "SuperAdmin"){ ?>
			  <li class="nav-item">
                <a href="../telephony_server_performance/welcome2.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Optimiser</p>
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
  <div class="content-wrapper" id = "content_loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Overview</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><button style = "background-color: green;border-radius: 20px; color: white;" type ="button" onclick = "refreshData()"><i class="fas fa-sync-alt"></i></button></span></li>
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Overview</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" id = "content_loading">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-6 col-sm-6 col-md-2" >
            <div class="info-box">
              <span class="info-box-icon bg-danger elevation-1" style = "display:none1"><i class="fas fa-users"></i></span>  <!--  style = "color: green;font-size: 29px;"-->

              <div class="info-box-content">
                <span class="info-box-text">Team Count</span>
                <span class="info-box-number" id = "TeamCount"> 0              
                  <!--<small>%</small>-->
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon  elevation-1" style = "display:none1;background-color:green"><i class="fas fa-user" style = "color: white;font-size: 29px;"></i></span> <!-- fas fa-headphones-alt-->

              <div class="info-box-content" >
                <span class="info-box-text">Login</span>
                <span style="cursor:pointer" data-toggle="modal" data-target="#myModal"><span class="info-box-number" id = "Login">0</span></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1" style = "display:none1"><i class="fas fa-user" style = "color: white;font-size: 29px;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Not Login</span>   
               <span style="cursor:pointer" data-toggle="modal" data-target="#myModal_LogoutData"> <span class="info-box-number" id = "NotLogin">0</span></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:blue"><i class="fas fa-phone-alt" style = "color: white;font-size: 29px;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Calls</span> 
                <span class="info-box-number" id = "TotalCalls">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		  <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:gold"><i class="fa fa-check" style = "color: white;font-size: 29px;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Connected Calls</span> 
                <span class="info-box-number" id = "connectedCalls">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		  <br>
		    <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:green"><i class="far fa-clock" style = "color: white;font-size: 29px;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Talk Time</span> 
                <span class="info-box-number" id = "TotalTalkTime">00:00:00</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		    <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:yellow"><i class="fas fa-headphones-alt" style = "color: white;font-size: 29px;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">On Calls</span> 
                <span class="info-box-number" id = "OnCalls">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		    <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:green;color:white"><i class="fas fa-play"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Available For Calls</span> 
                <span style="cursor:pointer" data-toggle="modal" data-target="#myModal_AvailableForCalls"><span class="info-box-number" id = "AvailableForCalls">0</span> </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		    <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:yellow;color:white"><i class="far fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Time/Rec/Day</span> 
                <span class="info-box-number" id = "Time_Rec_Day">0</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		
        </div>
        <!-- /.row -->
		<hr>
		<div class="row">
		
		<?php
		
														
							$color = array('3d9970','36457E','82f5aa','ff851b','007bff','adfc03','03b5fc','9d03fc','fc037b');
		for($n=0;$n<count($VerticalNameVal);$n++){
			
							$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");    
							$bulk = new MongoDB\Driver\BulkWrite;
							$filter = ['vertical_name' => "$VerticalNameVal[$n]"];							
							$options = [ ]; //'sort' => ['created_date' => -1] 
							$qry = new MongoDB\Driver\Query($filter,$options);						
							$duplicateVertical = $mongo->executeQuery("admin.vertical", $qry);
							$checkingDuplicateVertical1 = $duplicateVertical->toArray();
							$checkingDuplicateVertical = json_decode(json_encode($checkingDuplicateVertical1), true);
							
							$D1Vertical_headname = $checkingDuplicateVertical[0]['vertical_headname'];
							$D1Vertical_Name = $checkingDuplicateVertical[0]['vertical_name'];
							
									
		?>

		  <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style = "display:none1;background-color:#<?php echo $color[$n]; ?>;color:white"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text" style = "cursor:pointer" onclick = "d1UsersVertical('<?php echo $D1Vertical_headname; ?>','<?php echo $color[$n]; ?>','<?php echo $n;?>','<?php echo $D1Vertical_Name; ?>');"><b><?php echo $VerticalNameVal[$n];?></b></span> 
			  </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
		 <?php }
				?>
		</div>

		<hr>
		<div class="row" id = "D1BaseOnVerticalName">
		<input type = "hidden" value = "" id = "d3UsersUnderD2ID"> 
		<input type = "hidden" value = "" id = "d2UsersUnderD1ID">
		<input type = "hidden" value = "" id = "d1UsersUnderD2ID">
		</div>
	<!-- D3 Users -->

		<hr>
		<div class="row" id = "D2UsersUnderD2AndD1"></div>
		<hr>
		<div class="row" id = "D3UsersUnderD2AndD1"></div>
		
		

        <div class="row" style="display:none;">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Monthly Recap Report</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <a href="#" class="dropdown-item">Action</a>
                      <a href="#" class="dropdown-item">Another action</a>
                      <a href="#" class="dropdown-item">Something else here</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">Separated link</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                      Add Products to Cart
                      <span class="float-right"><b>160</b>/200</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      Complete Purchase
                      <span class="float-right"><b>310</b>/400</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Visit Premium Page</span>
                      <span class="float-right"><b>480</b>/800</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Send Inquiries
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">$35,210.43</h5>
                      <span class="description-text">TOTAL REVENUE</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                      <h5 class="description-header">$10,390.90</h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-12">
            <!-- MAP & BOX PANE -->
            <div class="card" style="display:none;">
              <div class="card-header">
                <h3 class="card-title">US-Visitors Report</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="d-md-flex">
                  <div class="p-1 flex-fill" style="overflow: hidden">
                    <!-- Map will be created here -->
                    <div id="world-map-markers" style="height: 325px; overflow: hidden">
                      <div class="map"></div>
                    </div>
                  </div>
                  <div class="card-pane-right bg-success pt-2 pb-2 pl-4 pr-4">
                    <div class="description-block mb-4">
                      <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                      <h5 class="description-header">8390</h5>
                      <span class="description-text">Visits</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block mb-4">
                      <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                      <h5 class="description-header">30%</h5>
                      <span class="description-text">Referrals</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block">
                      <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                      <h5 class="description-header">70%</h5>
                      <span class="description-text">Organic</span>
                    </div>
                    <!-- /.description-block -->
                  </div><!-- /.card-pane-right -->
                </div><!-- /.d-md-flex -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="row">
              <div class="col-md-6" style="display:none;">
                <!-- DIRECT CHAT -->
                <div class="card direct-chat direct-chat-warning">
                  <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                      <span title="3 New Messages" class="badge badge-warning">3</span>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                        <i class="fas fa-comments"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages">
                      <!-- Message. Default to the left -->
                      <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-left">Alexander Pierce</span>
                          <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                          Is this template really for free? That's unbelievable!
                        </div>
                        <!-- /.direct-chat-text -->
                      </div>
                      <!-- /.direct-chat-msg -->

                      <!-- Message to the right -->
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-right">Sarah Bullock</span>
                          <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                          You better believe it!
                        </div>
                        <!-- /.direct-chat-text -->
                      </div>
                      <!-- /.direct-chat-msg -->

                      <!-- Message. Default to the left -->
                      <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-left">Alexander Pierce</span>
                          <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                          Working with AdminLTE on a great new app! Wanna join?
                        </div>
                        <!-- /.direct-chat-text -->
                      </div>
                      <!-- /.direct-chat-msg -->

                      <!-- Message to the right -->
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-right">Sarah Bullock</span>
                          <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                          I would love to.
                        </div>
                        <!-- /.direct-chat-text -->
                      </div>
                      <!-- /.direct-chat-msg -->

                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <div class="direct-chat-contacts">
                      <ul class="contacts-list">
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                Count Dracula
                                <small class="contacts-list-date float-right">2/28/2015</small>
                              </span>
                              <span class="contacts-list-msg">How have you been? I was...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                Sarah Doe
                                <small class="contacts-list-date float-right">2/23/2015</small>
                              </span>
                              <span class="contacts-list-msg">I will be waiting for...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                Nadia Jolie
                                <small class="contacts-list-date float-right">2/20/2015</small>
                              </span>
                              <span class="contacts-list-msg">I'll call you back at...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                Nora S. Vans
                                <small class="contacts-list-date float-right">2/10/2015</small>
                              </span>
                              <span class="contacts-list-msg">Where is your new...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                John K.
                                <small class="contacts-list-date float-right">1/27/2015</small>
                              </span>
                              <span class="contacts-list-msg">Can I take a look at...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                        <li>
                          <a href="#">
                            <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                Kenneth M.
                                <small class="contacts-list-date float-right">1/4/2015</small>
                              </span>
                              <span class="contacts-list-msg">Never mind I found...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                          </a>
                        </li>
                        <!-- End Contact Item -->
                      </ul>
                      <!-- /.contacts-list -->
                    </div>
                    <!-- /.direct-chat-pane -->
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <form action="#" method="post">
                      <div class="input-group">
                        <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                        <span class="input-group-append">
                          <button type="button" class="btn btn-warning">Send</button>
                        </span>
                      </div>
                    </form>
                  </div>
                  <!-- /.card-footer-->
                </div>
                <!--/.direct-chat -->
              </div>
              <!-- /.col -->

              <div class="col-md-6" style="display:none;">
                <!-- USERS LIST -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Latest Members</h3>

                    <div class="card-tools">
                      <span class="badge badge-danger">8 New Members</span>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <ul class="users-list clearfix">
                      <li>
                        <img src="dist/img/user1-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander Pierce</a>
                        <span class="users-list-date">Today</span>
                      </li>
                      <li>
                        <img src="dist/img/user8-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Norman</a>
                        <span class="users-list-date">Yesterday</span>
                      </li>
                      <li>
                        <img src="dist/img/user7-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Jane</a>
                        <span class="users-list-date">12 Jan</span>
                      </li>
                      <li>
                        <img src="dist/img/user6-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">John</a>
                        <span class="users-list-date">12 Jan</span>
                      </li>
                      <li>
                        <img src="dist/img/user2-160x160.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Alexander</a>
                        <span class="users-list-date">13 Jan</span>
                      </li>
                      <li>
                        <img src="dist/img/user5-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Sarah</a>
                        <span class="users-list-date">14 Jan</span>
                      </li>
                      <li>
                        <img src="dist/img/user4-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Nora</a>
                        <span class="users-list-date">15 Jan</span>
                      </li>
                      <li>
                        <img src="dist/img/user3-128x128.jpg" alt="User Image">
                        <a class="users-list-name" href="#">Nadia</a>
                        <span class="users-list-date">15 Jan</span>
                      </li>
                    </ul>
                    <!-- /.users-list -->
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer text-center">
                    <a href="javascript:">View All Users</a>
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!--/.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Recruiter Information Of &nbsp;<span style = "color: blue" id = "displayUserDetails"><?php echo $D1UserLoggedIn; ?></span></h3>

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
              <div class="card-body p-0">
                <div class="table-responsive" id="realtimeData">
				</div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix" style="display:none;">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-md-4">
           
           
          
             
			
            <div class="card" style="display:none;">
              <div class="card-header"> 
                <h3 class="card-title">Browser Usage</h3> 

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="chart-responsive">
                      <canvas id="pieChart" height="150"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-danger"></i> Chrome</li>
                      <li><i class="far fa-circle text-success"></i> IE</li>
                      <li><i class="far fa-circle text-warning"></i> FireFox</li>
                      <li><i class="far fa-circle text-info"></i> Safari</li>
                      <li><i class="far fa-circle text-primary"></i> Opera</li>
                      <li><i class="far fa-circle text-secondary"></i> Navigator</li>
                    </ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      United States of America
                      <span class="float-right text-danger">
                        <i class="fas fa-arrow-down text-sm"></i>
                        12%</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      India
                      <span class="float-right text-success">
                        <i class="fas fa-arrow-up text-sm"></i> 4%
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      China
                      <span class="float-right text-warning">
                        <i class="fas fa-arrow-left text-sm"></i> 0%
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
            <!-- /.card -->

            <!-- PRODUCT LIST -->
            <div class="card" style="display:none;">
              <div class="card-header">
                <h3 class="card-title">Recently Added Products</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Samsung TV
                        <span class="badge badge-warning float-right">$1800</span></a>
                      <span class="product-description">
                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Bicycle
                        <span class="badge badge-info float-right">$700</span></a>
                      <span class="product-description">
                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">
                        Xbox One <span class="badge badge-danger float-right">
                        $350
                      </span>
                      </a>
                      <span class="product-description">
                        Xbox One Console Bundle with Halo Master Chief Collection.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">PlayStation 4
                        <span class="badge badge-success float-right">$399</span></a>
                      <span class="product-description">
                        PlayStation 4 500GB Console (PS4)
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2021-2023</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 4.1-rc
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<!--<script src="customjs/custom.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/pages/dashboard2.js"></script>

<script src = "https://code.jquery.com/jquery-3.5.1.js"></script> 
<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script>

var loginUser = '<?php echo $D1UserLoggedIn; ?>';
	
		function agentLiveData(){  
			var d1UsersUnderD2ValueFromInput = 	document.getElementById('d1UsersUnderD2ID').value;
			var d2UsersUnderD1ValueFromInput = 	document.getElementById('d2UsersUnderD1ID').value;
			var d3UsersUnderD2ValueFromInput = 	document.getElementById('d3UsersUnderD2ID').value;	
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				 var result1 =  this.responseText;	
				//	alert(result1);
					//console.log(result1);
					var result = result1.split("**");
							$('#realtimeData').html(result[0]);  //
							$('#TeamCount').html(result[1]); 
							$('#Login').html(result[2]); 
							$('#NotLogin').html(result[3]); 
							$('#TotalCalls').html(result[4]); 
							$('#OnCalls').html(result[5]); 
							$('#AvailableForCalls').html(result[6]);  
							$('#TotalTalkTime').html(result[7]);   
							$('#LoginUserData').html(result[8]); 
							$('#NotLoginUserData').html(result[9]);  
							$('#AvailableForCallsDiv').html(result[10]);
							$('#Time_Rec_Day').html(result[11]); 
							$('#connectedCalls').html(result[12]);
							$('#table_agentLog').dataTable();	
							$('#loginUserId').dataTable();	  	
							$('#NotloginUserId').dataTable();  
							$('#AvailableForCallsTable').dataTable();
			 }
		  };
		  xhttp.open("GET", "ajax/superAdmin.php?loginUser="+loginUser+"&d3UsersUnderD2ValueFromInput="+d3UsersUnderD2ValueFromInput+"&d2UsersUnderD1ValueFromInput="+d2UsersUnderD1ValueFromInput+"&d1UsersUnderD2ValueFromInput="+d1UsersUnderD2ValueFromInput, true);
		  xhttp.send();
		}
		agentLiveData();
		setInterval(function(){agentLiveData()},60000);
		
		


	

function refreshData(){
	callsInformation();
	overViewInfo();
	agentLiveData();
	//alert("test");
}

function D3UsersUnderD2AndD1(d3users,d3_id,D3_Count){
	
	//alert(d3_id);
	//alert(D3_Count);
	
	for(let i = 0; i < D3_Count ; i++ ){
		if(d3_id == i){
			document.getElementById('D3User_'+d3_id).style.color = '#e65c00';
			document.getElementById('D3User_'+d3_id).style.fontWeight = "1000";
		}else{
			document.getElementById('D3User_'+i).style.color = 'black';
			document.getElementById('D3User_'+i).style.fontWeight = "400";
		}
	}
	//alert(d3users);
	document.getElementById('d3UsersUnderD2ID').value = d3users;   //d1UsersUnderD2ID,d2UsersUnderD1ID,d3UsersUnderD2ID
	document.getElementById('displayUserDetails').innerHTML = d3users;
	agentLiveData();
}


function d1UsersVertical(d1userVal,colorCodepar,numberVal,d1VertivalName){
	
	
	var BaseonverticalD1Val = d1userVal;
	
	var colorCode = colorCodepar;
	
	var loginUserD1 = '<?php echo $D1UserLoggedIn; ?>';
	
	var D1VerticalName = d1VertivalName;
	
	//alert(D1VerticalName);
	
	 const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {
		// alert(this.responseText);
		document.getElementById("D3UsersUnderD2AndD1").innerHTML ='';
		document.getElementById("D2UsersUnderD2AndD1").innerHTML = '';
		document.getElementById("D1BaseOnVerticalName").innerHTML = this.responseText;
		
		}
	  xhttp.open("GET", "ajax/d1UseDisplayBaseonVertical.php?BaseonverticalD1Val="+BaseonverticalD1Val+"&loginUserD1="+loginUserD1+"&colorCode="+colorCode+"&number="+numberVal+"&D1VerticalName="+D1VerticalName, true);
	  xhttp.send();
	
	
	
	
}	

function d1UsersUnderSuperAdmin(d1users,colorCodepar,number,D1_Count,VerticalNameD1){   
	
	document.getElementById('d2UsersUnderD1ID').value = '';
	document.getElementById('d3UsersUnderD2ID').value = ''; 
	
	//alert(VerticalNameD1);
	//var D1_Count = "<?=$D1_Count;?>";
	
	for(let i = 0; i < D1_Count ; i++ ){
		if(number == i){
			document.getElementById('D1User_'+number).style.color = '#e65c00';
			document.getElementById('D1User_'+number).style.fontWeight = "1000";
		}else{
			document.getElementById('D1User_'+i).style.color = 'black';
			document.getElementById('D1User_'+i).style.fontWeight = "400";
		}
	}
	
	var loginUserD1 = '<?php echo $D1UserLoggedIn; ?>';
	
	var colorCode = colorCodepar;
	
	var VerticalNameD1ToD2 = VerticalNameD1;
	
	 const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {
		 // alert(this.responseText);
		  document.getElementById("D3UsersUnderD2AndD1").innerHTML ='';
		document.getElementById("D2UsersUnderD2AndD1").innerHTML = this.responseText;
		}
	  xhttp.open("GET", "ajax/d1UserForDisplayForSuperAdmin.php?D1userUderSuperAdmin="+d1users+"&loginUserD1="+loginUserD1+"&colorCode="+colorCode+"&VerticalNameD1ToD2="+VerticalNameD1ToD2, true);
	  xhttp.send();
	  
	document.getElementById('d1UsersUnderD2ID').value = d1users;   
	document.getElementById('displayUserDetails').innerHTML = d1users;
	agentLiveData();
	//alert("D1 clicked");
}   //



function D2UsersUnderD1AndSuperAdmin(d2users,colorCodepar,id,D2_Count,VerticalNameD2){    
	document.getElementById('d3UsersUnderD2ID').value = ''; 
	document.getElementById('d2UsersUnderD1ID').value  = '';
	
	for(let i = 0; i < D2_Count ; i++ ){
		if(id == i){
			document.getElementById('D2User_'+id).style.color = '#e65c00';
			document.getElementById('D2User_'+id).style.fontWeight = "1000";
		}else{
			document.getElementById('D2User_'+i).style.color = 'black';
			document.getElementById('D2User_'+i).style.fontWeight = "400";
		}
	}
	
	var loginUserD1 = '<?php echo $D1UserLoggedIn; ?>';
	var D1UserName = document.getElementById('d1UsersUnderD2ID').value;
	//alert(VerticalNameD2);
	var colorCode = colorCodepar;
	var VerticalNameD2ToD3 = VerticalNameD2;
	
	 const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {
		document.getElementById("D3UsersUnderD2AndD1").innerHTML = this.responseText;
		}
	  xhttp.open("GET", "ajax/d2UserForDisplayForSuperAdmin.php?D2userUderSuperAdmin="+d2users+"&loginUserD1="+D1UserName+"&colorCode="+colorCode+"&VerticalNameD2ToD3="+VerticalNameD2ToD3, true);
	  xhttp.send();
	  
	document.getElementById('d2UsersUnderD1ID').value = d2users;  
	document.getElementById('displayUserDetails').innerHTML = d2users;
	//blink(id);
	agentLiveData();
}
/*function blink(id){
	//alert('D2User_'+id);
	$('#D2User_'+id).fadeOut(500).fadeIn(500, blink);
} */

function makeItEmptyValue(D2loginUser){
	document.getElementById("D3UsersUnderD2AndD1").innerHTML = '';
	document.getElementById("D2UsersUnderD2AndD1").innerHTML = '';
	document.getElementById('d3UsersUnderD2ID').value = '';
	document.getElementById('d2UsersUnderD1ID').value = '';
	document.getElementById('d1UsersUnderD2ID').value = '';
	document.getElementById('displayUserDetails').innerHTML = D2loginUser;
	agentLiveData();
}

</script>
  <script>
  $(document).ready(function(){
	
   sampleDiv_zoom.style.zoom='80%';
	var scale = 'scale(1)';
	document.body.style.webkitTransform = scale;  // Chrome, Opera, Safari
	document.body.style.msTransform = scale;     // IE 9
	document.body.style.transform = scale;     // General
  });
	
  </script>
	<!-- The Modal -->
  <div class="modal fade" id="myModal_addScript" style="opacity: 3;top : 104px !important">
    
  </div>
  
  <div class="modal fade" id="myModal" role="dialog" style="margin-left: -13%;">
    <div class="modal-dialog"  style="overflow-y: initial !important">
    
      <!-- Modal content-->
      <div class="modal-content" style="width: 205%;">
        <div class="modal-header">
      <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">LOGIN USERS</h4>
        </div>
        <div class="modal-body" style="height: 650px; overflow-y: auto;" id = "LoginUserData">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
   <div class="modal fade" id="myModal_LogoutData" role="dialog" style="margin-left: -13%;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="width: 205%;">
        <div class="modal-header">
      <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">NOT LOGIN USERS</h4>
        </div>
        <div class="modal-body"  style="height: 650px; overflow-y: auto;" id = "NotLoginUserData">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
   <div class="modal fade" id="myModal_AvailableForCalls" role="dialog" style="margin-left: -13%;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="width: 205%;">
        <div class="modal-header">
      <!--    <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title"> AVAILABLE FOR CALLS</h4>
        </div>
        <div class="modal-body" style="height: 650px; overflow-y: auto;" id = "AvailableForCallsDiv">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</body>
</html>
