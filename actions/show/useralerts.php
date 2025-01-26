<?php
if ($what == 'useralerts') {
    if ($sortby=='') {
        $sortby="a.id desc";
    }

                $tmp=($this->html->readRQ('sent')*1);
    if ($tmp >0) {
        $sql = "$sql and a.fromuserid=$uid";
    }
                $tmp=($this->html->readRQ('received')*1);
    if ($tmp >0) {
        $sql = "$sql and a.userid=$uid";
    }
                $tmp=($this->html->readRQ('showall')*1);
    if ($tmp >0) {
        $sql = "";
        $showall=1;
    }
                $tmp=($this->html->readRQ('refid')*1);
    if ($tmp >0) {
        $sql = "$sql and a.refid=$tmp";
    }
                $tmp=($this->html->readRQ('tablename')*1);
    if ($tmp >0) {
        $sql = "$sql and a.tablename='$tmp'";
    }
                /*
                $tmp=($this->html->readRQ('from')*1);
                if (($tmp >0)&&($access[main_admin])){$sql = "$sql and a.fromuserid=$tmp";}
                $tmp=($this->html->readRQ('to')*1);
                if (($tmp >0)&&($access[main_admin])){$sql = "$sql and a.userid=$tmp";}
                */
                
                $tmp=($this->html->readRQ('from')*1);
    if (($tmp >0)) {
        $sql = "$sql and a.fromuserid=$tmp";
    }
                $tmp=($this->html->readRQ('to')*1);
    if (($tmp >0)) {
        $sql = "$sql and a.userid=$tmp";
    }
                
                
                $tmp=($this->html->readRQ('unread')*1);
    if ($tmp >0) {
        $sql = "$sql and wasread='0'";
    }
                $tmp=($this->html->readRQ('wasread')*1);
    if ($tmp >0) {
        $sql = "$sql and wasread='1'";
    }

    if (($showall>0)&&($access[main_admin])) {
        $sql1="SELECT a.id, a.descr, a.date, substr(a.time,0,20) as time, u.firstname||' '||u.surname as userfrom, u2.firstname||' '||u2.surname as userto, wasread, addinfo, tablename, refid";
        $sql=" from useralerts a, users u, users u2 where a.fromuserid=u.id and a.userid=u2.id $sql";
    } else {
        $sql1="SELECT a.id, a.descr, a.date, substr(a.time,0,20) as time, u.firstname||' '||u.surname as userfrom, u2.firstname||' '||u2.surname as userto, wasread, addinfo, tablename, refid";
        $sql=" from useralerts a, users u, users u2 where a.fromuserid=u.id and a.userid=u2.id and (a.userid=$uid or a.fromuserid=$uid) $sql";
    }

                $sqltotal=$sql;
                $sql = "$sql order by $sortby, a.id";
                $sql2=" limit $limit offset $offset;";
                $sql=$sql1.$sql.$sql2;
                //if($access[main_admin])
                //$out.= $sql;
    if ($this->db->GetVal("select count(*)".$sqltotal)==0) {
        $out.= "<div id='info'> </div>";
        return;
    }
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    $csv.=$this->data->csv($sql);
                $fields=array('id','date','from','to','Text','ref');
                    //$sort= $fields;
                    $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);
                $nbrow=0;
                $i=$limit*$page;
    while ($row = pg_fetch_array($cur)) {
        $nbrow++;
        $i=$i+1;
        $no=sprintf("%03s", $row[id]);
        $col_col = "";
        //$datedif=$this->dates->F_datediff($row[date],$today);
        if ($row[wasread]=='t') {
            $col_col = "d";
        }
        $ref=$this->data->get_name($row[tablename], $row[refid]);
        $ref=$row[tablename].": <a href='?act=details&what=$row[tablename]&id=$row[refid]'>$ref</a>";
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
        $out.= "  <td>$i</td><td id='$what:$row[id]' class='cart-selectable' reference='$what'>$no</td>";
        $out.= "<td>$row[time]</td>
						<td class=''>$row[userfrom]$prc</td>
						<td class=''>$row[userto]$prc</td>
						<td onMouseover=\"showhint('$row[addinfo]', this, event, '400px')\">$row[descr]</td>
						<td class=''>$ref</td>
						";
            $out.=$this->html->HT_editicons($what, $row[id]);
            $out.= "</tr>";
            $csv.="$row[id]	$row[name]\t$row[descr]\n";
            $totals[2]+=$row[qty];
        if ($allids) {
            $allids.=','.$what.':'.$row[id];
        } else {
            $allids.=$what.':'.$row[id];
        }
                        
        $totals[0]=(int) $totals[0] + 1;
        $totals[1]+=$row[share];
    }
    include(FW_DIR.'/helpers/end_table.php');
}
