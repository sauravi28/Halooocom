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
/*if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/quessAdmin/pages/Agent/quessLogin/index.php');
}*/
require_once("db_connect.php");

$stmt_select="SELECT * from features_settings";
	      $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 	
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
				  $incoming_did = $row["incoming_did"];
				  $incoming_ivr = $row["incoming_ivr"];
				  $predictive_dialing = $row["predictive_dialing"];
				  $userwise_dialing = $row["userwise_dialing"];
				  $voice_blast = $row["voice_blast"];
				  $announcement = $row["announcement"];
				  $star_month = $row["star_month"];
				  $dependant_dropdown = $row["dependant_dropdown"];
				  $feedback_ivr = $row["feedback_ivr"];
				  $phone_book = $row["phone_book"];
			   }
			   
	if($_POST['addUser']){
		
		    $User_id = $_POST['User_id'];
			$User_name = $_POST['User_name'];
			$User_level = $_POST['User_level'];
			$User_status = $_POST['User_status']; 
			$User_password = $_POST['User_password'];
			$extensionIdPhone = $_POST['extensionIdPhone']; 
			$extensionPassword = $_POST['extensionPassword'];
		    $campaign = $_POST['userWorkType']; 
			$conf_number = $_POST['conf_num']; 
			$ingroup = $_POST['ingroup'];
			$ingroupValue = implode(",", $ingroup);
		    $work_type = $_POST['work_type'];
			
		$stmt_rs="SELECT user_id from users where user_id='$User_id' or extension_id='$extensionIdPhone';";
	                           $rslt_rs= mysqli_query($conn,$stmt_rs);
	                           $row_rs= mysqli_fetch_row($rslt_rs);
							   
							if(count($row_rs) == 0 ){			
									
					$date = date("Y-m-d H:i:s");
					$stmt_insert="INSERT INTO users(created_date,user_id,user_name,user_level,status,user_password,extension_id,extension_password,Campaign,conference_number,ingroup,work_type)values('$date','$User_id','$User_name','$User_level','$User_status','$User_password','$extensionIdPhone','$extensionPassword','$campaign','$conf_number','$ingroupValue','$work_type')";
					$rslt_insert= mysqli_query($conn,$stmt_insert);
					// echo "<script>alert('Campaign has been created successfully.');</script>";
					
					exec("/usr/bin/curl -k 'http://192.168.3.15/API/backAPI/createuser.php?phone_id=$extensionIdPhone&secret=$extensionPassword'");
	                          
							}else{
								echo "<script>alert('User id or Extension already exist.');</script>";
							}
	
			
	}
	
	
	if($_POST['uploadUsers']){
		
		// Allowed mime types
					$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
					
					// Validate whether selected file is a CSV file
					if(!empty($_FILES['uploadfile']['name']) && in_array($_FILES['uploadfile']['type'], $csvMimes)){
						
						// If the file is uploaded
						if(is_uploaded_file($_FILES['uploadfile']['tmp_name'])){
							
							// Open uploaded CSV file with read-only mode
							$csvFile = fopen($_FILES['uploadfile']['tmp_name'], 'r');
							
							// Skip the first line
							fgetcsv($csvFile);
							
							// Parse data from CSV file line by line
							while(($line = fgetcsv($csvFile)) !== FALSE){
								// Get row data
							
            $AUser_id = $line[0];
			$AUser_name = $line[1];
			$AUser_level = $line[2];
			$AUser_status = $line[3];
			$AUser_password = $line[4];
			$AextensionIdPhone = $line[5]; 
			$AextensionPassword = $line[6];
		    $Acampaign = $line[7];
			$Aconf_number = $line[8];
			
			$Astmt_rs="SELECT user_id from users where user_id='$AUser_id' or extension_id='$AextensionIdPhone';";
	                           $Arslt_rs= mysqli_query($conn,$Astmt_rs);
	                           $Arow_rs= mysqli_fetch_row($Arslt_rs);
							   
							if(count($Arow_rs) == 0 ){	
							
			$date = date("Y-m-d H:i:s");
              $stmt_upload="INSERT INTO users(created_date,user_id,user_name,user_level,status,user_password,extension_id,extension_password,Campaign,conference_number)values('$date','$AUser_id','$AUser_name','$AUser_level','$AUser_status','$AUser_password','$AextensionIdPhone','$AextensionPassword','$Acampaign','$Aconf_number')";
					$rslt_upload= mysqli_query($conn,$stmt_upload);
								
			   exec("/usr/bin/curl -k 'http://192.168.3.15/API/backAPI/createuser.php?phone_id=$AextensionIdPhone&secret=$AextensionPassword'");
	                		
						}else{
								echo "<script>alert('UserID or Extension already exist.');</script>";
							}
	
								
							}


							// Close opened CSV file
							fclose($csvFile);
							
							//$qstring = '?status=succ';
							echo "<script>alert('Data uploaded successfully');</script>";
						}else{
							$qstring = '?status=err';
						}
					}else{
						//$qstring = '?status=invalid_file';
						echo "<script>alert('Invalid file format(Please upload csv format)');</script>";
					}
					
	}
	
	
	if($_POST['editUser']){  
			$txt_User_id = $_POST['txt_User_id'];
			$txt_User_name = $_POST['txt_User_name'];
			$txt_User_level = $_POST['txt_User_level'];
			$txt_User_status = $_POST['txt_User_status'];
			$txt_User_password = $_POST['txt_User_password']; 
			$txt_extension_id = $_POST['txt_extension_id'];
			$txt_extensionPassword = $_POST['txt_extensionPassword'];
			$txt_id = $_POST['txt_id'];		
		    $txt_campaign = $_POST['txt_userWorkType']; 
			$txt_ingroup = $_POST['txt_ingroup'];
			$txt_ingroupValue = implode(",", $txt_ingroup);
			$txt_work_type = $_POST['txt_work_type'];
						
			$stmt_update = "update users set user_id='$txt_User_id',user_name='$txt_User_name',user_level='$txt_User_level',status='$txt_User_status',user_password='$txt_User_password',extension_id='$txt_extension_id',extension_password='$txt_extensionPassword',Campaign='$txt_campaign',ingroup='$txt_ingroupValue',work_type='$txt_work_type' where id='$txt_id'";
			$rslt_update= mysqli_query($conn,$stmt_update);		
	}
	if(isset($_POST['deleteUser'])){ 
			$deleteUserId = $_POST['deleteUserID'];	
		 $stmt_delete = "delete from users where id='$deleteUserId'";
			
			$rslt_delete= mysqli_query($conn,$stmt_delete);
	
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
	
	.scrollit {
    overflow:scroll;
    height:700px;
}
.modal-dialog_FAQ {
	max-width: 500px;
	position: relative;
    width: auto;
    margin: .5rem;
    pointer-events: none;
}

.modal-backdrop.show {
    opacity:0 !important;
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
  <li>
	  <div style="color:white;margin-left:10px;"><img src="img/hexa.png" alt="Admin Logo" class="brand-image" width ="220px" height="65px"></div>
	  </li>
	  
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
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #1A2B6D; color:white;">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link" style="background-color: #1A2B6D; color:white;">
      <img src="../../dist/img/logo.png" alt="Admin Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light" style="color:#1A2B6D;">Users</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/voip.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="color:white;"><?php echo $loggedInuserName; ?></a>
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
              
             
            </ul>
          </li>
       
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
			  	
			  <?php if($incoming_did == 1){ ?>
			   <li class="nav-item">
                <a href="did.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DID</p>
                </a>
              </li>
			  <?php } ?>
			  <?php if($incoming_ivr == 1){ ?>
			  <li class="nav-item">
                <a href="ivr_list.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>IVR</p>
                </a>
              </li>
			  <?php } ?>
			   <li class="nav-item">
                <a href="User.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="voice_logger.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Voice Recordings</p>
                </a>
              </li> 
			  <li class="nav-item">
                <a href="show_screen_label.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Screen Labels</p>
                </a>
              </li>
			 <li class="nav-item">
                <a href="List.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Upload List</p>
                </a>
              </li>
			  
			    <li class="nav-item">
                <a href="report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Report</p>
                </a>
              </li>
			  <?php if($announcement == 1){ ?>
			  <li class="nav-item">
                <a href="addBanner.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Announcement</p>
                </a>
              </li>
			  <?php } ?>
			  <?php if($star_month == 1){ ?>
			  <li class="nav-item">
                <a href="Add_Star_Month.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Star Of the Month</p>
                </a>
              </li>
			   <?php } ?>
			  <li class="nav-item">
                <a href="pause_code.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pause Codes</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="UserStats.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Stats</p>
                </a>
              </li>
			   <?php if($dependant_dropdown == 1){ ?>
      <li class="nav-item">
                <a href="add_dependent_dropdowns.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dependent Dropdowns</p>
                </a>
              </li>
			  <?php } ?>
			  <?php if($phone_book == 1){ ?>
      <li class="nav-item">
                <a href="phone_book.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Phone Book</p>
                </a>
              </li>
			  <?php } ?>
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
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
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
		   <?php //if($loggedInUserLevel =="SuperAdmin"){?>
			<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD USER</button>
			<?php //} ?>
			<form action ="<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST" enctype="multipart/form-data">
			<div class="form-group" style="display: inline-block;width: 20%;vertical-align: top;margin-right:7px;margin-left:5px;">
                    <label for="CampaignName">Upload Users</label>
                    <input type="file" name="uploadfile"  id ="uploadfile" class="form-control" required />
                  </div>
				  <div class="form-group" style="display: inline-block;width: 20%;margin-top:30px;margin-right:7px;margin-left:5px;">
                   <input  type="submit" name ="uploadUsers" value ="Submit" class="btn btn-warning">
                  </div>
			</form>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
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
                  <tr>
                      <th>
                         Sr.No.
                      </th>
                     
                      <th>
                         USER ID
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
                          CAMPAIGN 
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
							$stmt_select="SELECT * from users order by id DESC";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			  
			  
			  
			  ?>
                  <tr>
				      
                      <td>
                          <?php echo $x ; ?>
                      </td>
                      <td>
                         
                            <?php echo $row["user_id"]; ?>
                         
                      </td>
				
                      <td>
                        <?php
						if($row["user_level"]=="D4"){
						echo "Agent"; 
			   }else{
				   echo $row["user_level"];
			   }?>
                      </td>
					    
					  <td>
							<?php
							if($row["status"] == "Active"){
							?>
    <span style="background-color:#00b33c;border:10px solid #00b33c;border-radius:20px;color:white;"><?php echo $row["status"]; ?></span>
	   <?php
							}else{
								?>
	<span style="background-color:#e60000;border:10px solid #e60000;border-radius:20px;color:white;"><?php echo $row["status"]; ?></span>
								<?php 
							}
							?>
                            </td>
					  <td>
                        <?php echo $row["extension_id"]; ?>
                      </td>
					
					   <td>
                        <?php echo $row["Campaign"]; ?>
                      </td>
					  
					   <?php //if($loggedInUserLevel =="SuperAdmin"){?>
                      <td class="project-actions text-right">
                         <!-- <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal_edit"> -->
						 <span class="btn btn-info btn-sm"  onclick = "document.getElementById('myModal_edit').style.display='block'; userEditFun('<?php echo $row["id"]; ?>')">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
							  </span>
                      
					
					   
					    <span class="btn btn-danger btn-sm"  onclick = "document.getElementById('myModal_delete').style.display='block'; userDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["user_id"]; ?>','<?php echo $row["user_name"]; ?>')">
                              <i class="fas fa-trash">
                              </i>
                              Delete
							  </span>
							
                      </td>
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
      <div class="modal-content scrollit" style="top:40px;">
      
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
						<label for="UserID">User ID<span style="color:red;">*</span></label>
						<input type="text" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" class="form-control" id="txt_User_id" name="txt_User_id" readonly>
					</div>
					
					<div class="form-group">
						<label for="UserName">User Name<span style="color:red;">*</span></label>
						<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter characters" class="form-control" id="txt_User_name" name="txt_User_name" required>
					</div>
					<div class="form-group">
						<label for="UserName">User Password<span style="color:red;">*</span></label>
						<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="txt_User_password" name="txt_User_password" required>
					</div>
					<div class="form-group">
					  <label> User Level<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="txt_User_level" name  = "txt_User_level" required>
						<option value="">Select</option>
						<option value="SuperAdmin"> Super Admin</option>
						<option value="Admin">Admin</option>
						 <option value="TeamLead">Team Lead</option>
						 <option value="D4">Agent</option> 
					  </select>
					</div>
					
					<div class="form-group">
					  <label> User Status<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="txt_User_status" name = "txt_User_status" required>
						<option value="">Select</option>
						<option value="Active">Active</option>
						<option value="Inactive">Inactive</option>
						
					  </select>
					</div>
	
					
						<div class="form-group">
					  <label> Extension Id</label>
					  <input type="text" pattern="[0-9]+" title="Please enter only numbers" class="form-control" id="txt_extension_id" name = "txt_extension_id" placeholder="Enter Extension Id">
					</div>
					
					<div class="form-group">
					  <label> Extension Password</label>
					   <!--<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="txt_extensionPassword" name = "txt_extensionPassword" placeholder="Enter Extension Password">-->
					    <input type="text" class="form-control" title="Please enter password" id="txt_extensionPassword" name="txt_extensionPassword" placeholder="Enter Extension Password">
					
					</div>
					
					
					<div class="form-group">
					  <label> Assign Campaign</label>
					  <select class="form-control select2" style="width: 100%;" name = "txt_userWorkType" id = "txt_userWorkType" onchange="txtcampaignChange()">
						<option value="">Select Campaign &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						
						<?php			
			$stmt_select="SELECT * from campaign";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
								 ?>
								
									<option value = "<?php echo $row["campaign_name"] ; ?>" ><?php echo $row["campaign_name"]; ?></option> 
							 <?php } ?>
					  </select>
					</div>
					<div class="form-group" id="ingroup_display_txt">
					  
					</div>
					<div class="form-group">
					  <label>Work Type<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="txt_work_type" name="txt_work_type" required>
						<option value="">Select</option>
						<option value="WFO">Work from office</option>
						<option value="WFH">Work from home</option>
					 </select>
					</div>
					<div class="form-group" style="display:none;">
						<label for="UserID">Conference Number</label> 
						<input type="text" title="Please enter only 10digits numbers" class="form-control" id="txt_conf_num" name = "txt_conf_num"  placeholder="Enter Conference Number">
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
      <div class="modal-content scrollit">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ADD A NEW USER</h4>
		 <button onclick="myFunctionForFAQ();" title="Add User FAQ" style="border: none;background-color: white;"><i class="fa fa-question-circle" aria-hidden="true" style="font-size:20px;margin-top:7px;"></i></button>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
		
		
        
        <!-- Modal body -->
        <div class="modal-body">
		 <form action = "" method = "POST">
                <div class="card-body">
					<div class="form-group">
						<label for="UserID">User ID<span style="color:red;">*</span></label> 
						<input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="User_id" name = "User_id"  placeholder="Enter User ID" required>
					</div>
					
					<div class="form-group">
						<label for="UserName">User Name<span style="color:red;">*</span></label>
						<input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="User_name" name = "User_name" placeholder="Enter User Name" required>
					</div>
					<div class="form-group">
						<label for="UserName">User Password<span style="color:red;">*</span></label>
						<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="User_password" name = "User_password" placeholder="Enter Password" required>
					</div>
					<div class="form-group">
					  <label> User Level<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="User_level" name = "User_level" required>
						<option value="">Select</option>
						<option value="SuperAdmin"> Super Admin</option>
						<option value="Admin">Admin</option>
						<option value="TeamLead">Team Lead</option>
						<option value="D4">Agent</option>
						
					  </select>
					</div>
					<div class="form-group">
					  <label> User Status<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="User_status" name = "User_status" required>
						<option value="">Select</option>
						<option value="Active">Active</option>
						<option value="Inactive">Inactive</option>
						
					  </select>
					</div>
					<div class="form-group">
					  <label> Extension Id</label>
					  <input type="text" pattern="[0-9]+" title="Please enter numbers" class="form-control" id="extensionIdPhone" name = "extensionIdPhone" placeholder="Enter Extension Id">
					</div>
					
					<div class="form-group">
					  <label> Extension Password</label>
					   <!--<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="extensionPassword" name = "extensionPassword" placeholder="Enter Extension Password">-->
					   <input type="text" class="form-control" title="Please enter password" id="extensionPassword" name = "extensionPassword" placeholder="Enter Extension Password">
					</div>
					
					
					<div class="form-group">
					  <label> Assign Campaign</label>
					  <select class="form-control select2" style="width: 100%;" name ="userWorkType" id ="userWorkType" onchange="campaignChange()">
						<option value="">Select Campaign &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						
						<?php			
				$stmt_select="SELECT * from campaign";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
								 ?>
								
									<option value = "<?php echo $row["campaign_name"]; ?>" ><?php echo $row["campaign_name"]; ?></option> 
							 <?php } ?>
					  </select>
					</div>
					
					 <div class="form-group" id="ingroup_display">
					
					</div>
					<div class="form-group">
					  <label>Work Type<span style="color:red;">*</span></label>
					  <select class="form-control select2" style="width: 100%;" id="work_type" name="work_type" required>
						<option value="">Select</option>
						<option value="WFO">Work from office</option>
						<option value="WFH">Work from home</option>
						
					  </select>
					</div>
				<!--	<div class="form-group">
						<label for="UserID">Conference Number</label> 
						<input type="text" pattern="[0-9]{10}" title="Please enter only 10digits numbers" class="form-control" id="conf_num" name = "conf_num"  placeholder="Enter Conference Number">
					</div>  -->
					
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value = "Submit" name = "addUser">
				   <button type="button" class="close" data-dismiss="modal" style="color:red;">Cancel</button>
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
						<input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_deleteUser_id" name="txt_deleteUser_id" >
					</div>
					<div class="form-group">
						<label for="UserName">User Name</label>
						<input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_deleteUser_name" name="txt_deleteUser_name">
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
      <b>Version</b> 6.0
    </div>
    <strong>Copyright &copy; Haloocom Technologies All rights reserved.
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

function myFunctionForFAQ() {
  var myWindow = window.open("help/userFAQ.html", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,width=400,height=400");
}

function campaignChange(){
	var campaign = document.getElementById("userWorkType").value;
	//alert(campaign);
	const xhttp = new XMLHttpRequest();
				  xhttp.onload = function() {
					var result =  this.responseText;
					
					document.getElementById('ingroup_display').innerHTML = result;
							//console.log(result);
						
					}
				  xhttp.open("GET", "ajax/checkIngroup.php?campaign="+campaign, true);
				  xhttp.send();
}
</script>
<script>
function txtcampaignChange(){
	var campaign = document.getElementById("txt_userWorkType").value;
	const xhttp = new XMLHttpRequest();
				  xhttp.onload = function() {
					var result =  this.responseText;
					
            	document.getElementById('ingroup_display_txt').innerHTML = result;
	}
				  xhttp.open("GET", "ajax/txt_checkIngroup.php?campaign="+campaign, true);
				  xhttp.send();
}
</script>
<script>
  $(function () {
  
  $('#table_camp').dataTable({
	    "order": [[ 0, "asc" ]]
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
			document.getElementById('txt_id').value=res[6];
			document.getElementById('txt_userWorkType').value=res[7];
			document.getElementById('txt_conf_num').value=res[8];
			document.getElementById('txt_extensionPassword').value=res[9];
			document.getElementById('txt_work_type').value=res[10];
			
			//document.getElementById('txt_ingroup').innerHTML=res[11];
			
			$.ajax({
			type: 'POST',
			url: "ajax/edit_checkIngroup.php",
			data: {
				'campval': res[7],
				'userval': res[0]
				
			},
			success: function(result) {
				//console.log(result);
				document.getElementById('ingroup_display_txt').innerHTML = result;
				
			}
			
		});
			//alert(res[10]);
		    }
        }; 
		xhttp.open("GET", "/haloocomConnect5/pages/Agent_mongodb/ajax/get_useredit_data.php?id="+val, true);
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
