<?php

session_start();

	$username = $_SESSION['username'];
	$password = $_SESSION['password'];

if($username=="" && $password=="" ){
	header('location:authentication-login.php');
}
require('dbconnect_mysqli.php');
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Haloo-xchange</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/multicheck/multicheck.css">
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	
	
	
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<link href="dist/css/Hover-master/css/hover.css" rel="stylesheet" media="all">   <!-- application model css -->
<style>
/* Applications model ess start */
		 .w3-modal-content2_new{
		     width: 800px !important;;
			 background-color: #ffff;
			 margin-left: 425px;
	 }
	 	.modal-content_new {
			width:800px !important;
		}
	 	.hvr-shutter-in-horizontal {
			width:200px;
			height:46px !important;
			 margin: 5px;
			 border-radius:5px;
			
		}
			.text_align{
			    position: absolute;
				top: 14px;
				left: 25px;
				font-weight: bold;
				color: #1a2b6d;
				font-family: "Nunito Sans", sans-serif;
				font-size: 0.875rem;
		}
		.app_pop_class{z-index:3;display:none;padding-top:100px;position:fixed;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgb(0,0,0);background-color:rgba(0,0,0,0.4)}
			.btn_pop{
	 background-color:#8bc541;
	 padding:5px 5px;
	 font-size:11px;
	 border-radius:5px;
	 color:#fff; 
	 }
	 .btn_pop:hover{
		 background-color: #1a2b6d;
	 }
	 .modal-header {
  padding: 19px 1px;
  background-color: #1a2b6d;
  color: white;
  text-align: center;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.modal-content {	
	border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
	}
 
		/* Applications model ess end */

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 50 px;
  top: 20;
  width:50%; /* Full width */
  height: 50%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto; /* 15% from the top and centered */
  padding: 20px;
  border: none;
  width: 100%; /* Could be more or less, depending on screen size */
}








.btn {
  background-color:transparent;
  border: none;
  color: #1a2b6d;
  padding: 5px 5px;
  font-size: 16px;
  cursor: pointer;
}

.btn2 {
  background-color:transparent;
  border: none;
  color: #1a2b6d;
  padding: 5px 5px;
  font-size: 16px;
  cursor: pointer;
}


/* Darker background on mouse-over */
.btn:hover {
  background-color: #8bc541;
}
	
	
<style>
	#sip_setting{
	 background-color:#6eba01;
	 padding:5px 10px;
	 font-size:20px;
	 border-radius:5px;	 
	 
	 }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

 <style>
	#main-wrapper[data-sidebartype=full] .page-wrapper {
     margin-left: 0px !important; 
}
	.page-wrapper{
		margin-left:0px !important;
	}
	
        /*Position and style for the sidebar*/ 
          
        .sidebar { 
            height: 93%; 
            width: 0; 
           position: absolute; 
		   z-index: 1;
            /*Stays in place */ 
            background-color: #6eba01; 
            /*green*/ 
            overflow-x: hidden; 
            /*for Disabling horizontal scroll */ 
        } 
		#sidebar { 
         disply:none; 
            width: 0; 
           position: absolute; 
		   z-index: 1;
            /*Stays in place */ 
            background-color: #ffff; 
            /*green*/ 
            overflow-x: hidden; 
            /*for Disabling horizontal scroll */ 
        } 
        /* Position and style for the sidebar links */ 
          
        .sidebar a { 
            padding: 10px 10px 10px; 
            //font-size: 25px; 
            color: #111; 
            display: block; 
            transition: 0.3s; 
        } 
        /* the links change color when mouse hovers upon them*/ 
          
       /* .sidebar a:hover { 
            color: #FFFFFF; 
        } */
        /* Position and style the for cross button */ 
          
        .sidebar .closebtn { 
            position: absolute; 
            top: 0; 
            right: 25px; 
        } 
        /* Style for the sidebar button */ 
          
        .openbtn,.mdi-menu { 
            font-size: 32px; 
            background-color: #1a2b6d; 
            color: #FFFFFF; 
            //padding: 10px 10px 10px; 
            border: none; 
        } 
        /* the sidebar button changes  
      color when mouse hovers upon it */ 
		.openbtn:hover { 
		  color: #FFFFFF; 
		} 
  
      /* pushes the page content to the right 
      when you open the side navigation */ 
          
        #main { 
            transition: margin-left .5s; 
            /* If you want a transition effect */ 
            padding: 10px; 
        } 
	.topbar .top-navbar .navbar-nav>.nav-item>.nav-link {
		padding-top: 5px !important;
	}
    </style> 
		<script> 
    /* Sets the width of the sidebar  
    to 250 and the left margin of the  
    page content to 250 */ 
	document.getElementById("sidebar1").style.width = "0";
   /* function openNav() { 
		//alert("open menu");
		document.getElementById("sidebar1").style.width = "250px";
        document.getElementById("sidebar").style.width = "250px"; 
        document.getElementById("main").style.marginLeft = "0px"; 
		//alert("sidebar");
    } */
  
    /* Set the width of the sidebar  
    to 0 and the left margin of the  
    page content to 0 */ 
    function closeNav() { 
		//alert('test');
		document.getElementById("sidebar1").style.width = "0";
        document.getElementById("sidebar").style.width = "0"; 
        document.getElementById("main").style.marginLeft = "0"; 
    }

</script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" id = "sidebar1" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand">
                      
                        <span class="logo-text">
                             <!-- dark Logo text -->
                             <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" />
                            
                        </span>
                        
                    </a>
                   
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                  <ul class="navbar-nav float-left mr-auto">
						<li id = "main"><a class="openbtn" onclick="openNav()"> <i class="mdi mdi-menu font-24"></i></a></li>                   
				  <!--     <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                       
                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li> -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                       
                       
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                            
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a></div>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
		<div id="sidebar" class="sidebar">   
	   <aside class="left-sidebar1" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
			<a href="javascript:void(0)" class="closebtn sidebar-link waves-effect waves-dark sidebar-link" onclick="closeNav();"><i class="fa fa-window-minimize" style="font-size:20px;color:#1a2b6d"></i></a>	<br>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/index.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Real Time Graph</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/management-dashboard.php" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span class="hide-menu">Management Dashboard</span></a></li>  <!--Charts-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/trunk.php" aria-expanded="false"><i class="mdi mdi-phone-settings"></i><span class="hide-menu">Trunk</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/inbound-routes.php" aria-expanded="false"><i class="mdi mdi-rotate-left-variant"></i><span class="hide-menu">Inbound Routes</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/outbound-routes.php" aria-expanded="false"><i class="mdi mdi-rotate-right-variant"></i><span class="hide-menu">Outbound Routes</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../us/html/ltr/extension.php" aria-expanded="false"><i class="mdi mdi-phone-in-talk"></i><span class="hide-menu">Extension</span></a></li>
					<!--	<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="application.php" aria-expanded="false"><i class="mdi mdi-pencil"></i><span class="hide-menu">Applications</span></a></li> -->
					<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#" onclick = "document.getElementById('app_pop').style.display='block'" aria-expanded="false"><i class="mdi mdi-pencil"></i><span class="hide-menu" style="color:#1A2B6D">Applications</span></a></li>	
					
						<!--<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="conf.php" aria-expanded="false"><i class="mdi mdi-account-multiple-plus"></i><span class="hide-menu">Addons</span></a></li>-->
						
						<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#" onclick = "document.getElementById('addons_pop').style.display='block'" aria-expanded="false"><i class="mdi mdi-account-multiple-plus"></i><span class="hide-menu" style="color:#1A2B6D">Addons</span></a></li>
						
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
		</div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                              
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
			
			
			
	
			
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
				
                    <div class="col-12">
					                      
                        <div class="card">
                            <div class="card-body">
							
							<br><br>							
							<a href="download_voiceFiles.php" style="text-decoration: underline";>Download VoiceFiles</a>
							<br><br><br>	
								
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
   <button class ="btn2" onClick="closespan();" /><i class="fas fa-close"></i></button>
   </br>
    <p>  <audio controls id="myAudio"> <source src="horse.ogg" type="audio/ogg"></audio></p>
  </div>

</div>
								
								
								
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
											
                                                <th>Id</th>
                                                <th>DateTime</th>
												<th>Extension</th>
												<th>Number</th>
												<th>Status</th>
												<th>Duration</th>
												<th>Recording File</th>
												<th>Play/Download</th>
												
                                            </tr>
                                        </thead>
                                   
                                       </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
			
				
				



							
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                All Rights Reserved by Haloocom. Designed and Developed by <a href="https://haloocom.com">Haloocom</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
	<script>
/****************************************
 Basic Table 
****************************************/
//$('#zero_config').DataTable();

$(document).ready(function() {
    $('#zero_config').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server.php"
    } );
} );

function play(val)
{
	 //alert(val);
	 
	 var modal = document.getElementById('myModal');
     modal.style.display = "block";
	 
	 
	 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange=function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("myAudio").src= '';
		//alert(this.responseText);
     document.getElementById("myAudio").src = this.responseText;
    }
  };
  xhttp.open("GET", "get_file.php?id=" + val, true);
  xhttp.send();	
}


function closespan()
{
	
	var modal = document.getElementById("myModal");
    modal.style.display = "none";
	var x = document.getElementById("myAudio"); 
	x.pause();
	var voice_file = document.getElementById("myAudio").src;
	
	 var xhttp1 = new XMLHttpRequest();
     xhttp1.onreadystatechange=function() {
    if (this.readyState == 4 && this.status == 200) {
		//alert(this.responseText);
    }
  };
  xhttp1.open("GET", "del_file.php?voice_file=" + voice_file, true);
  xhttp1.send();	
	
	
    modal.style.display = "none";
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
	

</script>
 <!-- Applications pop div start-->
	<div id="app_pop" class="app_pop_class">
						<br>
				 
				<div class="w3-modal-content2  w3-modal-content2_new  w3-animate-left w3-card-4">	
						<div class="modal-content_new">      
							<div class="modal-header">
								<div align = "left"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Applications</h4></div>
							  
							</div>
							<div class="modal-body" >
						  <div align = "center">
							<a href="../../../us/html/ltr/ivr.php" class="hvr-shutter-in-horizontal"> <span class = "text_align">IVR</span></a>
							<a href="../../../us/html/ltr/announcement.php" class="hvr-shutter-in-horizontal"><span class = "text_align">ANNONCEMENT</span></a>
							<a href="../../../us/html/ltr/disa.php" class="hvr-shutter-in-horizontal"><span class = "text_align">DISA</span></a>
							<br>
							<a href="../../../us/html/ltr/queue.php" class="hvr-shutter-in-horizontal"><span class = "text_align">QUEUES</span></a>				
							<a href="../../../us/html/ltr/follow_me.php" class="hvr-shutter-in-horizontal"><span class = "text_align">FOLLOW ME</span></a>				
							<a href="../../../us/html/ltr/time_group.php" class="hvr-shutter-in-horizontal"><span class = "text_align">TIME GROUP</span></a>
							<br>
							<a href="../../../us/html/ltr/time_conditions.php" class="hvr-shutter-in-horizontal"><span class = "text_align">TIME CONDITION</span></a>
							<a href="../../../us/html/ltr/voicemail.php" class="hvr-shutter-in-horizontal"><span class = "text_align">VOICEMAIL TO EMAIL</span></a>							
							<a href="../../../us/html/ltr/speeddial.php" class="hvr-shutter-in-horizontal"><span class = "text_align">SPEED DAIL</span></a>	
							<br>	
							<a href="../../../us/html/ltr/pinset.php" class="hvr-shutter-in-horizontal" style="left:-106px;"><span class = "text_align">PINSET</span></a>
							<a href="../../../us/html/ltr/system_records.php" class="hvr-shutter-in-horizontal" style="left: -106px;"><span class = "text_align">SYSTEM RECORDS</span></a>
							<!--<a href="" class="hvr-shutter-in-horizontal" ><span class = "text_align"></span></a>-->
						</div>
						    
			
					</div>
						 <div class="modal-footer">
          <button type="button"  onclick="document.getElementById('app_pop').style.display='none'" class="btn_pop btn-danger" data-dismiss="modal">Close</button>
        </div>
				</div>
			
			</div>
			
		</div>
		<!-- Applications pop div end-->
		
			<!-- addons pop start -->
		<div id="addons_pop" class="app_pop_class">
						<br>
				 
				<div class="w3-modal-content2 w3-modal-content2_new w3-animate-left w3-card-4">	
						<div class="modal-content_new">      
							<div class="modal-header">
								<div align = "left"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Addons</h4></div>
							  
							</div>
							<div class="modal-body" >
						  <div align = "center">
							<a href="../../../dialout/agent.php" class="hvr-shutter-in-horizontal"> <span class = "text_align">DIALOUT CONFERENCE</span></a>
							<a href="../../../dialin/agent.php" class="hvr-shutter-in-horizontal"><span class = "text_align">DIALIN CONFERENCE</span></a>
							<a href="../../../us/html/ltr/billing.php" class="hvr-shutter-in-horizontal"><span class = "text_align">BILLING</span></a>
							<br>
							<a href="../../../Haloocom_report/report.php" class="hvr-shutter-in-horizontal" style="left:-108px;"><span class = "text_align">REPORTS</span></a>				
							<a href="https://golive.haloocom.in/" target="_blank" class="hvr-shutter-in-horizontal" style="left:-108px;"><span class = "text_align">VIDEO CONFERENCE</span></a>				
						<!--	<a href="time_group.php" class="hvr-shutter-in-horizontal"><span class = "text_align">TIME GROUP</span></a>
							<br>
							<a href="time_conditions.php" class="hvr-shutter-in-horizontal"><span class = "text_align">TIME CONDITION</span></a>
							<a href="voicemail.php" class="hvr-shutter-in-horizontal"><span class = "text_align">VOICEMAIL TO EMAIL</span></a>							
							<a href="speeddial.php" class="hvr-shutter-in-horizontal"><span class = "text_align">SPEED DAIL</span></a>	
							<br>	
							<a href="pinset.php" class="hvr-shutter-in-horizontal" style="left:-106px;"><span class = "text_align">PINSET</span></a>
							<a href="system_records.php" class="hvr-shutter-in-horizontal" style="left: -106px;"><span class = "text_align">SYSTEM RECORDS</span></a>
							<!--<a href="" class="hvr-shutter-in-horizontal" ><span class = "text_align"></span></a>-->
						</div>
						    
			
					</div>
						 <div class="modal-footer">
          <button type="button"  onclick="document.getElementById('addons_pop').style.display='none'" class="btn_pop btn-danger" data-dismiss="modal">Close</button>
        </div>
				</div>
			
			</div>
			
		</div>
		<!-- addons pop end -->

</body>

</html>