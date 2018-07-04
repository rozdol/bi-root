<?php
$close="<span id='closeresults' onclick='
document.getElementById(\"checkresult\").innerHTML=\"\";
' 
class='icon-remove-circle tooltip-test' data-original-title='Close'></span><br>";
$readRQ=$this->html->readRQ('value');
$result.=$this->html->pre_display($readRQ,'readRQ');

$readRQ=$this->html->readRQn('value');
$result.=$this->html->pre_display($readRQ,'readRQn');

$readRQ=$this->html->readRQh('value');
$result.=$this->html->pre_display($readRQ,'readRQh');

$readRQ=$this->html->readRQs('value');
$result.=$this->html->pre_display($readRQ,'readRQs');

$readRQ=$this->html->readRQd('value');
$result.=$this->html->pre_display($readRQ,'readRQd');

$readRQ=$this->html->readRQdd('value');
$result.=$this->html->pre_display($readRQ,'readRQdd');

$readRQ=$this->html->readRQf('value');
$result.=$this->html->pre_display($readRQ,'readRQf');

$readRQ=$this->html->readRQc('value');
$result.=$this->html->pre_display($readRQ,'readRQc');

$out.= "$close<hr>$result";
$body.=$out;