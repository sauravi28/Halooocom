<?
                                        $hostname='localhost';
                                        $user = 'root';
                                        $password = 'Hal0o(0m@72427242';
                                        $mysql_database = 'haloocomCRM';
                                        $link = mysqli_connect($hostname, $user, $password,$mysql_database);
										
										
	if (isset($_GET["start_date"]))				{$start_date=$_GET["start_date"];}
	elseif (isset($_POST["start_date"]))	{$start_date=$_POST["start_date"];}
	if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))	{$end_date=$_POST["end_date"];}
	
?>
<html>
	<head>
	<!--<script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src = "https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
	<script src = "https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
	<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
	
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
		/*$(document).ready(function() {
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
} );*/



$(document).ready(function() {
	
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = dd + '-' + mm + '-' + yyyy;
	
    $('#example').DataTable( {
        initComplete: function () {
            this.api().columns('0').every( function () {
                var column = this;
                var select = $('<select><option value="">select</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"dom": "1Bfrtip",
		"order": [[ 0, "desc" ]],
		 buttons: [
		 'pageLength',

             {
                extend: 'excelHtml5',
				title:'Engineering_Daily_Task_Report_Excel_'+today
                
            },
            {
                extend: 'pdfHtml5',
				title:'Engineering_Daily_Task_Report_PDF_'+today
            }

		 
        ] 
    } );
} );


	</script>
	<style>
	
		.thclass{
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
		
		input[type=text] {
		background: #fff !important;
		padding: 5px !important;
		border: 1px solid #a5e8d6 !important;
		border-radius: 4px !important;
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
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Task Title</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Task Description</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Product Type</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Priority</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Task Type</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Customer Name</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Status</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Start Date</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">End Date</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Developer</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Assigned To</th>
            </tr>
        </thead>
		<tfoot>
            <tr>

            </tr>
        </tfoot>
			<tbody>
				<?php 

				if($start_date != '' && $end_date != ''){
						//echo $start_date;
						$sql_user = "SELECT * ,CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time,CONVERT_TZ(`end_date`,'+00:00','+05:30') as end_datetime FROM engdt_engineering where date_entered >= '$start_date 00:00:00' and date_entered <= '$end_date 23:59:59' and deleted='0'";
						}else{
							$date = date("Y-m-d");
						//echo "test".$date."<br>";
							$sql_user = "SELECT * ,CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time,CONVERT_TZ(`end_date`,'+00:00','+05:30') as end_datetime FROM engdt_engineering where date_entered >= '$date 00:00:00' and date_entered <= '$date 23:59:59' and deleted='0'";
							
							//CONVERT_TZ(`date_entered`,'+00:00','+05:30') as time 
						}
						

                    $res_user = mysqli_query($link,$sql_user);
					
					while($rows=mysqli_fetch_array($res_user)){						
						
						$task_title = $rows['task_title'];
						$task_description = $rows['task_description'];
						$product_type = $rows['product_type'];
						$priority = $rows['priority'];
						$task_type = $rows['task_type'];
						$customer_name = $rows['customer_name'];
						$status = $rows['status'];
						$date_entered = $rows['time'];
						$date_created = date("m/d/Y g:iA", strtotime($date_entered));
						
						$end_date = $rows['end_datetime'];
						if($end_date ==''){
						 $date_end = '';
						}else {
							$date_end = date("m/d/Y g:iA", strtotime($end_date));
						}
						
						$created_by = $rows['created_by'];
						$TaskId_c = $rows['id'];
						$assigned_user_id = $rows['assigned_user_id'];
						
						$sel_stmt1 = "SELECT accounts_engdt_engineering_1accounts_ida FROM accounts_engdt_engineering_1_c WHERE accounts_engdt_engineering_1engdt_engineering_idb='$TaskId_c'";
							//echo $sel_stmt1;
							//die;
							$rslt_stmt1=mysqli_query($link,$sel_stmt1);	
							$row_stmt1 = mysqli_fetch_row($rslt_stmt1);
							$accountNameID =$row_stmt1[0];
							
							
							$sel_stmt2 = "SELECT name FROM accounts WHERE id='$accountNameID'";
							//echo $sel_stmt2;
							//die;
							$rslt_stmt2=mysqli_query($link,$sel_stmt2);	
							$row_stmt2 = mysqli_fetch_row($rslt_stmt2);
							$accountName =$row_stmt2[0];
							

if($product_type == "connect_4.0"){
	$product_type = "Connect 4.0";
}elseif($product_type == "connect_5.0"){
	$product_type = "Connect 5.0";
}elseif($product_type == "connect_6.0"){
	$product_type = "Connect 6.0";
}

if($priority == "low"){
	$priority = "Low";
}elseif($priority == "medium"){
	$priority = "Medium";
}elseif($priority == "high"){
	$priority = "High";
}

if($task_type == "calling"){
        $task_type = "Calling";
}elseif($task_type == "reports"){
        $task_type = "Reports";
}elseif($task_type == "voice_logger"){
        $task_type = "Voice Logger";
}elseif($task_type == "customisation"){
        $task_type = "Customisation";
}elseif($task_type == "integration"){
        $task_type = "Integration";
}

if($status == "open"){
        $status = "Open";
}elseif($status == "completed"){
        $status = "Completed";
}elseif($status == "inprogress"){
        $status = "In Progress";
}elseif($status == "onhold"){
        $status = "On Hold";
}elseif($status == "awaiting_confirmation"){
        $status = "Awaiting For Confirmation";
}elseif($status == "awaiting_info_client"){
        $status = "Awaiting Info From Client";
}

$stmt = "select user_name,first_name,last_name from users where id='$assigned_user_id'";
$rslt = mysqli_query($link,$stmt);
$urow = mysqli_fetch_array($rslt);
$assigned_to = $urow['user_name'];
$first_name = $urow['first_name'];

				?>
			   <tr>
					<td align="center" ><?php echo $task_title; ?></td>
					<td align="center" ><?php echo $task_description; ?></td>
					<td align="center" ><?php echo $product_type; ?></td>
					<td align="center" ><?php echo $priority; ?></td>
					<td align="center" ><?php echo $task_type; ?></td>
					<td align="center" ><?php echo $accountName; ?></td>
					<td align="center" ><?php echo $status; ?></td>
					<td align="center" ><?php echo $date_created; ?></td>
					<td align="center" ><?php echo $date_end; ?></td>
					<td align="center" ><?php echo $first_name; ?></td>
					<td align="center" ><?php echo $assigned_to; ?></td>
				</tr> 
						<?php   
							}
						?>
			</tbody>

    </table>
	</body>
</html>
<?php exit;?>
