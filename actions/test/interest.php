<?php
use Rozdol\Loans\Loan;
$this->loan = Loan::getInstance();

$test=array (
  'loan_data' =>
  array (
    'amount' => 1000000,
    'rate' => 0.01,
    'freq' => '0',
    'df' => '02.01.2018',
    'dt' => '05.01.2018',
    'base' => '365',
    'p_rate' => 0.01,
    'date' => '05.01.2018',
    'compound' => 'f',
  ),
  'transactions' =>
  array (
    0 =>
    array (
      'date' => '02.01.2018',
      'given' => 1000000,
      'returned' => 0,
      'paid' => 0,
      'adjustment' => 0,
      'descr' => ' Loan Drawdown',
    ),
    1 =>
    array (
      'date' => '05.01.2018',
      'given' => 0,
      'returned' => 0,
      'paid' => 27.40*2,
      'adjustment' => 0,
      'descr' => ' ',
    ),
    2 =>
    array (
      'date' => '05.01.2018',
      'given' => 0,
      'returned' => 1000000,
      'paid' => 0,
      'adjustment' => 0,
      'descr' => ' ',
    ),
  ),
);

$test=array (
  'loan_data' =>
  array (
    'amount' => '27000000',
    'rate' => 0.015,
    'freq' => '0',
    'df' => '26.04.2016',
    'dt' => '26.04.2018',
    'base' => '365',
    'p_rate' => 0.015,
    'date' => '11.07.2018',
    'compound' => 'f',
  ),
  'transactions' =>
  array (
    0 =>
    array (
      'date' => '26.04.2016',
      'given' => 27000000,
      'returned' => 0,
      'paid' => 0,
      'adjustment' => 0,
      'descr' => ' Loan Drawdown',
    ),
    1 =>
    array (
      'date' => '10.05.2017',
      'given' => 0,
      'returned' => 0,
      'paid' => 275532.79,
      'adjustment' => 0,
      'descr' => ' ',
    ),
  ),
);

$loan_array=$test;
$test_arr=[];
$date='30.12.2017';
$date='25.04.2018';
$test_arr=[];
for ($i=1; $i <= 2; $i++) {
	$date=$this->dates->F_date_add($date);
	$loan_array['loan_data']['date']=$date;
	$loan_r=$this->loan->calcLoan($loan_array);
	$int=$loan_r[interest_accrued];
	$test_arr[$i]['date']=$loan_array['loan_data']['date'];
	$test_arr[$i]['given']=$loan_r[given];
	$test_arr[$i]['returned']=$loan_r[returned];
  $test_arr[$i]['returned_i']=$loan_r[returned_i];
  $test_arr[$i]['balance']=$loan_r[balance];
	$test_arr[$i]['int_acc']=$int;
	$diff=round($int-$int_prev,2);
	$int_prev=$int;
	$test_arr[$i]['diff']=$diff;
	$test_arr[$i]['interest']=$loan_r[interest];
  $tbl.=$this->html->tag("$date",'foldered','');
  $tbl.=$loan_r[tbl];

}


$out.=$this->html->array_display($test_arr,'test',2);

$out.=$tbl;
$body.=$out;
 ?>