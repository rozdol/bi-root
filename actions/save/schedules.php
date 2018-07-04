<?php


$id=$this->html->readRQn('id');
$name=$this->html->readRQ('name');
$type=$this->html->readRQn('type');
if ($type<3000) {
    $type=3001;
}

$userid=$this->html->readRQn('userid');
//$usersinvolved=$this->html->readRQ('usersinvolved');
$usersinvolved=($this->html->readRQ('userslist'));
if ($usersinvolved=='') {
    $usersinvolved=$GLOBALS[uid];
}
$usersinvolved=$this->utils->normalize_list($usersinvolved, ',');
$date=$this->dates->F_date($this->html->readRQ('date'), 1);
$refid=$this->html->readRQn('refid');
$tablename=$this->html->readRQ('tablename');
$active=$this->html->readRQn('active');
$confidential=$this->html->readRQn('confidential');

$nextdate=$this->dates->F_date($this->html->readRQ('nextdate'), 1);
$diff=$this->dates->F_datediff($today, $nextdate);
if ($diff<0) {
    $out.= "<div class='alert alert-error'>$nextdate (".$this->dates->F_weekdayname($nextdate).") is earlier than today. Get back and correct it.</div>";
    $GLOBALS[no_refresh]=1;
}

$prevdate=$this->dates->F_date($this->html->readRQ('prevdate'), 1);
$interval=$this->html->readRQn('interval');
if ($interval<1) {
    $interval=1;
}

$descr=$this->html->readRQ('descr');
$addinfo=$this->html->readRQ('addinfo');
$confirm=$this->html->readRQn('confirm');
$qty=$this->html->readRQn('qty');
$makeintorders=$this->html->readRQn('makeintorders');
$makerequests=$this->html->readRQn('makerequests');
$send_sms=$this->html->readRQn('send_sms');
$send_mail=$this->html->readRQn('send_mail');

$vals=array(
    'name'=>$name,
    'type'=>$type,
    'userid'=>$GLOBALS[uid],
    'usersinvolved'=>$usersinvolved,
    'date'=>$date,
    'refid'=>$refid,
    'tablename'=>$tablename,
    'active'=>$active,
    'confidential'=>$confidential,
    'nextdate'=>$nextdate,
    'prevdate'=>$prevdate,
    'interval'=>$interval,
    'descr'=>$descr,
    'addinfo'=>$addinfo,
    'confirm'=>$confirm,
    'qty'=>$qty,
    'makeintorders'=>$makeintorders,
    'makerequests'=>$makerequests,
    'send_sms'=>$send_sms,
    'send_mail'=>$send_mail
    );
//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;
if ($id==0) {
    $id=$this->db->insert_db($what, $vals);
} else {
    $id=$this->db->update_db($what, $id, $vals);
}
$body.=$out;
