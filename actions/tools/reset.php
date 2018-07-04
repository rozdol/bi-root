<?php 
$GLOBALS[unsafe_acts]=['tools'];
$this->crypt->csrf_chk();
if (!$access['main_admin'])
	$this->html->error('Honey pot');
//echo "OK<br>";
echo $this->data->reset_data();
exit;
