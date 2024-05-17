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
				
				$source_file = $item->getFilename();
                                $source_file_path = $dir."/".$source_file;
                                $file_split = explode(".",$source_file);
                                $source_file_name = $file_split[0];
                                $source_file_format = $file_split[1];
                                $destination_file = $source_file_name.".mp3";
                                $destination_file_path = $dir."/".$destination_file;

                                // Use the shell_exec function to execute the sox command
                                //$command = "sox $source_file_path -e signed-integer -r 44100 -b 16 -c 2 -t mp3 $destination_file_path";
				if($source_file_format == "gsm")
				{
					$command = "sox $source_file_path $destination_file_path";
					shell_exec($command);

					// Check if the conversion was successful
					if (file_exists($destination_file_path)) {
						echo 'Conversion Successful!';
						if($source_file_format == "gsm"){
							shell_exec("rm $source_file_path");
						}
					} else {
                                        	echo 'Conversion failed.';
                                	}
				}
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
                  //echo $date_cal."===".$time."===".$source."===".$destination."===".$duration."==".$campaign;
                // echo "$campaign";  
			//$sql1 = "select count(id) from voicefiles where filename = '$filename1'";
			$sql1 = "select count(id) from voicefiles where filename = '$destination_file'";
                        $res  = mysqli_query($conn,$sql1);
                        $row  = mysqli_fetch_array($res,MYSQLI_NUM);
                       
                        $cnt = $row[0];
                        if($cnt == 0)
			{
			echo "Voicefile File".$filename1."is not there in the DB";
//echo "$campaign";
           	    $sql = "insert into voicefiles(date,time,source,destination,duration,filename,campaign)values('$date_cal','$time','$source','$destination','$duration','$destination_file','$campaign')";
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
       if($type=="gsm" or $type=="mp3")
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
