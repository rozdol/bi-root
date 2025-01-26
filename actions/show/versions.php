<?php
if ($what == 'versions') {
    if ($sortby=='') {
        $sortby="id desc";
    }

                $sql1="SELECT id, name, date, descr as descr";
                $sql=" from versions where id>0 $sql";

                $sqltotal=$sql;
                $sql = "$sql order by $sortby, id";
                $sql2=" limit $limit offset $offset;";
                $sql=$sql1.$sql.$sql2;
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
                $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>\n";
                $out.= "<tr class='c'>
					<td><a href='?act=search&what=ownership' class='c'><i class='icon-search'></i></a></td>
					<td><a href='?$qry&sortby=date$order' TITLE='Sort' class='c' ><b>Date</b></a></td>
					<td><a href='?$qry&sortby=fromuserid$order' TITLE='Sort' class='c' ><b>Version</b></a></td>
					<td><a href='?$qry&sortby=descr$order' TITLE='Sort' class='c' ><b>Text</b></a></td>
					<td> </td>
					</tr>\n";
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
        //$row[descr]="Проверка обрезания";
        $row[descr]=$this->utf8->utf8_cutByPixel($row[descr], 400, false);
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
        $out.="  <td>$i</td><td>$row[date]</td>
						<td>$row[name]</td>
						<td>$row[descr]</td>
						";
        $out.= $this->html->HT_editicons($what, $row[id]);
        $out.= "\t</tr>\n";
        $totals[0]=(int) $totals[0] + 1;
        $totals[1]+=$row[share];
    }
    if ($showtotals<>'') {
        $out.= "<tr class='t'><td>$totals[0]</td><td colspan='5'> </td><td class='n'>$totals[1]</td></tr>";
    }
                $totals=$this->utils->F_toarray($this->db->GetResults("select count(*)".$sqltotal));
    if ($dynamic>0) {
        $nav=$this->html->HT_ajaxpager($totals[0], $orgqry, "$titleorig.");
    } else {
        $nav=$this->html->HT_pager($totals[0], $orgqry);
    }
    if ($opt=='') {
        $out.= "<tr class='a'><td colspan='15'>$nav</td></tr>";
    }
                $out.= "</table>";
}
            
$body.=$out;
