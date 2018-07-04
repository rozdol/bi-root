<?php
if($fieldname=='')$fieldname='id';
if($table=='')$fieldname='partners';
$count=$this->db->GetVal("SELECT count(*) FROM $table WHERE lower(name) like lower('%$value%')");
if($count>0){
	$sql="SELECT id, name FROM $table WHERE lower(name) like lower('%$value%') ORDER by name";				  
	$response=$this->html->htlist($fieldname,$sql,$id,'Select from '.$table,"");
}else{$response="No $table found";}
$out.= "$response";

$body.=$out;