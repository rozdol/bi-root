<?php
if ($what == 'piechart'){
	$h=($this->html->readRQ('h')*1);
	$w=($this->html->readRQ('w')*1);
	$total=$this->html->readRQn('total');
	$values=($this->html->readRQ('values'));
	$labels=($this->html->readRQ('labels'));
	$json=$this->html->readRQc("json");
	//$out.= "Crart:<br>$json<br>";
	if($labels=='')$labels="ten,five,thirty";
	if($values=='')$values="10,5,30";
	//$json='';
	//if($json=='')
	//$json = '{"fifty":50,"one":1,"ten":10,"two":2,"twenty":20}';
	$json=str_replace(":","^",$json);
	//$json=str_replace('"',"±",$json);
	$title=($this->html->readRQ('title'));
	if($h==0)$h=round(400);
	if($w==0)$w=round(450);
	$chart=($this->html->readRQ('chart'));
	$type="Pie2D.swf";
  $strDataURL = "?act=graphdata&what=$what&opt=nowrap&next=-1&values=$values&title=$title&labels=$labels&total=$total&json=$json";
  $strDataURLuncoded = $strDataURL;
  $strDataURL = urlencode($strDataURL);
	$response="<div align='center' class='text'><OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH=$w HEIGHT=$h id='FusionCharts' ALIGN=''>
	     <PARAM NAME='FlashVars' value='&dataURL=$strDataURL'>
	     <PARAM NAME=movie VALUE='".APP_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h'>
	     <PARAM NAME=quality VALUE=high>
	     <PARAM NAME=bgcolor VALUE=#FFFFFF>
	     <EMBED src='".APP_URI."/assets/swf/$type?chartWidth=$w&chartHeight=$h' FlashVars='&dataURL=$strDataURL' quality=high bgcolor=#FFFFFF WIDTH=$w HEIGHT=$h NAME='FusionCharts' ALIGN='' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED>
	   </OBJECT></div>
	";

	$out.= "$response";
	//$out.= "<br><textarea cols=120 rows=10>$strDataURL\n\n$strDataURLuncoded</textarea>";
	//%7B%22Cyprus %22 :40,%22Russia%22:50,%22GB%22:10%7D
	//%7B %22 Cyprus %22 %5E40,%22Russia%22%5E50,%22GB%22%5E10%7D
	//%7B %26quot%3B Cyprus %26quot%3B% 5E40%2C%26quot%3BRussia%26quot%3B%5E50%2C%26quot%3BGB%26quot%3B%5E10%7D
	//{"Cyprus":40,"Russia":50,"GB":10}
}

$body.=$out;
