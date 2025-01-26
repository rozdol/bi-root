<?php
if ($what == 'menus') {
    if ($sortby=='') {
        $sortby="groupid asc, parentid asc, sort asc";
    }

    $groupid=$this->html->readRQn("groupid");
    $sql = "$sql and  groupid=$groupid";
    $parentid=$this->html->readRQn("parentid");
    $sql = "$sql and  parentid=$parentid";
    $tmp=($this->html->readRQn("id"));
    if ($tmp>0) {
        $sql = "$sql and  id=$tmp";
    }
    $tmp=($this->html->readRQcsv('ids'));
    if ($tmp!='') {
        $sql = "$sql and  id in ($tmp)";
    }
    $tmp=($this->html->readRQcsv('pids'));
    if ($tmp!='') {
        $sql = "$sql and  parentid in ($tmp)";
    }

    $parentitemid=$this->db->GetVal("select menuid from menus where id=$parentid")*1;
    $group=$this->data->get_name('groups', $groupid);
    $parent=$this->data->get_name('menuitems', $parentitemid);

    $sql1="select *";
    $sql=" from $what a where id>0 $sql";
    $sqltotal=$sql;
    $sql = "$sql order by $sortby";
        //$sql2=" limit $limit offset $offset;";
    $sql=$sql1.$sql.$sql2;
        //$out.= "$sql\n";
    $totalrecs=$this->db->GetVal("select count(*)".$sqltotal);
    if ($totalrecs==0) {
        $out.= "<div id='info'>No $what. $addbutton</div>";
        return;
    }
    $fields=array('Group','Parent','Sort','Name','Children','Link','H','E');
                //$sort= $fields;
    $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);

    $nbrow=0;
    $i=$limit*$page;
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    $csv.=$this->data->csv($sql);
    while ($row = pg_fetch_array($cur)) {
        $nbrow++;
        $i=$i+1;
        $no=sprintf("%03s", $row[id]);

        $descr=$this->utf8->utf8_cutByPixel($row[descr], 400, false);

        $children=$this->utils->F_tostring($this->db->GetResults("select '<a href=\"?act=show&what=$what&groupid=$groupid&parentid='||m.id||'&refid='||m.id||'\">'||i.name||'</a>'
			from menuitems i, menus m where m.parentid=$row[id] and i.id=m.menuid and i.id in (select menuid from menus where groupid=$groupid and parentid=$row[id]) order by m.sort"), 1, ", ");

        $out.= "\t<tr>\n";
        $menuitem=$this->db->GetRow("select * from menuitems where id=$row[menuid]");

        $out.= "<td>$i ($row[id])</td><td>$group</td><td><a href='?act=show&what=$what&parentid=$menuitem[parentid]&groupid=$groupid&refid=$menuitem[id]'>$parent</a></td><td>$row[sort]</td>
		<td><a href='?act=show&what=$what&parentid=$row[id]&groupid=$groupid&refid=$row[id]'>$menuitem[name]</a></td>
		<td>$children</td>
		<td><a href='$menuitem[link]'>$menuitem[link]</a></td><td>$menuitem[hidden]</td><td><a href='?act=edit&what=menuitems&id=$menuitem[id]'>EDIT</a></td>";


        $out.=$this->html->HT_editicons($what, $row[id]);
        $out.= "\t</tr>\n";
                //$totals[0]=(int) $totals[0] + 1;
    }
    include(FW_DIR.'/helpers/end_table.php');
}
