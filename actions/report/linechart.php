<?php
$h=($this->html->readRQ('h')*1);
$w=($this->html->readRQ('w')*1);
$total=$this->html->readRQn('total');
$values=($this->html->readRQ('values'));
$labels=($this->html->readRQ('labels'));
$json=$this->html->readRQc("json");
$subtitle=$this->html->readRQ("subtitle");
$xAxisName=$this->html->readRQ("xAxisName");
$yAxisName=$this->html->readRQ("yAxisName");
$numberPrefix=$this->html->readRQ("numberPrefix");
//echo "Crart:<br>$json<br>";
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
$type="Line.swf";
$strDataURL = "?act=graphdata&what=$what&opt=nowrap&next=-1&values=$values&title=$title&labels=$labels&total=$total&subtitle=$subtitle&xAxisName=$xAxisName&yAxisName=$yAxisName&numberPrefix=$numberPrefix&json=$json";
$strDataURLuncoded = $strDataURL;
$strDataURL = urlencode($strDataURL);
//$strDataURL = "http://www.fusioncharts.com/demos/gallery/Data/Line2.xml";
$response="<div align='center' class='text'>
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='$w' height='$h' id='sampleChart$GLOBALS[settings]['rnd]'>
<param name='allowScriptAccess' value='always' />
	<param name='movie' value='Charts$type?registerWithJS=1'/>		
	<param name='FlashVars' value='&chartWidth=$w&chartHeight=$h&debugMode=0&dataURL=$strDataURL' />
	<param name='quality' value='high' />
	<embed src='".APP_URI."/assets/swf/$type?registerWithJS=1' FlashVars='&chartWidth=$w&chartHeight=$h&debugMode=0&dataURL=$strDataURL' quality='high' width='$w' height='$h' name='sampleChart' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object>
	</div>
	";

$body.=$response;