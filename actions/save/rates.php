<?php
if ($what == 'rates') {
    $date=$this->dates->F_date($this->html->readRQ('date'), 1);
    $sql="select * from listitems where list_id=6 order by id";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    while ($row = pg_fetch_array($cur)) {
        $rate=$this->html->readRQn("rate-$row[id]");
        $count=$this->db->GetVal("select count(*) from rates where date='$date' and currency=$row[id]");
        if ($count>0) {
            $sql="update rates set rate=$rate where date='$date' and currency=$row[id]";
        } else {
            $sql="insert into rates (rate,date,currency) values($rate,'$date',$row[id])";
        }
        if ($rate>0) {
            $err.=$this->db->GetVal($sql);
        }
        //$err.="$row[name]=$rate SQL=$sql<br>";
    }
    if ($err!='') {
        $out.= $err;
        exit;
    }
}
$body.=$out;
