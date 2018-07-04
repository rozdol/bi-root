<?php
if ($what == 'import_lh'){
		$reftype=($this->html->readRQ('reftype'))*1;
		$refid=($this->html->readRQ('refid'))*1;
		$tablename=($this->html->readRQ('tablename')); 
		$desc="(Partners Letter Head)";
		$link="?csrf=$GLOBALS[csrf]&act=save&what=import_lh";
		$out.= $this->html->file_form($link,$tablename,$refid, $desc);
	}
	
$body.=$out;
