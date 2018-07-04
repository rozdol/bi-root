<?php
if ($what == 'menus'){
	$id=$this->html->readRQn('id');
	$parentid=$this->html->readRQn('parentid');
	$groupid=$this->html->readRQn('groupid');
	if($groupid==0)$groupid=2;
	$menuid=$this->html->readRQn('menuid');
	$type=$this->html->readRQn('type');
	$sort=$this->html->readRQn('sort');
	$level=$this->html->readRQn('level');

	$vals=array(
		'parentid'=>$parentid,
		'groupid'=>$groupid,
		'menuid'=>$menuid,
		'type'=>$type,
		'sort'=>$sort,
		'level'=>$level
	);
	//$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;
	if($id==0){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
}


$body.=$out;
