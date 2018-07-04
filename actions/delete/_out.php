<?php
if($this->data->table_exists($what)){
	$name=$this->data->get_name($what, $id);
	if($sql=='')$sql="DELETE FROM $what WHERE id=$id";

	//if($id>0)
	$err.="".$this->db->GetVal($sql);

	if($err!='No query given')$out.= $this->html->refreshpage('',0.1,$this->html->message("Record id=$id from [$what] is deleted. ($name)","$act $what",'alert-warn'));
	//exit;

	//Log deletion
	//$this->data->DB_log("DELETE from $what RECORD ID=$id ($name) DATA:$deldata]");
	//$deldata=json_encode($this->data->record_array($what, $id));

	$this->data->DB_change($what,$id,'DELETE');



	if($secondtime!='1'){
		$_POST[secondtime]=1;
		
		if($this->data->table_exists('comments')){
			$sql="select id from comments where tablename='$what' and refid=$id";
			if($access[view_debug])$out.= $this->html->message($sql,'SQL');
			if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
			while ($row = pg_fetch_array($cur)) {
				$this->project->DB_delete('comments',$row[id]);		
			}
		}
		if($this->data->table_exists('events')){
			$sql="select id from events where reference='$what' and refid=$id";
			if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
			while ($row = pg_fetch_array($cur)) {
				$_POST[id]=$row[id];
				$this->project->DB_delete('events',$row[id]);		
			}
		}
		
		if($this->data->table_exists('docs2obj')){
			$sql="select id from docs2obj where ref_table='$what' and ref_id=$id";
			if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
			while ($row = pg_fetch_array($cur)) {
				$_POST[id]=$row[id];
				$this->project->DB_delete('docs2obj',$row[id]);		
			}
		}
		
	}
}else{
	if($err!='No query given')$out.= $this->html->refreshpage('',0.1,$this->html->message("Record id=$id from [$what] is deleted. ($name)","$act $what",'alert-warn'));
}
$body.=$out;