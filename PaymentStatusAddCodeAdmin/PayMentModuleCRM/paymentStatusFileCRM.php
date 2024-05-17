<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>ADD PAYMENT STATUS</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
<style>
	
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
  //width: 29.7cm;
  //height: 21cm;
  margin-left: 50% !important;
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

</head>
  
  <?php
$hostname='localhost';
        $user = 'root';
        $password = 'Hal0o(0m@72427242';
        $mysql_database = 'haloocomCRM';
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
	//$payment_status = $_POST["payment_statusVal"];
	$ammount = $_POST["ammount"];
	$client_name = $_POST['client_name'];
			
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
            $sql = "INSERT INTO vicidial_PaymentStatus_data(filename,size,entry_date_time,pay_date,ammount,client_name) VALUES ('$filename','$size','$date','$pay_date','$ammount','$client_name')";
			mysqli_query($link, $sql);
			
			$lastInsert_id = mysqli_insert_id($link);
			//echo "lastInsert_id ===>".$lastInsert_id;
				
			  require "Mail/phpmailer/PHPMailerAutoload.php";
			  //$file_name="Payslip (4).pdf";
			  $mail = new PHPMailer;

			  $mail->isSMTP();
			  $mail->Host = 'smtp.gmail.com';
			  $mail->Port = 587;
			  $mail->SMTPAuth = true;
			  $mail->SMTPSecure = 'tls';
			  
			  $mail->Username = 'crm@haloocom.com'; // Replace with your email
			  $mail->Password = 'zggfhiqxqbmhzcou'; // Replace with your email password

			  $mail->setFrom('crm@haloocom.com', 'Haloocom');
			  $adminEmail = "jose.mathew@haloocom.com"; // Replace with the desired email address
			  $mail->addAddress($adminEmail);
			  $mail->AddCC("sauravi.sarode@haloocom.com");

			  $mail->isHTML(true);
			  $mail->Subject = "Payment Update Status By Customer -: " .$client_name;
			  $mail->Body = "Restore the services as payment is made.<br>";
			  $mail->addAttachment("/var/www/html/haloocomCRM/uploadsPaymentStatus_files/".$filename);
			 
				 if($mail->Send())        //Send an Email. Return true on success or false on error
				 {
				  echo '<div class="alert alert-success">Email successfully sent</div>';
				  $selu2 = "update vicidial_PaymentStatus_data set st = '1', mail_status = 'Email successfully sent' where id = '$lastInsert_id'";
				  $resu2 = mysqli_query($link,$selu2);
				  
				 }
				 else
				 {
				  echo '<div class="alert alert-danger">There is an Error</div>';
					$selu1 = "update vicidial_PaymentStatus_data set st = '1',mail_status = 'Email not successfully sent' where id = '$lastInsert_id'";
					$resu1 = mysqli_query($link,$selu1);
				 }
				
		}
		
		//header("Location:paymentStatus_Mail.php");
}
?>

  <body class="p-3 m-0 border-0 bd-example m-0 border-0" style="background:url(img/login2.png) fixed no-repeat;background-size:cover";>

    <!-- Example Code -->
    
<div class="row">
    <div class="col-sm-4"><img src="img/logo2New.png" name="logo" alt="Live Call" width="150px" height="80px" border="0" style="margin-left:204px;"/></div>
    <div class="col-sm-4"><h3 align="center" style="color:#6eba01;margin-top:20px;">ADD PAYMENT STATUS<h3></div>
    <div class="col-sm-4"></div>
</div>
<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
        
    <form class="row g-3" action="" method="POST" name="userform1" id="userform1" enctype="multipart/form-data">
	<div class="col-md-3"></div>
      <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Payment Date <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
        <input type="date" class="form-control" id="pay_date" name="pay_date" required>
      </div>
      <!--<div class="col-md-3">
        <label for="inputState" class="form-label">Payment Status <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
        <select id="payment_statusVal" class="form-select" name="payment_statusVal" required>
          <option selected="">--Select--</option>
          <option value='Partial Payment'>Partial Payment</option>
		  <option value='Full Payment'>Full Payment</option>
        </select>
      </div>-->
	  <div class="col-3">
        <label for="inputAddress" class="form-label">Total Amount <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
        <input type="text" class="form-control" name="ammount" id="ammount" required>
      </div>
	  <div class="col-md-3"></div>
	  <div class="col-md-3"></div>
      <div class="col-3">
       <label for="inputState" class="form-label">Client Name <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
	   <input type="text" name="client_name" id="client_name" class="form-control" autocomplete="off" placeholder="Type Name" />
        <!--<select id="client_name" class="form-select" name="client_name" required>
          <option selected="">--Select--</option>
        <?php 
			/*$query = "SELECT name FROM accounts";
			//echo $query;
			$result = mysqli_query($link,$query);
			while ($row = mysqli_fetch_array($result)){
				echo "<option value = ".$row['name'].">" . $row['name'] . "</option>";
			}*/		
		?>
        </select>-->
		
      </div>
	  <div class="col-md-3">
        <label for="inputCity" class="form-label">Location <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
        <input type="text" class="form-control" name="location" id="location" required>
      </div>
	  <div class="col-md-3"></div>
	  <div class="col-md-3"></div>
      <div class="col-md-3">
        <label for="inputState" class="form-label">Upload PDF/IMG <i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i></label>
        <input type="file" class="form-control" name="myfile" id="myfile" required>
      </div>
	  <div class="col-md-3"><button type="submit" name="submit_1" class="btn btn-primary" style="margin-top:30px;" onclick="myFunctionValidation();">Submit</button>
</div>
      
        
    </form>
    
     
	<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
	   
	<table cellpadding="0" cellspacing="0" bgcolor="#fff"> 
	<div class="col-md-12">
		<tbody>
			<tr>
			<td></td>
			<td></td>
			<td style="width:100%;">
				<div id="canvasId" style="display:none;">
					<canvas class="page" size="A4" layout="landscape"></canvas>
				</div>
				<div id="imgDisplay" style="display:none;margin-left: 50%;">
					<div class="empty-text"></div>
				</div>
			</td>
			<td></td>
			<td></td>
			</tr>
		</tbody>
	</div>
	</table> 
    <!-- End Example Code -->
	
	<script>


function myFunctionValidation() {
  let x = document.getElementById("ammount").value;
  if (isNaN(x) || x < 1 ) {
    alert("Please add valid amount value.");
	document.getElementById("ammount").value='';
	}
  }


 $('#myfile').on('change', function() {

        var file = this.files[0];
        var imagefile = file.type;
        var imageTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
        if (imageTypes.indexOf(imagefile) == -1) {
            //display error
			document.getElementById('imgDisplay').style.display = 'none';
            return false;
            $(this).empty();
        }
        else {
			document.getElementById('imgDisplay').style.display = 'flex';
            
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
	
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
 
<script>
$(document).ready(function(){
 
 $('#client_name').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"fetchclient_name.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });
 
});
</script>


  </body>
</html>