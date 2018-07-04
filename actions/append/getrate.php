<?php
if ($what == 'getrate') {
    $date=$this->dates->F_date($this->html->readRQ('date'), 1);
    $base=$this->html->readRQn('base');
    $rate=$this->data->get_rate($id, $date);
    $baserate=1;
    if ($base>0) {
        $baserate=$this->data->get_rate($base, $date);
    }
    $response=$rate/$baserate;
    $out.= $response;
}

$body.=$out;
