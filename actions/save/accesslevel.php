<?php
if (($what == 'accesslevel')&&($access['main_admin'])){
		$delimiter =$this->html->readRQc('delimiter');
		foreach ($_POST as $key => $value) {
			$out.= $key . " => " . $value . "<br>\n";
			$arr=explode($delimiter,$key);
			$item=$arr[0];
			$groupid=$arr[1];
			$accid=$arr[2];
			$sql="update accesslevel set access='$value' where groupid=$groupid and accessid=$accid";
			if(($groupid>0)&&($accid>0)){
				//$out.= "$sql<br>";
				$cur= $this->db->GetVal($sql);  
			}
		}
		//exit;
		$logtext.=" group_id=$refid";		
	}
	
$body.=$out;
