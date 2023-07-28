<?php 	
		$id =$_REQUEST['id'];	
		$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");			
		$qry = new MongoDB\Driver\Query(['id' => $id]);    					
		$rows = $mongo->executeQuery("admin.vertical", $qry); 
		foreach ($rows as $row) {
			echo $row->vertical_name."*".$row->id;
		}



?>