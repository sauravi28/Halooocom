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
           	    $sql = "insert into voicefiles(date,time,source,destination,duration,filename,campaign)values('$date_cal','$time','$source','$destination','$duration','$filename1','$campaign')";
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
