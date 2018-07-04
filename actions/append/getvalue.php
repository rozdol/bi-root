<?php
if ($what == 'getvalue'){
	$sql="SELECT $field FROM $table WHERE id=$id";				  
	$response=$this->db->GetVal($sql);
	$out.= "$response";
}

$body.=$out;
