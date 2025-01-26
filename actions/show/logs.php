<?php
if ($what == 'logs'){
			if($sortby==''){$sortby="id desc";}

			$tmp=$this->html->readRQn('uid');
			if ($tmp>0){$sql.=" and userid=$tmp";}
			
			$tmp=$this->html->readRQn('dayshift');
			if ($tmp!=0){$tmp2=$tmp-1; $sql.=" and date>=current_date - INTERVAL '$tmp day' and date<=current_date - INTERVAL '$tmp2 day'";}
			
			$tmp=$this->html->readRQn('today');
			if ($tmp!=0){$sql.=" and date>=current_date";}
			
			$tmp=$this->html->readRQn('last30');
			if ($tmp!=0){$sql.=" and date>=current_date - INTERVAL '30 day'";}

			$tmp=$this->html->readRQ('refference');
			if ($tmp!=''){$sql.=" and action like '%\"what\":\"$tmp\"%'";}

			$tmp=$this->html->readRQ('ref_id');
			if ($tmp!=''){$sql.=" and action like '%\"id\":\"$tmp\"%'";}

			$sql1="select *";
			$sql=" from $what a where id>0 $sql";
			$sqltotal=$sql;
			$sql = "$sql order by $sortby";
			$sql2=" limit $limit offset $offset;";
			$sql=$sql1.$sql.$sql2;
			//$out.= $sql;
	$fields=array('id','user','ip','date','action',);
		//$sort= $fields;
		$out.=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);

		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	$rows=pg_num_rows($cur);
	$csv.=$this->data->csv($sql);
		while ($row = pg_fetch_array($cur)) {
			$i++;
			$class='';
			$user=$this->data->get_val('users','username',$row[userid]);
			if($row[id]==0)$class='d';
			$out.= "<tr class='$class'>";
			$out.= "<td>$i</td><td>$row[id]</td>";
			$out.= "<td>$user</td><td class='n'>$row[ip]</td><td class='n'>$row[date]</td><td class=''>$row[action]</td>";
			$out.=$this->html->HT_editicons($what, $row[id]);
			$out.= "</tr>";
			$csv.="$row[id]	$row[name]\t$row[descr]\n";
			$totals[2]+=$row[qty];
			if ($allids) $allids.=','.$what.':'.$row['id']; else $allids.=$what.':'.$row['id'];			
		}
include(FW_DIR.'/helpers/end_table.php');
	}
	
	