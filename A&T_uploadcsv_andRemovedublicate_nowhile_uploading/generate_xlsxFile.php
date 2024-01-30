<?php 
 
// Load the database configuration file 
require('dbconnect_mysqli.php');
 
// Include XLSX generator library 
require_once 'PhpXlsxGenerator.php'; 
 
// Excel file name for download 
$fileName = "account-data_" . date('Y-m-d') . ".xlsx"; 
 
// Define column names 
$excelData[] = array('ID', 'entry_date_time', 'accno', 'final_city', 'state', 'zone', 'name', 'pos'); 
 
// Fetch records from database and store in an array 
$query = "SELECT * FROM vicidial_Accountdetails ORDER BY id ASC"; 
$res_data = mysqli_query($link,$query);
while($row=mysqli_fetch_assoc($res_data)){
 
        $lineData = array($row['id'], $row['entry_date_time'], $row['accno'], $row['final_city'], $row['state'], $row['zone'], $row['name'], $row['pos']);  
        $excelData[] = $lineData; 
    } 
 
 
// Export data to excel and download as xlsx file 
$xlsx = CodexWorld\PhpXlsxGenerator::fromArray( $excelData ); 
$xlsx->downloadAs($fileName); 
 
 
 $sql = "TRUNCATE TABLE `vicidial_Accountdetails`";
 $rslt = mysqli_query($link,$sql);
							
exit; 
 
?>