<html>
<body>
<head>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/user_profile_style_new.css" rel="stylesheet" type="text/css" />
<title>ADD PAYMENT STATUS</title>
<!------ Include the above in your HEAD tag ---------->

<style>


input[type=text], select {
  width: 10%;
  padding: 12px 20px;
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
  padding: 14px 20px;
  margin:4px 10px 25px 30px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}


</style>

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
            $sql = "INSERT INTO vicidial_PaymentStatus_data(filename,size,entry_date_time,payment_status,pay_date) VALUES ('$filename','$size','$date','$payment_status','$pay_date')";
            if (mysqli_query($link, $sql)) {
				
						echo "FILE UPLOADED SUCCESSFULLY";					
                }
        } else {
					echo "FAILED TO UPLOAD FILE";
			
        }
    
		}
?>

<div class="row">

    <div class="col-sm-4"><img src="./images/connect_log_new.png" name="logo" alt="logo" width="200px" height="80px" border="0" style="margin-left:153px;" /></div>
    <div class="col-sm-4"><h3 align="center" style="color:#6eba01;margin-top:20px;">ADD PAYMENT STATUS<h3></div>
    <div class="col-sm-4"><img src="./images/logo2.png" name="logo" alt="Live Call" width="150px" height="80px" border="0" style="margin-left:204px;"/></div>


</div>
<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
<div class="col-md-10">
                	
    <form action="" method="POST" name="userform1" id="userform1" enctype="multipart/form-data">
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label for="fname">Payment Date  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>
    &nbsp; &nbsp;<input type="date" name="pay_date" id="pay_date" required>&nbsp; &nbsp; &nbsp; &nbsp;
	
	<label for="fname1">Payment Status  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>&nbsp; &nbsp;
    <select name="payment_statusVal" required>
		<option value=''>--Select--</option>
		<option value='Unpaid'>Unpaid</option>
		<option value='Paid'>Paid</option>
	</select>&nbsp; &nbsp; &nbsp; &nbsp;
	
	<label for="fname">Upload PDF/IMG  </label><i class='fa fa-asterisk' style='color:red;font-size: x-small;'></i>&nbsp; &nbsp;
    <input type='file' name='myfile' required>
	<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<input type="submit" name="submit_1" value="Submit" align="center">
  </form>	
	
<table id="datatable" class="table styled-table table-bordered" cellspacing="0" bgcolor="#fff" align="center"> <!--table-bordered -->
			 
    				<thead>
						<tr>
						    <th>Sr.No</th>
						    <th>Entry Datetime</th>
							<th>Payment Date</th>
							<th>Payment Status</th>
						    <th>Uploaded Filename</th>
						</tr>
					</thead>
						
					<tbody>
					<?php
					
							$sel_data = "SELECT entry_date_time,pay_date,payment_status,filename from vicidial_PaymentStatus_data";
								//echo "else Condi===>".$sel_data;
										//echo "<br>";
										$res_DropCall = mysqli_query($link,$sel_data);
									
										$a=1;
										while($row=mysqli_fetch_assoc($res_DropCall)){
									?>    
                                        <tr>
										<td><?php echo $a; ?></td>
										<td><?php echo $row['entry_date_time'];?></td>
										<td><?php echo $row['pay_date'];?></td>
										<td><?php echo $row['payment_status'];?></td>
										<td><?php echo $row['filename'];?></td>	
										</tr>
										<?php 
										$a++;    
											}
																			
								?>
							
						
			        </tbody>
									
				</table>					
					
                    </div>
                </div>
           
        </div>
</body>
</html>