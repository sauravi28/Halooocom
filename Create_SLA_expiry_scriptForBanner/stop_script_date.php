<?php

	$unique_id = $_REQUEST['id'];
	$date = date("Y-m-d");
	
    $hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'haloocomCRM';
	$conn = mysqli_connect($hostname, $user, $password,$mysql_database);
	
                        $stmt_select = "SELECT * from accounts_cstm where unique_account_no_c='$unique_id'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        while ($row = mysqli_fetch_assoc($rslt_rs)) {

                          $stop_date = $row["sla_date_c"];
						  $pay_status = $row["payment_status_c"];
						  $installation_date_c = $row["installation_date_c"];
                          
                         }
                         
                         if($date == $stop_date){
                             echo "True";
							 echo "==";
							 echo $stop_date;
							 echo "==";
							 echo $installation_date_c;
							 
                         }else{
                             echo "False";
                         }
                        ?>