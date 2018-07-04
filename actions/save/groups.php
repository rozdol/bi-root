<?php
if (($what == 'groups')&&($access['main_admin'])){
	$name=$this->html->readRQ('name');
	$descr=$this->html->readRQ('descr');

	if ($id<>0){ 
		$query = "UPDATE groups set $sqladd name='$name', descr='$descr' where id=$id;";
		$result = $this->db->GetVar($query);
	}else{
		$query = "INSERT INTO groups (name, descr ) VALUES ('$name','$descr');";
		$result = $this->db->GetVar($query);
	}
	$out.= "$query";
	//exit;
	$logtext.=" name=$name descr=$descr";

}

$body.=$out;
