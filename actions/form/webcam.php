<?php

$reftype = ($this->html->readRQn('reftype')) * 1;
$refid = ($this->html->readRQn('refid')) * 1;
$tablename = ($this->html->readRQ('tablename'));
$related_data = $this->html->readRQ('related_data');
$cam_settings = $this->html->readRQ('settings');
if ($cam_settings != '') {
    $settings = json_decode($cam_settings, true);
} else {
    $settings = array('size' => '640x480', 'dest' => '640x480', 'crop' => '640x480', 'quality' => '90', 'flip' => '0');
}
if ($tablename == "documents") {
    $row = $this->db->GetRow("select * from documents where id=$refid");
    $fdate = $this->dates->F_YMDate($row['datefrom']);
    $ftype = $this->db->GetVal("select name from listitems where id=$row[type]");
    //$partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from partners p, docs2partners d where d.docid=$refid and d.partnerid=p.id"));
    $partnersnamelist = substr($partnersnamelist, 0, strlen($partnersnamelist) - 1);
    $fsum = $row['amount'];
    $newfname = "$fdate-$ftype-$fsum.jpg";
} else {
    $row = $this->db->GetRow("select * from $tablename where id=$refid");
    $fdate = $this->dates->F_YMDate($row[date]);
    $ftype = $this->db->GetVal("select name from listitems where id=$reftype");
    //$partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from partners p, docs2partners d where d.docid=$refid and d.partnerid=p.id"));
    $newfname = "$fdate-$tablename$refid-$ftype.jpg";
}

$url = "?act=save&what=webcam&plain=1&reftype=$reftype&tablename=$tablename&refid=$refid&newfname=$newfname&rename=1&related_data=$related_data";
$out .= $this->html->link_button("<i class='icon-arrow-left icon-white'></i> Back", "?act=details&what=$tablename&id=$refid", 'info') . " ";
//$settings=array('size'=>'640x480','dest'=>'1632x1224','crop'=>'1085x1015','quality'=>'60','flip'=>'0');
$out .= $this->html->cameraJS($url, $row['name'], $settings);
$out .= "File name: $newfname";





$body .= $out;
