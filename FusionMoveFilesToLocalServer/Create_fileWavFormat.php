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
	 $mydir1 = 'voicefiles/'.$datefolder."/";
		 //echo $mydir1."<br>"; 
		//scanning files in a given directory in descending order
		$myfiles1 = scandir($mydir1, 1);		 
		//displaying the files in the directory
		//echo "<pre>";
		foreach($myfiles1 as $voiceFiles){
			
			if(substr($voiceFiles,-4) == ".gsm"){
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
				
				$voicefilePH = explode("_",$voiceFiles);
				$voicefilesource = $voicefilePH[1];

					$stmt_val = "select first_name,middle_initial,postal_code from vicidial_list where phone_number='$voicefilesource'";
					$rslt_val = mysqli_query($db,$stmt_val);
					$row_val = mysqli_fetch_array($rslt_val);
					$accountNo = $row_val[0];
					$allocationbucket = $row_val[1];
					$portfoliocode = $row_val[2];
									
						
				$Valchangeext = $voiceFiles;
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
					 
                if (!file_exists('/srv/www/htdocs/voicefiles_Wav/'.$date_complete)) 
						{
							mkdir('/srv/www/htdocs/voicefiles_Wav/'.$date_complete, 0777, true);
						}
						
				$filePath ="/srv/www/htdocs/Haloo_Voicelogs/voicefiles/".$date_complete."/".$voiceFiles;
				
				$output_wav = "/srv/www/htdocs/voicefiles_Wav/".$date_complete."/".$FilenameRemovecmap;
						$input_wav=$filePath;
						$output_wav = str_replace("gsm","wav",$output_wav);
						$play1=exec("sox $input_wav $output_wav");
				}
				
			}
			
		}

 //}
 
 
 


?>
