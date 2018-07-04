<?php
if ($what == 'calcdays') {
    $date1=$this->dates->F_date($this->html->readRQ('date1'), 1);
    $date2=$this->dates->F_date($this->html->readRQ('date2'), 1);
    $response=$this->dates->F_datediff($date1, $date2);
    $out.= "$response";
}



$body.=$out;
