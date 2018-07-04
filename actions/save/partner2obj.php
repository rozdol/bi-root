<?php
//save partner2obj
//echo $this->html->pre_display($_POST,'Post'); exit;

$id=$this->html->readRQn('id');
$partner_id=$this->html->readRQn('partner_id');
$ref_id=$this->html->readRQn('ref_id');
$ref_table=$this->html->readRQ('ref_table');
$type_id=$this->html->readRQn('type_id');

$vals=array(
	'partner_id'=>$partner_id,
	'ref_id'=>$ref_id,
	'ref_table'=>$ref_table,
	'type_id'=>$type_id
);
//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;
if($id==0){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
$body.=$out;