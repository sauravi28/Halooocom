<?php
date_default_timezone_set('Asia/Kolkata');
//upload.php

	$hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$link = mysqli_connect($hostname, $user, $password,$mysql_database);
	
	$campaign=$_REQUEST['campaign'];
	
	
	//Update Calls 
				$notification = $_POST["get_notification"];
				if($notification == 'get notification'){
					
					$phonNo = $_POST["phonNo"];
					
					$sqlcount = 'select id,phone_number from bluedart_list_upload where phone_number="'.$phonNo.'" and call_update = "0"';   
					//echo $sqlcount;
					$resultcount = mysqli_query($link,$sqlcount);
					$row1 = mysqli_fetch_array($resultcount);
					$id = $row1[0];
					$phone_number = $row1[1];
					
					$update_call = 'update bluedart_list_upload set call_update = "1" where phone_number = "'.$phone_number.'" and id="'.$id.'"'; 
					mysqli_query($link,$update_call);

					echo "Upadte call";
					exit;
						
				}

	
?>
<style>
.newtable {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 50%;
 
}

.newtabletd, .newtableth  {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.newtabletr:nth-child(even){
  background-color: #dddddd;
}
</style>
 <hr>
	<h3>Uploaded Leads</h3>
		<table class="newtable" id="newtable">
						<tr class="newtabletr">
							<th class="newtableth" style="font-size:12px;">DateTime</th>
							<th class="newtableth" style="font-size:12px;">Customer Name</th>
							<th class="newtableth" style="font-size:12px;">Phone Number</th>
							<th class="newtableth" style="font-size:12px;">Campaign</th>
							<th class="newtableth" style="font-size:12px;">Call</th>
						</tr>
       
        <tbody>
			 <?php 
							   $sel_data = 'select * from bluedart_list_upload where campaign="'.$campaign.'" and call_update = "0"';
									//echo   $sel_data;
									//echo "<br>";
										$res_data = mysqli_query($link,$sel_data);
									
										$a=1;
										while($row=mysqli_fetch_assoc($res_data)){
									?>    
                                        <tr class="newtabletr">
                                            <td class="newtabletd" style="font-size:10px;"><?php echo $row['entry_date_time'];?></td>
											<td class="newtabletd" style="font-size:10px;"><?php echo $row['customer_name'];?></td>
											<td class="newtabletd" style="font-size:10px;"><?php echo $row['phone_number'];?></td>
											<td class="newtabletd" style="font-size:10px;"><?php echo $row['campaign'];?></td>
                                            <td class="newtabletd" style="font-size:10px;"><button type="button" class="btn btn-sm" style="width: 23px;height:23px;border: none;cursor: pointer;padding:0px;color: white;font-family: roboto, sans-serif;border-radius: 55px;background-color: #52c343;" title="Call" onclick ="num('<?php echo $row['phone_number']; ?>');"> 
												<i class="fa fa-phone" aria-hidden="true" style="font-size: 12px;"></i></button>
											</td>
										</tr>
										<?php 
										$a++;    
									}?>
		</tbody>
        
    </table>
