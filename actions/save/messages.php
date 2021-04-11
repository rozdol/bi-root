<?php
//Save messages
$id=$this->html->readRQn('id');
$name=$this->html->readRQ('name');
$ref_name=$this->html->readRQ('ref_name');
$ref_id=$this->html->readRQn('ref_id');
$type_id=$this->html->readRQn('type_id');
$stage_id=$this->html->readRQn('stage_id');
$date=$this->html->readRQd('date',1);
$send_date=$this->html->readRQ('send_date');
$user_id=$this->html->readRQn('user_id');
$descr=$this->html->readRQ('descr');
$addinfo=$this->html->readRQ('addinfo');
$message=$this->html->readRQ('message');
$data_json=$this->html->readRQ('data_json');
$destination=$this->html->readRQ('destination');


$vals=array(
	'name'=>$name,
	'ref_name'=>$ref_name,
	'ref_id'=>$ref_id,
	'type_id'=>$type_id,
	'stage_id'=>$stage_id,
	'date'=>$date,
	'send_date'=>$send_date,
	'user_id'=>$user_id,
	'descr'=>$descr,
	'addinfo'=>$addinfo,
	'message'=>$message,
	'data_json'=>$data_json,
	'destination'=>$destination,
);
    //echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;
    if($id==0){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
    $body.=$out;
