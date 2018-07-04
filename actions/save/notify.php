<?php

$id=$this->html->readRQn('id');
$name=$this->html->readRQ('name');
$refid=$this->html->readRQn('refid');
$tablename=$this->html->readRQ('tablename');
$descr=$this->html->readRQ('descr');
$groupslist=$this->html->readRQ('groupslist');
$groupid=$this->html->readRQ('groupid');
$userslist=$this->html->readRQ('userslist');
$userid=$this->html->readRQn('userid');
$sendalert=$this->html->readRQn('sendalert');
$sendsms=$this->html->readRQn('sendsms');
$makeintorders=$this->html->readRQn('makeintorders');
$act=$this->html->readRQ('act');
$confirm=$this->html->readRQn('confirm');
$sendmail=$this->html->readRQn('sendmail');
$this->data->notify_users($tablename,$refid,$descr,$groupslist,$userslist,$sendalert,$sendsms,$sendmail,$confirm);

$body.=$out;
