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
		echo "File==".$file;
                echo "<br>";
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
                $date_complete = date('Y-m-d');

 
			$sql1 = "select count(id) from voicefiles where filename = '$filename1'";
                        $res  = mysqli_query($conn,$sql1);
                        $row  = mysqli_fetch_array($res,MYSQLI_NUM);
                       
                        $cnt = $row[0];
            if($cnt == 0)
			{
				$link = mysqli_connect("localhost","root","Hal0o(0m@72427242","asterisk");
				// Check connection
				if (mysqli_connect_errno())
					{
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}

				    $stmt_val = "select first_name,middle_initial,postal_code from vicidial_list where phone_number='$source';";
					$rslt_val = mysqli_query($link,$stmt_val);
					$row_val = mysqli_fetch_array($rslt_val);
					$accountNo = $row_val[0];
					$allocationbucket = $row_val[1];
					$portfoliocode = $row_val[2];
					
				$FilenameWithNewVal = $portfoliocode."_".$allocationbucket."_".$accountNo."_".$filename1;
			    $Valchangeext = $filename1;
				$changeFileext = str_replace("gsm","wav",$Valchangeext);
				$changeFileName = $portfoliocode."_".$allocationbucket."_".$accountNo."_".$changeFileext;
				
				$voicefileName = explode("_",$changeFileName);
				$voicefileNameVal0 = $voicefileName[0];
                $voicefileNameVal1 = $voicefileName[1];
                $voicefileNameVal2 = $voicefileName[2];
				$voicefileNameVal3 = $voicefileName[3];
                $voicefileNameVal4 = $voicefileName[4];
                $voicefileNameVal5 = $voicefileName[5];
                    
                $FilenameRemovecmap = $voicefileNameVal0."_".$voicefileNameVal1."_".$voicefileNameVal2."_".$voicefileNameVal3."_".$voicefileNameVal4."_".$voicefileNameVal5.".wav";
					 
                if (!file_exists('/srv/www/htdocs/Haloo_Voicelogs/voicefiles_campaign/'.$date_complete.'/'.$campaign)) 
				{
                    mkdir('/srv/www/htdocs/Haloo_Voicelogs/voicefiles_campaign/'.$date_complete.'/'.$campaign, 0777, true);
                }
				
				$filePath ="/srv/www/htdocs/Haloo_Voicelogs/voicefiles/".$date_complete."/".$file;
				//$file = "voicefiles/2024-03-05/20240305-103751_8939478414_USER3_CYC15-all.gsm";
				//$output = "TestFilewavFormat/20240305-103751_8939478414_USER3_CYC15-all.wav";
				
				$output = "/srv/www/htdocs/Haloo_Voicelogs/voicefiles_campaign/".$date_complete."/".$campaign."/".$FilenameRemovecmap;
				$input=$filePath;
				$folder = date('Y-m-d');
				$output = str_replace("gsm","wav",$output);
				$play=exec("sox $input $output");

				
				//$destinationFilePath = "/srv/www/htdocs/Haloo_Voicelogs/voicefiles_campaign/".$date_complete."/".$campaign."/".$changeFileName;
			
				/*if(!copy($filePath, $destinationFilePath) ) {  
				echo "File can't be moved!".$changeFileName;  
				}  
				else {  
				echo "File has been moved!";
				}*/	
				
			echo "Voicefile File".$filename1."is not there in the DB";
			//echo "$campaign";
           	    $sql = "insert into voicefiles(date,time,source,destination,duration,filename,campaign,FilenameWithNewVal,accountNo,allocationbucket,portfoliocode)values('$date_cal','$time','$source','$destination','$duration','$filename1','$campaign','$FilenameWithNewVal','$accountNo','$allocationbucket','$portfoliocode')";
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
