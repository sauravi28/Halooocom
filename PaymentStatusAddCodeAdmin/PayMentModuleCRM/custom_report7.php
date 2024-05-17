<?
	$hostname='localhost';
    $user = 'root';
    $password = 'Hal0o(0m@72427242';
    $mysql_database = 'haloocomCRM';
    $link = mysqli_connect($hostname, $user, $password,$mysql_database);
										
?>
<html>
	<head>
	
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
	
#pdfViewer {
      width: 100%;
      height: 500px;
    }

    #pdfModal {
      display: none;
      position: fixed;
      top: 0;
      left: 270px;
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
		
	
	</head>
	
	<body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
	
	<!--<div>
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
	</div>-->
		<br>
		<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Sr.No</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Entry Datetime</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Client Name</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Payment Date</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Amount</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Uploaded Filename</th>
			<th class="thclass" style="font-family:Nunito Sans,sans-serif;font-size: 0.875rem;">Action</th>
            </tr>
        </thead>
		<tfoot>
            <tr>

            </tr>
        </tfoot>
			<tbody>
				<?php 

				
							$date = date("Y-m-d");
						//echo "test".$date."<br>";
							$sel_data = "SELECT * ,CONVERT_TZ(`entry_date_time`,'+00:00','+05:30') as entry_date_timeVal FROM vicidial_PaymentStatus_data";
						$res_DropCall = mysqli_query($link,$sel_data);
							$a=1;
						while($row=mysqli_fetch_assoc($res_DropCall)){
							
						$QC_file = 'uploadsPaymentStatus_files/' . $row['filename'];
						
						$date_entered = $row['entry_date_timeVal'];
						$date_created = date("m/d/Y g:iA", strtotime($date_entered));
						
				?>
									<tr>
										<td><?php echo $a; ?></td>
										<td><?php echo $date_created;?></td>
										<td><?php echo $row['client_name'];?></td>
										<td><?php echo $row['pay_date'];?></td>
										<td><?php echo $row['ammount']."/-";?></td>
										<td><?php echo $row['filename'];?></td>	
										<td><button onclick="viewPDF('<?php echo $QC_file; ?>')" title="Preview file"> <i class="fa fa-eye"></i></button></td>
									</tr>
						<?php   
							$a++;}
						?>
			</tbody>

    </table>
	
	<div id="pdfModal" onclick="closePDFModal()">
    <div id="modalContent">
      <h2><b>Preview Uploaded File</b></h2>
      <span id="closeBtn" onclick="closePDFModal()"><b>&times;</b></span>
      <div id="pdfViewer"></div>
    </div>
  </div>
<script>
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
	</body>
</html>

<?php exit;?>
