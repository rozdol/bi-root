<?php
if ($what == 'menuitems'){
	$name=$this->html->readRQ('name');
	$link=$this->html->readRQurl('link');
	$hidden=$this->html->readRQn('hidden');
	$descr=$this->html->readRQ('descr');

	if ($id<>0){
		$sql="update $what SET 
			name='$name',
			link='$link',
			hidden='$hidden',
			descr='$descr'
			WHERE id='$id';";	
		$err=$this->db->GetVal($sql);
	}else{ 
		$sql="insert into $what (
			name,
			link,
			hidden,
			descr
		) VALUES (
			'$name',
			'$link',
			'$hidden',
			'$descr'
			);";
			//$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;
		$err=$this->db->GetVal($sql);
		if($err!=''){$out.= $err;exit;}
	}
	if($id==0)$id=($this->db->GetVal("select max(id) from $what")*1);
}

$body.=$out;
