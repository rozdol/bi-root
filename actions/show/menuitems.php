<?php
if ($what == 'menuitems') {
    if ($sortby=='') {
        $sortby="name asc";
    }

            $tmp=($this->html->readRQn("groupid"));
    if ($tmp>0) {
        $sql = "$sql and  groupid=$tmp";
    }
            $tmp=($this->html->readRQn("parentid"));
    if ($tmp>0) {
        $sql = "$sql and  parentid=$tmp";
    }
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
        $sql = "$sql and  partnerid in ($tmp)";
    }
            $tmp=($this->html->readRQ("hidden"));
    if ($tmp!='') {
        $sql = "$sql and  hidden='true'";
    }
            $tmp=($this->html->readRQ("not_hidden"));
    if ($tmp!='') {
        $sql = "$sql and  hidden='false'";
    }

            $sql1="select *";
            $sql=" from $what a where id>0 $sql";
            $sqltotal=$sql;
            $sql = "$sql order by $sortby";
            $sql2=" limit $limit offset $offset;";
            $sql=$sql1.$sql.$sql2;
            //$out.= "$sql\n";
            $totalrecs=$this->db->GetVal("select count(*)".$sqltotal);
    if ($totalrecs==0) {
        $out.= "<div id='info'>No $what.</div>";
        return;
    }


                $fields=array('id','name','link','H','descr','groups');
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

        //$groups=$this->utils->F_tostring($this->db->GetResults("select '<a href=\"?act=details&what=documents&id='||d.id||'\">'||substr(d.name,7,4)||'</a>' from documents d where d.id in (select docid from docs2requests where clientrequestid=$row[id])"),1,", ");
        $out.= "\t<tr>\n";
        $out.= "<td>$i</td><td>$row[id]</td>
					<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[name]</td>
						<td><a href='$row[link]'>$row[link]</a></td><td>$row[hidden]</td>
						<td onMouseover=\"showhint('$row[descr]', this, event, '400px')\">$descr</td>
						<td style='text-align: right;'>$groups</td>";

        $out.=$this->html->HT_editicons($what, $row[id]);
        $out.= "\t</tr>\n";
        $totals[0]+=1;
    }
    include(FW_DIR.'/helpers/end_table.php');
}
