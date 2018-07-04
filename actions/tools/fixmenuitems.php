<?php
//tools fixmenuitems
if ($access['main_admin']) {
    $sql="select * from menuitems where link like '%table=%' and link not like '%what=%'";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    $start_time=$this->utils->get_microtime();
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $this->progress($start_time, $rows, $i, "$row[name]");
        $link=str_replace("table=", "what=", $row[link]);
        $sql="update menuitems set link='$link' where id=$row[id]";
        $this->db->GetVal($sql);
    }
    $this->livestatus("Done.");
    $sql="select * from menuitems where link like '%&amp;%'";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    $start_time=$this->utils->get_microtime();
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $this->progress($start_time, $rows, $i, "$row[name]");
        $link=str_replace("&amp;", "&", $row[link]);
        $sql="update menuitems set link='$link' where id=$row[id]";
        $this->db->GetVal($sql);
    }
    $this->livestatus("Done.");
}
$body.=$out;
