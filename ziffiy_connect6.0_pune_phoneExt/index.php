<?php 

require_once("db_connect.php");

$stmt_select="SELECT * from features_settings";
	      $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
                           if(mysqli_num_rows($rslt_rs)>=1){					 	
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
				  $star_month = $row["star_month"];
				 
			   }
			   }
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

		   
error_reporting(E_ERROR | E_WARNING | E_PARSE);

   session_start();
   $login_errorr_cnt=1;
   $login_errorr="";
   
    if (isset($_POST['Submit']))
   {  
       
	    $username = $_POST['username'];
	   
		$password = $_POST['pass'];
		//$userlocation = $_POST['userlocation'];
		$userWorkType1  = $_POST['userWorkType'];
		$phone_id = $_POST['phone_id'];
		$phone_pass = $_POST['phone_pass'];
		
		  
		 
     if( $username != "" && $password != "" ) 
	  {
			
				$stmt_rs="SELECT user_level from users where user_id='$username' AND user_password='$password';";
				
				
										   $rslt_rs= mysqli_query($conn,$stmt_rs);
										   $row_rs= mysqli_fetch_array($rslt_rs);
							   
	
		 
				   if(mysqli_num_rows($rslt_rs) > 0)
		  {
			
		   $_SESSION['user_level'] = $row_rs[0];
		//echo $row_rs[0]; exit;
		
		if($row_rs[0] == 'D4'){
		$stmt_phone="SELECT extensionIdPhone,extensionPassword from phone_extension where extensionIdPhone='$phone_id' AND extensionPassword='$phone_pass'";
				//echo $stmt_phone;
				
				$rslt_phone= mysqli_query($conn,$stmt_phone);
				$row_phone= mysqli_fetch_row($rslt_phone);
				
				$extensionIdPhone = $row_phone[0];
				$extensionPassword = $row_phone[1];
			//echo $extensionIdPhone; exit;
				if($extensionIdPhone == $phone_id && $extensionPassword == $phone_pass){
					
					//live table validation
					$stmt_liveEx="SELECT extension from user_live where extension ='$extensionIdPhone';";
	                           $rslt_liveEx= mysqli_query($conn,$stmt_liveEx);
	                           $row_liveEx= mysqli_fetch_row($rslt_liveEx);
	
				if($row_liveEx == ""){
				
			
				$stmt_phone="SELECT extensionIdPhone,extensionPassword from phone_extension where extensionIdPhone='$phone_id' AND extensionPassword='$phone_pass'";
				//echo $stmt_phone;
				
				$rslt_phone= mysqli_query($conn,$stmt_phone);
				$row_phone= mysqli_fetch_row($rslt_phone);
				
				$extensionIdPhone = $row_phone[0];
				$extensionPassword = $row_phone[1];
			
				
				$query_updatephone = "update users set Campaign='$userWorkType1',extension_id='$extensionIdPhone', extension_password='$extensionPassword' where user_id='$username' and user_level='D4'";
				mysqli_query($conn,$query_updatephone);
				
			
			
$stmt_rs="SELECT user_level,extension_id,conference_number from users where user_id='$username' AND user_password='$password';";
				
				
										   $rslt_rs= mysqli_query($conn,$stmt_rs);
										   $row_rs= mysqli_fetch_array($rslt_rs);
							   
	
		 
				   if(mysqli_num_rows($rslt_rs) > 0)
		  {
			
		   $userExtension = $row_rs[1];
		   $Conf_Number = $row_rs[2];			
						///added by nagendra
			$resd = mysqli_query($conn,"select agent from user_conference where agent=(select extension_id from users where user_id='$username')");
			if(mysqli_num_rows($resd)>=1){
			$rowd=mysqli_fetch_array($resd);
			$agd = $rowd['agent'];
			mysqli_query($conn,"update user_conference set agent='',status='0' where agent='$agd'");}
			mysqli_query($conn,"update user_conference set status='0' where agent=''");
				  
			$stmt_con="SELECT conference_number from user_conference where status='0' ORDER BY id ASC LIMIT 1";
										   $rslt_con= mysqli_query($conn,$stmt_con);
										   $row_con= mysqli_fetch_row($rslt_con);
							   $user_conference_number = $row_con[0];
							   
							   $query_update = "update users set conference_number='$user_conference_number',Campaign='$userWorkType1' where user_id='$username' and user_level='D4'";
							   
							   mysqli_query($conn,$query_update);
							   

			$query_conf = "update user_conference set status='1' where conference_number='$user_conference_number'";
			mysqli_query($conn,$query_conf);
			
			  mysqli_query($conn,"update user_conference set agent='$userExtension' where conference_number='$user_conference_number'"); 
			  
		  }
							$_SESSION['AgentUserName'] = $username;
								$_SESSION['AgentPassword'] = $password;
								$_SESSION['extension'] = $userExtension;
								$_SESSION['campaign'] =$userWorkType1;
								$_SESSION['conf_num']=$user_conference_number;
						
							$stmt_rs1="SELECT id from user_live where user ='$username';";
	                           $rslt_rs1= mysqli_query($conn,$stmt_rs1);
	                           $row_rs1= mysqli_fetch_row($rslt_rs1);
	
							 if($row_rs1 == ""){
								 
									$date = date("Y-m-d H:i:s");
									
	$stmt_insert_live="insert into user_live(created_date,user,status,extension,callstatus,campaign,confnum,auto_flag,waittime,timer,pause_flag,ringroup) values('$date','$username','PAUSED','$userExtension','IDLE','$userWorkType1','$user_conference_number','0','$date','0','0','');";
								//echo $stmt_insert_live; exit;
	                           $rslt_insert_live= mysqli_query($conn,$stmt_insert_live);
								 
								 
								 
								
								$date = date("Y-m-d H:i:s");
							
	$stmt_insert_log="insert into user_log(created_date,user,status,campaign) values('$date','$username','LOGIN','$userWorkType1');";
	
	                           $rslt_insert_log= mysqli_query($conn,$stmt_insert_log);
								 
								
								$date = date("Y-m-d H:i:s");
							
$stmt_insert_log1="insert into user_log(created_date,user,status,total_sec,paused_code,campaign) values('$date','$username','PAUSED','','LOGIN','$userWorkType1');";
	                           $rslt_insert_log1= mysqli_query($conn,$stmt_insert_log1);
								 
									
								header('Location: /haloocomConnect5/pages/connect5AgentPage/home.php');   //haloocomConnect5/pages/haloocomConnect5_O/home.php
						 }else{
							 $_SESSION['AgentUserName'] = $username;
								$_SESSION['AgentPassword'] = $password;
							  $login_errorr_cnt=1;
							 $login_errorr="USER ALREADY LOGIN, PLEASE CLICK ON THIS BUTTON <button type = \"button\" class = \"btn\"><a href=\"../../connect5AgentPage/logout.php\">Logout</a></button>";
						} 
				}else{
			   $login_errorr_cnt=1;
          $login_errorr="USER ALREADY LOGIN WITH THIS EXTENSITION";
		  }	
				/// 
		  }else{
			   $login_errorr_cnt=1;
          $login_errorr="INVALID EXTENSITION OR PASSWORD  ";
		  }
		
		}else if($row_rs[0] == 'Admin' || $row_rs[0] == 'SuperAdmin' || $row_rs[0] == 'SuperUserAccess'){
						 $_SESSION['username'] = $username;
						$_SESSION['pass'] = $password;
						
						header('Location: /haloocomConnect5/index.php');
						
					}
		 }
		 else
		 {
          $login_errorr_cnt=1;
          $login_errorr="INVALID USERNAME OR PASSWORD  ";
		  
		  $query_update = "update users set conference_number='' where user_id='$username'";
mysqli_query($conn,$query_update);

$query_conf = "update user_conference set status='0' where conference_number='$user_conference_number'";
mysqli_query($conn,$query_conf);

         }

     }
     else
     {
		 $login_errorr_cnt=1;
         $login_errorr = "USERNAME OR PASSWORD IS MISSING ";
     }
  }





?>
<html lang="en">
<head>


<style>



</style>
	<title>Connect 6.0 Login Page </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	
	
	
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="css/custom.css">

<style>
.header {
  padding: 8px;
 // text-align: center;
  background: #1A2B6D;
  color: white;
  font-size: 13px;
}
#star {
	background-color: white;
	
}

div.slider img {
  width: 220px;
  height: 250px;
}

div.desc {
	width: 220px;
  height: 90px;
  padding: 5px;
  text-align: center;
  background-color: #1A2B6D;
  color: white;
}



.slider-container {
  width: 220px;
  overflow: hidden;
  
}

.slider {
  display: flex;
  transition: transform 0.5s ease-in-out;
}

.slide {
  flex: 0 0 100%; /* Each slide takes 100% of the container width */
  box-sizing: border-box;
}
</style>
</head>
<body>
<div class="header">
  <div><img src = "images/wp_logo1.png" width ="110px" height="55px">&nbsp;&nbsp;<b></b></div>

</div>

	
   
	<div class="limiter">
		<div class="container-login100">
		<?php 
		if($star_month == 1){
		?>
		<div id="star" style="justify-content: left; padding:10px;margin-top:70px; border-radius:10px; height:450px;">
		<p><h4 style="align:center; color: #1A2B6D;"><b><i class="fa fa-star" aria-hidden="true"></i> Star Of The Month <i class="fa fa-star" aria-hidden="true"></i></b></h4></p><br>
<div class="slider-container" style="height:420px;">
  <div class="slider">
  <?php 
							$stmt_select="SELECT * from start_month";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
				   ?>

    <div class="slide">
<img src="star_images/<?php echo $row["image_path"]; ?>" alt="Image 1"  width="600" height="400">
<div class="desc">
  <div style="margin-top:5px;"><b><?php echo $row["name"]; ?></b></div>
  <div style="margin-top:10px;"><?php echo $row["designation"]; ?></div>
</div>
	</div>
     <?php $x++; } ?>
  </div>
</div>
  </div>
  <?php }?>
  
  <?php 
		if($star_month == 1){
		?>
			<div class="wrap-login100" style="height: 530px; margin-left:260px; margin-top:50px;">
			<?php } else{?>
			<div class="wrap-login100" style="height: 480px; margin-left:480px; margin-top:50px;">
			<?php } ?>
				 <div style="margin-top:25px;margin-left:50px;"><img src = "images/hexa.png" width="280px" height="80px"></div>

                <div class="form-group" style="margin-top:25px !important;margin-bottom: -9px !important;margin-left: 60px !important;">
				  <font color="red" size = "2">
				   <?php 
				   if($login_errorr_cnt =='1')
				   echo $login_errorr;
			       else
				   echo $login_errorr;
				   ?>
				  </font>
				</div>
			<br>
				<form class="login100-form1 validate-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
					<div class="form-group" style="margin-bottom:-15px !important;">
					  <label for="username" style="font-size:13px;font-family:Helvetica;"><b>Enter Login ID:</b></label>
					  <input type="text" class="form-control" name="username" id = "username" placeholder="Enter username" onchange = "checkUserLevelLogin();" >
					</div> <br>
					<div class="form-group" style="margin-bottom:-15px !important;">
					  <label for="pass" style="font-size:13px;font-family:Helvetica;"><b>Enter Password:</b></label>
					  <input class="form-control" type="password" name="pass" placeholder="Enter password">
					</div> </br>
					
					<div class="form-group" id = "extensionDivId" style="margin-bottom:-10px !important;display:none;">
					  <label for="username" style="font-size:13px;font-family:Helvetica;"><b>Enter Extension ID:</b></label>
					  <input type="text" class="form-control" name="phone_id" id = "phone_id" placeholder="Enter Phone id" >
					</div>
					<br>
					<div class="form-group" id = "extensionDivPass" style="margin-bottom:-12px !important;display:none;">
					  <label for="pass" style="font-size:13px;font-family:Helvetica;"><b>Enter Phone Password:</b></label>
					  <input class="form-control" type="password" name="phone_pass" id="phone_pass" placeholder="Enter password">
					  </div><br>
					<div class="form-group"  id = "userWorkTypeDivId" style = "margin-bottom:10px !important;display:none;">
					 <label for ="campaign" style="font-size:13px;font-family:Helvetica;"><b>Campaign</b></label>	
					 <select class="form-control select2" style="font-size: 13px;font-family:Helvetica;padding: 2pt;width: 228px;height: 27pt;" name ="userWorkType" id = "userWorkType" autocomplete="off"></select></div>
					 					  
					 <div class="container-login100-form-btn1" style="margin-bottom:9px !important;">
						
						<input  type="submit" class="login100-form-btn" name="Submit" value="Login"  style = "margin-left: 89px;">
					</div>
						</div></div>
                    
                </div></div>
					
				

					<br><br>
					
					
				</form>
					  
			</div>
		</div>
	</div>
	 

<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    var slideCount = $('.slide').length;
    var slideWidth = $('.slide').width();
    var sliderWidth = slideCount * slideWidth;

    $('.slider').css('width', sliderWidth);

    function autoplay() {
      $('.slider').animate({
        marginLeft: -slideWidth
      }, 1000, function() {
        $('.slider .slide:first').appendTo('.slider');
        $('.slider').css('marginLeft', 0);
      });
    }

    var autoplayInterval = setInterval(autoplay, 4000); // Change slide every 3 seconds

    // Pause autoplay on hover
    $('.slider-container').hover(function() {
      clearInterval(autoplayInterval);
    }, function() {
      autoplayInterval = setInterval(autoplay, 4000);
    });
  });
</script>
	<script>
		function checkUserLevelLogin(){
			var username = document.getElementById("username").value;
			
			/*	const xhttp = new XMLHttpRequest();
				  xhttp.onload = function() {
					var result =  this.responseText;
					var res = result.split("=====");
					//alert(res);
					 
						if((res[0] == "display")){
						document.getElementById("userWorkTypeDivId").style.display ="block";
						document.getElementById('userWorkType').value=res[1];
						document.getElementById("userWorkType").required  =true;
				
						}
						else if(res[0] == 'No display'){
							document.getElementById("userWorkTypeDivId").style.display ="none";
							document.getElementById("userWorkType").required  =false;
							
						}
						 else if(res[0] == 'admin'){
							document.getElementById("userWorkTypeDivId").style.display ="none";
							document.getElementById("userWorkType").required  =false;
							document.getElementById("supportType").style.display ="none";
							document.getElementById("supportWorkType").required  =false;
							
							
						}
					}
				  xhttp.open("GET", "checkUserLevelLogin.php?username="+username, true);
				  xhttp.send(); */
				  
				  $.ajax({
			type: 'POST',
			url: "checkUserLevelLogin.php",
			data: {
				'username': username
			},
			success: function(result) {
				var res = result.split("=====");
				//alert(result);
				//console.log(result);
		      if((res[0] == "display")){
						document.getElementById("userWorkTypeDivId").style.display ="block";
						document.getElementById('userWorkType').innerHTML=res[1];
						document.getElementById("userWorkType").required  =true;
						document.getElementById("extensionDivId").style.display ="block";
						document.getElementById("phone_id").required  =true;
						document.getElementById("extensionDivPass").style.display ="block";
						document.getElementById("phone_pass").required  =true;
						
						}
						else if(res[0] == 'No display'){
							document.getElementById("userWorkTypeDivId").style.display ="none";
							document.getElementById("userWorkType").required  =false;
							document.getElementById("extensionDivId").style.display ="none";
							document.getElementById("phone_id").required  =false;
							document.getElementById("extensionDivPass").style.display ="none";
							document.getElementById("phone_pass").required  =false;
						
						
						}
						 else if(res[0] == 'admin'){
							document.getElementById("userWorkTypeDivId").style.display ="none";
							document.getElementById("userWorkType").required  =false;
							document.getElementById("supportType").style.display ="none";
							document.getElementById("supportWorkType").required  =false;
							document.getElementById("extensionDivId").style.display ="none";
							document.getElementById("phone_id").required  =false;
							document.getElementById("extensionDivPass").style.display ="none";
						    document.getElementById("phone_pass").required  =false;
						
							
							
						}
			}
		});
		}
	</script>
	
</body>
</html>
