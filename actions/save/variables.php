<?php
if ($what == 'variables'){
	$id=$this->html->readRQ('id');
	$id=$this->html->readRQ('name');
	$data=$this->html->readRQ('data');
	$count=$this->db->GetVar("SELECT COUNT(*) FROM variables  WHERE id='$id';");
	if ($count[0]>0)
		$sql="UPDATE $what SET varvalue='$data' WHERE id='$id';";		
	else 
		$sql="INSERT INTO $what (id, varvalue) VALUES ('$id', '$data');";
	$out.= "$sql";
	$cur= $this->db->GetVar($sql);
	$logtext.=" name=$name";
}


$body.=$out;
