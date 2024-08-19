<?php 

session_start();

$loggedInuserName  = $_SESSION['username'] ;
$loggedInPassword  = $_SESSION['pass'];
$loggedInUserLevel = $_SESSION['user_level'];


require_once("db_connect.php");

$stmt_group="SELECT user_group from users where user_id='$loggedInuserName'";
//echo $stmt_group;
			  $rslt_group= mysqli_query($conn,$stmt_group);
	                           $row_g= mysqli_fetch_row($rslt_group);
				   $user_group = $row_g[0];
				   
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
			   
if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/haloocomConnect5/pages/Agent/connect5Login/index.php');
}

		if($_POST['addtemplate']){
			
		   $extensionIdPhone = $_POST['extensionIdPhone'];
		   $extensionPassword = $_POST['extensionPassword'];
		   
			$stmt_rs="SELECT extensionIdPhone from phone_extension where extensionIdPhone='$extensionIdPhone';";
	                           $rslt_rs= mysqli_query($conn,$stmt_rs);
	                           $row_rs= mysqli_fetch_row($rslt_rs);
							   
							if($row_rs == ""){			
									
					$date = date("Y-m-d H:i:s");
					$stmt_insert="INSERT INTO phone_extension(created_date,extensionIdPhone,extensionPassword) values('$date','$extensionIdPhone','$extensionPassword')";
					$rslt_insert= mysqli_query($conn,$stmt_insert);
					exec("/usr/bin/curl -k '$incoming_api/API/backAPI/createuser.php?phone_id=$extensionIdPhone&secret=$extensionPassword'");
	                  
					       
							}else{
								echo "<script>alert('Extension id already exist.');</script>";
							}
	}
	
	if($_POST['editTemplate'] == 'edit_Template'){  
			$txt_extension_id = $_POST['txt_extension_id'];
			$txt_extensionPassword = $_POST['txt_extensionPassword'];
			$txt_id = $_POST['txt_id'];
			
		 $stmt_update = "update phone_extension set extensionIdPhone='$txt_extension_id',extensionPassword='$txt_extensionPassword' where id='$txt_id'";
		 //echo $stmt_update;
		 //die;
			$rslt_update= mysqli_query($conn,$stmt_update);	
exec("/usr/bin/curl -k '$incoming_api/API/backAPI/createuser.php?phone_id=$txt_extension_id&secret=$txt_extensionPassword'");
				
	}
	
	if($_POST['deleteTemplate'] == 'delete_Template'){	
			$deleteTemplateID = $_POST['deleteTemplateID'];
			
		 $stmt_delete = "delete from phone_extension where id='$deleteTemplateID'";
			
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
      <span class="brand-text font-weight-light" style="color:#1A2B6D;">Pause</span>
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
			  	<li class="nav-item">
                <a href="phone_extesion.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Phone</p>
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
			  <li class="nav-item">
                <a href="email_template.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email Template</p>
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
            <h1>PHONE EXTENSION</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">PHONE EXTENSION</li>
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
			<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD PHONE EXTENSION</button>
			<?php } ?>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">PHONE EXTENSION LISTINGS</h3>

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
                      EXTENSION ID
                      </th>
					  <th>
                      EXTENSION PASSWORD
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
			 $stmt_select="SELECT * from phone_extension";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			  
			  
			  ?>
                  <tr>
                      <td>
                          <?php echo $x; ?>
                      </td>
					  <td>
                          <?php echo $row["extensionIdPhone"]; ?>
                      </td>
                      <td>
                          <a>
                           <?php echo $row["extensionPassword"]; ?>
                          </a>
                      </td>
					 
                   
					  <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <td class="project-actions text-right">
                         <!-- <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal_edit"> -->
						 <span class="btn btn-info btn-sm"  onclick = "document.getElementById('myModal_edit').style.display='block'; templateEditFun('<?php echo $row["id"]; ?>')">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
							  </span>
					   
					    <span class="btn btn-danger btn-sm"  onclick = "document.getElementById('myModal_delete').style.display='block'; templateDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["extensionIdPhone"]; ?>')">
                              <i class="fas fa-trash">
                              </i>
                              Delete
							  </span>
								
                      </td>
					   <?php }?>
                  </tr>
                 
			  <?php $x++; } ?>
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
		
		<!-- The Modal -->
  <div class="modal fade" id="myModal_edit" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">EDIT PHONE EXTENSION</h4>
          <button type="button" class="close1" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
             <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
                <div class="card-body">
                  <div class="form-group">
					  <label> Extension Id <span style="color:red;">*</span></label>
					  <input type="text" pattern="[0-9]+" title="Please enter only numbers" class="form-control" id="txt_extension_id" name = "txt_extension_id" placeholder="Enter Extension Id" readonly>
					</div>
					
					<div class="form-group">
					  <label> Extension Password <span style="color:red;">*</span></label>
					   <!--<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="txt_extensionPassword" name = "txt_extensionPassword" placeholder="Enter Extension Password">-->
					    <input type="text" class="form-control" title="Please enter password" id="txt_extensionPassword" name="txt_extensionPassword" placeholder="Enter Extension Password" required>
					
					</div>
                </div>
                <!-- /.card-body -->
                <input type = "hidden" value = "" id = "txt_id" name = "txt_id">
                <div class="card-footer">
				<input type = "hidden" value="edit_Template" name="editTemplate" >
                  <button type="submit"  class="btn btn-primary">Modify</button>
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
		
		<!-- The Modal Edit -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ADD PHONE EXTENSION</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
                <div class="card-body">
                    <div class="form-group">
					  <label> Extension Id <span style="color:red;">*</span></label>
					  <input type="text" pattern="[0-9]+" title="Please enter numbers" class="form-control" id="extensionIdPhone" name = "extensionIdPhone" placeholder="Enter Extension Id" required>
					</div>
					
					<div class="form-group">
					  <label> Extension Password <span style="color:red;">*</span></label>
					   <!--<input type="text" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])(?!.*\s).{8,16}$" title="Password must be 8-16 characters long, include at least one lowercase letter, one uppercase letter, one digit, and one special character (@#$%^&+=), and should not contain spaces" id="extensionPassword" name = "extensionPassword" placeholder="Enter Extension Password">-->
					   <input type="text" class="form-control" title="Please enter password" id="extensionPassword" name = "extensionPassword" placeholder="Enter Extension Password" required>
					</div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer"> 
                  <input  type="submit" class="btn btn-primary" name ="addtemplate" value = "Submit">
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
		
			<!-- The Modal Delete-->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">DELETE PHONE EXTENSION</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "" method = "POST">
                <div class="card-body">
                  <div class="form-group">
						<label for="UserID">EXTENSION ID</label>
						<input type="text" class="form-control" id="txt_deleteTemplate_id" name="txt_deleteTemplate_id" readonly>
					</div>
				
                </div>
				<input type = "hidden" value = "" id = "deleteTemplateID" name = "deleteTemplateID">
                <!-- /.card-body -->

                <div class="card-footer">
				  <input type = "hidden" value="delete_Template" name="deleteTemplate" >
                  <input type="submit" class="btn btn-primary">
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
  $(function () {
  
  $('#table_camp').dataTable();
  
    //Initialize Select2 Elements
	
	 $(function () {
        $("#phone_name").change(function () {
            if ($(this).val() == "Local" ) {
                $("#dvPhoneNo").show();
            } else {
                $("#dvPhoneNo").hide();
            }
        });
		
		$("#txt_phone_name").change(function () {
            if ($(this).val() == "Local" ) {
                $("#txt_dvPhoneNo").show();
            } else {
                $("#txt_dvPhoneNo").hide();
				document.getElementById('txt_Phone_no').value="";
            }
        });
		
    });
	
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
  
  
  
   function templateEditFun(val)
	{
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
			
		//alert(res);
			document.getElementById('txt_extension_id').value=res[0];
			document.getElementById('txt_extensionPassword').value=res[1];
			document.getElementById('txt_id').value=res[2];
			}
        }; 
		xhttp.open("GET", "/haloocomConnect5/pages/Agent_mongodb/ajax/get_extension_edit_data.php?id="+val, true);
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
	
	
	function templateDeleteFun(val_grpd,PhoneIDVal)
	{
		document.getElementById('deleteTemplateID').value=val_grpd;
		document.getElementById('txt_deleteTemplate_id').value=PhoneIDVal;
		
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
