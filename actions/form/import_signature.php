<?php
if ($what == 'import_signature'){
		$reftype=($this->html->readRQ('reftype'))*1;
		$refid=($this->html->readRQ('refid'))*1;
		$tablename=($this->html->readRQ('tablename')); 
		$desc="(Users signature)";
		$link="?act=save&what=import_signature";
		$out.= $this->html->file_form($link,$tablename,$refid, $desc);
	}


$body.=$out;
