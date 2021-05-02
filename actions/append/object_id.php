<?php
if($fieldname=='')$fieldname='id';
if($table=='')$table='partners';
$where_sql="lower(name) like lower('%$value%')";
if($table=='partners')$where_sql="lower(name) like lower('%$value%') or lower(ru) like lower('%$value%') or lower(en) like lower('%$value%') or lower(synonyms) like lower('%$value%') ";
$count=$this->db->GetVal("SELECT count(*) FROM $table WHERE $where_sql");
if($count>0){
	$sql="SELECT id, name FROM $table WHERE $where_sql ORDER by name";
	$sql="SELECT id, CASE WHEN active='t' THEN name
		ELSE '[inactive] '||name
		END as name FROM $table WHERE $where_sql ORDER by name";
	if($table=='partners')$sql="SELECT id, CASE WHEN not ((dateclose>=now() or dateclose is null) and active='t') THEN '[inactive] '||name
		ELSE name
		END as name FROM $table WHERE $where_sql ORDER by name";
	$response=$this->html->htlist($fieldname,$sql,$id,'Select from '.$table,"");
}else{$response="No $table found";}
$out.= "$response";

$body.=$out;