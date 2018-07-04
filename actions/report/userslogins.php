<?php
if ($what == 'userslogins') {
    $fields=array('date','ip','user');
    $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);
    //$sql="select * from logs where action like 'LOGIN%' order by id desc limit 20";
    //$sql="select * from logs where action like '%act=login&%' order by id desc limit 40";
    $sql="select * from logs order by id desc limit 400";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    while ($row = pg_fetch_array($cur)) {
        $out.= "<tr>";
        //$data=explode("=",$row[action]);
        //$data2=explode("&",$data[2]);
        $user=$this->data->get_val('users', 'username', $row[userid]);
        //$out.= "<td>$row[id]</td><td>$row[date]</td><td>$row[ip]</td><td>$row[userid]</td><td>$data2[0]</td></tr>\n";
        $out.= "<td>$row[id]</td><td>$row[date]</td><td>$row[ip]</td><td>$user - $row[userid]</td><td>$row[action]</td></tr>\n";
    }
    $out.=$this->html->tablefoot($i, $totals, $totalrecs);
    $totals=$this->utils->F_toarray($this->db->GetResults("select count(*)".$sqltotal));
    if ($dynamic>0) {
        $nav=$this->html->HT_ajaxpager($totals[0], $orgqry, "$titleorig.");
    } else {
        $nav=$this->html->HT_pager($totals[0], $orgqry);
    }
    $export= "	
		<div class='dropdown2 dropdown-toggle' data-toggle='dropdown2'>.
		<div class='dropdown-menu2'>
		<textarea cols='1' rows='1'>$csv</textarea>
	</div>
	</div>";
    $body.= "$out $nav $export";
}
