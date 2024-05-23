<?php

session_start();

$loggedInuserName  = $_SESSION['username'];
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

if ($_POST['addlist']) {

 
 $campaign = $_POST['campaign'];
  $ivr_assign = $_POST['ivr_assign'];

  $stmt_rs = "SELECT id from feedback_ivr where campaign='$campaign';";
  $rslt_rs = mysqli_query($conn, $stmt_rs);
  $row_rs = mysqli_fetch_row($rslt_rs);

  if (count($row_rs) == 0) {

  
    $target_dir = "feedback_ivr/";

    $target_file = $target_dir . basename($_FILES["uploadvoice"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($imageFileType != "mp3" && $imageFileType != "") {
      //  echo "File Format Not Suppoted";
      echo "<script>alert('File Format Not Suppoted(It accepts only mp3)');</script>";
    } else {
      $video_path = $_FILES['uploadvoice']['name'];

      $uploadSuccess = move_uploaded_file($_FILES["uploadvoice"]["tmp_name"], $target_file);

      if ($uploadSuccess) {
        $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);

        $allowedExtensions = ['mp3', 'ogg', 'wav', 'flac'];
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
          $newFilePath = $target_dir . pathinfo($video_path, PATHINFO_FILENAME) . '.wav';
          $newFilePath_audio = pathinfo($video_path, PATHINFO_FILENAME) . '.wav';
          if (!file_exists($newFilePath)) {
            $ffmpegCommand = "ffmpeg -i $target_file -acodec pcm_s16le -ar 8000 $newFilePath";

            exec($ffmpegCommand, $output, $returnCode);

            if ($returnCode === 0) {
              //echo basename($target_file);
            } else {
              //echo 'Error converting file to .wav.';
            }
          } else {
            // echo basename($target_file);
          }
        } else {
          // File is not an allowed audio format
        }
      } else {
        //echo 'Error uploading file.';
      }

     
            $date = date("Y-m-d H:i:s");
            $stmt_insert = "INSERT INTO feedback_ivr(created_date,campaign,audio_file,route_ivr) values('$date','$campaign','$newFilePath_audio','$ivr_assign')";
            $rslt_insert = mysqli_query($conn, $stmt_insert);
			 
          }
		  
  } else {
    echo "<script>alert('Feedback IVR alredy assigned for the selected campaign');</script>";
  }
}

if ($_POST['deleteVoiceblasting'] == 'delete_Voiceblast') {
  $deleteList = $_POST['deleteVoiceblast'];
  $stmt_delete = "delete from feedback_ivr where id='$deleteList'";

  $rslt_delete = mysqli_query($conn, $stmt_delete);
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
    thead th,
    table.dataTable thead td {
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

    .custom-dropdown {
      width: 100%;
      border: 1px solid #d8bebe;
      position: relative;
      margin-bottom: 43px;
      border-radius: 13px;
    }

    .dropdown-header {
      background-color: #f1f1f1;
      padding: 5px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .choose-audio-type {
      font-weight: bold;
    }

    .play-button {
      background-color: #007BFF;
      color: #fff;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      width: 26%;
      margin-bottom: 3px;
    }

    .play-button:hover {
      background-color: #0056b3;
    }


    .dropdown-item {
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 5px;
      border-bottom: 1px solid #ccc;
      cursor: pointer;
    }

    .dropdown-list {
      max-height: 200px;
      overflow-y: auto;
      display: none;
    }

    .dropdown-list.open {
      display: block;
    }

    .resizable-textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      resize: vertical;
      min-height: 100px;
    }

    #main_div_convertButton {
      background-color: #007bff;
      color: white;
      margin: auto;
      width: 32%;
      border-style: none;
      border-radius: 19px;
      padding: 7px;
	  text-align:center;
    }
  </style>
  <script>
    $(document).ready(function() {
      sampleDiv_zoom.style.zoom = '80%';
      var scale = 'scale(1)';
      document.body.style.webkitTransform = scale; 
      document.body.style.msTransform = scale; 
      document.body.style.transform = scale; 
    });
  </script>
</head>

<body class="hold-transition sidebar-mini" id="sampleDiv_zoom">
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
        <span class="brand-text font-weight-light" style="color:#1A2B6D;">Upload List</span>
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
              <h1>Feedback IVR</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                <li class="breadcrumb-item active">UPLOAD IVR</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="card-footer">
            <?php if ($loggedInUserLevel == "SuperAdmin") { ?>
              <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Upload IVR</button>&nbsp;&nbsp;
            <?php } ?>

            <?php if ($loggedInUserLevel == "SuperAdmin") { ?>
              <!--<span class="btn btn-danger" style="color:white;" onclick="bulkdelete()">Bulk Delete</span>-->
            <?php } ?>
          </div>

          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">UPLOADED FEEDBACK IVR</h3>

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
                          <!--   <th>
                        Check
                      </th>-->
                          <th>
                            Sr.No.
                          </th>
                          <th>
                            Created Date
                          </th>
                          <th>
                            Campaign
                          </th>
                          <th>
                            Audio File Name
                          </th>
						   <th>
                           IVR Name
                          </th>
                          
                          <?php if ($loggedInUserLevel == "SuperAdmin") { ?>
                            <th>
                              Action
                            </th>
                          <?php } ?>
                         

                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $stmt_select = "SELECT id,created_date,campaign,audio_file,route_ivr from feedback_ivr order by created_date DESC";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
                          
                        ?>
                          <tr>
                            <td>
                              <?php echo $x; ?>
                            </td>
                            <td>
                              <?php echo $row["created_date"]; ?>
                            </td>
                            <td>
                              <?php echo $row["campaign"]; ?>
                            </td>
                            <td>
                              <?php echo $row["audio_file"]; ?>
                            </td>
							 <td>
                              <?php echo $row["route_ivr"]; ?>
                            </td>
                           
                            <?php if ($loggedInUserLevel == "SuperAdmin") { ?>
                              <td class="project-actions text-right">

                                <span class="btn btn-danger" onclick="document.getElementById('myModal_delete').style.display='block'; voiceBlastDeleteFun('<?php echo $row["id"]; ?>','<?php echo $row["campaign"]; ?>')">
                                  <i class="fas fa-trash">
                                  </i>
                                  Delete
                                </span>
                              </td>
                              
                            <?php } ?>
                          </tr>
                        <?php $x++;
                        } ?>


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

      
          <!-- The Modal Edit -->
          <div class="modal fade" id="myModal">
            <div class="modal-dialog" style="overflow: scroll; height:400px;">
              <div class="modal-content scrollit">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Upload Feedback IVR</h4>
				  <button onclick="myFunctionForFAQ();" title="help" style="border: none;background-color: white;"><i class="fa fa-question-circle" aria-hidden="true" style="font-size:20px;margin-top:7px;"></i></button>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                     
                      <div class="form-group" id="campaign_display">
                        <label>Campaign</label>
                        <select class="form-control select2" style="width: 100%;" id="campaign" name="campaign">
                          <option value="">Select</option>
                          <?php
                          $stmt_select = "SELECT * from campaign where feedback_ivr='Yes'";
                          $rslt_rs = mysqli_query($conn, $stmt_select);

                          $x = 1;
                          while ($row = mysqli_fetch_assoc($rslt_rs)) {
                          ?>

                            <option value="<?php echo $row["campaign_name"]; ?>"><?php echo $row["campaign_name"]; ?></option>
                          <?php } ?>
                        </select>
                      </div>
					  
                      <div class="form-group" id="blast_display">
                        <label for="CampaignID">Feedback To</label>
                        <select class="form-control select2" style="width: 100%;" id="blast_assign" name="blast_assign" onchange="blastAssign()">
                          <option value="">Select</option>
                          <option value="Audio">Audio</option>
						   <option value="IVR">IVR</option>
                          <option value="tts">Text to Speech</option>
                        </select>
                      </div>
					  
 <div class="form-group" id="ivr_diaplay" style="display:none;">
                        <label for="CampaignName">Assign IVR</label>
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

                      <div class="form-group" id="tts-display" style="display: none;">
                        <label for="CampaignName">Choose audio type for Text to Speech</label>
                        <div class="custom-dropdown">
                          <div class="dropdown-header" id="dropdownHeader">
                            <span style="    margin: auto;color: black;font-weight: 600;">Choose Audio Type</span>
                            <div class="dropdown-icon">▼</div>
                          </div>
                          <div class="dropdown-list" id="voiceDropdown">
                          </div>
                        </div>
                        <audio controls id="audioPlayer" style="margin-left: 11%;">
              Your browser does not support the audio element.
            </audio>
            <input type="hidden" name="newFilePath_audio" id="newFilePath_audio" value="">

                        <textarea id="main_tts_inputtext" class="resizable-textarea" placeholder="Enter text to convert to audio..." rows="4" cols="40" style="display: block; margin-top: 3%; margin-bottom: 5%;"></textarea>
                        <span id="main_div_convertButton" aria-label="Convert Text to Audio" style="display: block;" onclick="convertButton()">Convert</span>
                      </div>

                      <div class="form-group" id="voice_diaplay" style="display:none;">
                        <label for="CampaignName">Voice File Upload ( mp3 format )</label>
                        <input type="file" name="uploadvoice" id="uploadvoice" class="form-control" />
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <input type="submit" name="addlist" value="Submit" class="btn btn-primary">
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
                  <h4 class="modal-title">DELETE FEEDBACK IVR</h4>
                  <button type="button" class="close23" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  <form action="" method="POST">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="UserID">Campaign</label>
                        <input type="text" class="form-control" pattern="^[a-zA-Z0-9\s]*$" title="Please enter only numbers and characters" id="txt_deletelistId" name="txt_deletelistId" readonly>
                      </div>

                    </div>
                    <input type="hidden" value="" id="deleteVoiceblast" name="deleteVoiceblast">
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <input type="hidden" value="delete_Voiceblast" name="deleteVoiceblasting">
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
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

  <script>
  
  
function myFunctionForFAQ() {
  var myWindow = window.open("help/feedbackIVR.html", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,width=400,height=400");
}

    document.addEventListener('DOMContentLoaded', async () => {
      const voiceDropdown = document.getElementById('voiceDropdown');
      const dropdownHeader = document.getElementById('dropdownHeader');

      let isOpen = false;

      dropdownHeader.addEventListener('click', () => {
        isOpen = !isOpen;
        voiceDropdown.classList.toggle('open', isOpen);

        if (isOpen && voiceDropdown.children.length === 0) {
          loadVoices();
        }
      });

      async function loadVoices() {
        try {
          const response = await fetch('https://api.elevenlabs.io/v1/voices', {
            method: 'GET',
            headers: {
              'accept': 'application/json',
              'xi-api-key': '8540f423a0e16397b4c1e07425d34fce',
            },
          });

          if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
          }

          const data = await response.json();

          data.voices.sort((a, b) => a.name.localeCompare(b.name));

          voiceDropdown.innerHTML = '';

          const chooseAudioTypeItem = document.createElement('div');
          chooseAudioTypeItem.classList.add('dropdown-item', 'choose-audio-type');
          chooseAudioTypeItem.addEventListener('click', (e) => {
            e.stopPropagation();
          });

          voiceDropdown.appendChild(chooseAudioTypeItem);

          data.voices.forEach(voice => {
            const dropdownItem = document.createElement('div');
            dropdownItem.classList.add('dropdown-item');

            const name = document.createElement('span');
            name.textContent = voice.name;

            const playButton = document.createElement('button');
            playButton.classList.add('play-button');
            playButton.textContent = 'Play';

            playButton.addEventListener('click', (e) => {
              e.stopPropagation();
              if (voice.preview_url) {
                const audioPlayer = new Audio(voice.preview_url);
                audioPlayer.play();
              }
            });

            dropdownItem.addEventListener('click', () => {
              dropdownHeader.querySelector('span').textContent = voice.name;
              isOpen = false;
              voiceDropdown.classList.remove('open');
              localStorage.setItem('selectedVoiceId', voice.voice_id);
              // alert(`Voice ID: ${voice.voice_id}`);
            });

            dropdownItem.appendChild(name);
            dropdownItem.appendChild(playButton);
            voiceDropdown.appendChild(dropdownItem);
          });
        } catch (error) {
          console.error('Error fetching data:', error);
        }
      }
    });

    
		function convertButton(){
	const main_level_convert_button = document.getElementById("main_div_convertButton");
    const levelTextInput = document.getElementById("main_tts_inputtext");
    main_level_convert_button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Converting';
    const selectedVoiceId = localStorage.getItem("selectedVoiceId");
    const audioPlayer = document.getElementById("audioPlayer");
    const inputText = levelTextInput.value;
    console.log('Input Text:', inputText);
    console.log(selectedVoiceId);

    const formData = new FormData();
    formData.append('text', inputText);
    formData.append('voice_id', selectedVoiceId);
    fetch('audioblast_tts.php', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        // const updated_main_tts_url = "VoiceBlast/" + data.filename;
        main_level_convert_button.textContent = 'Convert';
        audioPlayer.src = data.filename;
        document.getElementById("newFilePath_audio").value = data.filename;
      })
      .catch(error => console.error(error));

  };

   

    function blastAssign() {
      var blast_name = document.getElementById("blast_assign").value;
      if (blast_name == "Audio") {
		  document.getElementById("tts-display").style.display = "none";
		  document.getElementById("ivr_diaplay").style.display = "none";
        document.getElementById("voice_diaplay").style.display = "block";
        
      }  else if (blast_name == "IVR") {
        document.getElementById("voice_diaplay").style.display = "none";
		 document.getElementById("tts-display").style.display = "none";
        document.getElementById("ivr_diaplay").style.display = "block";
       
      } else if (blast_name == "tts") {
        document.getElementById("voice_diaplay").style.display = "none";
		 document.getElementById("ivr_diaplay").style.display = "none";
        document.getElementById("tts-display").style.display = "block";
      } else {
        document.getElementById("tts-display").style.display = "none";
        document.getElementById("voice_diaplay").style.display = "none";
		 document.getElementById("ivr_diaplay").style.display = "none";
      }
    }

    function campaignChange() {
      var campaign = document.getElementById("voiceBlast").value;
      //alert(campaign);
      const xhttp = new XMLHttpRequest();
      xhttp.onload = function() {
        var result = this.responseText;

        document.getElementById('remote_agent').innerHTML = result;
        //console.log(result);

      }
      xhttp.open("GET", "ajax/remoteAgents.php?campaign=" + campaign, true);
      xhttp.send();
    }
  </script>

  <script>
    $(function() {

      $('#table_camp').dataTable();
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('dd/mm/yyyy', {
        'placeholder': 'dd/mm/yyyy'
      })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date picker
      $('#reservationdate').datetimepicker({
        format: 'L'
      });

      //Date and time picker
      $('#reservationdatetime').datetimepicker({
        icons: {
          time: 'far fa-clock'
        }
      });

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
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
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

      $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
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
      file.previewElement.querySelector(".start").onclick = function() {
        myDropzone.enqueueFile(file)
      }
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





    function listuploadedit() {

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
	
	
	
	 function listuploaddnc() {

      // Get the modal
      var modal1 = document.getElementById('myModal_DNC');
      // Get the button that opens the modal
      var btn1 = document.getElementById("myBtngrpedit");
      // Get the <span> element that closes the modal
      var span1 = document.getElementsByClassName("close1_dnc")[0];
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
	
	
	function listdownloaddnc() {

      // Get the modal
      var modal1 = document.getElementById('myModal_DDNC');
      // Get the button that opens the modal
      var btn1 = document.getElementById("myBtngrpedit");
      // Get the <span> element that closes the modal
      var span1 = document.getElementsByClassName("close1_ddnc")[0];
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
	
	
	
	
	function listremovednc() {

      // Get the modal
      var modal1 = document.getElementById('remove_DNC');
      // Get the button that opens the modal
      var btn1 = document.getElementById("myBtngrpedit");
      // Get the <span> element that closes the modal
      var span1 = document.getElementsByClassName("close1_remove")[0];
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






    function voiceBlastDeleteFun(val_grpd, CampaignIDVal) {
      document.getElementById('deleteVoiceblast').value = val_grpd;
      document.getElementById('txt_deletelistId').value = CampaignIDVal;

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
    /*
  function bulkdelete(){

var checkboxes = document.getElementsByName('bulkList[]');
            var path = "";
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    path += "'" + checkboxes[i].value
                        + "'" + ",";
                }
            }
           
			
$.ajax({
			type: 'POST',
			url: "bulklist_delete.php",
			data: {
				'path': path
			},
			success: function(result) {
				
				//alert(result);		
location.reload();
	}
		});
 //window.location.reload();
}
*/
  </script>
</body>

</html>