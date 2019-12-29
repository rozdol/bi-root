<?php
$this->project->dailyroutine();
$newdate=$this->dates->F_dateadd($GLOBALS[today], 1);
$param='daily';
$this->db->GetVal("update config set value='$newdate' where name='$param'");