<?php

if($err=='')$timeout=0.1; else $timeout=100;
if($GLOBALS[save_timeout]>0)$timeout=$GLOBALS[save_timeout];
if($id*1<=0)$id=$this->html->readRQn('id');
$noduplicate=$this->html->readRQn('noduplicate');
$duplicate=($noduplicate>0)?0:1;
$redirect=$this->html->readRQ('redirect');

if($_POST[backtoedit]>0){unset($_POST[backtodetails]);$where="?act=edit&what=$GLOBALS[what]&duplicate=0&backtoedit=1&id=$_POST[id]";}
if(($this->html->readRQn('backtoedit')>0)&&($id>0))$where="?act=edit&what=$GLOBALS[what]&duplicate=$duplicate&backtoedit=1&id=$id";
if(($this->html->readRQn('backtodetails')>0)&&($id>0))$where="?act=details&what=$GLOBALS[what]&id=$id";
if($redirect!='')$where=$redirect;


if($GLOBALS[redirect_link]!='')$where=$GLOBALS[redirect_link];
$this->data->DB_change($what,$id,$GLOBALS[form_mode]);

$edit_more_after_save=$this->html->readRQc('edit_more_after_save');
if(($edit_more_after_save!='')&&($id>0)){
	$stop=1; 
	$this->html->set_reflink($where);
	$GLOBALS[reflink]=$_COOKIE["reflink"];
	echo "REFLINK:$where";
	$back=$edit_more_after_save.$id.'&back_to_url='.$where;
	$body.= $this->html->refreshpage($back,20,$this->html->message("Record id=$id is saved in [$what].","$act $what.<br>Redirecting to:".$back."<br>Then to $where",''));
	
}

if($GLOBALS[info_message]!=''){$stop=1;$body.= $this->html->refreshpage($where,60,$this->html->message($GLOBALS[info_message],"INFO",'alert alert-info'));}
if($GLOBALS[error_message]!=''){
	//echo $this->html->pre_display($where,"where"); exit;
	$stop=1;$body.= $this->html->refreshpage($where."&noduplicate=$noduplicate&error_message=".$GLOBALS[error_message],3,$this->html->message("$GLOBALS[error_message]","ERROR",'alert alert-error'));}
if($stop!=1)$body.= $this->html->refreshpage($where,$timeout,$this->html->message("Record id=$id is saved in [$what].","$act $what",''));
//exit;

if($what=='events')$this->project->after_save($what,$id,$GLOBALS[form_mode]);
//$this->data->DB_log($GLOBALS[form_mode].' '.$what.' DATA:'.$GLOBALS[form_diff_vals_json]);	