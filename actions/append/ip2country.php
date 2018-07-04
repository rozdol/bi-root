<?php
if ($what == 'ip2country'){
	$ip=$this->html->readRQ('ip');
	$response=IP2country($ip);
	$out.= "$response";
}

$body.=$out;
