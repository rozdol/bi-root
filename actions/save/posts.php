<?php
$id=$this->html->readRQn('id');
$name=$this->html->readRQ('name');
$ref_id=$this->html->readRQn('ref_id',0,297);
$ref_table=$this->html->readRQ('ref_table','partners');
$text=$this->html->readRQ('text');

if($id==0){
	$user_id=$GLOBALS[uid];
	$date='now()';
	
}else{
	$res=$this->data->get_row($what,$id);
	$user_id=$res[user_id];
	$ref_id=$res[ref_id];
	$ref_table=$res[ref_table];
	$date=$res[date];
}

$vals=array(
	'name'=>$name,
	'user_id'=>$user_id,
	'ref_id'=>$ref_id,
	'ref_table'=>$ref_table,
	'date'=>$date,
	'text'=>$text
);
//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;
if($id==0){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
$body.=$out;
