<?php
$ref_table=$this->html->readRQ('ref_table');
if($ref_table=='')$ref_table=$this->html->readRQ('reftable');
if($ref_table=='')$ref_table=$this->html->readRQ('tablename');
if($ref_table=='')$ref_table=$this->html->readRQ('reference');
$ref_id=$this->html->readRQn('ref_id');
if($ref_id==0)$ref_id=$this->html->readRQn('refid');

if(!$_POST[notitle])$out.=$this->html->tag('Discussion','foldered');
$out.=$this->html->link_button('New Post',"?act=add&what=posts&ref_table=$ref_table&ref_id=$ref_id",'primary');
$out.=$this->data->post($ref_table,$ref_id);

$body=$out;
