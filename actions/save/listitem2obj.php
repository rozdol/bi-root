<?php
if ($what == 'listitem2obj'){
	$ref_id=$this->html->readRQn('ref_id');
	$listitem_id=$this->html->readRQn('listitem_id');
	$ref_table=$this->html->readRQ('ref_table');
	
	//fix
		$sql="select * from listitem2obj";
		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
		while ($row = pg_fetch_array($cur)) {
			$list_id=$this->db->GetVal("select list_id from listitems where id=$row[listitem_id]");
			$vals=array('list_id'=>$list_id); $this->db->update_db('listitem2obj',$row[id],$vals);
		}
	
	
	//foreach ($_POST as $key => $value) {$out.= $key . " => " . $value . "<br>\n";} exit;
	$sql="select count(*) from listitem2obj where ref_id=$ref_id and listitem_id=$listitem_id and ref_table='$ref_table';";
	$count=$this->db->GetVal($sql)*1;
	if($count==0){
		$list_id=$this->db->GetVal("select list_id from listitems where id=$listitem_id");
		$sql="insert into listitem2obj (ref_id, listitem_id,ref_table,list_id)values($ref_id,$listitem_id,'$ref_table',$list_id);";
		$out.= "$sql $name"; 
		$dummy=$this->db->GetVal($sql);
	}
	//exit;
}

$body.=$out;
