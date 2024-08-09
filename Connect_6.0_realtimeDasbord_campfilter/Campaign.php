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

	//Campaign_id Campaign_name 
	if($_POST['addCampaign']){
		
			$Campaign_id = $_POST['Campaign_id'];
			$Campaign_name = $_POST['Campaign_name'];
			$group_id = $_POST['group_id'];
			$active_camp = $_POST['active_camp'];
			$auto_dial_level = $_POST['auto_dial_level'];
			$next_agent_call = $_POST['next_agent_call'];
			$dial_method = $_POST['dial_method'];
			$dial_timeout = $_POST['dial_timeout'];
			$ingroup = $_POST['ingroup'];
			$sticky_agent = $_POST['sticky_agent'];
			$dial_prefix = $_POST['dial_prefix'];
			$call_going = $_POST['call_going'];  			
            $channel = $_POST['channel'];  		
            $did_number = $_POST['did_number'];
			$feedback = $_POST['feedback'];
			$hopper_level = $_POST['hopper_level'];
			
	if($dial_method == "Blended" || $dial_method == "Inbound" || $dial_method == "Blended Predictive"){
		$ingroupValue = implode(",", $ingroup);
	}else{
		$ingroupValue = "";
	}
			//echo $Campaign_id; exit;	
$target_dir = "Park_Music/";

$target_file = $target_dir . basename($_FILES["park_music"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		//echo $imageFileType; exit;	
			$stmt_rs="SELECT campaign_id from campaign where campaign_id='$Campaign_id';";
		//	echo $stmt_rs; exit;
	                           $rslt_rs= mysqli_query($conn,$stmt_rs);
	                           $row_rs= mysqli_fetch_row($rslt_rs);
							   
							if($row_rs == ""){			
									
	if($imageFileType != "mp3" && $imageFileType != "")
{
  //  echo "File Format Not Suppoted";
	echo "<script>alert('File Format Not Suppoted(It accepts only mp3)');</script>";
} 

else
{
	$video_path=$_FILES['park_music']['name'];
	
					$date = date("Y-m-d H:i:s");
					
	$stmt_insert="INSERT INTO campaign(created_date,campaign_id,campaign_name,active_camp,dial_ratio,screen_label,dial_method,dial_timeout,park_music,Ingroup,sticky_agent,dial_prefix,call_through,channel,did_number,feedback_ivr,hopper_level,group_id) values('$date','$Campaign_id','$Campaign_name','$active_camp','$auto_dial_level','$next_agent_call','$dial_method','$dial_timeout','$video_path','$ingroupValue','$sticky_agent','$dial_prefix','$call_going','$channel','$did_number','$feedback','$hopper_level','$group_id')";
		//echo $stmt_insert; exit;
					$rslt_insert= mysqli_query($conn,$stmt_insert);
					
	    move_uploaded_file($_FILES["park_music"]["tmp_name"],$target_file);

}				
        }else{
				echo "<script>alert('Campaign id already exist.');</script>";
			 }
	
							
	}
	
	if($_POST['editCampaign'] == 'edit_Campaign'){  
	 
			$txt_Campaign_id = $_POST['txt_Campaign_id'];
			$txt_Campaign_name = $_POST['txt_Campaign_name'];
			$txt_ingroup = $_POST['txt_ingroup'];
			$txt_active_camp = $_POST['txt_active_camp'];
			$txt_auto_dial_level = $_POST['txt_auto_dial_level'];
			$txt_group_id = $_POST['txt_group_id'];
			$txt_next_agent_call = $_POST['txt_next_agent_call'];
			$txt_id = $_POST['txt_id'];	
			$dial_method = $_POST['txt_dial_method'];
			$dial_timeout = $_POST['txt_dial_timeout'];
			$dummy_park_music = $_POST['dummy_park_music'];
			$txt_sticky_agent = $_POST['txt_sticky_agent'];
			$txt_dial_prefix = $_POST['txt_dial_prefix'];
			$txt_call_going = $_POST['txt_call_going'];   
            $txt_channel = $_POST['txt_channel'];    
             $txt_did_number = $_POST['txt_did_number'];
			 $txt_feedback = $_POST['txt_feedback'];
			 $txt_hopper_level = $_POST['txt_hopper_level'];
			
	if($dial_method == "Blended" || $dial_method == "Inbound" || $dial_method == "Blended Predictive"){
		$txt_ingroupValue = implode(",", $txt_ingroup);
	}else{
		$txt_ingroupValue = "";
	}
			$target_dir_txt = "Park_Music/";

$target_file_txt = $target_dir_txt . basename($_FILES["txt_park_music"]["name"]);
$imageFileType_txt = pathinfo($target_file_txt,PATHINFO_EXTENSION);
if($imageFileType_txt != ''){
	
if($imageFileType_txt != "mp3")
{
    //echo "File Format Not Suppoted";
	echo "<script>alert('File Format Not Suppoted(It accepts only mp3)');</script>";
} 

else
{
	$video_path_txt=$_FILES['txt_park_music']['name'];
		
		$stmt_update = "update campaign set campaign_id='$txt_Campaign_id',campaign_name='$txt_Campaign_name',active_camp='$txt_active_camp',dial_ratio='$txt_auto_dial_level',screen_label='$txt_next_agent_call',dial_method='$dial_method',dial_timeout='$dial_timeout',park_music='$video_path_txt',Ingroup='$txt_ingroupValue',sticky_agent='$txt_sticky_agent',dial_prefix='$txt_dial_prefix',call_through='$txt_call_going',channel='$txt_channel',did_number='$txt_did_number',feedback_ivr='$txt_feedback',hopper_level='$txt_hopper_level',group_id='$txt_group_id' where id='$txt_id'";
			$rslt_update= mysqli_query($conn,$stmt_update);	
				
			move_uploaded_file($_FILES["txt_park_music"]["tmp_name"],$target_file_txt);
	
}	
	}else{
		
			
		$stmt_update = "update campaign set campaign_id='$txt_Campaign_id',campaign_name='$txt_Campaign_name',active_camp='$txt_active_camp',dial_ratio='$txt_auto_dial_level',screen_label='$txt_next_agent_call',dial_method='$dial_method',dial_timeout='$dial_timeout',park_music='$dummy_park_music',Ingroup='$txt_ingroupValue',sticky_agent='$txt_sticky_agent' ,dial_prefix='$txt_dial_prefix',call_through='$txt_call_going',channel='$txt_channel',did_number='$txt_did_number',feedback_ivr='$txt_feedback',hopper_level='$txt_hopper_level',group_id='$txt_group_id' where id='$txt_id'";
		//echo $stmt_update; exit;
			$rslt_update= mysqli_query($conn,$stmt_update);	
			
			
	}		
	}
	
	if($_POST['deleteCampaign'] == 'delete_Campaign'){	
			$deleteCampaignID = $_POST['deleteCampaignID'];		
			$stmt_delete = "delete from campaign where id='$deleteCampaignID'";
			
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
   <!-- <a href="../../index.php" class="brand-link" style="background-color: #1A2B6D; color:white;">
      <img src="../../dist/img/connect1.png" alt="Admin Logo" class="brand-image" style="opacity: .8" width ="60px" height="55px">
      <span class="brand-text font-weight-light" style="color:#1A2B6D;">Campaign</span>
    </a>-->
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
			  <li class="nav-item">
                <a href="group.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Group</p>
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
            <h1>Campaigns</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">Campaigns</li>
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
			<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD CAMPAIGN</button>
			<a class="btn btn-primary" href="webform_module.php">ADD WEBFORM TO CAMPAIGN</a>
		 <?php } ?>
		   </div>
	  
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">CAMPAIGN LISTINGS</h3>

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
                      <th style="width: 10%">
                         Sr.No.
                      </th>
                      <th style="width: 30%">
                          CAMPAIGN ID
                      </th>
                      <th style="width:30%">
                          CAMPAIGN NAME
                      </th>
					  <th style="width:30%">
                          DIAL METHOD
                      </th>
					   <th style="width:10%">
                          ACTIVE
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
						$stmt_select="SELECT * from campaign order by id DESC";
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
                             <?php echo $row["campaign_id"]; ?>
                          </a>
                      </td>
                      <td>
                         <?php echo $row["campaign_name"]; ?>
                      </td>
					  <td>
                         <?php echo $row["dial_method"]; ?>
                      </td>
					   <td>
							<?php
							if($row["active_camp"] == "Y"){
							?>
    <span style="background-color:#00b33c;border:10px solid #00b33c;border-radius:20px;color:white;"><?php echo $row["active_camp"]; ?></span>
	   <?php
							}else{
								?>
	<span style="background-color:#e60000;border:10px solid #e60000;border-radius:20px;color:white;"><?php echo $row["active_camp"]; ?></span>
								<?php 
							}
							?>
                            </td>
                     <?php if($loggedInUserLevel =="SuperAdmin"){?>
                      <td class="project-actions text-right">
                          <!-- <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal_edit"> -->
						 <span class="btn btn-info btn-sm"  onclick = "document.getElementById('myModal_edit').style.display='block'; campaignEditFun('<?php echo $row["id"]; ?>')">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
							  </span>
					   
					    <span class="btn btn-danger btn-sm"  onclick = "document.getElementById('myModal_delete').style.display='block'; campaignDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["campaign_id"]; ?>')">
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
  <div class="modal fade" id="myModal_edit" style="opacity: 3;">
    <div class="modal-dialog" style="overflow: scroll; height:600px;">
      <div class="modal-content" style="top:30px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">EDIT CAMPAIGN</h4>
          <button type="button" class="close1" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="CampaignID">Campaign ID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_Campaign_id" name="txt_Campaign_id" readonly>
                  </div>
                  <div class="form-group">
                    <label for="CampaignName">Campaign Name<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_Campaign_name" name="txt_Campaign_name" required>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Active <span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="txt_active_camp" name = "txt_active_camp" required>
                        <option value="">Select</option>
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">User Group</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_group_id" name = "txt_group_id">
					 <option value="">--Select--</option>
					<?php
                       $stmt_select1="SELECT group_id,group_name from user_group";
	                   $rslt_rs1= mysqli_query($conn,$stmt_select1);
	                 	while($row1 = mysqli_fetch_assoc($rslt_rs1)) {
							  ?>
						<option value = "<?php echo $row1["group_id"]; ?>" ><?php echo $row1["group_name"]; ?></option>
			        <?php } ?>
                    </select>
                  </div>
				   <div class="form-group">
                    <label for="CampaignName">Screen Label</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_next_agent_call" name = "txt_next_agent_call">
					 <option value="">Select</option>
					<?php
                       $stmt_select="SELECT * from screen_labels";
	                   $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			  
							  ?>
						<option value = "<?php echo $row["label_id"]; ?>" ><?php echo $row["label_name"]; ?></option>
			  <?php } ?>
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Dial Method</label>
             <select class="form-control select2" style="width: 100%;" id="txt_dial_method" name ="txt_dial_method" onchange="txtcampaignChange()">
                        <option value="">Select</option>
						 <option value="Predictive">Predictive</option>
                        <option value="Inbound">Inbound</option>
						 <option value="Manual">Manual</option>
						  <option value="Click2Call">Click2Call</option>
						  <option value="Blended">Blended</option>
						   <option value="Blended Predictive">Blended Predictive</option>
						  <option value="VoiceBlast">Voice Blast</option>
                        
                    </select>
                  </div>
				   <div class="form-group" id="txt_autoDialLevel" style="display:none;">
                    <label for="CampaignName">Auto Dial Level</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_auto_dial_level" name = "txt_auto_dial_level">
                        <option value="">Select</option>
                        <option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
                    </select>
                  </div>
				 
					<div class="form-group" id="txt_ingroup_display" style="display:none;">
					
					</div>
					 <div class="form-group" id="hopperLevel">
                    <label for="CampaignName">Hopper Level</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_hopper_level" name="txt_hopper_level">
                        <option value="">Select</option>
                        <option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
<option value="60">60</option>
<option value="61">61</option>
<option value="62">62</option>
<option value="63">63</option>
<option value="64">64</option>
<option value="65">65</option>
<option value="66">66</option>
<option value="67">67</option>
<option value="68">68</option>
<option value="69">69</option>
<option value="70">70</option>
<option value="71">71</option>
<option value="72">72</option>
<option value="73">73</option>
<option value="74">74</option>
<option value="75">75</option>
<option value="76">76</option>
<option value="77">77</option>
<option value="78">78</option>
<option value="79">79</option>
<option value="80">80</option>
<option value="81">81</option>
<option value="82">82</option>
<option value="83">83</option>
<option value="84">84</option>
<option value="85">85</option>
<option value="86">86</option>
<option value="87">87</option>
<option value="88">88</option>
<option value="89">89</option>
<option value="90">90</option>
<option value="91">91</option>
<option value="92">92</option>
<option value="93">93</option>
<option value="94">94</option>
<option value="95">95</option>
<option value="96">96</option>
<option value="97">97</option>
<option value="98">98</option>
<option value="99">99</option>
<option value="100">100</option>
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Dial Timeout </label>
                    <select class="form-control select2" style="width: 100%;" id="txt_dial_timeout" name ="txt_dial_timeout">
                        <option value="">Select</option>
						 <option value="30">30</option>
                        <option value="50">50</option>
						 <option value="60">60</option>
                        
                    </select>
                  </div>
				  <div class="form-group" style="display:none">
                    <label for="CampaignName">Park Music-on-Hold </label>
                   <input type="file" class="form-control" id="txt_park_music" name="txt_park_music"> 
                  </div>
				  
				  <div class="form-group">
                    <label for="CampaignName">Sticky Agent</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_sticky_agent" name ="txt_sticky_agent">
                        <option value="">Select</option>
						 <option value="Yes">Yes</option>
                        <option value="No">No</option>
						
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignID">Dial Prefix</label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_dial_prefix" name="txt_dial_prefix" placeholder="Enter Prefix"> 
                  </div>
				  
				  <div class="form-group">
                    <label for="CampaignName">Call Through<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="txt_call_going" name="txt_call_going" required>
                        <option value="">Select</option>
                        <option value="GSM">GSM</option>
                        <option value="SIP">SIP/PRI</option>
                    </select>
                  </div>
				  
				   <div class="form-group">
                    <label for="CampaignName">Channel<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="txt_channel" name="txt_channel" placeholder="Ex: SIP/JIO1/ or SIP/GSM01/ or DAHDI/g0/ etc .." required>
                  </div>
				  
				  <div class="form-group">
                    <label for="Ingroup">DID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="txt_did_number" name="txt_did_number" placeholder="Enter DID">
                  </div>
				  <?php
				  if($feedback_ivr == 1){
				  ?>
				  <div class="form-group">
                    <label for="CampaignName">Feedback IVR</label>
                    <select class="form-control select2" style="width: 100%;" id="txt_feedback" name ="txt_feedback">
                        <option value="">Select</option>
						 <option value="Yes">Yes</option>
                        <option value="No">No</option>
						
                    </select>
                  </div>
				  <?php } ?>
                </div>
                <!-- /.card-body -->
                <input type = "hidden" value = "" id ="dummy_park_music" name ="dummy_park_music">
                <input type = "hidden" value = "" id = "txt_id" name = "txt_id">
                <div class="card-footer">
				  <input type = "hidden" value="edit_Campaign" name="editCampaign" >
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
    <div class="modal-dialog" style="overflow: scroll; height:600px;">
      <div class="modal-content scrollit">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">ADD A NEW CAMPAIGN</h4>
          <button type="button" class="close5" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form action ="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="CampaignID">Campaign ID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="Campaign_id" name = "Campaign_id" placeholder="Enter Campaign ID" required> 
                  </div>
                  <div class="form-group">
                    <label for="CampaignName">Campaign Name<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="Campaign_name" name = "Campaign_name" placeholder="Enter Campaign Name" required>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Active<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="active_camp" name = "active_camp" required>
                        <option value="">Select</option>
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                  </div>
				   <div class="form-group">
                    <label for="CampaignName">User Group<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="group_id " name="group_id">
					 <option value="">--Select--</option>
                       <?php
                            $stmt_select1="SELECT group_id,group_name from user_group";
	                        $rslt_rs1= mysqli_query($conn,$stmt_select1);
			           while($row1 = mysqli_fetch_assoc($rslt_rs1)) {
							  ?>
						<option value = "<?php echo $row1["group_id"]; ?>" ><?php echo $row1["group_name"]; ?></option>
			        <?php } ?>
                    </select>
                  </div>
				   <div class="form-group">
                    <label for="CampaignName">Screen Label</label>
                    <select class="form-control select2" style="width: 100%;" id="next_agent_call " name="next_agent_call">
					 <option value="">Select</option>
                       <?php
                       $stmt_select="SELECT * from screen_labels";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			  
							  ?>
						<option value = "<?php echo $row["label_id"]; ?>" ><?php echo $row["label_name"]; ?></option>
			  <?php } ?>
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Dial Method</label>
                    <select class="form-control select2" style="width: 100%;" id="dial_method" name="dial_method" onchange="campaignChange()">
                        <option value="">Select</option>
						 <option value="Predictive">Predictive</option>
                        <option value="Inbound">Inbound</option>
						 <option value="Manual">Manual</option>
						  <option value="Click2Call">Click2Call</option>
						  <option value="Blended">Blended</option>
						  <option value="Blended Predictive">Blended Predictive</option>
                        <option value="VoiceBlast">Voice Blast</option>
                        
                    </select>
                  </div>
				   <div class="form-group" id="autoDialLevel" style="display:none;">
                    <label for="CampaignName">Auto Dial Level</label>
                    <select class="form-control select2" style="width: 100%;" id="auto_dial_level" name="auto_dial_level">
                        <option value="">Select</option>
                        <option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>

                    </select>
                  </div>
				 
					<div class="form-group" id="ingroup_display" style="display:none;">
					  <label>Ingroup</label><br>
			<?php			
			$stmt_select="SELECT * from ingroups";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
								 ?>
							
                       <input type="checkbox" id="ingroup" name ="ingroup[]" value="<?php echo $row["ingroup_id"] ; ?>"><?php echo $row["ingroup_id"] ; ?><br>
							 <?php } ?>
					 
					</div>
					 <div class="form-group" id="hopperLevel">
                    <label for="CampaignName">Hopper Level</label>
                    <select class="form-control select2" style="width: 100%;" id="hopper_level" name="hopper_level">
                        <option value="">Select</option>
                        <option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
<option value="60">60</option>
<option value="61">61</option>
<option value="62">62</option>
<option value="63">63</option>
<option value="64">64</option>
<option value="65">65</option>
<option value="66">66</option>
<option value="67">67</option>
<option value="68">68</option>
<option value="69">69</option>
<option value="70">70</option>
<option value="71">71</option>
<option value="72">72</option>
<option value="73">73</option>
<option value="74">74</option>
<option value="75">75</option>
<option value="76">76</option>
<option value="77">77</option>
<option value="78">78</option>
<option value="79">79</option>
<option value="80">80</option>
<option value="81">81</option>
<option value="82">82</option>
<option value="83">83</option>
<option value="84">84</option>
<option value="85">85</option>
<option value="86">86</option>
<option value="87">87</option>
<option value="88">88</option>
<option value="89">89</option>
<option value="90">90</option>
<option value="91">91</option>
<option value="92">92</option>
<option value="93">93</option>
<option value="94">94</option>
<option value="95">95</option>
<option value="96">96</option>
<option value="97">97</option>
<option value="98">98</option>
<option value="99">99</option>
<option value="100">100</option>
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignName">Dial Timeout </label>
                    <select class="form-control select2" style="width: 100%;" id="dial_timeout" name="dial_timeout">
                        <option value="">Select</option>
						 <option value="30">30</option>
                        <option value="50">50</option>
						 <option value="60">60</option>
                        
                    </select>
                  </div>
				  <div class="form-group" style="display:none">
                    <label for="CampaignName">Park Music-on-Hold </label>
                   <input type="file" class="form-control" id="park_music" name="park_music"> 
                  </div>
				  
				  <div class="form-group">
                    <label for="CampaignName">Sticky Agent</label>
                    <select class="form-control select2" style="width: 100%;" id="sticky_agent" name ="sticky_agent">
                        <option value="">Select</option>
						 <option value="Yes">Yes</option>
                        <option value="No">No</option>
						
                    </select>
                  </div>
				  <div class="form-group">
                    <label for="CampaignID">Dial Prefix</label>
<input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="dial_prefix" name="dial_prefix" placeholder="Enter Prefix"> 
                  </div>
				  
				  <div class="form-group">
                    <label for="CampaignName">Call Through<span style="color:red;">*</span></label>
                    <select class="form-control select2" style="width: 100%;" id="call_going" name="call_going" required>
                        <option value="">Select</option>
                        <option value="GSM">GSM</option>
                        <option value="SIP">SIP/PRI</option>
                    </select>
                  </div>
				  
				   <div class="form-group">
                    <label for="CampaignName">Channel<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="channel" name="channel" placeholder="Ex: SIP/JIO1/ or SIP/GSM01/ or DAHDI/g0/ etc .." required>
                  </div>
				  
				<div class="form-group">
                    <label for="Ingroup">DID<span style="color:red;">*</span></label>
                    <input type="text" class="form-control" pattern="^[0-9\s]*$" title="Please enter only numbers" id="did_number" name="did_number" placeholder="Enter DID">
                  </div>
				  <?php
				  if($feedback_ivr == 1){
				  ?>
				  <div class="form-group">
                    <label for="CampaignName">Feedback IVR</label>
                    <select class="form-control select2" style="width: 100%;" id="feedback" name ="feedback">
                        <option value="">Select</option>
						 <option value="Yes">Yes</option>
                        <option value="No">No</option>
						
                    </select>
                  </div>
				  <?php } ?>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input  type="submit" name="addCampaign" value="Submit" class="btn btn-primary">
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
        <!-- /.row -->
		
		
					<!-- The Modal Delete-->
  <div class="modal fade" id="myModal_delete" style="opacity: 3;top : 104px !important">
    <div class="modal-dialog">
      <div class="modal-content" style="top:40px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">DELETE CAMPAIGN</h4>
          <button type="button" class="close23" data-dismiss="modal">&times;</button>
        </div>
       
        <!-- Modal body -->
        <div class="modal-body">
         <form action = "" method = "POST">
                <div class="card-body">
                  <div class="form-group">
						<label for="UserID">Campaign ID</label>
						<input type="text" class="form-control" id="txt_deleteCampaign_id" name="txt_deleteCampaign_id" readonly>
					</div>
				
                </div>
				<input type = "hidden" value = "" id = "deleteCampaignID" name = "deleteCampaignID">
                <!-- /.card-body -->

                <div class="card-footer">
				  <input type = "hidden" value="delete_Campaign" name="deleteCampaign" >
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
function campaignChange(){
	var ingroup = document.getElementById("dial_method").value;
	//alert(ingroup);
	if(ingroup == "Blended" || ingroup == "Inbound" || ingroup == "Blended Predictive"){
		document.getElementById("ingroup_display").style.display ="block";
	}else if(ingroup == "Predictive"){
		document.getElementById("autoDialLevel").style.display ="block";
	}else{
		document.getElementById("ingroup_display").style.display ="none";
		document.getElementById("autoDialLevel").style.display ="none";
		
	}
}
</script>
<script>
function txtcampaignChange(){
	var ingroup = document.getElementById("txt_dial_method").value;
	//alert(ingroup);
	if(ingroup == "Blended" || ingroup == "Inbound" || ingroup == "Blended Predictive"){
		document.getElementById("txt_ingroup_display").style.display ="block";
		document.getElementById("txt_autoDialLevel").style.display ="none";
		var campval = 0;
		$.ajax({
			type: 'POST',
			url: "edit_camp_ingroup.php",
			data: {
				'campval': campval
				
			},
			success: function(result) {
			console.log(result);
				document.getElementById('txt_ingroup_display').innerHTML = result;
				
			}
			
		});
	}else if(ingroup == "Predictive"){
		document.getElementById("txt_autoDialLevel").style.display ="block";
		document.getElementById("txt_ingroup_display").style.display ="none";
	}else{
		document.getElementById("txt_ingroup_display").style.display ="none";
		document.getElementById("txt_autoDialLevel").style.display ="none";
		
	}
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
  
  
  
    
  
   function campaignEditFun(campval)
	{
		var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange=function() {
            if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
		    var val = this.responseText;
			var res = val.split("*");
		   // alert(res[6]);
			document.getElementById('txt_Campaign_id').value=res[0];
			document.getElementById('txt_Campaign_name').value=res[1];
			document.getElementById('txt_id').value=res[2];
			document.getElementById('txt_active_camp').value=res[3];
			document.getElementById('txt_auto_dial_level').value=res[4];
			document.getElementById('txt_next_agent_call').value=res[5];
			document.getElementById('txt_dial_method').value=res[6];
	if(res[6] == "Predictive"){
		document.getElementById("txt_autoDialLevel").style.display ="block";
		document.getElementById("txt_ingroup_display").style.display ="none";
		document.getElementById('txt_dial_timeout').value=res[7];
	}else if(res[6] == "Blended" || res[6] == "Inbound" || res[6] == "Blended Predictive"){
		document.getElementById("txt_ingroup_display").style.display ="block";
		document.getElementById("txt_autoDialLevel").style.display ="none";
		document.getElementById('txt_dial_timeout').value=res[7];
		//alert(campval);
		$.ajax({
			type: 'POST',
			url: "camp_ingroup.php",
			data: {
				'campval': campval
				
			},
			success: function(result) {
			//console.log(result);
				document.getElementById('txt_ingroup_display').innerHTML = result;
				
			}
			
		});
	}else{
		document.getElementById("txt_autoDialLevel").style.display ="none";
		document.getElementById("txt_ingroup_display").style.display ="none";
	}
		document.getElementById('dummy_park_music').value=res[8];
			
			document.getElementById('txt_sticky_agent').value=res[10];
			document.getElementById('txt_dial_prefix').value=res[11];
			document.getElementById('txt_call_going').value=res[12];
			document.getElementById('txt_channel').value=res[13];
			document.getElementById('txt_did_number').value=res[14];
			document.getElementById('txt_feedback').value=res[15];
			document.getElementById('txt_hopper_level').value=res[16];
			document.getElementById('txt_group_id').value=res[17];
			
			//alert(res[5]);
			}
        }; 
		xhttp.open("GET", "/haloocomConnect5/pages/Agent_mongodb/ajax/get_campaignedit_data.php?id="+campval, true);
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
  
  // Get the modal
        var modal5 = document.getElementById('myModal');

		
		// Get the <span> element that closes the modal
		var span5 = document.getElementsByClassName("close5")[0];
		// When the user clicks on <span> (x), close the modal
		span5.onclick = function() {
			document.getElementById('Campaign_id').value="";
			document.getElementById('Campaign_name').value="";
			document.getElementById('active_camp').value="";
			document.getElementById('auto_dial_level').value="";
			document.getElementById('next_agent_call').value="";
			document.getElementById('dial_method').value="";
			document.getElementById('dial_timeout').value="";
			document.getElementById('park_music').value="";
			document.getElementById('ingroup').value="";
			document.getElementById('sticky_agent').value="";
		modal5.style.display = "none";
		}
</script>
</body>
</html>
