<?php
require_once("dbconnect_mysqli.php");
?>
<html>
<body>
<head>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/user_profile_style_new.css" rel="stylesheet" type="text/css" />
<title>Data Searching</title>
<!------ Include the above in your HEAD tag ---------->

<style>


input[type=text], select {
  width: 30%;
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
<!--<div>
 <img src="./images/cm.png" name="logo" alt="logo" width="180px" height="80px" border="0" style="margin-left:153px;" />
 <img src="./images/logo2.png" name="logo" alt="Live Call" width="130px" height="80px" border="0" style="margin-left:930px;"/>
 <h1 align="center">Detail Info<h1>
<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">
	
</div>-->

<div class="row">

    <div class="col-sm-4"><img src="./images/connect_log_new.png" name="logo" alt="logo" width="200px" height="80px" border="0" style="margin-left:153px;" /></div>
    <div class="col-sm-4"><h3 align="center" style="color:#6eba01;margin-top:20px;">Customer Details Base On Loan Id<h3></div>
    <div class="col-sm-4"><img src="./images/logo2.png" name="logo" alt="Live Call" width="150px" height="80px" border="0" style="margin-left:204px;"/></div>


</div>
<hr style="border:1px solid #6eba01;margin-left:100px;margin-right:100px">

                    <div class="col-md-10">
                      
					
                    <form id="form_1" action="" method="post">
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<label for="fname">Loan ID</label>
    <input type="text" id="fname" name="number" placeholder="Loan Id">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	
	<label for="fname1">Phone Number</label>
    <input type="text" id="phoneNo" name="PhoneNo" placeholder="Phone Number">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 


   <input type="submit" name="submit_1" value="Serach" align="center">
  </form>
	
<table id="datatable" class="table styled-table table-bordered" cellspacing="0" bgcolor="#fff"> <!--table-bordered -->
			 
			   <?php
				if(isset($_POST['submit_1'])){
					$no = 	$_POST['number']; 
					$PhoneNoVal = $_POST['PhoneNo'];	
				?>
					<label>Loan Id : <?php echo "$no"; ?>   Phone No.<?php echo "$PhoneNoVal"; ?> </label>
					<br><br>
				<?php			
				}	
				?>
			     
    				<thead>
						<tr>
						    <th>Customer Name</th>
							<th>Customer phone No.</th>
							<th>Loan Id</th>
						    <th>Account No.</th>
							<th>City</th>
							<th>State</th>
							<th>Credit Card No.</th>	
							<th>Reversal Payment Status</th>
							<th>Active Card NBR</th>
							<th>Disposition</th>
							<th>Sub-Disposition</th>
							<th>Comments</th>
							<th>Cibil Score</th>
						</tr>
					</thead>
						
					<tbody>
					<?php
						
					if (isset($_POST["submit_1"]) == "Serach") {
						$LoanID = 	$_POST['number'];
                        $PhoneNoVal = $_POST['PhoneNo'];						
					
					            if($PhoneNoVal !='' && $LoanID ==''){
					                $sel_data = "SELECT `first_name`,`middle_initial`,`last_name`,`address1`,`address2`,`address3`,`city`,`state`,`phone_number`,`field35`,`field36`,`comments`,`field30` FROM `vicidial_list` WHERE `phone_number`='$PhoneNoVal' ORDER by `modify_date` DESC LIMIT 1";
								//echo "ifcondi===>".$sel_data;
								}else if($LoanID !='' && $PhoneNoVal==''){
									$sel_data = "SELECT `first_name`,`middle_initial`,`last_name`,`address1`,`address2`,`address3`,`city`,`state`,`phone_number`,`field35`,`field36`,`comments`,`field30` FROM `vicidial_list` WHERE `middle_initial`='$LoanID' ORDER by `modify_date` DESC LIMIT 1";
								//echo "else if condi===>".$sel_data;
								}else {
									$sel_data = "SELECT `first_name`,`middle_initial`,`last_name`,`address1`,`address2`,`address3`,`city`,`state`,`phone_number`,`field35`,`field36`,`comments`,`field30` FROM `vicidial_list` WHERE `middle_initial`='$LoanID' and `phone_number`='$PhoneNoVal' ORDER by `modify_date` DESC LIMIT 1";
								//echo "else Condi===>".$sel_data;
								}
									
										//echo "<br>";
										$res_DropCall = mysqli_query($link,$sel_data);
									
										$a=1;
										while($row=mysqli_fetch_assoc($res_DropCall)){
									?>    
                                        <tr>
										<td><?php echo $row['first_name'];?></td>
										<td><?php echo $row['phone_number'];?></td>
										<td><?php echo $row['middle_initial'];?></td>
										<td><?php echo $row['last_name'];?></td>	
										<td><?php echo $row['address1'];?></td>
										<td><?php echo $row['address2'];?></td>
										<td><?php echo $row['address3'];?></td>	
										<td><?php echo $row['city'];?></td>
										<td><?php echo $row['state'];?></td>
										<td><?php echo $row['field35'];?></td>
										<td><?php echo $row['field36'];?></td>
										<td><?php echo $row['comments'];?></td>
										<td><?php echo $row['field30'];?></td>
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
            </form>   

        </div>
</body>
</html>