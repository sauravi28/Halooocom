<?php 

	//require ('modules/Users/SaveTimezone.php');
    //require ('modules/Users/SetTimezone.php');

	
	if (isset($_GET["start_date"]))				{$start_date=$_GET["start_date"];}
	elseif (isset($_POST["start_date"]))	{$start_date=$_POST["start_date"];}
	if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))	{$end_date=$_POST["end_date"];}
	
	$case_type_cVal = $_GET["case_type"];
	
	//echo "===>".$case_type_cVal;

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
            this.api().columns('2,3,7').every( function () {
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
		 buttons: [
		 'pageLength',

             {
                extend: 'excelHtml5',
				title:'Case_Report_excel_'+today
                
            },
            {
                extend: 'pdfHtml5',
				title:'Case_Report_pdf_'+today
            }

		 
        ] 
    } );
} );

	function sendUserId(userId){
		alert(userId);
	}

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
	
	<!--<div>
		<form action = "" method = "POST" >
			<table class = "gfg">
				<tr>
				<td>Start Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><input type = "date" value = "<?php  //echo $start_date; ?>" name = "start_date" class = "date_cls"> &nbsp;&nbsp;&nbsp;
				<td> </td>
				<td>End Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><input type = "date" value = "<?php  //echo $end_date; ?>" name = "end_date" class = "date_cls">
				<td></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name = "submit" class = "btn1">Submit</button></td>
				</tr>
			</table>
		</form>
	</div>-->
		<br>
		<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
			    <th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Case Number</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Case Type</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Assigned To</th>
                <th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Client Name</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Created Date</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">End Date</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Subject</th>
				<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">State</th>
				
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th></th>
				<th></th>
				<th></th>
                <th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
            </tr>
        </tfoot>
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
						
						if($start_date != '' && $end_date != ''){
						//echo $start_date;
						$sql_case = "select cc.case_type_c,c.id,c.name,c.state,c.account_id,c.case_number,CONVERT_TZ(c.date_entered,'+00:00','+05:30') as time,CONVERT_TZ(cc.end_date_c,'+00:00','+05:30') as end_datetime,cc.case_type_c from cases c join cases_cstm cc on c.id = cc.id_c where c.assigned_user_id = '$userId' and cc.case_type_c='$case_type_cVal' and c.date_entered >= '$start_date 00:00:00' and c.date_entered <= '$end_date 23:59:59'";
						//echo $sql_case;
						}else{
							$date = date("Y-m-d");
						//echo "test".$date."<br>";
							$sql_case = "select cc.case_type_c,c.id,c.name,c.state,c.account_id,c.case_number,CONVERT_TZ(c.date_entered,'+00:00','+05:30') as time,CONVERT_TZ(cc.end_date_c,'+00:00','+05:30') as end_datetime,cc.case_type_c from cases c join cases_cstm cc on c.id = cc.id_c where c.assigned_user_id = '$userId' and cc.case_type_c='$case_type_cVal' and c.date_entered >= '$date 00:00:00' and c.date_entered <= '$date 23:59:59'";
					//echo $sql_case;
						}
					
						
					//echo $sql_total_cases."<br>";
						$res_total_cases = mysqli_query($link,$sql_case);
						while($rows_case=mysqli_fetch_array($res_total_cases)){
						
						$state = $rows_case['state'];
						$date_entered = $rows_case['time'];
						$date_created = date("m/d/Y g:iA", strtotime($date_entered));
						$id = $rows_case['id'];
						$case_name = $rows_case['name']; 
						$case_no = $rows_case['case_number'];
						$account_id = $rows_case['account_id'];
						
						$sql_account = "SELECT name FROM `accounts` WHERE `id` = '$account_id'";
						$res_account = mysqli_query($link,$sql_account);
						$row_account = mysqli_fetch_array($res_account);
						$accountName = $row_account['name'];
						
						$case_type_c = $rows_case['case_type_c'];
						$endDate = $rows_case['end_datetime'];
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
					<td align="center" ><?php echo $accountName; ?></td>
					<td align="center" ><?php echo $date_created ;?></td>
					<td align="center" ><?php echo $date_end;?></td>
					<td align="center" ><?php echo $case_name;?></td>
					<td align="center" ><?php echo $state;?></td>
					
				</tr> 
					<?php  } 
					}?>
			</tbody>

    </table>
	</body>
</html>
<?php exit;?>