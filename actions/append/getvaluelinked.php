<?php
if ($what == 'getvaluelinked'){
	$sql="SELECT $field FROM $table WHERE id=$id";				  
	$response=$this->db->GetVal($sql);
	$out.= "<a href='".$this->html->link(array('act'=>'details','what'=>$table,'id'=>$id))."'>$response</a>";
}

$body.=$out;
