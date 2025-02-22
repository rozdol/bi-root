<?php
global $access, $uid, $db, $uploaddir, $progdir, $ht, $gid, $userrec;
if ($did == '') $id = $this->html->readRQ('id');
else $id = $did;
//$id=readRQ('id');
if ((int) $id > 0) {
	$GLOBALS['record_old_vals'] = $this->data->record_array($what, $id);
}
$secondtime = ($this->html->readRQn('secondtime') * 1);
//echo $this->html->pre_display($_GET,'get'); exit;