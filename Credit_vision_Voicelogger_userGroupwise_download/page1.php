<!DOCTYPE html>
<?php
if (isset($_POST)) {
  $min = $_POST['min']; // this will get you what was in the
  $max = $_POST['max']; 
  
  }

?>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--  
    Document Title
    =============================================
    -->
    <title>VOICELOGS</title>
    <!--  
    Favicons
    =============================================
    -->

	 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
	
	<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
	
	
	
	
	 
	  <link href="https://cdn.datatables.net/v/dt/dt-1.10.12/se-1.2.0/datatables.min.css" rel="stylesheet">
	  <link href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/css/dataTables.checkboxes.css" rel="stylesheet">
	</style>
	<style>
	.btn {
  background-color: #8bc541; /* Blue background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding:3px 14px; /* Some padding */
  font-size: 16px; /* Set a font size */
  cursor: pointer; /* Mouse pointer on hover */
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: #C0392B;
}
	
/* add modal */
.h4class{
	font-size: 16px;
	color:#fff;
}
.modal1 {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top:60px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color:;
}

/* Modal Content */
.modal-content1 {
  background-color: #1a2b6d;
  margin: auto;
  padding: 40px;
  border: 1px solid #888;
  width: 40%;
  height:30%;
}

/* The Close Button */
.close1 {
  color: #FFF;
  float: right;
  font-size: 22px;
  font-weight: bold;
}

.close1:hover,
.close1:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.dt-buttons{
	margin-right: 300px !important;
}
.dt-button-collection {
		    margin-top: 2.5px !important;
			margin-bottom: 5px !important;
	}
	
	.btn.btn-xs {
    font-size: 14px !important;
}


	</style>
	
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
	

	
	<script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		   
	  <!--- new code script -->
	 <script src="datetimepicker.js" type="text/javascript"></script> 
	 
	
	
	
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
  
  
	  		
	  
  
    <main>
      <div class="page-loader">
        <div class="loader">Loading...</div>
      </div>
      <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="page1.php">VOICELOGS</a>
          </div>
          <div class="collapse navbar-collapse" id="custom-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Voicelog</a>
                <ul class="dropdown-menu">
                <?php $user = $_REQUEST['user'];
				$level = $_REQUEST['level'];
				
                   
				if($user == '')
                   {
                          echo "====You are not Authorized To view the Page";
                   }

                  ?> 
                  <li><a href="page1.php">voice details</a></li>
                  <li><a href="page2.php?user=<?php echo $user;?>">Download</a></li>
                  <li><a href="../admin/admin.php">Back To Admin</a></li>
                </ul>
              </li>
            
      
            </ul>
          </div>
        </div>
		  
      </nav>
	
      <div class="main">
	   <p><div id="myModal1" class="modal1">
 <!-- Modal content add -->
  <div class="modal-content1"  align = "center">
      <span class="close1">&times;</span>
	  <h4 class="h4class" > VOICEFILE PLAY</h4>
	  <hr>
	  <audio controls id="myAudio">
  <source src="horse.ogg" type="audio/ogg">

</audio>
  </div>
</div></p>
	  <hr>  <hr> 
	<div align="right">  

<p><button class="btn btn-danger" onclick="bulkdelete()">DELETE</button>
</p>
<div class="test" align="left" style="margin-left:50px;margin-bottom:20px">
		<form name="form1" method="post" action="<?php echo($_SERVER['PHP_SELF']."?user=".$user."&level=".$level) ?>">
        <tbody><tr>
		
            <td><b>Start Date:</b></td>
            <td><input type="text" id="min" name="min">
			<?php echo "<img src=\"assets/images/cal.gif\" onclick=\"javascript:NewCssCal('min')\" style=\"cursor:pointer\"/>" ?></td>
        </tr><tr><td>  </td></tr><br><br>
        <tr>
            <td><b>End Date:</b></td>
            <td><input type="text" id="max" name="max">
			<?php echo "<img src=\"assets/images/cal.gif\" onclick=\"javascript:NewCssCal('max')\" style=\"cursor:pointer\"/>" ?></td>
        </tr>
		
    </tbody>
	<input type="submit" class="btn btn-success1">
</form>
		
	
</div>	


<?php 
//echo $user

/*$date1=$min;
$date2=$max;

$hostname='localhost';
$user1 = 'root';
$password = 'Hal0o(0m@72427242';
$mysql_database = 'asterisk';
$db = mysqli_connect($hostname, $user1, $password,$mysql_database);
	
    $sql = "select user_group from vicidial_users where user = '$user' ";
	$res = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($res);
	$sql1 = "select allowed_campaigns from vicidial_user_groups where user_group = '$row[0]'";
	$res1 = mysqli_query($db,$sql1);
	$row1 = mysqli_fetch_array($res1);
	$campaign = trim($row1[0]);
	
	if($campaign == "-ALL-CAMPAIGNS- - -")
	{
		$sql_all_camp = "select campaign_id from vicidial_campaigns";
		//echo $sql_all_camp."<br>";	
		$res_camp = mysqli_query($db,$sql_all_camp);
		
		while($row_camp = mysqli_fetch_array($res_camp))
		{
			$campaign_all .= trim($row_camp[0].",");
			
		}
		//echo $campaign_all;
		$arr = explode(",",$campaign_all);
			//print_r($arr);
			$str = implode("','",$arr);	
			$val = "campaign in ('$str') and date>='".$date1."'and date<='".$date2."'and deleted='0'";
			//echo $val;	
	}
	else {
	$arr = explode(" ",$campaign);
	//print_r($arr);
	$str = implode("','",$arr);	
	$val = "campaign in ('$str') and date>='".$date1."'and date<='".$date2."'and deleted='0'";
	echo $val;
	}
	$where = $val;*/

 ?>

    <br />
     <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
			    <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
                <th>Agent ID</th>
				<th>Phone No</th>
                <th>Process</th>
				<th>Status</th>
				<th>Call Type</th>
				<th>Call Mode</th>
				<th>Call Date</th>
				<th>Call Time</th>
				<th>Duration</th>
				<th>filename</th>
				<th>Play</th>
				<th>Download</th>
				<th>Delete</th>
				
        </thead>
			
			<tfoot>
            <tr>
			    <th></th>
                <th>Agent ID</th>
				<th>Phone No</th>
                <th>Process</th>
				<th>Status</th>
				<th>Call Type</th>
				<th>Call Mode</th>
				<th>Call Date</th>
				<th>Call Time</th>
				<th>Duration</th>
				<th>filename</th>
				<th></th>
				<th></th> 
				<th></th>
			
            </tr>
        </tfoot>
          
        </tbody>
      
    </table>






        <hr class="divider-w">
 	
        <hr class="divider-d">
        <footer class="footer bg-dark">
          <div class="container">
            <div class="row">
              <div class="col-sm-6">
                <p class="copyright font-alt">&copy; 2018&nbsp;<a href="page1.php">VOICELOGS</a>, All Rights Reserved</p>
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

	
  

<script>
	
	
	
$(document).ready(function() {

var file_name;
var min = "<?php echo $min ?>"; 
var max = "<?php echo $max ?>"; 
var user = "<?php echo $user ?>";
	
	
	  // Handle form submission event
  // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Get all rows with search applied
      var rows = table.rows({ 'search': 'applied' }).nodes();
      // Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
	
    // Setup - add a text input to each footer cell
    $('#example tfoot th').each( function () {
		
        var title = $(this).text();
        if(title != '')
        {
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	    }
    } );
 
  $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
	    "order":[[0,"desc"],[1,"desc"]],
        "ajax": "server.php?min="+min+"&max="+max+"&user="+user,
		'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
             return '<input type="checkbox" name="id[]" id="id[]" value="' + $('<div/>').text(data).html() + '">';
         }
      }],
	  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'excelHtml5',
				title:'Voicelogger_Report'
                
            }

		 
        ]
    } );
    // DataTable
    var table = $('#example').DataTable();

    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

// Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Get all rows with search applied
      var rows = table.rows({ 'search': 'applied' }).nodes();
      // Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });

   // Handle form submission event
   $('#frm-example').on('submit', function(e){
    var form = this;
      // Iterate over all checkboxes in the table
      table.$('input[type="checkbox"]').each(function(){
         // If checkbox doesn't exist in DOM
         if(!$.contains(document, this)){
            // If checkbox is checked
            if(this.checked){
               // Create a hidden element
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         }
      });
 
    

   });// delete


	
} );



function add(val)
	{// Get the modal

	
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
	
	
	
	
	
var modal1 = document.getElementById('myModal1');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span1 = document.getElementsByClassName("close1")[0];
modal1.style.display = "block";

// When the user clicks on <span> (x), close the modal
span1.onclick = function() {
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
	
	
    modal1.style.display = "none";
  
  
  
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal1) {
	 
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
	 
    modal1.style.display = "none";
  }
}
}

function deletevoice(value)
	{
			var level = "<?php echo $_REQUEST['level']; ?>"
			var userVal = "<?php echo $_REQUEST['user'];?>"
			
			//alert(value);
			if(level == "9" && userVal == "creditadmin"){
			 var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange=function() {
				if (this.readyState == 4 && this.status == 200) {
					
				alert(this.responseText);
				location.reload();
			  
				}
			  };
			  xhttp.open("GET", "delete_voice.php?id=" + value, true);
			  xhttp.send();	
			  }else{
				  alert("you dont have access to delete the files.");
			  }
	
	}
	
	
function bulkdelete(){

var level = "<?php echo $_REQUEST['level']; ?>"
var userVal = "<?php echo $_REQUEST['user'];?>"
			

if(level == "9" && userVal == "creditadmin"){
var checkboxes = document.getElementsByName('id[]');
            var result = "";
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    result += "'" + checkboxes[i].value
                        + "'" + ",";
                }
            }
           
			//alert(result);
				
 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange=function() {
    if (this.readyState == 4 && this.status == 200) {
		
	alert(this.responseText);
    location.reload();
    }
  };
  xhttp.open("GET", "bulk_delete.php?path="+ result, true);
  xhttp.send();	
  }else{
	  alert("you dont have access to delete the files.");
  }
}

</script>
		 
	
	
	
  </body>
</html>
