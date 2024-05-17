<?php
include 'PHPExcel-1.8.1/Classes/PHPExcel/IOFactory.php';
$inputFileType = 'Excel2007';
$inputFileName = 'FAQ/fileExample.xls';

//echo $inputFileName;
//die;
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
//$objReader->setLoadAllSheets();
$objPHPExcel = $objReader->load($inputFileName);
//$objPHPExcel->getSheetNames();


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
//$objWriter = $objPHPExcel->getSheetByName('sheet1'); 
//$objWriter->getTitle();
$objWriter->writeAllSheets();
//$objWriter->setSheetIndex(0);
$objWriter->save('php://output');



?>


<html>
<head>
 
 <title>FAQ</title>
  
<style>
  .navigation {
		float: left;
		margin: 0;
		padding-left: 0;
		list-style: none;
  }
  
  .navigation>li {
    float: left;
  }
  
  .navigation>li {
    position: relative;
    display: block;
}

.navigation>li>a {
    padding-top: 15px;
    padding-bottom: 15px;
}
.navigation>li>a {
   // padding-top: 10px;
    //padding-bottom: 10px;
    line-height: 20px;
}

.navigation>li>a {
    position: relative;
    display: block;
    padding: 10px 15px;
}

.navigation {
    background-color: #f8f8f8;
    border-color: #e7e7e7;
}

ul.navigation {
  list-style-type: circle;
}

  </style>
 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<script src="jquery.min.js" type="text/javascript"></script>
<script>
for(var x=1;x<35;x++){	 
	document.getElementById('sheet'+x).style.display = "none";
}

$(document).ready(function()
{    
	 function someFunction(foo)
    {
		var sheetName1 = foo.substr(foo.length - 7);
			//const str = "abc's test#s";
			//console.log(sheetName1);
         var sheetName = sheetName1.replace(/[^a-zA-Z0-9 ]/g, "");
			
        //console.log(sheetName);
		for(var x=0;x<35;x++){	 
			document.getElementById('sheet'+x).style.display = "none";
		}
		document.getElementById(sheetName).style.display = "block";		
    }
   for(var x=0;x<35;x++){
    $('a[href$="sheet'+x+'"]').click(function()
    {
		console.log(this.href);
        someFunction(this.href);
    });
   }
});
</script>


</head>
</html>

