<?php
$GLOBALS[pdf_mode]=1;

setlocale(LC_TIME, "C");
$tz = @date_default_timezone_get();
date_default_timezone_set($GLOBALS[settings][timezone]);
$fulldate = date("d F Y");
$date_displayed=date("d.m.Y, H:i:s")." ".$GLOBALS[settings][timezone];
date_default_timezone_set($tz);