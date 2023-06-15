<?php
$targetDir = "upload_emailFile";
if(is_array($_FILES)) {
	//echo $_FILES['file'];
if(is_uploaded_file($_FILES['file']['tmp_name'])) {
if(move_uploaded_file($_FILES['file']['tmp_name'],"$targetDir/".$_FILES['file']['name'])) {
echo "File uploaded successfully";
}
}
}
?>