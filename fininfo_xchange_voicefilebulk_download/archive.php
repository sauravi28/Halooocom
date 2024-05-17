<?php
	$start_date = $_REQUEST['min']; 
	$end_date = $_REQUEST['max']; 
    $txt_extension = $_REQUEST['txt_extension'];
	
	$date_start = $start_date." 00:00:00";
	$date_end   = $end_date." 23:59:59";
		
		$filename   =   'voicefiles_'.$start_date.'-'.$end_date.'.zip';
		$db         =   new mysqli('localhost','root','','xchange');
		$fileQry    =   $db->query("SELECT rec_filename FROM cdr WHERE date_start >='$date_start' and date_end <='$date_end' and extension='$txt_extension' and rec_filename !=''");
		$zip = new ZipArchive;
		if ($zip->open($filename,  ZipArchive::CREATE)){
			while($row  =   $fileQry->fetch_assoc()){
			  $recFile = $row['rec_filename'];
			  //$file1 = explode(".",$recFile);
			  //$recFileName = $file1[0].'.ogg';
			  $zip->addFile('/var/www/html/voicefiles/'.$recFile, $recFile);
			}
			$zip->close();
			 
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . basename($filename).'"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			flush();
			 
			readfile($filename);
		}
		else{
				   echo 'Failed!';
			}
		

?>
