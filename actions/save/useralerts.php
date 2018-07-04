<?php
if ($what == 'useralerts'){
		$id=($this->html->readRQn('id'));
		$userid=($this->html->readRQn('userid'));
		$descr=($this->html->readRQ('descr'));
		$addinfo=($this->html->readRQ('addinfo'));
		if ($id<>0){
			$sql="update $what SET 
				id='$id',
				userid='$userid',
				descr='$descr',
				addinfo='$addinfo'
				WHERE id='$id';";	
			$cur= $this->db->GetVal($sql);
		}

	}
	
				
$body.=$out;
