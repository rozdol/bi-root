<?php
$func=$this->html->readRQ('func');
$this->utils->altercart($func);
echo "$func<br>";
if($func=='add'){
	echo $this->html->refreshpage('?act=add&table=processdata', 0, "Redirecting...");
	exit;
}
