<?php
$id=$this->html->readRQn('id');
$this->data->gen_fast_menu($id);
//$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;
$body.=$out;
