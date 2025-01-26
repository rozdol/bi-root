<?php

if($sortby==''){$sortby="id asc";}

$tmp=$this->html->readRQn('list_id');
if ($tmp>0){$sql.=" and list_id=$tmp";}

$tmp=$this->html->readRQcsv('ids');
if ($tmp>0){$sql.=" and id in ($tmp)";}


$tmp=$this->html->readRQ('name');
if ($tmp!=''){$sql.=" and lower(name) like lower('%$tmp%')";}
$tmp=$this->html->readRQ('alias');
if ($tmp!=''){$sql.=" and lower(alias) like lower('%$tmp%')";}
$tmp=$this->html->readRQ('text1');
if ($tmp!=''){$sql.=" and lower(text1) like lower('%$tmp%')";}
$tmp=$this->html->readRQ('text2');
if ($tmp!=''){$sql.=" and lower(text2) like lower('%$tmp%')";}

$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= $sql;

$fields=array('id','list','name','alias','qty','text1','text2','num1','num2');
//$sort= $fields;
$tbl=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);

if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);
$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$list=$this->data->get_name('lists',$row[list_id]);
	$tbl.= "<tr>";

	$tbl.= "  <td>$i</td>";
	$tbl.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
	$tbl.= "<td>$list</td>"; 
	$tbl.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[name]</td><td>$row[alias]</td><td class='n'>$row[qty]</td><td>$row[text1]</td><td>$row[text2]</td><td class='n'>".$this->html->money($row[num1])."</td><td class='n'>".$this->html->money($row[num2])."</td>\n";
	//$csv.="$row[id]\t$row[name]\t$row[alias]\t$row[qty]\t$row[values]\t$row[text1]\t$row[text2]\t$row[num1]\t$row[num2]\t$row[descr]\t$row[addinfo]\n";
	$tbl.=$this->html->HT_editicons($what, $row[id]);
	$tbl.= "</tr>\n";
	if ($allids) $allids.=','.$what.':'.$row['id']; else $allids.=$what.':'.$row['id'];		
}
$out=$tbl;
include(FW_DIR.'/helpers/end_table.php');
