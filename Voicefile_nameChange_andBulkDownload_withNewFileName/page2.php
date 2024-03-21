<!DOCTYPE html>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--  
    Document Title
    =============================================
    -->
    <title>VoiceLogs_Download</title>
    <!--  
    Favicons
    =============================================
    -->

	 
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template specific stylesheets-->
 
    <link href="assets/lib/animate.css/animate.css" rel="stylesheet">
    <link href="assets/lib/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/lib/et-line-font/et-line-font.css" rel="stylesheet">
    <link href="assets/lib/flexslider/flexslider.css" rel="stylesheet">
    <link href="assets/lib/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet">
    <link href="assets/lib/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    <link href="assets/lib/simple-text-rotator/simpletextrotator.css" rel="stylesheet">
    <!-- Main stylesheet and color file-->
     <link href="assets/css/style.css" rel="stylesheet"> 

    <link href="voicecss/style.css" rel="stylesheet">

    <link id="color-scheme" href="assets/css/colors/default.css" rel="stylesheet">
	<!-- dateranger -->
	<link href="datestylejs/style_date1.css" rel="stylesheet">
	
	<!-- dateranger -->
	<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
	
	</style>
  
   <style> 
    input[type=submit] {
    background-color: #1a2b6d;
    border: none;
    color: white;
    padding: 5px 16px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
}
a {
    color: #8bc541;
}


</style>
  
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
	  
	  
	  		  <?php
	       $user = $_REQUEST['user'];
		   $download = $_REQUEST['download'];
		   $txt_campaign = $_REQUEST['cam'];
		  
		  
	      
	       if($user == '')
	       {
			  echo "====You are not Authorized To view the Page"; 
		   }
		   
		  
          	 
		   
			$conn=mysqli_connect("localhost","root","Hal0o(0m@72427242","asterisk");
            // Check connection
            if (mysqli_connect_errno())
           {
			echo "Failed to connect to DATABASE ASTERISK: " . mysqli_connect_error();
           }
          	   
           
           			$sql = "select pass,user_level from vicidial_users where user = '$user'";
		            $result = mysqli_query($conn,$sql);
					$row = mysqli_fetch_assoc($result);
					$level = $row["user_level"];
                    mysqli_close($conn);
                  if($level >=9)
                    {
		
  
			  ?>
	  
	  
	  
	  
    <main>
      <div class="page-loader">
        <div class="loader">Loading...</div>
      </div>
      <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="page1.php">VoiceLogs</a>
          </div>
          <div class="collapse navbar-collapse" id="custom-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Voicelog</a>
                <ul class="dropdown-menu">
                 
                  <li><a href="page1.php?user=<?php echo $user;?>">voice details</a></li>
                  <li><a href="page2.php?user=<?php echo $user;?>">Download</a></li>
                  <li><a href="admin/admin.php">Back To Admin</a></li>
                </ul>
              </li>
             
        
          
            
              <!--li.dropdown.navbar-cart-->
              <!--    a.dropdown-toggle(href='#', data-toggle='dropdown')-->
              <!--        span.icon-basket-->
              <!--        |-->
              <!--        span.cart-item-number 2-->
              <!--    ul.dropdown-menu.cart-list(role='menu')-->
              <!--        li-->
              <!--            .navbar-cart-item.clearfix-->
              <!--                .navbar-cart-img-->
              <!--                    a(href='#')-->
              <!--                        img(src='assets/images/shop/product-9.jpg', alt='')-->
              <!--                .navbar-cart-title-->
              <!--                    a(href='#') Short striped sweater-->
              <!--                    |-->
              <!--                    span.cart-amount 2 &times; $119.00-->
              <!--                    br-->
              <!--                    |-->
              <!--                    strong.cart-amount $238.00-->
              <!--        li-->
              <!--            .navbar-cart-item.clearfix-->
              <!--                .navbar-cart-img-->
              <!--                    a(href='#')-->
              <!--                        img(src='assets/images/shop/product-10.jpg', alt='')-->
              <!--                .navbar-cart-title-->
              <!--                    a(href='#') Colored jewel rings-->
              <!--                    |-->
              <!--                    span.cart-amount 2 &times; $119.00-->
              <!--                    br-->
              <!--                    |-->
              <!--                    strong.cart-amount $238.00-->
              <!--        li-->
              <!--            .clearfix-->
              <!--                .cart-sub-totle-->
              <!--                    strong Total: $476.00-->
              <!--        li-->
              <!--            .clearfix-->
              <!--                a.btn.btn-block.btn-round.btn-font-w(type='submit') Checkout-->
              <!--li.dropdown-->
              <!--    a.dropdown-toggle(href='#', data-toggle='dropdown') Search-->
              <!--    ul.dropdown-menu(role='menu')-->
              <!--        li-->
              <!--            .dropdown-search-->
              <!--                form(role='form')-->
              <!--                    input.form-control(type='text', placeholder='Search...')-->
              <!--                    |-->
              <!--                    button.search-btn(type='submit')-->
              <!--                        i.fa.fa-search-->
            
            </ul>
          </div>
        </div>
		  
      </nav>
	  
	  <?php
	  $hostname='localhost';
$user1 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'asterisk';
$db = mysqli_connect($hostname, $user1, $password,$mysql_database);

	  ?>
	
	
      <div class="main">
		  	  <form action="archive.php" method="post">
	  <hr>  <hr> 
	 <?php $date = date('m-d-y');?>
	  <br/> <br/>

	
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b><font size="3">Select Date </font> </b> &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; 
	 <input type="text" name="daterange" id ="daterange" value="<?php echo $date-$date;?>" size = "40" />
	 
	  <b><font size="3">Select Campaign</font> </b>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<select id="txt_campaign" name = "txt_campaign" style="height: 28px;">
								<option value ="<?php echo $txt_campaign; ?>" selected><?php echo $txt_campaign; ?></option>
								<?php 
								$query = "SELECT * FROM vicidial_campaigns";
								//echo $query;
								$result = mysqli_query($db,$query);
								while ($row = mysqli_fetch_array($result)){
									echo "<option value = ".$row['campaign_id'].">" . $row['campaign_id'] . "</option>";
								}			
								?>
								
							</select>
	  <input type="hidden" name="user" id ="user" value="<?php echo $user;?>" size = "40" />  
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if($download == 'ok'){?><a href='<?php echo "backup_$user.tgz"?> '  target="_self">Click to Download</a> <?php }elseif($download == 'fail'){ echo "Data not Available"; } ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">
	
	 
	
	 
	 <br/> <br/> <br/>
	 </form>
        <hr class="divider-w">
        
    

        <hr class="divider-d">
        <footer class="footer bg-dark">
          <div class="container">
            <div class="row">
              <div class="col-sm-6">
                <p class="copyright font-alt">&copy; 2018&nbsp;<a href="page1.php">VoiceLogs</a>, All Rights Reserved</p>
              </div>
            
              </div>
            </div>
          </div>
        </footer>
      </div>
      <div class="scroll-up"><a href="#totop"><i class="fa fa-angle-double-up"></i></a></div>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
	
  
	
    <script src="assets/lib/jquery/dist/jquery.js"></script>
    <script src="assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/lib/wow/dist/wow.js"></script>
    <script src="assets/lib/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.js"></script>
    <script src="assets/lib/isotope/dist/isotope.pkgd.js"></script>
    <script src="assets/lib/imagesloaded/imagesloaded.pkgd.js"></script>
    <script src="assets/lib/flexslider/jquery.flexslider.js"></script>
    <script src="assets/lib/owl.carousel/dist/owl.carousel.min.js"></script>
    <script src="assets/lib/smoothscroll.js"></script>
    <script src="assets/lib/magnific-popup/dist/jquery.magnific-popup.js"></script>
    <script src="assets/lib/simple-text-rotator/jquery.simple-text-rotator.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
	
	 <script src="voicejs/v1.js"></script>
	 <script src="voicejs/v2.js"></script>
	 <!--dateranger-->
	  <script src="datestylejs/date1.js"></script>
	   <script src="datestylejs/date2.js"></script>
	    <script src="datestylejs/date3.js"></script>
	 <!-- dateranger -->
	  
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});



</script>


		 
	<?php } else "You are not Authorized To View the Page";?>
	
	
  </body>
</html>
