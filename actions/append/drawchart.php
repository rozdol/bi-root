<?php
if ($what == 'drawchart'){
	$h=($this->html->readRQ('h')*1);
	$w=($this->html->readRQ('w')*1);
	$id=($this->html->readRQ('id')*1);
	$rate=($this->html->readRQ('rate')*1);
	$title=($this->html->readRQ('title'));
	if($h==0)$h=round(400);
	if($w==0)$w=round(450);
	$chart=($this->html->readRQ('chart'));
	if($chart=='')$chart="accounting";
	if($chart=='accounting')$type="MultiLevelPie.swf";
	if($chart=='budget')$type="Pie2D.swf";
  $strDataURL = "?act=graphdata&what=$chart&opt=nowrap&next=-1&id=$id&title=$title&rate=$rate";
  $strDataURL = urlencode($strDataURL);
  
	$response="<div align='center' class='text'><OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH=$w HEIGHT=$h id='FusionCharts' ALIGN=''>
	     <PARAM NAME='FlashVars' value='&dataURL=$strDataURL'>
	     <PARAM NAME=movie VALUE='".ASSETS_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h'>
	     <PARAM NAME=quality VALUE=high>
	     <PARAM NAME=bgcolor VALUE=#FFFFFF>
	     <EMBED src='".ASSETS_URI."/assets/swf/$type?chartWidth=$w&chartHeight=$h' FlashVars='&dataURL=$strDataURL' quality=high bgcolor=#FFFFFF WIDTH=$w HEIGHT=$h NAME='FusionCharts' ALIGN='' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED>
	   </OBJECT></div>
	";
		
	$out.= "$response";
}


$body.=$out;
