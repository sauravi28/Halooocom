<html>
<head>
	
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="admin/css/user_profile_style_new.css" rel="stylesheet" type="text/css" />
<title>ADD PAYMENT STATUS</title>
<!------ Include the above in your HEAD tag ---------->

<style>


input[type=text], select {
  width:52%;
  padding: 1px 8px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  //width: 30%;
  background-color: #4CAF50;
  color: white;
  padding: 4px 20px;
  margin:4px 10px 25px 30px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

#datatable_wrapper {
	    margin-left:80px !important;
}


.page {
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
}

.page[size="A4"] {
  width: 21cm;
  height: 29.7cm;
}

.page[size="A4"][layout="landscape"] {
  width: 29.7cm;
  height: 21cm;
}

.page[size="A3"] {
  width: 29.7cm;
  height: 42cm;
}

.page[size="A3"][layout="landscape"] {
  width: 42cm;
  height: 29.7cm;
}

.page[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}

.page[size="A5"][layout="landscape"] {
  width: 21cm;
  height: 14.8cm;
}

@media print {
  body,
  page {
    margin: 0;
    box-shadow: 0;
  }
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdf.js/1.8.349/pdf.min.js"></script>


	<script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	
	

<script>
  $(document).ready(function(){
  sampleDiv_zoom.style.zoom='95%';
	var scale = 'scale(1)';
	document.body.style.webkitTransform = scale;  // Chrome, Opera, Safari
	document.body.style.msTransform = scale;     // IE 9
	document.body.style.transform = scale;     // General
  });
  
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
                extend: 'pdf',
				title:'PaymentStatus_Report_'+today
                
            }

		 
        ] 
    } );
} );
</script>
</head>
<?php
$hostname='localhost';
        $user = 'root';
        $password = 'Hal0o(0m@72427242';
        $mysql_database = 'asterisk';
        $link = mysqli_connect($hostname, $user, $password,$mysql_database);
        mysqli_select_db($db,$mysql_database);
        if (mysqli_connect_errno())
  {
   die("Connection failed: " . mysqli_connect_error());
  }

$date = date('Y-m-d H:i:s');
	// Uploads files
if (isset($_POST["submit_1"])){
	
	$pay_date = $_POST["pay_date"];
	$payment_status = $_POST["payment_statusVal"];
	$ammount = $_POST["ammount"];
			
    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];

    // destination of the file on the server
    $destination = 'uploadsPaymentStatus_files/' . $filename;

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO vicidial_PaymentStatus_data(filename,size,entry_date_time,payment_status,pay_date,ammount) VALUES ('$filename','$size','$date','$payment_status','$pay_date','$ammount')";
			echo $sql;
			//die;
            mysqli_query($link, $sql);
    
		}
		
		//header("Location:paymentStatus_Mail.php");
}
?>
<body id = "sampleDiv_zoom">

<div class="row">

    <div class="col-sm-4"><img src="./images/connect_log_new.png" name="logo" alt="logo" width="200px" height="80px" border="0" style="margin-left:153px;" /></div>
    <div class="col-sm-4"><h3 align="center" style="color:#6eba01;margin-top:20px;">ADD PAYMENT STATUS<h3></div>
    <div class="col-sm-4"><img src="./images/logo2.png" name="logo" alt="Live Call" width="150px" height="80px" border="0" style="margin-left:204px;"/></div>


</div>
<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
<div class="col-md-12">
   <table cellpadding="0" cellspacing="0" bgcolor="#fff">             	
    <form action="" method="POST" name="userform1" id="userform1" enctype="multipart/form-data">
	<tbody>
		<tr>
		<td style="width:25%;">
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label for="fname">Payment Date  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>
    &nbsp; &nbsp;<input type="date" name="pay_date" id="pay_date" required>&nbsp; &nbsp; &nbsp; &nbsp;
	</td>
	<td style="width: 20%;">
	<label for="fname1">Payment Status  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>&nbsp; &nbsp;
    <select name="payment_statusVal" required>
		<option value=''>--Select--</option>
		<option value='Partial Payment'>Partial Payment</option>
		<option value='Full Payment'>Full Payment</option>
	</select>&nbsp; &nbsp; &nbsp; &nbsp;</td>
	<td style="width: 20%;">
	<label for="fname">Total Amount </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>
    &nbsp; &nbsp;<input type="text" name="ammount" id="ammount" required>&nbsp; &nbsp; &nbsp; &nbsp;</td>
	<td style="width:5%;">
	<label for="fname">Upload PDF/IMG  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>&nbsp; &nbsp;
    <input type='file' name='myfile' id='myfile' required>
	
	<div id="canvasId" style="display:none;">
	<canvas class="page" size="A4" layout="landscape"></canvas>
	</div>
	<div class="item-images clearfix">
        <div class="empty-text">
		</div>
	</div>
		</td>							
   <td><input type="submit" name="submit_1" value="Submit" align="center" onclick="myFunctionValidation();"></td>
  </form>
</table>
	<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
	   
	
<table id="datatable" class="table styled-table table-bordered" cellspacing="0" bgcolor="#fff" align="center"> <!--table-bordered -->
			 
    				<thead>
						<tr>
						    <th>Sr.No</th>
						    <th>Entry Datetime</th>
							<th>Payment Date</th>
							<th>Payment Status</th>
							<th>Ammount</th>
						    <th>Uploaded Filename</th>
							<th>Action</th>
						</tr>
					</thead>
						
					<tbody>
					<?php
					
							$sel_data = "SELECT entry_date_time,pay_date,payment_status,filename,ammount from vicidial_PaymentStatus_data";
								//echo "else Condi===>".$sel_data;
										//echo "<br>";
										$res_DropCall = mysqli_query($link,$sel_data);
									
										$a=1;
										while($row=mysqli_fetch_assoc($res_DropCall)){
											
											$QC_file = 'uploadsPaymentStatus_files/' . $row['filename'];
									?>    
                                        <tr>
										<td><?php echo $a; ?></td>
										<td><?php echo $row['entry_date_time'];?></td>
										<td><?php echo $row['pay_date'];?></td>
										<td><?php echo $row['payment_status'];?></td>
										<td><?php echo $row['ammount']."/-";?></td>
										<td><?php echo $row['filename'];?></td>	
										<td><button onclick="viewPDF('<?php echo $QC_file; ?>')" title="Preview file"> <i class="fa fa-eye"></i></button></td>
										</tr>
										<?php 
										$a++;    
											}
																			
								?>
							
						
			        </tbody>
									
				</table>					
					
                    </div>
					
					
<div id="pdfModal" onclick="closePDFModal()">
    <div id="modalContent">
      <h2><b>Preview Uploaded File</b></h2>
      <span id="closeBtn" onclick="closePDFModal()"><b>&times;</b></span>
      <div id="pdfViewer"></div>
    </div>
  </div>

<script>


function myFunctionValidation() {
  let x = document.getElementById("ammount").value;
  if (isNaN(x) || x < 1 ) {
    alert("Please add valid ammount value.");
	document.getElementById("ammount").value='';
	}
  }


 $('#myfile').on('change', function() {

        var file = this.files[0];
        var imagefile = file.type;
        var imageTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
        if (imageTypes.indexOf(imagefile) == -1) {
            //display error
            return false;
            $(this).empty();
        }
        else {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(".empty-text").html('<img src="' + e.target.result + '"  />');
            };
            reader.readAsDataURL(this.files[0]);
        }

    });
	
	
document.querySelector("#myfile").addEventListener("change", function(e) {
  var canvasElement = document.querySelector("canvas")
  var file = e.target.files[0]
  if (file.type != "application/pdf") {
	  document.getElementById('canvasId').style.display = 'none';
    console.error(file.name, "is not a pdf file.")
    return
  }

  var fileReader = new FileReader();

  fileReader.onload = function() {
    var typedarray = new Uint8Array(this.result);

    PDFJS.getDocument(typedarray).then(function(pdf) {
      // you can now use *pdf* here
      console.log("the pdf has ", pdf.numPages, "page(s).")
      pdf.getPage(pdf.numPages).then(function(page) {
        // you can now use *page* here
        var viewport = page.getViewport(2.0);
		document.getElementById('canvasId').style.display = 'flex';
        var canvas = document.querySelector("canvas")
        canvas.height = viewport.height;
        canvas.width = viewport.width;


        page.render({
          canvasContext: canvas.getContext('2d'),
          viewport: viewport
        });
      });

    });
  };

  fileReader.readAsArrayBuffer(file);
})
	
function viewPDF(file_qc) {
      var pdfPath = file_qc;

      // Set the PDF path and display the modal
      document.getElementById('pdfViewer').innerHTML = '<object data="' + pdfPath + '" type="application/pdf" width="100%" height="100%"></object>';
      document.getElementById('pdfModal').style.display = 'flex';
    }

function closePDFModal() {
      document.getElementById('pdfModal').style.display = 'none';
      // Clear the content when closing the modal
      document.getElementById('pdfViewer').innerHTML = '';
    }
</script>

<style>
#pdfViewer {
      width: 100%;
      height: 500px;
    }

    #pdfModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /background: rgba(255, 255, 255, 0.9);/
      /* Semi-transparent white background */
      justify-content: center;
      align-items: center;
      margin-top: 70px;
    }

    #modalContent {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.7);
      max-width: 90%;
      /* Adjust as needed */
      max-height: 90%;
      /* Adjust as needed */
      overflow: auto;
      position: relative;
      width: 700px;
    }
</style>

                </div>
           
        </div>
</body>
</html>