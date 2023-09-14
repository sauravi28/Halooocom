<?php    
date_default_timezone_set('Asia/Kolkata');
require("dbconnect_mysqli.php");
$db = $link;

session_start();


$current_date = date('Y-m-d');


?>
<html>
<head>
 <title>Agent Performance Detail</title>
	
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
	
	<script>
	$(document).ready(function() {
		
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#datatable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'csv',
				title:'DropCall_Report_'+today
                
            }

		 
        ] 
    } );
} );


/*$(document).ready(function() {
	
	
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#datatable').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value="">select</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'excelHtml5',
				title:'Agent_Chat_Report_'+today
                
            }

		 
        ] 
    } );
} );*/
	/*$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#datatable tfoot th').each( function () {
		var title = $(this).text();       
		  if(title != '') //
			{
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			}
		
    } );
 
    // DataTable
    var table = $('#datatable').DataTable({
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        },
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'excelHtml5',
				title:'agentData_excel'
                
            }

		 
        ] 
    });
 
} );*/

	</script>



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
    background-color: #6eba01;
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
<br><br>
	<div class="row">
		<font style="font-size:25px; margin-left:15px; color:#36457E;font-weight: 700;">Agent Performance Detail</font>
	</div>
	
	<div class="row">
	<br><br>
			<div class="col-md-12">
				<div>
					<table cellpadding="0" cellspacing="0" bgcolor="#fff" style="background-color:#a5d26b;border: 5px solid #36457E;width: 100%;height: 20%;">  
					<form name="form1" method="post" action="#">  
					<tbody>
						<tr>
							<td style="width: 20%;">&nbsp;&nbsp;From:&nbsp;&nbsp;<input type="date" id="min" name="min"><!--&nbsp;&nbsp;<input type="text" id="min_time" name="min_time" SIZE="9" MAXLENGTH="8" value="" placeholder="00:00:00">--></td>
							<td style="width: 20%;">To:&nbsp;&nbsp;<input type="date" id="max" name="max"><!--&nbsp;&nbsp;<input type="text" id="max_time" name="max_time" SIZE="9" MAXLENGTH="8" value=""  placeholder="23:59:59">--></td>
							<td>
								<font size="3">Campaign</font> </b>&nbsp;&nbsp;:&nbsp;&nbsp;<select id="txt_campaign" name = "txt_campaign[]" multiple style="height: 100px;">
								<option value ="<?php echo $txt_campaign; ?>" selected><?php echo $txt_campaign; ?></option>
								<?php 
								$query = "SELECT * FROM vicidial_campaigns";
								//echo $query;
								$result = mysqli_query($db,$query);
								while ($row = mysqli_fetch_array($result)){
									echo "<option value = ".$row['campaign_id'].">" . $row['campaign_id'] . "</option>";
								}			
								?>
								
							</select></td> 	
							<td>
								<font size="3">User</font> </b>&nbsp;&nbsp;:&nbsp;&nbsp;<select id="txt_user" name = "txt_user[]" multiple style="height: 100px;">
								<option value ="<?php echo $txt_user; ?>" selected><?php echo $txt_user; ?></option>
								<?php 
								$query = "SELECT user,full_name FROM vicidial_users order by user";
								//echo $query;
								$result = mysqli_query($db,$query);
								while ($row = mysqli_fetch_array($result)){
									echo "<option value = ".$row['user'].">" . $row['user'] . " - " . $row['full_name'] . " </option>";
								}			
								?>
								
							</select></td> 	
							<td></td>  
							<td><input type="submit" name = "btn_submit" class="button_upload"> </td> 
						</tr>
					</tbody>	
					</form> 
					</table>
		 
		        <br><br>
				
				</div>
				
             <table id="datatable" class="table styled-table table-bordered" cellspacing="0" bgcolor="#fff" style="border: 5px solid #36457E;width: 100%;height: 20%;position: absolute;left: -190px;"> <!--table-bordered -->
			 
			   <?php
				if (isset($_POST["btn_submit"]) == "btn_submit") {
											
							$start_date = $_POST['min']; 
							$end_date = $_POST['max']; 
							$campaign = $_POST['txt_campaign'];
							$user = $_POST['txt_user'];
							
							//Campaign
							
								foreach ($campaign as $campaignVal1){
										$Camp .= "'$campaignVal1',";
									}
								$campaignVal2 = rtrim($Camp, ',');

								if($campaignVal2 == ''){
									
									 $SelectedCamp = "''";
								}
								else{
									$SelectedCamp = $campaignVal2;
								}
								
							//User	
								
								foreach ($user as $userVal1){
										$USER1 .= "'$userVal1',";
									}
								$userVal2 = rtrim($USER1, ',');

								if($userVal2 == ''){
									
									 $SelectedUser = "''";
								}
								else{
									$SelectedUser = $userVal2;
								}
							
							
							/*$start_date_Time = $_POST['min_time']; 
							$end_date_Time = $_POST['max_time']; 
							
							if ($start_date_Time =="") 
							{
								$start_date_TimeVal="00:00:00";
							}
							else
							{
								$start_date_TimeVal = $start_date_Time;
							}
							if ($end_date_Time =="") {
								
								$end_date_TimeVal="23:59:59";
							}
							else
							{
								$end_date_TimeVal=$end_date_Time;
							}*/
							
							
							
				?>
					<label style="font-size: small;">Date Range : <?php echo "$start_date 00:00:00"; ?> to  <?php echo "$end_date 23:59:59"; ?></label>
					<br><br>
				<?php			
				}else{ ?> 
				<label style="font-size: small;">Please select Date,Campaign and User Value..</label>
				<?php }	?>
				<br><br> 	
			
			     
    				<thead style="font-size: smaller;">
						<tr>
						    <th>USER NAME</th>
						    <th>ID</th>
						    <th>CURRENT USER GROUP</th>
							<th>LOGIN DATE</th>
							<th>LOGIN TIME</th>
							<th>LOGOUT DATE</th>
							<th>LOGOUT TIME</th>
							<th>CALLS</th>
							<th>TIME</th>
							<th>PAUSE</th>
							<th>PAUS AVG</th>
							<th>WAIT</th>
							<th>WAIT AVG</th>
							<th>TALK</th>
							<th>TALK AVG</th>
							<th>DISPO</th>
							<th>DISP AVG</th>
							<th>DEAD</th>
							<th>DEAD AVG</th>
							<th>CUSTOMER</th>
							<th>CUSTOMER AVG</th>
						</tr>
					</thead>
						
					<tbody>
					<?php
						
					if (isset($_POST["btn_submit"]) == "btn_submit") {
					
					                    //$sel_user = "select call_date,campaign_id,phone_number,status from vicidial_closer_log where call_date >='$start_date 00:00:00' and call_date <= '$end_date 23:59:59' ORDER by `call_date` DESC ";
										$sel_user = "SELECT user,campaign_id,user_group FROM `vicidial_agent_log` WHERE event_time >='$start_date 00:00:00' and event_time <= '$end_date 23:59:59' and campaign_id IN ($SelectedCamp) and user IN ($SelectedUser) GROUP BY user";
										//echo   $sel_user;
										//echo "<br>";
										$res_User = mysqli_query($db,$sel_user);
									
										$a=1;
										while($row=mysqli_fetch_assoc($res_User)){
										
                                           $UserID = $row['user'];
										   $campaign_id = $row['campaign_id'];
										   $user_group = $row['user_group'];
										   
										   $query = "SELECT full_name FROM vicidial_users where user='$UserID'";
										   $result = mysqli_query($db,$query);
										   $rowUserName  = mysqli_fetch_array($result);
										   $UserName = $rowUserName[0];
								
											$sel_login_time = "select  DATE(event_date) as login_date, TIME(event_date) as logintime from vicidial_user_log where event_date >='$start_date 00:00:00' and event_date <= '$end_date 23:59:59' and user='$UserID' and event='LOGIN' ORDER by `event_date` ASC";
											//echo $sel_login_time."<br>";
											$res_login_time = mysqli_query($db,$sel_login_time);
											$row_login_time = mysqli_fetch_array($res_login_time);
											$logindt = $row_login_time[0];
											$loginTime = $row_login_time[1];

											// ############ login time ##########

											// ############ logout time ##########
											$sel_logout_time = "select DATE(event_date) as logout_date, TIME(event_date) as logouttime from vicidial_user_log where event_date >='$start_date 00:00:00' and event_date <= '$end_date 23:59:59' and user='$UserID' and event='LOGOUT' ORDER by `event_date` DESC";
											$res_logout_time = mysqli_query($db,$sel_logout_time);
											//$row_logout_time = mysqli_fetch_array($res_logout_time);
											//$logoutTime = $row_logout_time[0];

											$row_logout_time = mysqli_fetch_array($res_logout_time);
											
												$logoutdt = $row_logout_time[0];
												$logoutTime = $row_logout_time[1];
												
											####### Outbound ############										
											$sel_tco_out = "select count(*) from vicidial_log where call_date >='$start_date 00:00:00' and call_date <= '$end_date 23:59:59' and user='$UserID'";
											$res_tco_out = mysqli_query($db,$sel_tco_out);
											$row_tco_out = mysqli_fetch_array($res_tco_out);
											$call_out  = $row_tco_out[0];
											$tottc_out = $tottc_out + $call_out;

											####### Inbound ############	
											$sel_tco_in = "select count(*) from vicidial_closer_log where call_date >='$start_date 00:00:00' and call_date <= '$end_date 23:59:59' and user='$UserID'";
											$res_tco_in = mysqli_query($db,$sel_tco_in);
											$row_tco_in = mysqli_fetch_array($res_tco_in);
											$call_in  = $row_tco_in[0];
											$tottc_in = $tottc_in + $call_in;
											
											$CallsVal= $call_out + $call_in;
											
											
											
											//TIME = Total time of these (PAUSE + WAIT + TALK + DISPO)

										   
									?>    
                                        <tr>
										    <td><?php echo $UserName;?></td>
                                            <td><?php echo $UserID;?></td>
											<td><?php echo $user_group;?></td>
											<td><?php echo $logindt;?></td>
                                            <td><?php echo $loginTime;?></td>
											<td><?php echo $logoutdt;?></td>
											<td><?php echo $logoutTime;?></td>
											<td><?php echo $CallsVal;?></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
                                        </tr>
										<?php 
										$a++;    
											}
																			
								?>
							
						
			        </tbody>
					
					
						<?php } ?>					
				</table>
				

	
	</div>
	</div>
	
	
  
</div>
</div>



 </body>

</html>
