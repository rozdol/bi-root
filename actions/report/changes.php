<?php

$df="01.01.$thisyear";
$dt=$this->html->readRQ('dt');
$all=$this->html->readRQ('all');
if ($all=='') {
    $sqladd=" and (action like 'INS%' OR action like 'CH%' OR action like 'DEL%' OR action like 'HACK%' OR action like 'TOOLS%')";
}
if ($dt=='') {
    $dt=$this->dates->F_date('', 1);
}
$dt2=$this->dates->F_dateadd($dt, 1);
$de="01.01.$nextyear";
$out.= "<div class='title2'>Changes made on $dt.</div>";
$sql="select * from logs where date>='$dt' and date<'$dt2' $sqladd";
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $username=$this->db->GetVal("SELECT username from users where id=".$row['userid']);
    //if (strlen(strstr($row['action'],"name"))>0) {$row['action']="<style='{font-style:italic}'>".$row['action']."<style>"; }
    if (strlen(strstr($row['action'], "HACK"))>0) {
        $row['action']="<span style='color:red;'>".$row['action']."</span>";
    }
    $output.="$row[date]\t$username\t$row[action]\n";
}
$out.= "<pre>$output</pre>";
$body.=$out;
