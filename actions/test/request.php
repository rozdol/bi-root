<?php
$var=$this->html->readRQn('id');
echo $this->html->pre_display($var,"var");
$num="DR345.567";
$num2=$this->utils->cleannumber($num);
echo $this->html->pre_display($num2,"result");

$num="345,567";
$num2=$this->utils->cleannumber($num);
echo $this->html->pre_display($num2,"result");