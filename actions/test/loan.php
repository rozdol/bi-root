<?php

use Rozdol\Loans\Loan;

$this->loan = new Loan();

$plan=$this->loan->planLoan($loan_data);
echo $this->html->pre_display($plan, "plan");

$loan = $this->loan->calcLoan($data);
echo $this->html->pre_display($loan, "loan");
