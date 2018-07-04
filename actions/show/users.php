<?php

//$access['main_admin']=1;
if($access['main_admin']){
	if($sortby==''){$sortby="active desc, id asc";}

	//Filters
	$tmp=$this->html->readRQn('list_id');
	if ($tmp>0){$sql.=" and list_id=$tmp";}

	//SQL aggregator
	$sql1="select *";
	$sql=" from $what a where id>0 $sql";
	$sqltotal=$sql;
	$sql = "$sql order by $sortby";
	$sql2=" limit $limit offset $offset;";
	$sql=$sql1.$sql.$sql2;
	//$out.= $sql;

	//Table
	$fields=array('id','username','name','a','group','email');
	//$sort= $fields;
	$out.=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);					
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	$rows=pg_num_rows($cur);
	$csv.=$this->data->csv($sql);
	while ($row = pg_fetch_array($cur)) {
		$i++;$n++;
		$class='';
		$user=$this->data->get_user_info($row[id])[full_name];
		$gid=$this->db->getval("select groupid from user_group where userid=$row[id] limit 1")*1;
		$group=$this->data->detalize('groups',$gid);
		$a=$this->html->tick($row[active]);
		if($row[id]==0)$class='d';
		$out.= "<tr class='$class'>";
		$out.= "<td>$n</td><td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
		$out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[username]</td>";
		$out.= "<td>$user</td>\n";
		$out.= "<td>$a</td>\n";
		$out.= "<td>$group</td>\n";
		$out.= "<td>$row[email]</td>\n";
		//$out.= "<td>$somevalue</td><td class='n'>".$this->html->money($row[qty])."</td>";
		$out.=$this->html->HT_editicons($what, $row[id]);
		$out.= "</tr>";

		if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];				
	}
	include(FW_DIR.'/helpers/end_table.php');
	$body.="ok";
	
	
}
