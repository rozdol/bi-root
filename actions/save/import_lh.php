<?php
if ($what == 'import_lh'){

	// if(!empty($_FILES['ufile']['tmp_name'])){	
	// 	$filename=$_FILES['ufile']['name'];
	// 	$chk_ext = explode(".",$filename);
	// 	if(strtolower($chk_ext[1]) != "pdf") {
	// 		$out.= "Please upload .pdf file only";	
	// 		exit;
	// 	}
	// 	$refid=$this->html->readRQn('refid');
	// 	$uploads_dir=$this->data->readconfig('letterhead_dir');
	// 	$tmp_name = $_FILES["ufile"]["tmp_name"];
	// 	$name = $_FILES["ufile"]["name"];
	// 	//$name=$refid."_lh.pdf";
	// 	//$out.= "<pre>";print_r($_POST);$out.= "</pre> $uploads_dir/$name";exit;
	// 	$place=DATA_DIR."/lh/$name";
	// 	if(!(move_uploaded_file($tmp_name, $place)))$this->html->error("File $place not uploaded.");
	// 	//exit;
	// }	
}

$body.=$out;
