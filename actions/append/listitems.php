<?php
if ($what == 'listitems'){
	$id=$this->html->readRQn('list_id');
	$_POST[list_id]="$id";
	$out.=$this->show('listitems');
}

$body.=$out;
