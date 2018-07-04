<?php
if ($what == 'fastmenu'){
			if($sortby==''){$sortby="id asc";}

			$tmp=$this->html->readRQn('list_id');
			if ($tmp>0){$sql.=" and list_id=$tmp";}

			$sql1="select *";
			$sql=" from $what a where id>0 $sql";
			$sqltotal=$sql;
			$sql = "$sql order by $sortby";
			$sql2=" limit $limit offset $offset;";
			$sql=$sql1.$sql.$sql2;
			//$out.= $sql;
	$fields=array('id','name','date','gid');
		//$sort= $fields;
		$out.=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);

		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	$rows=pg_num_rows($cur);
	$csv.=$this->data->csv($sql);
		while ($row = pg_fetch_array($cur)) {
			$i++;
			$class='';
			//$type=$this->data->get_name('listitems',$row[type]);
			if($row[id]==0)$class='d';
			$out.= "<tr class='$class'>";
			$out.= "<td>$i</td><td>$row[id]</td>";
			$out.= "<td>$row[name]</td>";
			$out.= "<td>$row[date]</td>";
			$out.= "<td>$row[gid]</td>";
			$out.=$this->html->HT_editicons($what, $row[id]);
			$out.= "</tr>";
			$csv.="$row[id]	$row[name]\t$row[descr]\n";
			$totals[2]+=$row[qty];
			if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];			
		}
include(FW_DIR.'/helpers/end_table.php');
	}
	