<?php
//tools stealth
$opt=$this->html->readRQ('opt');
//$this->data->stealth_mode($opt);
$workgroup_id=3;
if($opt=='unhide'){
	$sql="update documents set creator=41 where creator=8 and id<67257;
	update documents set executor=41 where executor=8 and id<67257;
	update config set value='0' where name='no_clients';
	update config set value='0' where name='no_projects';
	update config set value='0' where name='only_owner';
	update config set value='0' where name='only_cyp';
	update config set value='0' where name='hide_hidden_menu';
	";
	$GLOBALS['hide_hidden_menu']=0;
	echo "UN Stealthing<hr>";
	$this->db->GetVal("UPDATE workgroups set restricted='f', include_related='t' where id=$workgroup_id");
	echo "Restriction removed<br>";
	$this->db->GetVal("DELETE from workgroup_pids where workgroup_id=$workgroup_id");
	echo "Access granted to all partners<br>";
}
if($opt=='hide'){
	$sql="update documents set creator=8 where creator=41 and id<67257;
	update documents set executor=8 where executor=41 and id<67257;
	update config set value='1' where name='no_clients';
	update config set value='1' where name='no_projects';
	update config set value='1' where name='only_owner';
	--update config set value='1' where name='only_cyp';
	update config set value='1' where name='hide_hidden_menu';
	";
	$GLOBALS['hide_hidden_menu']=1;
	echo "Stealthing<hr>";

	//$this->livestatus('Updatting workgroup');
	echo "Checking restrictions...<br>";$this->livestatus("");
	$this->db->GetVal("UPDATE workgroups set restricted='t', include_related='t' where id=$workgroup_id");
	echo "Restriction applied<br>";$this->livestatus("");
	$this->project->upd_workgroup_pids($workgroup_id,1438,'',1);
	echo "Limited partners access<br>";$this->livestatus("");
}
//echo "SQL:$sql";
$this->db->GetVal($sql);
echo "Upadating menues<br>";

$sql="select * from groups where id>=2 order by id asc limit 100";
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);	
while ($row = pg_fetch_array($cur)) {
	$i++;
	$this->data->gen_fast_menu($row[id]);
	$this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));	
	echo "GID:$row[id] - $row[name]<br>";	
}


$this->livestatus("$opt done.<br>");
echo "Check is OK";