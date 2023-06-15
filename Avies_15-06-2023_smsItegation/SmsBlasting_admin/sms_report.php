<?php    
date_default_timezone_set('Asia/Kolkata');
require("dbconnect_mysqli.php");
$db = $link;

session_start();


$current_date = date('Y-m-d');


?>
<html>
<head>
 <title>SMS Report</title>
	
	<script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	
	

 <!-- Default stylesheets-->
    <link href="assetsCSS/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template specific stylesheets-->
	
	
	
 
    <link href="assetsCSS/lib/animate.css/animate.css" rel="stylesheet">
    <link href="assetsCSS/lib/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assetsCSS/lib/et-line-font/et-line-font.css" rel="stylesheet">
    <link href="assetsCSS/lib/flexslider/flexslider.css" rel="stylesheet">
    <link href="assetsCSS/lib/owl.carousel/dist/assetsCSS/owl.carousel.min.css" rel="stylesheet">
    <link href="assetsCSS/lib/owl.carousel/dist/assetsCSS/owl.theme.default.min.css" rel="stylesheet">
    <link href="assetsCSS/lib/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    <link href="assetsCSS/lib/simple-text-rotator/simpletextrotator.css" rel="stylesheet">
    <!-- Main stylesheet and color file-->
    <link href="assetsCSS/css/style.css" rel="stylesheet">
	
	



<style>
.pagination>li {
display: inline;
padding:0px !important;
margin:0px !important;
border:none !important;
}


.btn {
display: inline-block;
padding: 6px 12px !important;
margin-bottom: 0;
font-size: 14px;
font-weight: 400;
line-height: 1.42857143;
text-align: center;
white-space: nowrap;
vertical-align: middle;
-ms-touch-action: manipulation;
touch-action: manipulation;
cursor: pointer;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
background-image: none;
border: 1px solid transparent;
border-radius: 4px;
}

.btn-primary {
color: #fff !important;
background: #428bca !important;
border-color: #357ebd !important;
box-shadow:none !important;
}
.btn-danger {
color: #fff !important;
background: #d9534f !important;
border-color: #d9534f !important;
box-shadow:none !important;
}

.styled-table {
	border-collapse: collapse !important;
    margin: 31px 0 !important;
    font-size: 15px !important;
    font-family: inherit;
    min-width: 400px !important;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) !important;
}
.styled-table thead tr {
    background-color: #a5d26b !important;
    color: #ffffff !important;
    text-align: left !important;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px !important;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd !important;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3 !important;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #228b22 !important;
}
.modal3 {
	display: none; /* Hidden by default */
    position: fixed;
    z-index: 10;
    left: 605px;
    top: 121px;
   // height: 75%;
   // width: 42%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    -webkit-animation-name: fadeIn;
    -webkit-animation-duration: 0.4s;
    animation-name: fadeIn;
    animation-duration: 0.4s;
}
.modal1 {
	display: none; /* Hidden by default */
    position: fixed;
    z-index: 10;
    left: 605px;
    top: 121px;
   // height: 75%;
   // width: 42%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    -webkit-animation-name: fadeIn;
    -webkit-animation-duration: 0.4s;
    animation-name: fadeIn;
    animation-duration: 0.4s;
}

.modal2 {
	display: none; /* Hidden by default */
    position: fixed;
    z-index: 10;
    left: 605px;
    top: 121px;
   // height: 75%;
   // width: 42%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    -webkit-animation-name: fadeIn;
    -webkit-animation-duration: 0.4s;
    animation-name: fadeIn;
    animation-duration: 0.4s;
}


.modal-content_new {
    position: fixed;
    top: 80px;
    left: 386px;
    background-color: #fefefe;
    //overflow: auto;
    width: 50%;
	//height: 60%;
    -webkit-animation-name: slideIn;
    -webkit-animation-duration: 0.4s;
    animation-name: slideIn;
    animation-duration: 0.4s;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
	border: 1px solid;
}

.modal-content {
  position: fixed;
  bottom: 0;
  background-color: #ffff;
  //overflow: auto;
  width: 30%;
  top:170px;
 // background-color: beige;
  height: 30%;
  -webkit-animation-name: slideIn;
  -webkit-animation-duration: 0.4s;
  animation-name: slideIn;
  animation-duration: 0.4s;
  margin-left: 587px;
}


/* The Close Button */
.close {
  color: #000;
  float: right;
  font-size: 28px;
  font-weight: bold;
}



.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}


.modal-header {
  padding: 2px 5px;
  //background-color: #6eba01;
  color: #000;
  TEXT-ALIGN: LEFT;
  }

.modal-body {padding: 2px 16px;}

tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
	
	form {
		//width: 55%;
		//margin-left: 185px;
		//margin-top: 25px;
		//padding: 30px;
		//border: 1px solid #eee;
	}
	input {
	  //width: 100%;
	  border: 1px solid #ccc;
	  //display: block;
	  padding: 1px 10px;
	}
   .button_upload {
	padding: 5px;
    border-radius: 5px;
    background-color: #36457E;
    color: white;
    border: none;
	}
	.dt-button-collection {
		    margin-top: 2.5px !important;
			margin-bottom: 5px !important;
	}
	
	.btn.btn-xs {
    font-size: 14px !important;
}
b{
	color: #36457E;
}
</style>

</head>
<body>
 
      <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
         <div class="navbar-header">
			<img src="white_logo.png" class="light-logo" alt="homepage" height="55px" width="155px" />
          </div>
        <div class="collapse navbar-collapse" id="custom-collapse">
		    <ul class="nav navbar-nav navbar-right">
			  <li><a class="section-scroll" style="font-size: 15px;margin-top: 10px;" href="./admin.php?ADD=999999">Reports</a></li>
			</ul>
          </div>
        </div>
		  
      </nav>
	

	  <hr>  <hr>
<div class="container">
<br>
	<div class="row">
		<font style="font-size:25px; margin-left:15px; color:#36457E;font-weight: 700;">SMS Reports</font>
	</div>
	
	<div class="row">
	<br>
			<div class="col-md-12">
				<div>
					<table cellpadding="0" cellspacing="0" bgcolor="#fff" style="background-color:#a5d26b;border: 5px solid #36457E;width: 100%;height: 20%;">  
					<form name="form1" method="post" action="#">  
					<tbody>
						<tr>
							<td><b>From:</b>&nbsp;&nbsp;<input type="date" id="min" name="min" required><!--&nbsp;&nbsp;<input type="text" id="min_time" name="min_time" SIZE="9" MAXLENGTH="8" value="" placeholder="00:00:00">--></td>
							<td><b>To:</b>&nbsp;&nbsp;<input type="date" id="max" name="max" required><!--&nbsp;&nbsp;<input type="text" id="max_time" name="max_time" SIZE="9" MAXLENGTH="8" value=""  placeholder="23:59:59">--></td>
							
							
							<td><input type="submit" name = "btn_submit" class="button_upload"> </td> 
						</tr>
					</tbody>	
					</form> 
					</table>
		 
		        <br>
				
				</div>
				 <?php
				if (isset($_POST["btn_submit"]) == "btn_submit") {
											
							$start_date = $_POST['min']; 
							$end_date = $_POST['max']; 					
							
				?>
					<label style="font-size: small;">Date Range : <?php echo "$start_date 00:00:00"; ?> to  <?php echo "$end_date 23:59:59"; ?></label>
					<br><br> 
					
				<?php			
				}else{ ?> 
				<label style="font-size: small;color: #36457E;">Please select from date and to date..</label>
				<?php }	?>
				<br>
				
           
			<table id="datatable4" class="table styled-table table-bordered" cellspacing="0" bgcolor="#fff" style="border: 5px solid #36457E;width: 100%;height: 20%;"> 
			 
			     <font style="font-size:20px; margin-left:15px; color:#36457E;font-weight: 700;"><b style="text-decoration: underline;">SMS Report</b></font>
				 
    				<thead>
						<tr>
						    <th>Sr/No</th>
							<th>Date & Time</th>
						    <th>Mobile Number</th>
							<th>Message</th>
						    <th>Status</th>
							
						</tr>
					</thead>
					<tbody>
					<?php
						require_once("dbconnect_mysqli.php");
					if (isset($_POST["btn_submit"]) == "btn_submit") {
											
							$start_date = $_POST['min']; 
							$end_date = $_POST['max'];
							
							
							$startDate = $start_date." 00:00:00";
							$endDate = $end_date." 23:59:59";
							
							$i=1;
							
							$sms_api_records = "select date_time,phone_number,message,status from sms_report where date_time >='$start_date 00:00:00' and date_time <= '$end_date 23:59:59'";
							$res_api_records = mysqli_query($link,$sms_api_records);
							while($rows_api_records=mysqli_fetch_assoc($res_api_records)){
								
								$date_and_time = $rows_api_records['date_time'];
								$mobile_number = $rows_api_records['phone_number'];
								$message = $rows_api_records['message'];
								$status = $rows_api_records['status'];
						?>
						    <tr>
							<td><?php echo "$i"; ?></td>
							<td><?php echo "$date_and_time";?></td>
							<td><?php echo "$mobile_number";?></td>
							<td><?php echo "$message";?></td>
							<td><?php echo "$status";?></td>
							</tr>
							
						<?php	
							$i++;
							} 
							?>
							
					</tbody>
					
					<?php }  ?>		
					
				</table>  

		<script>


$(document).ready(function() {
		
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#datatable4').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'csv',
				title:'SMS_Report_'+today
                
            }

		 
        ] 
    } );
} );




	</script>		
				
				
				
				

	
	</div>
	</div>
	
	
  
</div>
</div>



 </body>


</html>

