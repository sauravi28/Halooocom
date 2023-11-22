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

       // Upload file
       if(move_uploaded_file($_FILES['fileupload']['tmp_name'][$i],$path)){
           $count += 1;
       } 
 
}

echo $count;
exit;
?>