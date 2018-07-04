<?php
$out.=$this->html->tag('My favorites', 'h2');
$sql="select distinct reference from favorites where userid=$GLOBALS[uid] order by reference";
if (!($cur2 = pg_query($sql))) {
    $this->html->SQL_error($sql2);
}
$rows=pg_num_rows($cur2);
$start_time=$this->utils->get_microtime();
while ($row2 = pg_fetch_array($cur2)) {
    //echo "$row2[reference]<br>";
    $_POST['ids']=$this->data->get_list_csv("select refid from favorites where userid=$GLOBALS[uid] and reference='$row2[reference]'");
    $out.=$this->show($row2[reference]);
}
$body.=$out;
