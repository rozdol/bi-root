<?php
if ($what == 'ordchart'){
		$h=round(800);
		$w=round(1650);
	  $id=($this->html->readRQ("id")*1);
	  $strDataURL = "?act=graphdata&what=$what&opt=nowrap&next=-1&id=$id";
	  $strDataURL = urlencode($strDataURL);
	  $chart="Gantt.swf";
   $out.= "<div align='center' class='text'><OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH=$w HEIGHT=$h id='FusionCharts' ALIGN=''>
     <PARAM NAME='FlashVars' value='&dataURL=$strDataURL'>
     <PARAM NAME=movie VALUE='".ASSETS_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h'>
     <PARAM NAME=quality VALUE=high>
     <PARAM NAME=bgcolor VALUE=#FFFFFF>
     <EMBED src='".ASSETS_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h' FlashVars='&dataURL=$strDataURL' quality=high bgcolor=#FFFFFF WIDTH=$w HEIGHT=$h NAME='FusionCharts' ALIGN='' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED>
   </OBJECT></div>";

	}
	
	
$body.=$out;
