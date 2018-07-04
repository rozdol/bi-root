<?php
if ($what == 'listitems'){
	$id=$this->html->readRQn('id');
	$name=$this->html->readRQ('name');
	$alias=$this->html->readRQ('alias');
	$list_id=$this->html->readRQn('list_id');
	$qty=$this->html->readRQn('qty');
	$default_value=$this->html->readRQn('default_value');
	$values=$this->html->readRQ('values');
	$descr=$this->html->readRQ('descr');
	$addinfo=$this->html->readRQ('addinfo');
	$text1=$this->html->readRQ('text1');
	$text2=$this->html->readRQ('text2');
	$num1=$this->html->readRQn('num1');
	$num2=$this->html->readRQn('num2');

	$vals=array(
		'id'=>$id,
		'name'=>$name,
		'alias'=>$alias,
		'list_id'=>$list_id,
		'qty'=>$qty,
		'default_value'=>$default_value,
		'values'=>$values,
		'descr'=>$descr,
		'addinfo'=>$addinfo,
		'text1'=>$text1,
		'text2'=>$text2,
		'num1'=>$num1,
		'num2'=>$num2
	);
	if($id==0)unset($vals[id]);
	//echo $this->html->pre_display($_POST,"POST"); 	echo $this->html->pre_display($vals,'VALS');exit;
	$count=$this->db->GetVal("select count(*) from $what where id=$id")*1;
	if(($count==0)||($id==0)){$id=$this->db->insert_db($what,$vals);}else{$id=$this->db->update_db($what,$id,$vals);}
}

$body.=$out;
