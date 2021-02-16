<?php
//?act=report&what=calendar&events=-1&transactions=-1&invoices=-1&account_transactions=-1&cash_transactions=-1&accounting_transactions=-1&documents=-1&inwardinvoices=-1&receipts=-1&schedules=-1&monthes=3&range=near
$year=$this->html->readRQn('year');
if($year==''){$year  = isset($_GET['y']) ? $_GET['y'] : $this->dates->F_thisyear();}
$month=$this->dates->F_thismonth();
$GLOBALS[calendar][data][events]=$this->html->readRQn('events');//$GLOBALS[is_owner_id];
$GLOBALS[calendar][data][transactions]=$this->html->readRQn('transactions');
$GLOBALS[calendar][data][invoices]=$this->html->readRQn('invoices');
$GLOBALS[calendar][data][account_transactions]=$this->html->readRQn('account_transactions');
$GLOBALS[calendar][data][cash_transactions]=$this->html->readRQn('cash_transactions');
if($this->html->readRQn('accounting_transactions')!=0)$GLOBALS[calendar][data][accounting_transactions]=$GLOBALS[is_owner_id];
if($this->html->readRQn('documents')!=0)$GLOBALS[calendar][data][documents]=$GLOBALS[is_owner_id];
if($this->html->readRQn('inwardinvoices')!=0)$GLOBALS[calendar][data][inwardinvoices]=$GLOBALS[is_owner_id];
if($this->html->readRQn('receipts')!=0)$GLOBALS[calendar][data][receipts]=$GLOBALS[is_owner_id];
//if($this->html->readRQn('schedules')!=0)$GLOBALS[calendar][data][schedules]=$GLOBALS[is_owner_id];
$GLOBALS[calendar][data][schedules]=$this->html->readRQn('schedules');
$GLOBALS[calendar][data][monthes]=$this->html->readRQcsv('monthes');
$range=$this->html->readRQ('range');
if($range=='now'){
	$GLOBALS[calendar][data][monthes]=$month;
}
if($range=='next'){
	if($month==12){
		$month=1;
		$year++;
	}else{
		$month++;
	}
	$GLOBALS[calendar][data][monthes]=$month;
}
if($range=='prev'){
	if($month==1){
		$month=11;
		$year--;
	}else{
		$month--;
	}
	$GLOBALS[calendar][data][monthes]=$month;
}
if($range=='near'){
	if($month==1){
		$year--;
		$GLOBALS[calendar][data][monthes]=12;
		$out.= $this->data->show_cal($year-1);
		$GLOBALS[calendar][data][monthes]="1,2";
		$out.= $this->data->show_cal($year);
	}elseif($month==11){
		$GLOBALS[calendar][data][monthes]="11,12";
		$out.= $this->data->show_cal($year);
		$GLOBALS[calendar][data][monthes]="1";
		$out.= $this->data->show_cal($year+1);
	}elseif($month==12){
		$GLOBALS[calendar][data][monthes]="12";
		$out.= $this->data->show_cal($year);
		$GLOBALS[calendar][data][monthes]="1,2";
		$out.= $this->data->show_cal($year+1);
	}else{
		$monthprev=$month-1;
		$monthnext=$month+1;
		$monthes="$monthprev,$month,$monthnext";
		$GLOBALS[calendar][data][monthes]=$monthes;
		$out.= $this->data->show_cal($year);
	}

}else{
	$out.= $this->data->show_cal($year);
}

//$out.= "<h3>Celendar</h3>";

$body.=$out;
