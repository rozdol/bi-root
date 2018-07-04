<?php
//tools rescue
if (!$access['main_admin'])$this->html->error('Honey pot');
	$this->data->panic_data();
	$body.="Done";
