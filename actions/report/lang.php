<?php
$path=DATA_DIR.'/lang/';
//$ype='.txt';
$processed=$this->html->show_folder($path,'LANG');
echo $processed;