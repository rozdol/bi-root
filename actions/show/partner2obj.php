<?php
//Show partner2obj
if($sortby==''){$sortby="id asc";}

$tmp=$this->html->readRQn('ref_id');
if ($tmp>0){$sql.=" and ref_id=$tmp";}

$tmp=$this->html->readRQn('type_id');
if ($tmp>0){$sql.=" and type_id=$tmp";}

$tmp=$this->html->readRQn('partner_id');
if ($tmp>0){$sql.=" and partner_id=$tmp";}

$tmp=$this->html->readRQ('ref_table');
if ($tmp!=''){$sql.=" and ref_table='$tmp'";}

$tmp=$this->html->readRQcsv('not_ids');
if ($tmp!=''){$sql.=" and partner_id not in ($tmp)";}


$tmp=$this->html->readRQcsv('ids');
if ($tmp!=''){$sql.=" and partner_id in ($tmp)";}


$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//echo $sql;
$fields=array('id','partner','role');
//$sort= $fields;
$out=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);
		
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$class='';
	$type=$this->data->get_name('listitems',$row[type_id]);
	$partner=$this->data->detalize('partners',$row[partner_id]);
	if($row[id]==0)$class='d';
	$out.= "<tr class='$class'>";
	$out.= "<td>$i</td>";
	$out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
	$out.= "<td>$partner</td>";
	$out.= "<td>$type</td>";
	$out.=$this->html->HT_editicons($what, $row[id]);
	$out.= "</tr>";
	$totals[2]+=$row[qty];
	if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];			
	$this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));	
}
$this->livestatus('');
include(FW_DIR.'/helpers/end_table.php');
