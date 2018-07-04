<?php
//form init

$tablename=$this->html->readRQ('tablename');
$reference=$this->html->readRQ('reference');
$refid=$this->html->readRQn('refid');
$back_to_url=$this->html->readRQn('back_to_url');
if($id==0)$action="Add"; else $action="Edit";



