<?php
//--------------------------
if (($what == 'addaccessitems')&&($access['main_admin'])){
	$item=$this->html->readRQ('item');
	if($item=='')$item=$this->html->readRQ('table');
	$count=$this->db->getVal("select count(*) from accessitems where name='$item'")*1;
	if($count==0){

		$sql="insert into accessitems (name) values ('$item');";
		$res=$this->db->GetVar($sql);
		$sql="update accesslevel set access='1' where groupid=$gid;";
		$res=$this->db->GetVar($sql);
		echo "<span class='alert alert-info'>Access item <b>$item</b> is added!</span><br>";
	}		
}
//--------------------------
if (($what == 'delaccessitems')&&($access['main_admin'])){
	$item=$this->html->readRQ('item');
	$sql="delete from accessitems where lower(name)=lower('$item');";
	$res=$this->db->GetVar($sql);
	echo "<span class='alert alert-error'>Access item <b>$item</b> is deleted!</span>";
}

$out.="<span class='alert alert-error'>Test <b>$item</b> is deleted!</span>";
$GLOBALS[message_time]=1;
$out.= $this->html->refreshpage($reflink,$GLOBALS[message_time],"<div class='alert alert-info'>Executed $function $what $item.</div>");
$body.="$out";