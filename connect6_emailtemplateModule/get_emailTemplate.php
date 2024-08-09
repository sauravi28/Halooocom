<?php
$template_id = $_REQUEST['template_idVal'];
require_once("db_connect.php");

//Email temaplate values
							   $stmt_emailTemplate = "SELECT `template_body`,`subject` FROM `email_template` WHERE `template_id`='$template_id'";
	                           $rslt_emailTemplate= mysqli_query($conn,$stmt_emailTemplate);
	                           $row_emailTemplate= mysqli_fetch_row($rslt_emailTemplate);
							   $template_body = $row_emailTemplate[0];
							   $subject = $row_emailTemplate[1];
							   
							   echo $template_body;
							   echo "**".$subject;
							   
							   


?>