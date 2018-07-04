<?php
if ($what == 'import_contract_template'){
		$reftype=($this->html->readRQ('reftype'))*1;
		$refid=($this->html->readRQ('refid'))*1;
		$tablename=($this->html->readRQ('tablename')); 
		$desc="(contract template)";
		$link="?act=save&what=import_contract_template";
		$out.= file_form($link,$tablename,$refid, $desc);
	}
	
$body.=$out;
