<?php

echo $this->html->pre_display($GLOBALS[settings][holidays],"holidays days");
$holidays=$this->dates->holidays(2018);
echo $this->html->pre_display($holidays,"holidays");