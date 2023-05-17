<?php 

	//require ('modules/Users/SaveTimezone.php');
    //require ('modules/Users/SetTimezone.php');

	
	if (isset($_GET["start_date"]))				{$start_date=$_GET["start_date"];}
	elseif (isset($_POST["start_date"]))	{$start_date=$_POST["start_date"];}
	if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))	{$end_date=$_POST["end_date"];}
	
	

?>
<html>
	<head>
	<script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	
	<script>
		$(document).ready(function() {
		$('#example').DataTable( {
		 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		 "dom": "1Bfrtip",
		 buttons: [
		 'pageLength',

            {
                extend: 'excelHtml5',
				title:''
                
            },
            {
                extend: 'pdfHtml5',
				title:''
            }

		 
        ] 
    } );
} );

	function sendUserId(userId){
		alert(userId);
	}

	</script>
	<style>
	
		th{
		background-color: grey !important;
		color: #fff;
		font-weight: bold !important;
		font-size:15px !important;
		}



		.bt {
		background-color: Transparent;
		background-repeat:no-repeat;
		text-decoration:underline;
		border: none;
		cursor:pointer;
		overflow: hidden;
		outline:none;
		color:#fff
		}



		.btn {
		background-color:#FFFFFF;
		border: 1px solid #6EBA01;
		color: #6EBA01;
		padding: 12px 16px;
		font-size: 16px;
		cursor: pointer;
		}

		/ Darker background on mouse-over /
		.btn:hover {
		background-color: ;
		}



		.btn_sub{
		background-color:#6eba01;
		border-radius:5px;
		color: #ffff;	
		}
		.btn_sub:hover{
		background-color:#1A2B6D ;

		color: #ffff;	
		}
		.btn {
		background-color:#FFFFFF;
		border: 1px solid #6EBA01;
		color: #6EBA01;
		padding: 12px 16px;
		font-size: 16px;
		cursor: pointer;
		}

		/ Darker background on mouse-over /
		.btn:hover {
		background-color: ;
		}
		.fa_icon {
		padding: 10% 0% 0% 20%;
		margin-top: -15%;
		margin-left: 14px;
		background-color: #6EBA01;
		}
		.scrollable {
		height: 171px;
		overflow-y: scroll;
		}
		.bt {
		background-color: Transparent;
		background-repeat:no-repeat;
		text-decoration:underline;
		border: none;
		cursor:pointer;
		overflow: hidden;
		outline:none;
		color:#fff
		}
		.dt-button-collection {
			margin-top: -1px !important;
		}
		.btn1{
			background-color: #6EBA01;
			border-color: #6EBA01;
			box-shadow: none;
            border: 1px solid transparent;
			color: #ffff;
			font-size:15px;
			margin-top:6px;
			
		}
		.btn1:hover{
			background-color: #1a2b6d;
		}
		</style>
	
	</head>
	
	<body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
	
	<div>
		<form action = "" method = "POST" >
			<table class = "gfg">
				<tr>
				<td>Start Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><input type = "date" value = "<?php  echo $start_date; ?>" name = "start_date" class = "date_cls"> &nbsp;&nbsp;&nbsp;
				<td> </td>
				<td>End Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><input type = "date" value = "<?php  echo $end_date; ?>" name = "end_date" class = "date_cls">
				<td></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name = "submit" class = "btn1">Submit</button></td>
				</tr>
			</table>
		</form>
	</div>
		<br>
		<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
			    <th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Case Number</th>
				 <th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Case Type</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Assigned To</th>
                <th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Client Name</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Created Date</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">End Date</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Subject</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Product Type</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">State</th>
				<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Priority</th>								
				<!--<th style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Description</th>-->
				
            </tr>
        </thead>
			<tbody>
				<?php 
					$hostname='localhost';
					$user = 'root';
					$password = 'Hal0o(0m@72427242';
					$mysql_database = 'haloocomCRM';
					$link = mysqli_connect($hostname, $user, $password,$mysql_database);

					
					
					$sql_user = 'SELECT * FROM `users`';
					$res_user = mysqli_query($link,$sql_user);
					while($rows=mysqli_fetch_array($res_user)){						
						
						$userId = $rows['id'];																																						
						$userName = $rows['user_name'];
					
						
						//total cases start
						if($start_date != '' && $end_date != ''){
						//echo $start_date;
						$sql_total_cases = "select id,name,description,priority,state,account_id,type,date_entered,CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time,case_number from cases where assigned_user_id = '$userId' and date_entered >= '$start_date 00:00:00' and date_entered <= '$end_date 23:59:59'";
						}else{
							$date = date("Y-m-d");
						//echo "test".$date."<br>";
							$sql_total_cases = "select id,name,description,priority,state,account_id,type,date_entered,CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time,case_number from cases where assigned_user_id = '$userId' and date_entered >= '$date 00:00:00' and date_entered <= '$date 23:59:59'";
							
							//CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time 
						}
						
					//echo $sql_total_cases."<br>";
						$res_total_cases = mysqli_query($link,$sql_total_cases);
						while($rows_case=mysqli_fetch_array($res_total_cases)){
						
						$description = $rows_case['description'];
						$priority = $rows_case['priority'];
						$state = $rows_case['state'];
						$type = $rows_case['type'];
						$date_entered = $rows_case['time'];
						$date_created = date("m/d/Y g:iA", strtotime($date_entered));
						$id = $rows_case['id'];
						$case_name = $rows_case['name']; 
						$case_no = $rows_case['case_number']; 
					//	echo $id;
						$account_id = $rows_case['account_id'];
						$sql_account = "SELECT name FROM `accounts` WHERE `id` = '$account_id'";
						$res_account = mysqli_query($link,$sql_account);
						$row_account = mysqli_fetch_array($res_account);
						$accountName = $row_account['name'];
						$sql_endDate = "SELECT CONVERT_TZ(`end_date_c`,'+00:00','+05:30') as end_datetime,case_type_c  FROM `cases_cstm` WHERE `id_c` = '$id'";
						$res_endDate = mysqli_query($link,$sql_endDate);
						$row_endDate = mysqli_fetch_array($res_endDate);
						$case_type_c = $row_endDate['case_type_c'];
						$endDate = $row_endDate['end_datetime'];
						if($endDate ==''){
						 $date_end = '';
						}else {
							$date_end = date("m/d/Y g:iA", strtotime($endDate));
						}
						
						
						
												
					
				?>
			   <tr>
					<td align="center" ><?php echo $case_no; ?></td>
					<td align="center" ><?php echo $case_type_c; ?></td>
					<td align="center" ><?php echo $userName; ?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=tot">--><?php echo $accountName; ?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Safe">--><?php echo $date_created ;?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Life_in_Danger">--><?php echo $date_end;?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Life_in_Danger">--><?php echo $case_name;?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Not_sure">--><?php echo $type; ?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Safe">--><?php echo $state;?></td>
					<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Life_in_Danger"> --><?php echo $priority;?></td>
					<!--<td align="center" ><!--<a href = "index.php?module=Home&action=detail_view&userId=<?php //echo $userId ?>&caseType=Not_sure"><?php //echo $description; ?></td>-->
					
				</tr> 
					<?php  } 
					}?>
			</tbody>
        <tfoot>
            <tr>
      
            </tr>
        </tfoot>
    </table>
	</body>
</html>
<?php exit;?>