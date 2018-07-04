<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
$procedure=$this->html->readRQ('procedure');
$file=$procedure;
$title=$file;
if($file=='form')$title='add';
$res.="<br><div class='h'>$title</div>";
$func_dir=FW_DIR.'/procedures/'.$file;
//$res.=$func_dir;
$files2 = scandir($func_dir);
foreach($files2 as $file2){
	if(!is_dir($func_dir.$file2)){
		$parts=explode('.',$file2);
		$filename=$parts[0];
		$ext=$parts[1];
		if(($ext=='php')&&($filename[0]=='-')&&($filename[1]=='-')){
			$newfile=substr($filename,2,strlen($filename));
			$newname=FW_DIR.'/procedures/'.$file.'/'.$newfile.'.php';
			$oldname=FW_DIR.'/procedures/'.$file.'/'.$filename.'.php';
			if(rename($oldname,$newname))$res.="<br>$oldname > <br>$newname<br><br>";
		}	
	}
}

//$res.=$this->pre_display($procs,'$procs');
$body.= $res;
