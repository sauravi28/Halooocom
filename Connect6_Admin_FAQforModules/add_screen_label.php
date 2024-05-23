<?php 

session_start();

$loggedInuserName  = $_SESSION['username'] ;
$loggedInPassword  = $_SESSION['pass'];
$loggedInUserLevel = $_SESSION['user_level'];


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

	
	if($_POST['submit_label']){
		
$label_id = $_POST["label_id"];
$label_name = $_POST["label_name"];
$label_status = $_POST["label_status"];
$label_first_name = $_POST["first_name"];
$label_middle_name = $_POST["middle_name"];
$label_last_name = $_POST["last_name"];
$label_address1 = $_POST["address1"];
$label_address2 = $_POST["address2"];
$label_address3 = $_POST["address3"];
$label_city = $_POST["label_city"];
$label_state = $_POST["label_state"];
$label_province = $_POST["label_province"];
$label_gender = $_POST["label_gender"];
$label_phone_number = $_POST["label_phone_number"];
$label_phone_code = $_POST["label_phone_code"];
$label_alt_phone1 = $_POST["alt_phone1"];
$label_alt_phone2 = $_POST["alt_phone2"];
$label_alt_phone3 = $_POST["alt_phone3"];
$label_alt_phone4 = $_POST["alt_phone4"];
$label_alt_phone5 = $_POST["alt_phone5"];
$label_alt_phone6 = $_POST["alt_phone6"];
$label_alt_phone7 = $_POST["alt_phone7"];
$label_alt_phone8 = $_POST["alt_phone8"];
$label_comments = $_POST["comments"];
$label_field1 = $_POST["label_filed1"];
$label_field2 = $_POST["label_filed2"];
$label_field3 = $_POST["label_filed3"];
$label_field4 = $_POST["label_filed4"];
$label_field5 = $_POST["label_filed5"];
$label_field6 = $_POST["label_filed6"];
$label_field7 = $_POST["label_filed7"];
$label_field8 = $_POST["label_filed8"];
$label_field9 = $_POST["label_filed9"];
$label_field10 = $_POST["label_filed10"];
$label_field11 = $_POST["label_filed11"];
$label_field12 = $_POST["label_filed12"];
$label_field13 = $_POST["label_filed13"];
$label_field14 = $_POST["label_filed14"];
$label_field15 = $_POST["label_filed15"];
$label_field16 = $_POST["label_filed16"];
$label_field17 = $_POST["label_filed17"];
$label_field18 = $_POST["label_filed18"];
$label_field19 = $_POST["label_filed19"];
$label_field20 = $_POST["label_filed20"];
$label_field21 = $_POST["label_filed21"];
$label_field22 = $_POST["label_filed22"];
$label_field23 = $_POST["label_filed23"];
$label_field24 = $_POST["label_filed24"];
$label_field25 = $_POST["label_filed25"];
$label_field26 = $_POST["label_filed26"];
$label_field27 = $_POST["label_filed27"];
$label_field28 = $_POST["label_filed28"];
$label_dropdown1 = $_POST["label_dropdown1"];
$label_dropdown2 = $_POST["label_dropdown2"];
$label_dropdown3 = $_POST["label_dropdown3"];
$label_dropdown4 = $_POST["label_dropdown4"];
$label_dropdown5 = $_POST["label_dropdown5"];
$label_dropdown6 = $_POST["label_dropdown6"];
$label_dropdown7 = $_POST["label_dropdown7"];
$label_dropdown_value1 = $_POST["dropdown1_list"];
$label_dropdown_value2 = $_POST["dropdown2_list"];
$label_dropdown_value3 = $_POST["dropdown3_list"];
$label_dropdown_value4 = $_POST["dropdown4_list"];
$label_dropdown_value5 = $_POST["dropdown5_list"];
$label_dropdown_value6 = $_POST["dropdown6_list"];
$label_dropdown_value7 = $_POST["dropdown7_list"];

	
							
						$stmt_rs="SELECT label_id from screen_label where label_id='$label_id';";
	                           $rslt_rs= mysqli_query($conn,$stmt_rs);
	                           $row_rs= mysqli_fetch_row($rslt_rs);
							   
							if(count($row_rs) == 0 ){			
									
					$date = date("Y-m-d H:i:s");
									
 $stmt_insert="INSERT INTO `screen_labels`(`label_id`, `label_name`, `label_status`, `label_first_name`, `label_middle_name`, `label_last_name`, `label_address1`, `label_address2`, `label_address3`, `label_city`, `label_state`, `label_province`, `label_gender`, `label_phone_number`, `label_phone_code`, `label_alt_phone1`, `label_alt_phone2`, `label_alt_phone3`, `label_alt_phone4`, `label_alt_phone5`, `label_alt_phone6`, `label_alt_phone7`, `label_alt_phone8`, `label_comments`, `label_field1`, `label_field2`, `label_field3`, `label_field4`, `label_field5`, `label_field6`, `label_field7`, `label_field8`, `label_field9`, `label_field10`, `label_field11`, `label_field12`, `label_field13`, `label_field14`, `label_field15`, `label_field16`, `label_field17`, `label_field18`, `label_field19`, `label_field20`, `label_field21`, `label_field22`, `label_field23`, `label_field24`, `label_field25`, `label_field26`, `label_field27`, `label_field28`, `label_dropdown1`, `label_dropdown2`, `label_dropdown3`, `label_dropdown4`, `label_dropdown5`, `label_dropdown6`, `label_dropdown7`, `label_dropdown_value1`, `label_dropdown_value2`, `label_dropdown_value3`, `label_dropdown_value4`, `label_dropdown_value5`, `label_dropdown_value6`, `label_dropdown_value7`, `label_entry_date`) VALUES ('$label_id','$label_name','$label_status','$label_first_name','$label_middle_name','$label_last_name','$label_address1','$label_address2', '$label_address3','$label_city','$label_state','$label_province','$label_gender','$label_phone_number','$label_phone_code','$label_alt_phone1','$label_alt_phone2','$label_alt_phone3','$label_alt_phone4','$label_alt_phone5','$label_alt_phone6','$label_alt_phone7','$label_alt_phone8','$label_comments','$label_field1','$label_field2','$label_field3','$label_field4','$label_field5','$label_field6','$label_field7','$label_field8','$label_field9','$label_field10','$label_field11','$label_field12','$label_field13','$label_field14','$label_field15','$label_field16','$label_field17','$label_field18','$label_field19','$label_field20','$label_field21','$label_field22','$label_field23','$label_field24','$label_field25','$label_field26','$label_field27','$label_field28','$label_dropdown1','$label_dropdown2','$label_dropdown3','$label_dropdown4','$label_dropdown5','$label_dropdown6','$label_dropdown7','$label_dropdown_value1','$label_dropdown_value2','$label_dropdown_value3','$label_dropdown_value4','$label_dropdown_value5','$label_dropdown_value6','$label_dropdown_value7','$date')";
				
	                 $rslt_insert= mysqli_query($conn,$stmt_insert);
					 echo "<script>alert('Screen label has been created successfully.');</script>";
	                          
							}else{
								echo "<script>alert('Screen label id already exist.');</script>";
							}
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
	 p{
	   color:white;
	   
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
      <span class="brand-text font-weight-light" style="color:#1A2B6D;">Screen Labels</span>
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
            <h1 style="color:#020252;">Add Screen Labels<button onclick="myFunctionForFAQ();" title="help" style="border: none;"><i class="fa fa-question-circle" aria-hidden="true" style="font-size:20px;margin-top:7px;"></i></button>
		 </h1>
			
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">Screen Labels</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
		
		
		<table>
							<form method="POST">
							
				                  <div class="form-group" style="display: inline-block;width: 18%;vertical-align: top;margin-right:7px;">
                                           <label for="field1">Label ID<span style="color:red;">*</span></label>
                                           <input type="text" pattern="^[0-9]*$" class="form-control" title="Please enter only numbers" id="label_id" name="label_id" required>
									</div>
									<div class="form-group" style="display: inline-block;width: 18%;vertical-align: top;margin-right:7px;">
											<label for="field1">Label Name<span style="color:red;">*</span></label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_name" name="label_name" required>
										</div>
									<div class="form-group" style="display: inline-block;width: 18%;vertical-align: top;margin-right:7px;">
											<label for="field3">Label Active<span style="color:red;">*</span></label>
											<select name="label_status" class="form-control"  id="label_status" required>
												<option value="" disabled selected>--Choose Status--</option>
												<option value="Y">Y</option>
												<option value="N">N</option>
												
											</select>
									</div>
									<div class="form-group" style="display: inline-block;width: 18%;vertical-align: top;margin-right:7px;">
											<label for="field2">Label Phone Number</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_phone_number" name="label_phone_number" value="Phone Number" readonly>
									</div>
									<div class="form-group" style="display: inline-block;width: 18%;vertical-align: top;margin-right:7px;">	
											<label for="field12">Label Comments</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="comments" name="comments" value="Comments" readonly>
										</div><br>
									
										
								<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="first_show()">Add TextBox</span>&nbsp;&nbsp;<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="nine_show()">Add Dropdown</span>
								
									
							
							
							
								<tr id="first_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field1">Label First Name</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="first_name" name="first_name">
										</div>
									</td>

									<td>
									<div class="form-group">
											<label for="field1">Label Middle Initial</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="middle_name" name="middle_name">
										</div>
										
									</td>

								
									<td>
										<div class="form-group">
											<label for="field3">Label Last Name</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="last_name" name="last_name">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field4">Label Address1</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="address1" name="address1">
										</div>
									</td>
								
									<td>
										<div class="form-group">
											<label for="field5">Label Address2</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="address2" name="address2">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field6">Label Address3</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="address3" name="address3">
										</div>
									</td>
									
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="first_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="second_show()">Add TextBox</span>
									</td>
									
									</tr>
									
									
									
							
								
								<tr id="second_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field7">Label City</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_city" name="label_city">
										</div>
									</td>
								
									
									<td>
										<div class="form-group">
											<label for="field8">Label State</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_state" name="label_state">
										</div>
									</td>
								
									<td>
										<div class="form-group">
											<label for="field2">Label Province</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_province" name="label_province">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Gender</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_gender" name="label_gender">
										</div>
									</td>
								
									
									<td>
										<div class="form-group">
											<label for="field2">Label Phone Code</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_phone_code" name="label_phone_code">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone1</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone1" name="alt_phone1">
										</div>
									</td>
									
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="second_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="third_show()">Add TextBox</span>
									</td>
									</tr>
								
								
								
								
								
								<tr id="third_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone2</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone2" name="alt_phone2">
										</div>

									</td>
								
									
						               <td>
										<div class="form-group">
											<label for="field12">Label Alt Phone3</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone3" name="alt_phone3">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone4</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone4" name="alt_phone4">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone5</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone5" name="alt_phone5">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Alt Phone6</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone6" name="alt_phone6">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone7</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone7" name="alt_phone7">
										</div>
									</td>
									
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="third_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="fourth_show()">Add TextBox</span>
									</td>
									</tr>
								
								
								
								
									<tr id="fourth_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field2">Label Alt Phone8</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="alt_phone8" name="alt_phone8">
										</div>

									</td>
								
	                                     <td>
										<div class="form-group">
											<label for="field12">Label Field1</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed1" name="label_filed1">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field2</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed2" name="label_filed2">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field3</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed3" name="label_filed3">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field4</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed4" name="label_filed4">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field5</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed5" name="label_filed5">
										</div>
									</td>
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="fourth_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="fifth_show()">Add TextBox</span>
									</td>
									</tr>
								
								
								
								
								<tr id="fifth_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field2">Label Field6</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed6" name="label_filed6">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field7</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed7" name="label_filed7">
										</div>
									</td>
									
									<td>
										<div class="form-group">
											<label for="field12">Label Field8</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed8" name="label_filed8">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field9</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed9" name="label_filed9">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field10</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed10" name="label_filed10">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field11</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed11" name="label_filed11">
										</div>
									</td>
									
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="fifth_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="sixth_show()">Add TextBox</span>
									</td>
									</tr>
								
								
								
								
								<tr id="sixth_row" style="display:none;">
									<td>
										<div class="form-group">
											<label for="field2">Label Field12</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed12" name="label_filed12">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field13</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed13" name="label_filed13">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field14</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed14" name="label_filed14">
										</div>
									</td>
									
									<td>
										<div class="form-group">
											<label for="field12">Label Field15</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed15" name="label_filed15">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field16</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed16" name="label_filed16">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field17</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed17" name="label_filed17">
										</div>

									</td>
									
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="sixth_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="seven_show()">Add TextBox</span>
									</td>
								
								</tr>
								
								
								
								<tr id="seven_row" style="display:none;">
									
					            <td>
										<div class="form-group">
											<label for="field12">Label Field18</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed18" name="label_filed18">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field19</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed19" name="label_filed19">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field20</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed20" name="label_filed20">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field21</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed21" name="label_filed21">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field12">Label Field22</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed22" name="label_filed22">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field23</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed23" name="label_filed23">
										</div>
									</td>
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="seven_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="eight_show()">Add TextBox</span>
									</td>
									</tr>
								
								
								
								<tr id="eight_row" style="display:none;">
									
									<td>
										<div class="form-group">
											<label for="field2">Label Field24</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed24" name="label_filed24">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field25</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed25" name="label_filed25">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field26</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed26" name="label_filed26">
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field2">Label Field27</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed27" name="label_filed27">
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Field28</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_filed28" name="label_filed28">
										</div>
									</td>
									<td>
								<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="eight_close()">Remove</span>
									</td>
									<td>
									<span class="btn btn-success" style="border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="nine_show()">Add Dropdown</span>
									</td>

								</tr>
								
								
								
								<tr id="nine_row" style="display:none;">
									
						               <td>
										<div class="form-group">
											<label for="field12">Label Dropdown1</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown1" placeholder="Enter Dropdown Name" name="label_dropdown1">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown1_list" name="dropdown1_list" placeholder="Enter Dropdown Values">
											
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown2</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown2" name="label_dropdown2" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown2_list" name="dropdown2_list" placeholder="Enter Dropdown Values">
											
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown3</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown3" name="label_dropdown3" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown3_list" name="dropdown3_list" placeholder="Enter Dropdown Values">
											
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown4</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown4" name="label_dropdown4" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown4_list" name="dropdown4_list" placeholder="Enter Dropdown Values">
											
										</div>
									</td>
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown5</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown5" name="label_dropdown5" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown5_list" name="dropdown5_list" placeholder="Enter Dropdown Values">
											
										</div>
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown6</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown6" name="label_dropdown6" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers,characters and comma" class="form-control" id="dropdown6_list" name="dropdown6_list" placeholder="Enter Dropdown Values">
											
										</div>

									</td>
								
									<td>
										<div class="form-group">
											<label for="field12">Label Dropdown7</label>
											<input type="text" pattern="^[a-zA-Z\s]*$" title="Please enter only characters" class="form-control" id="label_dropdown7" name="label_dropdown7" placeholder="Enter Dropdown Name">
											<input type="text" pattern="^[0-9a-zA-Z,\s]*$" title="Please enter only numbers, characters and comma" class="form-control" id="dropdown7_list" name="dropdown7_list" placeholder="Enter Dropdown Values">
											
										</div>
									</td>
									<td>
									<span class="btn btn-success" style="background-color:red;border-radius: 5px;color:white;font-weight:600;font-size:13px;margin-top:13px;" onclick="nine_close()">Remove</span>
									</td>

								</tr>
								
								<tr>
								<td>
								<div class="form-group">
									<input type="submit" class="btn btn-primary mb-3" id="submit_label" name="submit_label" value="Submit" style="width:100%;"> <br>
										</div>
										</td>
								</tr>
								
							</form>
						</table>
		
		
		
		
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <br><br><br> <footer class="main-footer">
    <strong>Copyright &copy; Haloocom Technologies</strong>
    All rights reserved.
    <div style="text-align: right;">
      <b>Version 6.0</b> 
    </div>
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
  var myWindow = window.open("help/ScreenLabel.html", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,width=400,height=400");
}
 
function first_show() {
		
		document.getElementById('first_row').style.display = 'block';
}
function first_close() {
		
		document.getElementById('first_row').style.display = 'none';
}

function second_show() {
		
		document.getElementById('second_row').style.display = 'block';
}
function second_close() {
		
		document.getElementById('second_row').style.display = 'none';
}

function third_show() {
		
		document.getElementById('third_row').style.display = 'block';
}
function third_close() {
		
		document.getElementById('third_row').style.display = 'none';
}
function fourth_show() {
		
		document.getElementById('fourth_row').style.display = 'block';
}
function fourth_close() {
		
		document.getElementById('fourth_row').style.display = 'none';
}
function fifth_show() {
		
		document.getElementById('fifth_row').style.display = 'block';
}
function fifth_close() {
		
		document.getElementById('fifth_row').style.display = 'none';
}
function sixth_show() {
		
		document.getElementById('sixth_row').style.display = 'block';
}
function sixth_close() {
		
		document.getElementById('sixth_row').style.display = 'none';
}
function seven_show() {
		
		document.getElementById('seven_row').style.display = 'block';
}
function seven_close() {
		
		document.getElementById('seven_row').style.display = 'none';
}
function eight_show() {
		
		document.getElementById('eight_row').style.display = 'block';
}
function eight_close() {
		
		document.getElementById('eight_row').style.display = 'none';
}
function nine_show() {
		
		document.getElementById('nine_row').style.display = 'block';
}
function nine_close() {
		
		document.getElementById('nine_row').style.display = 'none';
}
</script>
<script>
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
  
  
  
    
  
   function campaignEditFun(val)
	{
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
		    //alert(res);
			document.getElementById('txt_Campaign_id').value=res[0];
			document.getElementById('txt_Campaign_name').value=res[1];
			document.getElementById('txt_id').value=res[2];
			document.getElementById('txt_active_camp').value=res[3];
			document.getElementById('txt_auto_dial_level').value=res[4];
			document.getElementById('txt_next_agent_call').value=res[5];
			//alert(res[5]);
			}
        }; 
		xhttp.open("GET", "/haloocomConnect5/pages/Agent_mongodb/ajax/get_campaignedit_data.php?id="+val, true);
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
	
	
	function campaignDeleteFun(val_grpd,CampaignIDVal)
	{
		document.getElementById('deleteCampaignID').value=val_grpd;
		document.getElementById('txt_deleteCampaign_id').value=CampaignIDVal;
		
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
