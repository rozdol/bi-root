<?php
if (($what == 'tables')&&($access['main_admin'])) {
    $this->db->GetRow("select * from process_addaccsstbl();");
    //$this->db->GetVal("UPDATE accesslevel set access='1' WHERE userid=$userid;");
    $sql = "SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*' AND NOT relname ~ 'sql_.*' AND relkind = 'r' ORDER BY relname";
    //$out.= $sql;
    $out.="<div class='title2'>Tables used in DB</div>\n";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }

    $fields=array('table','show','download','records');
    $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);
    $nbrow=0;   //Local variable to count number of rows

    // fetch the succesive result rows
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $col_col = "";
        $dell="<i class='icon-fire tooltip-test addbtn' data-original-title='Empty' onclick=\"confirmation('?act=tools&what=emptytable&table=$row[0]&opt=nowrap','Empty table $row[0]?')\"></i>";
        $count=$this->db->getval("SELECT count(*) from $row[0]");
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
        $out.= "\t\t<td>$i</td>\n";
        $out.= "\t\t<td>$row[0]</td>\n";
        $out.= "\t\t<td><a href='?act=show&what=$row[0]'>show</a></td>\n";
        $out.= "\t\t<td><a href='?act=tools&what=savetable&table=$row[0]&opt=nowrap'>download</a></td>\n";
        $out.= "\t\t<td>$count</td>\n";
        $out.= "\t\t<td>$dell</td>\n";

        $out.= "\t</tr>\n";
        $totqty+=$row[1];
        $totamount+=$row[3];
        $tables[]="$row[0]\t$count";
    }
    $out.= "</table>";
    $csv=implode("\n", $tables);
    $out.=$this->utils->exportcsv($csv);
}

$body.=$out;
