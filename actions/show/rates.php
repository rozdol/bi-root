<?php
if ($what == 'rates') {
        $df="01.01.2011";
        $dt="01.01.2014";
        //foreach ($_POST as $key => $value) {$out.= $key . " => " . $value . "<br>\n";} $limit=30; //exit;
    if ($sortby=='') {
        $sortby="date desc";
    }
        $df=$this->dates->F_date($this->html->readRQ("df"));
        $dt=$this->dates->F_date($this->html->readRQ("dt"), 1);
    if ($df=='') {
        $df=$this->dates->F_dateadd($dt, -40);
    }
        $sql1="select distinct date ";
        $sql=" from rates where date>='$df' and date<='$dt' $sql";
        $sqltotal=$sql;
        $sql = "$sql order by $sortby";
        //$sql2=" limit $limit offset $offset;";
        $sql=$sql1.$sql.$sql2;
        //$out.= "$sql\n";
    if ($this->db->GetVal("select count(*)".$sqltotal)==0) {
        $sql="select distinct date from rates order by date desc limit 40";
    }


        $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>\n";
        $out.= "<tr class='c'>
		<td> </td>
			<td><a href='?$qry&sortby=date$order' TITLE='Sort' class='c' ><b>Date</b></a></td>";
        $sql2="select * from listitems where list_id=6 order by id";
    if (!($cur2 = pg_query($sql2))) {
        $this->html->SQL_error($sql2);
    }
    while ($row2 = pg_fetch_array($cur2)) {
        $out.= "<td><b><a href='?act=details&what=rates&id=$row2[id]'>$row2[name]</a></b></td>";
    }

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

        $out.= "<td>$i</td>
			<td>$row[date]</td>
			";
        $sql2="select * from listitems where list_id=6 order by id";
        if (!($cur2 = pg_query($sql2))) {
            $this->html->SQL_error($sql2);
        }
        while ($row2 = pg_fetch_array($cur2)) {
            $rate=$this->db->GetVal("select rate from rates where date='$row[date]' and currency=$row2[id]");
            $out.= "<td class='n'>$rate</td>";
        }
        $out.= "\t</tr>\n";
        //$totals[0]=(int) $totals[0] + 1;
    }

    $out.="</table>";
//include(FW_DIR.'/helpers/end_table.php');
    $out.=$this->html->link_button('Update from ECB', '?act=tools&what=update_rates', 'class');
    $body.=$out;
}
