<?php
if ($what == 'dragnoderel'){
		$id=$this->html->readRQ('id');
		//if($id==0){$id=116;}
		$h=round($w);
		$h=800; $w=1000;
		
	  $strDataURL = "index.php?act=graphdata&what=$what&opt=nowrap&prev=1&id=$id";
	  $strDataURL = urlencode($strDataURL);
	  $chart="DragNode.swf";
   $out.= "<div align='center' class='text'><OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH=$w HEIGHT=$h id='FusionCharts' ALIGN=''>
     <PARAM NAME='FlashVars' value='&dataURL=$strDataURL'>
     <PARAM NAME=movie VALUE='".APP_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h'>
     <PARAM NAME=quality VALUE=high>
     <PARAM NAME=bgcolor VALUE=#FFFFFF>
     <EMBED src='".APP_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h' FlashVars='&dataURL=$strDataURL' quality=high bgcolor=#FFFFFF WIDTH=$w HEIGHT=$h NAME='FusionCharts' ALIGN='' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED>
   </OBJECT></div>";	
	}
	
$body.=$out;
