<?php
//save favorites
$id=$this->html->readRQn('refid');
$reference=$this->html->readRQ('reference');
$sql="select * from favorites where refid=$id and reference='$reference' and userid=$uid";
$count=$this->db->GetVal($sql)*1;
if($count>0)$sql="delete from favorites where refid=$id and reference='$reference' and userid=$uid"; else $sql="insert into favorites (userid, reference, refid) values ($uid,'$reference', $refid)";
$err= $this->db->GetVal($sql);
//$err=$sql;
if($err!=''){$out.= $err;exit;}

$body.=$out;
