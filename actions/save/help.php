<?php
if ($what == 'help'){
	$id=($this->html->readRQ('id')*1);
	$name=($this->html->readRQ('name'));
	//$descr=($this->html->readRQ('descr'));
	$_POST[descr]=str_ireplace("'","\'",$_POST[descr]);
	$descr =$this->html->readRQc('descr');
	$out.= "<br><br><br><pre>$descr</pre>";
	if ($id<>0){
		$sql="update $what SET 
			name='$name',
		descr='$descr'
			WHERE id='$id';";	
		$cur= $this->db->GetVal($sql);
	}else{ 
		$sql="insert into $what (
		name,
		descr
			) VALUES (
		'$name',
		'$descr'
		);";
		$cur= $this->db->GetVal($sql);
		$id=($this->db->GetVal("select max(id) from $what")*1);
	}
}

$body.=$out;
