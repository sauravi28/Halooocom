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

//$filecopy =shell_exec(" sshpass -p 'Haloocom!2020@'  scp -r /srv/www/htdocs/Haloo_Voicelogs/voicefiles/2021-07-07/20210707-115351_8095848215_6666_HALOO_CB-all.gsm root@10.9.6.4:/srv/www/htdocs/Haloo_Voicelogs/voicefiles/2021-07-07 ");

/*
$Foldercount = shell_exec("find /srv/www/htdocs/Haloo_Voicelogs/voicefiles/ -maxdepth 1 -type d -print| wc -l");
echo "<pre>$Foldercount</pre>";  // when you are geeting count 1 extra folder count is geeting add for that you need to do minus 1; 
echo $foldercountval = $Foldercount - 1;
echo "<br>";

$insideFolderFileCount = shell_exec("find /srv/www/htdocs/Haloo_Voicelogs/voicefiles/2021-07-07/ ls | wc -l");
echo "<pre>$insideFolderFileCount</pre>";  // when you are geeting count 1 extra file count file is geeting add for that you need to do minus 1; 
echo $filecount = $insideFolderFileCount - 1;
*/


$current_year = date("Y");
$current_month = date("m");
$current_day = date("d");
if(mkdir("/srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year"))
{
        if(shell_exec("sshpass -p 'Bucksman@2024' scp -r /srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year SKS-106@192.168.29.233:D:/Call_Recordings/"))
	{
		//echo "Year Directory has been created successfully...";
	}
}
sleep(2);
if(mkdir("/srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year/$current_month"))
{
        if(shell_exec("sshpass -p 'Bucksman@2024' scp -r /srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year/$current_month SKS-106@192.168.29.233:D:/Call_Recordings/$current_year/"))
        {
                //echo "Month Directory has been created successfully...";
        }
}
sleep(2);
if(mkdir("/srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year/$current_month/$current_day"))
{
        if(shell_exec("sshpass -p 'Bucksman@2024' scp -r /srv/www/htdocs/Haloo_Voicelogs/sftp_folder/$current_year/$current_month/$current_day SKS-106@192.168.29.233:D:/Call_Recordings/$current_year/$current_month/"))
        {
                //echo "Day Directory has been created successfully...";
        }
}
sleep(2);


  //$mydir = 'voicefiles';   
 // $myfiles = array_diff(scandir($mydir), array('.', '..')); 
  //echo "<pre>";
 // print_r($myfiles);
 //echo  count($myfiles);
 
 //foreach($myfiles as $datefolder){
	 
	 $datefolder = date('Y-m-d');
	 $mydir1 = '/srv/www/htdocs/voicefiles_Wav/'.$datefolder."/";
		 //echo $mydir1."<br>"; 
		//scanning files in a given directory in descending order
		$myfiles1 = scandir($mydir1, 1);		 
		//displaying the files in the directory
		//echo "<pre>";
		foreach($myfiles1 as $voiceFiles){
			
			if(substr($voiceFiles,-4) == ".wav"){
				$voiceFilesPath = $datefolder."/".$voiceFiles;
				//echo "==>".$voiceFilesPath; 
				//die;
				
				$sel_voiceFile = 'select count(*),folder_name,file_name from voiceFile_move_status where file_name = "'.$voiceFiles.'" and folder_name = "'.$datefolder.'" ';
				$res_voiceFile = mysqli_query($db,$sel_voiceFile);
				$row_voiceFile = mysqli_fetch_array($res_voiceFile);
				
				if($row_voiceFile[0] > 0){
					
					//Dublicate files checking.. for avoiding 
				}
				else{
						$sql_insert = 'insert into voiceFile_move_status (entry_date_time,folder_name,file_name,path)values("'.$entry_date_time.'","'.$datefolder.'","'.$voiceFiles.'","'.$voiceFilesPath.'" )';
						
						$res = mysqli_query($db,$sql_insert);
						
						
						//$filecopy =shell_exec(" sshpass -p 'password'  scp -r /srv/www/htdocs/Haloo_Voicelogs/voicefiles/$voiceFilesPath SKS-106@192.168.29.233:D:/Recordings/2024/03/$voiceFiles");
						$filecopy = shell_exec("sshpass -p 'Bucksman@2024'  scp -r /srv/www/htdocs/voicefiles_Wav/$voiceFilesPath SKS-106@192.168.29.233:D:/Call_Recordings/$current_year/$current_month/$current_day/$voiceFiles");
						
						echo $filecopy;
						
						//echo $voiceFiles ;
						
						//$filecopy =shell_exec(" sshpass -p 'Haloocom!2020@'  scp -r /srv/www/htdocs/Haloo_Voicelogs/voicefiles/2021-07-07/$voiceFiles root@10.9.6.4:/home/RECORDINGS/NLPC/2021-07-07 ");
				}
				
			}
			
		}

 //}
 
 
 


?>
