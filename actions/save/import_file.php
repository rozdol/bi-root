<?php
if ($what == 'import_file'){
	// //$out.= "<pre>";print_r($_POST);$out.= "</pre>";
	// //$out.= "<pre>";print_r($_FILES);$out.= "</pre>";
	// require_once FW_DIR.'/vendor/PHPExcel/Classes/PHPExcel.php';
	// //extension_loaded('zip');
	// //$z = new ZipArchive();
	// // Create new PHPExcel object
	// //$objPHPExcel = new PHPExcel();
	// //$objReader = new PHPExcel_Reader_Excel2007();
	// 
	// //include 'PHPExcel/IOFactory.php';
	// 
	// /**	Load the quadratic equation solver worksheet into memory			**/
	// //$objPHPExcel = \PHPExcel_IOFactory::load('./Quadratic.xlsx');
	// 
	// 
	// if(!empty($_FILES['ufile']['tmp_name'])){
	// 
	// 
	// 	$filename=$_FILES['ufile']['name'];
	// 	$chk_ext = explode(".",$filename);
	// 	if(strtolower($chk_ext[1]) != "xls") {
	// 		$out.= "Please upload .xls file only";	
	// 		exit;
	// 	}
	// 
	// 
	// 	$objReader = new PHPExcel_Reader_Excel5();
	// 	$objReader->setReadDataOnly(true);
	// 	$input = $objReader->load($_FILES['ufile']['tmp_name']);
	// 	$output = new PHPExcel();	
	// 	//$output->setActiveSheetIndex(0);
	// 	$out.= "<h1>$filename<h1>";
	// 	$out.= '<hr />';
	// 	foreach ($input->getWorksheetIterator() as $worksheet) {
	// 
	// 		$worksheetTitle     = $worksheet->getTitle();
	// 		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
	// 		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
	// 		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	// 		$nrColumns = ord($highestColumn) - 64;
	// 
	// 		$out.= "<h2>$worksheetTitle</h2> R:$highestRow, C:$highestColumn<br>";
	// 		$out.= "<table border=1>";
	// 		for ($row=1; $row<=$highestRow; $row++) {
	// 			$out.= "<tr>";
	// 			for ($col=1; $col<=$highestColumnIndex; $col++) {
	// 				$val = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
	// 				$out.= "<td>$val</td>";
	// 			}
	// 			$out.= "</tr>";
	// 			//$item_name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
	// 		}
	// 		$out.= "</table>";
	// 	}
	// 
	// 
	// 
	// 	$out.= '<hr />';
	// 
	// 	$sheetData = $input->getActiveSheet()->toArray(null,true,true,true);
	// 	//var_dump($sheetData);		
	// }
	// 
	// 
	// 
	// 
	// exit;

}

$body.=$out;
