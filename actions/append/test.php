<?php
if ($what == 'test') {
    $sql="select id, name from lists order by name";
    $response=$this->utils->sql2json($sql);
    $out.= "$response";
}
$body.=$out;
