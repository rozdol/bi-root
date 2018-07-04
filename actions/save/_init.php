<?php
$err='';
$id=$this->html->readRQn('id');
if($id>0){
	$GLOBALS[form_mode]='EDIT';
	$GLOBALS[record_old_vals]=$this->data->record_array($what, $id);
} else {
	$GLOBALS[form_mode]='INSERT';
	$GLOBALS[record_old_vals]=array();
}
if($this->html->readRQ('duplicate')>0){if($id>0)$GLOBALS[old_id]=$id; $id=0;$_POST[id]=0;$_GET[id]=0; echo "dupe0 id:$id Gid:". $GLOBALS[old_id]."<br>";}

