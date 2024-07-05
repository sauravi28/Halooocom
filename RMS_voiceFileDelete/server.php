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
$table = 'voicefiles';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'filename', 'dt' => 0 ),
    array( 'db' => 'date', 'dt' => 1 ),
    array( 'db' => 'time',  'dt' => 2 ),
    array( 'db' => 'source',   'dt' => 3 ),
    array( 'db' => 'destination',     'dt' => 4 ),
	array( 'db' => 'duration',   'dt' => 5 ),
    array( 'db' => 'filename',     'dt' => 6 ),
    array( 'db' => 'campaign',     'dt' => 7 ),
	array( 'db' => 'filename',     'dt' => 9 ,'formatter' => function( $d, $row ) {
          $voice = explode("-",$d);
          $Year = substr($voice[0],0,4);
          $Month= substr($voice[0],4,2);
	  $Day  = substr($voice[0],6,2);
          $path = "voicefiles/$Year-$Month-$Day/$d";
          return "<a href ='$path'; return false; class = 'btn'><i class='fa fa-download'></i></button>";},
             ),
	array( 'db' => 'id',  'dt' => 8, 'formatter' => function( $d, $row ) {
		  return "<button id='myBtn1' onclick='add($d)'; return false; class = 'btn'><i class='fa fa-play'></i></button>";},
             ),
			 array( 'db' => 'id',  'dt' => 10, 'formatter' => function( $id, $row ) {
		  return "<button id='myBtn1' onclick='deletevoice($id)'; return false; class = 'btn'><i class='fa fa-remove'></i></button>";},
             )
	  

             
			 
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'Hal0o(0m@72427242',
    'db'   => 'Voicelogs',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
$val = "deleted='0'";
	 $where = $val;
 
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where )
);

?>

