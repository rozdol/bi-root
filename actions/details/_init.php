<?php
if($this->data->table_exists($what)){
	$count=$this->db->GetVal("select count(*) from $what where id=$id")*1;
	if($count==0)$body.=$this->html->error($this->html->message("Record id=$id from [$what] not found.","404",'alert-error'));
}
