<?php
//--------------------------
if (($what == 'addaccessitems')&&($access['main_admin'])){
	$item=$this->html->readRQ('item');
	if($item=='')$item=$this->html->readRQ('table');
	$this->data->add_access([$item]);
}
//--------------------------
if (($what == 'delaccessitems')&&($access['main_admin'])){
	$item=$this->html->readRQ('item');
	$this->data->delete_access($item);
}

$out.="<span class='alert alert-error'>Test <b>$item</b> is deleted!</span>";
$GLOBALS[message_time]=1;
$out.= $this->html->refreshpage($reflink,$GLOBALS[message_time],"<div class='alert alert-info'>Executed $function $what $item.</div>");
$body.="$out";