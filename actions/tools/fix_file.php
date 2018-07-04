<?php
//--------------------------
if ($access['main_admin']){
		$id=$this->html->readRQn('id');
		$filename=$this->html->readRQ('name');
		if($filename=='')$filename='file.pdf';
		$sql="update uploads set filename='$filename' where id=$id";
		$this->livestatus("$sql<br>");
		$this->db->GetVal($sql);
}
$body.=$out;
//15.0.20111205-ОРД--0-ДОМОСТРОЙ_no_sign.pdf
//15.0.20111205-ОРД--0-ДОМОСТРОЙ_no_sign.pdf