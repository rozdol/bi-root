<?php
if ($what == 'favorites') {
        //foreach ($_POST as $key => $value) {$out.= $key . " => " . $value . "<br>\n";} $limit=30; //exit;

    if ($sortby=='') {
        $sortby="date desc";
    }
        
        $tmp=($this->html->readRQ("reference"));
    if ($tmp!="") {
        $sql = "$sql and a.reference='$tmp'";
    }
    if (!($access['main_admin'])) {
        $sql = "$sql and a.userid='$GLOBALS[uid]'";
    }
        //$sql = "$sql and a.userid='$GLOBALS[uid]'";
        
        $sql1="select * ";
        $sql=" from $what a where id>0 $sql";
        $sqltotal=$sql;
        $sql = "$sql order by reference asc, id asc";
        $sql2=" limit $limit offset $offset;";
        $sql=$sql1.$sql.$sql2;
        //$out.= "$sql\n";
    if ($this->db->GetVal("select count(*)".$sqltotal)==0) {
        $out.= "<div id='info'>No $what.</div>";
        return;
    }

        $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>\n";
    if ($access[main_admin]) {
        $morecolls="<td>User</td>";
    }
        $out.= "<tr class='c'>
			<td> </td>
			$morecolls
			<td>Table</td>
			<td>Name</td>
			<td>Description</td>
			<td style='width:50px; text-align:center;' text-align=center>$addbutton</td>";


        $out.="
			</tr>\n";
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
        $col_col = "";
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
        $row[name]=$this->data->get_name($row[reference], $row[refid]);
        $row[name]="<a href='?act=details&what=$row[reference]&id=$row[refid]'>$row[name]</a>";
        $row[descr]=$this->data->get_val($row[reference], 'descr', $row[refid]);
        $descr=$this->utf8->utf8_cutByPixel($row[descr], 400, false);
        if ($access[main_admin]) {
            $row[user]=$this->data->get_val('users', 'surname', $row[userid])." ".$this->data->get_val('users', 'firstname', $row[userid]);
            $morecolls="<td>$row[user]</td>";
        } else {
            $morecolls="";
        }
        $out.= "<td>$i</td>
				$morecolls
				<td>$row[reference]</td>
				<td>$row[name]</td>
				<td>$descr</td>";
        if ($access[main_admin]) {
            $out.= $this->html->HT_editicons($what, $row[id]);
        }
        $out.= "\t</tr>\n";
        $totals[0]=(int) $totals[0] + 1;
    }

        $totals=$this->utils->F_toarray($this->db->GetResults("select count(*)".$sqltotal));
        $nav=$this->html->HT_ajaxpager($totals[0], $orgqry, $what."_");
        $nav=$this->html->HT_pager($totals[0], $orgqry, $what."_");
        $out.= "<tr class='a'><td colspan='15'>$nav</td></tr></table>";
}

    
$body.=$out;
