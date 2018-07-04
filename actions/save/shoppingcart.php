<?php
$this->utils->altercart($this->html->readRQ('func'));
echo $this->html->pre_display($_POST,"_POST");
echo $this->html->pre_display($_GET,"_GET");
exit;
//$this->html->refreshpage('?act=add&table=processdata',1,"<div class='alert alert-info'>Redirecting</div>");
echo $this->html->refreshpage("?act=add&table=processdata", 100, "Redirecting");
exit;
