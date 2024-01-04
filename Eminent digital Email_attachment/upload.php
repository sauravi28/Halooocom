<?php

// Count total files
$countfiles = count($_FILES['fileupload']['name']);

// Upload directory
$upload_location = "upload_emailFile/";

$count = 0;
for($i=0;$i < $countfiles;$i++){

   // File name
   $filename = $_FILES['fileupload']['name'][$i];

   // File path
   $path = $upload_location.$filename;

   // file extension
   $file_extension = pathinfo($path, PATHINFO_EXTENSION);
   $file_extension = strtolower($file_extension);

   // Valid file extensions
   $valid_ext = array("pdf","doc","docx","jpg","png","jpeg");

   // Check extension
   if(in_array($file_extension,$valid_ext)){

       // Upload file
       if(move_uploaded_file($_FILES['fileupload']['tmp_name'][$i],$path)){
           $count += 1;
       } 
   }

}

echo $count;
exit;
?>