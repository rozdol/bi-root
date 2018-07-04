<?php
if ($what == 'import_file'){
		$reftype=($this->html->readRQ('reftype'))*1;
		$refid=($this->html->readRQ('refid'))*1;
		$tablename=($this->html->readRQ('tablename')); 
		$desc="XLS only.";
		$link="?act=report&what=import_file";
		$out.= file_form($link,$tablename,$refid, $desc);
	}
	
$body.=$out;
