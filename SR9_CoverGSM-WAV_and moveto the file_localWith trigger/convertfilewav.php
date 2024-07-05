<?php 
date_default_timezone_set('Asia/Kolkata');
 session_start();
    $hostname='localhost';
	$user = 'root';
	$password = 'Hal0o(0m@72427242';
	$mysql_database = 'asterisk';
	$db = mysqli_connect($hostname, $user, $password,$mysql_database);
        mysqli_select_db($db,$mysql_database);
	if (mysqli_connect_errno())
	  {
	   die("Connection failed: " . mysqli_connect_error());
	  }

$entry_date_time = date('Y-m-d H:i:s');
$date_complete = date('Y-m-d');

	 $datefolder = date('Y-m-d');
	 $mydir1 = '/srv/www/htdocs/Haloo_Voicelogs/voicefiles/'.$datefolder."/";
		 //echo $mydir1."<br>"; 
		//scanning files in a given directory in descending order
		$myfiles1 = scandir($mydir1, 1);		 
		//displaying the files in the directory
		//echo "<pre>";
		foreach($myfiles1 as $voiceFiles){
			
			if(substr($voiceFiles,-11) == "INT-all.gsm"){
				$voiceFilesPath = $datefolder."/".$voiceFiles;
				//echo $voiceFilesPath; 
				
				$sel_voiceFile = 'select count(*),folder_name,file_name from voiceFileConvertWav where file_name = "'.$voiceFiles.'" and folder_name = "'.$datefolder.'" ';
				$res_voiceFile = mysqli_query($db,$sel_voiceFile);
				$row_voiceFile = mysqli_fetch_array($res_voiceFile);
				
				if($row_voiceFile[0] > 0){
					
					//Dublicate files checking.. for avoiding 
				}
				else{
						$sql_insert = 'insert into voiceFileConvertWav (entry_date_time,folder_name,file_name,path)values("'.$entry_date_time.'","'.$datefolder.'","'.$voiceFiles.'","'.$voiceFilesPath.'" )';
						$res = mysqli_query($db,$sql_insert);
						
				$new_filename = $voiceFiles;
				$Valchangeext = $new_filename;
				$changeFileext = str_replace("gsm","wav",$Valchangeext);
				
				$changeFileName = $changeFileext;
				
					 
                if (!file_exists('/srv/www/htdocs/voicefiles_Wav/'.$date_complete)) 
						{
							mkdir('/srv/www/htdocs/voicefiles_Wav/'.$date_complete, 0777, true);
						}
						
				$filePath ="/srv/www/htdocs/Haloo_Voicelogs/voicefiles/".$date_complete."/".$voiceFiles;
				
				$output_wav = "/srv/www/htdocs/voicefiles_Wav/".$date_complete."/".$changeFileName;
						$input_wav=$filePath;
						$output_wav = str_replace("gsm","wav",$output_wav);
						$play1=exec("sox $input_wav $output_wav");
				}
				
			}
			
		}

 //}
 
 
 


?>
