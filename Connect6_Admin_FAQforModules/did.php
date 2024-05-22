<?php 

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
			   
if($loggedInuserName == '' && $loggedInPassword == '' ){
	header('Location:/haloocomConnect5/pages/Agent/connect5Login/index.php');
}


		if($_POST['addIngroup']){
			
			 $did_number = $_POST['did_number'];
		     $did_assign = $_POST['did_assign'];
			 $ingroup_assign = $_POST['ingroup_assign'];
			 $ivr_assign = $_POST['ivr_assign'];
			
		 
		 
		 $stmt_rs="SELECT did from call_did where did='$did_number';";
	                           $rslt_rs= mysqli_query($conn,$stmt_rs);
	                           $row_rs= mysqli_fetch_row($rslt_rs);
							   
							if(count($row_rs) == 0 ){			
				if($did_assign == "Ingroup"){					
					$date = date("Y-m-d H:i:s");
			$stmt_insert="INSERT INTO call_did(created_date,did,route,assign_did) values('$date','$did_number','$did_assign','$ingroup_assign')";
					$rslt_insert= mysqli_query($conn,$stmt_insert);
				}else{
					$date = date("Y-m-d H:i:s");
			$stmt_insert="INSERT INTO call_did(created_date,did,route,assign_did) values('$date','$did_number','$did_assign','$ivr_assign')";
					$rslt_insert= mysqli_query($conn,$stmt_insert);
				}					
	                          
							}else{
								echo "<script>alert('DID already exist.');</script>";
							}
	
	}
	
	if($_POST['editIngroup'] == 'edit_Ingroup'){
		
			$txt_did_number = $_POST['txt_did_number'];
		     $txt_did_assign = $_POST['txt_did_assign'];
			 $txt_ingroup_assign = $_POST['txt_ingroup_assign'];
			 $txt_ivr_assign = $_POST['txt_ivr_assign'];
			$txt_id = $_POST['txt_id'];	
			
			if($txt_did_assign == "Ingroup"){
  $stmt_update = "update call_did set did='$txt_did_number',route='$txt_did_assign',assign_did='$txt_ingroup_assign' where id='$txt_id'";
			$rslt_update= mysqli_query($conn,$stmt_update);	
			}else{
      $stmt_update = "update call_did set did='$txt_did_number',route='$txt_did_assign',assign_did='$txt_ivr_assign' where id='$txt_id'";
			$rslt_update= mysqli_query($conn,$stmt_update);
			}
	}
	
	if($_POST['deleteIngroup'] == 'delete_Ingroup'){	
			$deleteIngroupID = $_POST['deleteDID_id'];		
		$stmt_delete = "delete from call_did where id='$deleteIngroupID'";
			
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
      <span class="brand-text font-weight-light" style="color:#1A2B6D;">DID</span>
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
            <h1>DID</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">DID</li>
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
			    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD DID</button>
			<?php } ?>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">DID LISTINGS</h3>

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
                      <th style="width: 2%">
                         Sr.No.
                      </th>
                      <th style="width: 20%">
                         DID
                      </th>
                      <th style="width:20%">
                          ROUTE
                      </th>
					  <th style="width:20%">
                          ASSIGN TO
                      </th>
					   
					  <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <th style="width:30%">
					  ACTION
                      </th>
					   <?php }?>
                  </tr>
              </thead>
              <tbody>
			  
			   <?php 
			  $stmt_select="SELECT * from call_did";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			  
			  ?>
                  <tr>
                      <td>
                          <?php echo $x; ?>
                      </td>
                      <td>
                          <a>
                             <?php echo $row["did"]; ?>
                          </a>
                      </td>
                      <td>
                         <?php echo $row["route"]; ?>
                      </td>
                     <td>
                         <?php echo $row["assign_did"]; ?>
                      </td>
                     
					  <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <td class="project-actions text-right">
						 <span class="btn btn-info btn-sm"  onclick = "document.getElementById('myModal_edit').style.display='block'; ingroupEditFun('<?php echo $row["id"]; ?>')">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
							  </span>
                      
					         <span class="btn btn-danger btn-sm"  onclick = "document.getElementById('myModal_delete').style.display='block'; ingroupDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["did"]; ?>')">
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
          <h4 class="modal-title">EDIT DID</h4>
          <button type="button" class="close1" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
                <div class="card-body">
                   <div class="form-group">
                    <label for="Ingroup">DID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_did_number" name="txt_did_number" placeholder="Enter DID" required>
                  </div>
                  <div class="form-group">
                    <label for="IngroupName">DID ASSIGN TO<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="txt_did_assign" name="txt_did_assign" onchange="txt_didAssign()" required>
                          <option value="">Select</option>
                          <option value="Ingroup">Ingroup</option>
                          <option value="IVR">IVR</option>
                          
                        </select>
                  </div>
				  <div class="form-group" id="txt_ingroup_display" style="display:none;">
                        <label>Ingroup</label>
                        <select class="form-control select2" style="width: 100%;" id="txt_ingroup_assign" name="txt_ingroup_assign">
                          <option value="">Select</option>
                          <?php
                          $stmt_select = "SELECT * from ingroups";
                          $rslt_rs = mysqli_query($conn, $stmt_select);

                          $x = 1;
                          while ($row = mysqli_fetch_assoc($rslt_rs)) {
                          ?>

                            <option value="<?php echo $row["ingroup_id"]; ?>"><?php echo $row["ingroup_id"]; ?></option>
                          <?php } ?>
                        </select>
                      </div>
					  <div class="form-group" id="txt_ivr_display" style="display:none;">
                        <label>IVR</label>
                        <select class="form-control select2" style="width: 100%;" id="txt_ivr_assign" name="txt_ivr_assign">
                          <option value="">Select</option>
                          <?php
                          $stmt_select = "SELECT DISTINCT ivr_name from ivr_flow";
                          $rslt_rs = mysqli_query($conn, $stmt_select);

                          $x = 1;
                          while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          ?>
                            <option value="<?php echo $row["ivr_name"]; ?>"><?php echo $row["ivr_name"]; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                </div>
                <!-- /.card-body -->
                 <input type = "hidden" value = "" id = "txt_id" name = "txt_id">
					<div class="card-footer">
					  <input type = "hidden" value="edit_Ingroup" name="editIngroup" >
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
          <h4 class="modal-title">ADD A NEW DID</h4>
		   <button onclick="myFunctionForFAQ();" title="Add New DID FAQ" style="border: none;background-color: white;"><i class="fa fa-question-circle" aria-hidden="true" style="font-size:20px;margin-top:7px;"></i></button>
		 
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
                <div class="card-body">
                  <div class="form-group">
                    <label for="Ingroup">DID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="did_number" name="did_number" placeholder="Enter DID" required>
                  </div>
                  <div class="form-group">
                    <label for="IngroupName">DID ASSIGN TO<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="did_assign" name="did_assign" onchange="didAssign()" required>
                          <option value="">Select</option>
                          <option value="Ingroup">Ingroup</option>
                          <option value="IVR">IVR</option>
                          
                        </select>
                  </div>
				  <div class="form-group" id="ingroup_display" style="display:none;">
                        <label>Ingroup</label>
                        <select class="form-control select2" style="width: 100%;" id="ingroup_assign" name="ingroup_assign">
                          <option value="">Select</option>
                          <?php
                          $stmt_select = "SELECT * from ingroups";
                          $rslt_rs = mysqli_query($conn, $stmt_select);

                          $x = 1;
                          while ($row = mysqli_fetch_assoc($rslt_rs)) {
                          ?>

                            <option value="<?php echo $row["ingroup_id"]; ?>"><?php echo $row["ingroup_id"]; ?></option>
                          <?php } ?>
                        </select>
                      </div>
					  <div class="form-group" id="ivr_display" style="display:none;">
                        <label>IVR</label>
                        <select class="form-control select2" style="width: 100%;" id="ivr_assign" name="ivr_assign">
                          <option value="">Select</option>
                          <?php
                          $stmt_select = "SELECT DISTINCT ivr_name from ivr_flow";
                          $rslt_rs = mysqli_query($conn, $stmt_select);

                          $x = 1;
                          while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          ?>
                            <option value="<?php echo $row["ivr_name"]; ?>"><?php echo $row["ivr_name"]; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                   <input  type="submit" class="btn btn-primary" name = "addIngroup" value = "Submit">
                </div>
            </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
        
      </div>
    </div>
  </div> <!-- /.row -->
  
    		<!-- The Modal Delete-->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">DELETE DID</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "" method = "POST">
                <div class="card-body">
                    <div class="form-group">
                    <label for="Ingroup">DID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_deletedid_id" name="txt_deletedid_id" placeholder="Enter DID" required>
                  </div>
					
				</div>
				<input type = "hidden" value = "" id = "deleteDID_id" name = "deleteDID_id">
                <!-- /.card-body -->

                <div class="card-footer">
				  <input type = "hidden" value="delete_Ingroup" name="deleteIngroup" >
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
function myFunctionForFAQ() {
  var myWindow = window.open("help/DidFAQ.html", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,width=400,height=400");
}


 function didAssign() {
      var did_name = document.getElementById("did_assign").value;
      if (did_name == "Ingroup") {
        document.getElementById("ivr_display").style.display = "none";
        document.getElementById("ingroup_display").style.display = "block";
      } else if (did_name == "IVR") {
        document.getElementById("ingroup_display").style.display = "none";
        document.getElementById("ivr_display").style.display = "block";
     } else {
        document.getElementById("ivr_display").style.display = "none";
        document.getElementById("ingroup_display").style.display = "none";
      }
    }
	
	function txt_didAssign() {
      var txt_did_name = document.getElementById("txt_did_assign").value;
      if (txt_did_name == "Ingroup") {
        document.getElementById("txt_ivr_display").style.display = "none";
        document.getElementById("txt_ingroup_display").style.display = "block";
      } else if (txt_did_name == "IVR") {
        document.getElementById("txt_ingroup_display").style.display = "none";
        document.getElementById("txt_ivr_display").style.display = "block";
     } else {
        document.getElementById("txt_ivr_display").style.display = "none";
        document.getElementById("txt_ingroup_display").style.display = "none";
      }
    }
	
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
  
  
  function ingroupEditFun(val)
	{
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
			
		//alert(res);
			document.getElementById('txt_did_number').value=res[0];
			document.getElementById('txt_did_assign').value=res[1];
			if(res[1] == "Ingroup"){
	    document.getElementById("txt_ivr_display").style.display = "none";
        document.getElementById("txt_ingroup_display").style.display = "block";
			document.getElementById('txt_ingroup_assign').value=res[2];
			}else{
			document.getElementById("txt_ingroup_display").style.display = "none";
        document.getElementById("txt_ivr_display").style.display = "block";
			document.getElementById('txt_ivr_assign').value=res[2];
			}
			document.getElementById('txt_id').value=res[3];
			
		    }
        }; 
		xhttp.open("GET", "/haloocomConnect5/pages/Agent_mongodb/ajax/get_didedit_data.php?id="+val, true);
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
	
	
	function ingroupDeleteFun(val_grpd,ingroupIDVal)
	{
		document.getElementById('deleteDID_id').value=val_grpd;
		document.getElementById('txt_deletedid_id').value=ingroupIDVal;
		
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
