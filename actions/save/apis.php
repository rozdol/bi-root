<?php
//Save apis
$id=$this->html->readRQn('id');
$key=$this->html->readRQ('key');
$date=$this->html->readRQ('date');
$use_date=$this->html->readRQ('use_date');
$exp_date=$this->html->readRQd('exp_date',1);
$active=$this->html->readRQn('active');
$functions=$this->html->readRQ('functions');
$user_id=$this->html->readRQn('user_id');
$ip=$this->html->readRQ('ip');

if(($user_id==0)&&($key!='')){$rec=$this->db->getrow("SELECT * from apis where key='$key'");$user_id=$rec[user_id];$id=$rec[id];}
if($user_id==0)$user_id=$this->html->readRQn('refid');


$vals=array(
	'key'=>$key,
	'exp_date'=>$exp_date,
	'active'=>$active,
	'functions'=>$functions,
	'user_id'=>$user_id
);
//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals'.$id);exit;
if($id==0){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
$body.=$out;
