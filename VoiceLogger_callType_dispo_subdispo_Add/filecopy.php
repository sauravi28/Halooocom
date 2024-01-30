<?php
  $date = date("Y-m-d");
get_all_directory_and_files("/srv/www/htdocs/Haloo_Voicelogs/voicefiles/$date");


 function get_all_directory_and_files($dir){
       $conn = mysqli_connect("localhost","root","Hal0o(0m@72427242","Voicelogs");
            // Check connection
            if (mysqli_connect_errno())
           {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
           }
     
     $dh = new DirectoryIterator("$dir");   
     foreach ($dh as $item) {
         if (!$item->isDot()) {
            if ($item->isDir()) {
	        //echo "if".$dir;
                get_all_directory_and_files("$dir/$item");
            } else {
		//echo "else";		
				$dir_dur = $dir . "/" .$item->getFilename();
				$filename1 = $item->getFilename();
		 
                $file = $item->getFilename();
		//echo "File==".$file;
               // echo "<br>";
                /* to get date */
                $date_file = explode("-",$file);
                $date_filecontents = $date_file[0];
                $Year = substr($date_filecontents,0,4);
                $month = substr($date_filecontents,4,2); 
                $date = substr($date_filecontents,6,2);      
                $date_cal = $Year."-".$month."-".$date;
                /* to get time */
                $time_file = explode("_",$date_file[1]);
                $hour = substr($time_file[0],0,2);
                $minute = substr($time_file[0],2,2); 
                $second = substr($time_file[0],4,2); 
                $time   = $hour .":".$minute.":".$second;
                /* to get source */
                $source = $time_file[1];
                $destination = $time_file[2];  
 
                /*  to get the file size */
			    $filename = $dir_dur;
                            $duration=wavDur($filename);
			    $campaign=$time_file['3'];
                  //echo $date_cal."===".$time."===".$source."===".$destination."===".$duration."==".$campaign;
                // echo "$campaign";  
			$sql1 = "select count(id) from voicefiles where filename = '$filename1'";
                        $res  = mysqli_query($conn,$sql1);
                        $row  = mysqli_fetch_array($res,MYSQLI_NUM);
                       
                        $cnt = $row[0];
                        if($cnt == 0)
			            {
							echo "Voicefile File".$filename1."is not there in the DB";
						   //echo "$campaign";
						   
						    $link = mysqli_connect("localhost","root","Hal0o(0m@72427242","asterisk");
								// Check connection
								if (mysqli_connect_errno())
							       {
										echo "Failed to connect to MySQL: " . mysqli_connect_error();
							        }
				 // Dispo and Sub Dispo//
						$voicefilesVal =  substr($filename1,0,-8);
						echo $voicefilesVal;
						//echo "<br>";
						
						$sql_fileid = "SELECT vicidial_id from recording_log where filename like '%$voicefilesVal%'";
						echo $sql_fileid ;
						//die;
						$result_fileid = mysqli_query($link,$sql_fileid);
						$row_fileid    = mysqli_fetch_array($result_fileid,MYSQLI_NUM);			
						if($row_fileid[0] !=''){
							
							$sel_status = "select count(*),status,field40 From vicidial_log where uniqueid = '$row_fileid[0]'";
							echo $sel_status;
							//echo "<br>";
							$res_status = mysqli_query($link,$sel_status);
							$row_status = mysqli_fetch_array($res_status,MYSQLI_NUM);
							
							//Incoming//
							$sel_status_in = "select count(*),status,field40 From vicidial_closer_log  where closecallid = '$row_fileid[0]'";
							echo $sel_status_in;
							//echo "<br>";
							$res_status_in = mysqli_query($link,$sel_status_in);
							$row_status_in = mysqli_fetch_array($res_status_in,MYSQLI_NUM);
							
							if($row_status[0] > 0){
							$Disposition_out = $row_status[1];
							$SubDisposition_out = $row_status[2];
							
							$Disposition = $Disposition_out;
							$SubDisposition = $SubDisposition_out;
							$call_type = "Outgoing";
							}
							else if($row_status_in[0] > 0){
								
							$Disposition_In = $row_status_in[1];
							$SubDisposition_In = $row_status_in[2];
							
							$Disposition = $Disposition_In;
							$SubDisposition = $SubDisposition_In;
							$call_type = "Incoming";	
							
							}else{
								
								$Disposition = '';
							    $SubDisposition = '';
							
							}
								
						}
						   
								$sql = "insert into voicefiles(date,time,source,destination,duration,filename,campaign,disposition,sub_disposition,call_type)values('$date_cal','$time','$source','$destination','$duration','$filename1','$campaign','$Disposition','$SubDisposition','$call_type')";
								$result = mysqli_query($conn,$sql);
									echo $sql;         
									echo "<br>";
						}
			else
			{
			echo "Voicefile File".$filename1."already there in the DB";
			echo "<br>"; 
			}
            }   
         }
      }
        mysqli_close($conn);
   }
 
 
      function wavDur($file)
{

       $type=substr($file,-3);     
       if($type=="gsm")
       {
       $fp = fopen($file, 'r');
       $size_in_bytes = filesize($file);
       $sec = ceil($size_in_bytes/1650);
       $minutes = intval(($sec / 60) % 60);
       $seconds = intval($sec % 60);
       return str_pad($minutes,2,"0", STR_PAD_LEFT).":".str_pad($seconds,2,"0", STR_PAD_LEFT);
       }
       
     
}  
          	  

?>
