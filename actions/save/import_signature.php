<?php
if ($what == 'import_signature'){
	// 
	// if(!empty($_FILES['ufile']['tmp_name'])){	
	// 	$filename=$_FILES['ufile']['name'];
	// 	$chk_ext = explode(".",$filename);
	// 	if(strtolower($chk_ext[1]) != "png") {
	// 		$out.= "Please upload .png file only. It supports transparency";	
	// 		exit;
	// 	}
	// 	$refid=$this->html->readRQn('refid');
	// 
	// 	$uploads_dir=$this->data->readconfig('signatures_dir');
	// 	$tmp_name = $_FILES["ufile"]["tmp_name"];
	// 	$name = $_FILES["ufile"]["name"];
	// 	$refid=$uid;
	// 	$name=$refid."_sign.png";
	// 	//$out.= "<pre>";print_r($_POST);$out.= "</pre> $uploads_dir/$name";exit;
	// 	if($refid>0){move_uploaded_file($tmp_name, "$uploads_dir/$name");};
	// 	//exit;
	// }	
}

$body.=$out;
