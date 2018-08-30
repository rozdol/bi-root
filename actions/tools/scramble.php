<?php
//tools scramble
if (!$access['main_admin'])$this->html->error('Honey pot');
	$this->data->scramble_data();
	echo "Done";
	exit;
