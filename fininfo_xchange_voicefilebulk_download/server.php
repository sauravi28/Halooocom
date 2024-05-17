<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'cdr';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id','dt' => 0 ),
    array( 'db' => 'date_start','dt' => 1 ),
    array( 'db' => 'extension','dt' => 2 ),
    array( 'db' => 'cust_number','dt' => 3 ),
	array( 'db' => 'call_status','dt' => 4 ),
	array( 'db' => 'duration','dt' => 5 ),
	array( 'db' => 'rec_filename','dt' => 6 ),
	
	array( 'db' => 'id','dt' => 7, 'formatter' => function( $d, $row ) {
		
		$link = mysqli_connect('localhost','root','','xchange');
		
		$sql="select call_status,rec_filename  from cdr where id = '$d'";
		$res_status = mysqli_query($link,$sql);
		$row_ = mysqli_fetch_array($res_status);
		
		if($row_[0] != 'CANCEL'){
			$voiceFileName = $row_[1];
		 return "<button class ='btn' onclick='play(\"$voiceFileName\")'; return false;><i class='fas fa-play' style='font-size:20px;color:#1a2b6d'></i></button>";
		}
        
	} )
	
		
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'xchange',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>

