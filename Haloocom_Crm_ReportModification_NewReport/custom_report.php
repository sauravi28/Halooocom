<?php 

						
				$loginUser = $_REQUEST['loginUser']; // Kiran Valve  //Jone
					$LastName = explode(" ", $loginUser);
					//echo "firstName: ".$LastName[0]; // Kiran Jone
					//echo "LastName ".$LastName[1]; // Valve
					//exit;
					//echo "<br>";
			//	echo $loginUser;exit;
					$hostname='localhost';
					$user = 'root';
					$password = 'Hal0o(0m@72427242';
					$mysql_database = 'haloocomCRM';
					$link = mysqli_connect($hostname, $user, $password,$mysql_database);
					if($LastName[1] == ''){
						$sql = "SELECT department FROM `users` where `department` = 'Sales' and  last_name  like '%$LastName[0]'"; //last_name like '%$LastName[1]' or					
						
					}else{
						$sql = "SELECT department FROM `users` where `department` = 'Sales' and  (last_name like '%$LastName[1]' or first_name  like '%$LastName[0]')"; //					
					}
					
					
			//echo $sql."<br>";
					$result = mysqli_query($link,$sql);
						$row = mysqli_fetch_array($result);	
			
						if($row['department'] == 'Sales'){
							echo "You Don't Have Permission To See This Reports.";exit;
						}
					
					
					
					
					$sql = "select *  from users where is_admin = 1 ";
					//echo $sql;
					$res= mysqli_query($link,$sql);
					$rows=mysqli_fetch_array($res);
				//	echo $rows['user_name']."<br>";
				//	echo $rows['user_hash'];

?>


<html>
	<head>
	
	<style>
	#example1 {
	  border: 1px solid;
	  padding: 10px;
	  box-shadow: 5px 10px;
	}
    </style>
	</head>
	<body>
		<h2 align = "center" style="text-decoration: underline;">Reports</h2>
		<br><br>
		<div id="example1">
		<a href = "index.php?module=Home&action=custom_report1" style="font-size: 15px;">&bull; &nbsp;&nbsp; Agent Wise Report </a>
		<br><br>
		<a href = "index.php?module=Home&action=custom_report2" style="font-size: 15px;">&bull; &nbsp;&nbsp; Case Type Wise Report </a>
		<br><br>
		<a href = "index.php?module=Home&action=custom_report4" style="font-size: 15px;">&bull; &nbsp;&nbsp; Monthly Wise Support Report </a>
		<br><br>
		
		<!--<a href = "index.php?module=Home&action=summary_report">Summary Report </a>-->
		</div>
	</body>
</html>