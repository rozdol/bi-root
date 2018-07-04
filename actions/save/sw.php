<?php
$id=$this->html->readRQn('id');
$table=$this->html->readRQs('table');
$field=$this->html->readRQs('field');
$comments=$this->html->readRQ('comments');
$sql="UPDATE $table SET $field=NOT($field) where id=$id";
//$this->html->error($sql);
$out.=$this->db->GetVar($sql);
if($this->data->field_exists($table, 'addinfo'))	$this->db->GetVar("UPDATE $table SET addinfo=addinfo||' Comment:$comments' where id=$id");		
