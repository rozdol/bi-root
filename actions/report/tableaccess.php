<?php
$uid=$this->html->readRQn('id');

$out.="<h3>Partners access</h3>";
$fields=array('#','id','Partner', 'hits','tr','docs','clrq','la');
$tbl=$this->html->tablehead('','', '', '', $fields);
$sql="select refid, count(refid) as hits from tableaccess where userid=$id and tablename='partners' and date>=now() - interval '7 days' group by  refid order by hits desc";
//echo "$sql<br>";
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
while ($row = pg_fetch_array($cur)) {
		$i++;
		$trans="T";
		$partner=$this->data->detalize('partners', $row[refid]);
		$tbl.= "<tr class='$class'>";
		$tbl.= "<td>$i</td>";
		$tbl.= "<td>$row[refid]</td>";
		$tbl.= "<td>$partner</td>";
		$tbl.= "<td class='n'><a href='?act=show&what=tableaccess&tablename=partners&refid=$row[refid]&userid=$id'>$row[hits]</a></td>";
		$tbl.= "<td class='n'><a href='?act=show&what=tableaccess&tablename=transactions&partnerid=$row[refid]&userid=$id'>$trans</a></td>";
		$tbl.= "<td class='n'><a href='?act=show&what=tableaccess&tablename=documents&partnerid=$row[refid]&userid=$id'>$trans</a></td>";
		$tbl.= "<td class='n'><a href='?act=show&what=tableaccess&tablename=clientrequests&partnerid=$row[refid]&userid=$id'>$trans</a></td>";
		$tbl.= "<td class='n'><a href='?act=show&what=tableaccess&tablename=loans&partnerid=$row[refid]&userid=$id'>$trans</a></td>";
		$tbl.= "</tr>";
		$csv.="$i\t$row[name]\t$jur\t$data[administrator]\t$data[ubos_plain]\t$data[clients]\t$dormant\n";
	
}
$tbl.="</table>";

$out.=$tbl;
$body.=$out;