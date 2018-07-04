<?php
if ($what == 'columnchart'){
		$h=($this->html->readRQ('h')*1);
		$w=($this->html->readRQ('w')*1);
		$total=$this->html->readRQn('total');
		$values=($this->html->readRQ('values'));
		$labels=($this->html->readRQ('labels'));
		$json=$this->html->readRQc("json");
		//$out.= "Crart:<br>$json<br>";
		if($labels=='')$labels="ten,five,thirty";
		if($values=='')$values="10,5,30";
		//if($json=='')
		//$json = '{"fifty":50,"one":1,"ten":10,"two":2,"twenty":20}';
		$json=str_replace(":","^",$json);
		$title=($this->html->readRQ('title'));
		if($h==0)$h=round(400);
		if($w==0)$w=round(450);
		$chart=($this->html->readRQ('chart'));
		$type="Column2D.swf";
	  $strDataURL = "?act=graphdata&what=$what&opt=nowrap&next=-1&values=$values&title=$title&labels=$labels&total=$total&json=$json";
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
	}

$body.=$out;
