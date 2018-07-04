<?php
if ($what=="upload"){

	$uploaddir=DATA_DIR.'/docs';
	$refid=$this->html->readRQn('refid');
		
	$docname=$this->db->GetVal("select name from documents where id=$refid");	

	//$docname="12-06-0794";
	$dirs=explode("-",$docname);
	$y=$dirs[0];
	$m=$dirs[1];
	$d=$dirs[2];
	$newdir=$uploaddir."/$y";
	if(!is_dir($newdir))mkdir($newdir);
	if(!is_dir($newdir)) {echo "UPLOAD_DIR_NO_PERMISSION $newdir cannot be created. $uploaddir is prohibited for writing"; exit;}
	
	$newdir=$uploaddir."/$y/$m";
	if(!is_dir($newdir))mkdir($newdir);
	$newdir=$uploaddir."/$y/$m/$d";
	if(!is_dir($newdir))mkdir($newdir);

	$docname=str_replace("-","/",$docname);
	$dir=$uploaddir."/$docname/";
	if(!is_dir($dir)){echo "UPLOAD_DIR_NO_PERMISSION $dir cannot be created. $uploaddir is prohibited for writing"; exit;}

//	echo "OK $dir";
	//$dir=readRQ('dir');
	//$dir="/data/";
	//$dir="";
	
	/**
	Disabed
	$class_path=FW_DIR.'/vendor/upload.class.php';
	**/
	//
	
	
	//echo "$class_path";
	//exit;
	require($class_path);
	$upload_handler = new UploadHandler(null,$dir, $this->db);
	//$upload_handler->setdir($dir);

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'OPTIONS':
		break;
		case 'HEAD':
		case 'GET':
		{$upload_handler->get();}
		break;
		case 'POST':
		if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
			$upload_handler->delete();
		} else {
			$upload_handler->post();
		}
		break;
		case 'DELETE':
		$upload_handler->delete();
		break;
		default:
		header('HTTP/1.1 405 Method Not Allowed');
	}
}