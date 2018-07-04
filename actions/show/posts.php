<?php
//Show posts
if($sortby==''){$sortby="id asc";}

$tmp=$this->html->readRQn('ref_id');
if ($tmp>0){$sql.=" and ref_id=$tmp";}

$tmp=$this->html->readRQn('refid');
if ($tmp>0){$sql.=" and ref_id=$tmp";}

$tmp=$this->html->readRQ('ref_table');
if ($tmp!=''){$sql.=" and ref_table='$tmp'";}

$tmp=$this->html->readRQ('tablename');
if ($tmp!=''){$sql.=" and ref_table='$tmp'";}

$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//echo $sql.'<br>';
$fields=array('id','name','date','user','reference');
//$sort= $fields;
$out=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);
		
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$class='';
	//$type=$this->data->get_name('listitems',$row[type]);
	if($row[id]==0)$class='d';
	$row[date]=substr($row[date],0,10);
	$ref=$this->data->detalize($row[ref_table],$row[ref_id]);
	$user=$this->data->username($row[user_id]);
	$out.= "<tr class='$class'>";
	$out.= "<td>$i</td>";
	$out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
	$out.= "<td onMouseover=\"showhint('$row[text]', this, event, '400px');\">$row[name]</td>";
	$out.= "<td>$row[date]</td>";
	$out.= "<td>$user</td>";
	$out.= "<td>$ref</td>";
	$out.=$this->html->HT_editicons($what, $row[id]);
	$out.= "</tr>";
	$totals[2]+=$row[qty];
	if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];			
	$this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));	
}
$this->livestatus('');
include(FW_DIR.'/helpers/end_table.php');
