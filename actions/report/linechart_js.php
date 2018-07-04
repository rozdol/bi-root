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
//$json=str_replace(":","^",$json);
$title=($this->html->readRQ('title'));
if($h==0)$h=round(400);
if($w==0)$w=round(450);
include_once(CLASSES_DIR.'/FusionChart.class.php');
$FC = new FusionCharts("Line","600","250"); 

# set the relative path of the swf file
  $FC->setSWFPath(FW_DIR.'/vendor/FusionCharts/');

  # Define chart attributes
  $strParam="caption=$title;xAxisName=$yAxisName;yAxisName=$yAxisName;numberPrefix=$numberPrefix";

  # Set chart attributes
  $FC->setChartParams($strParam);
 # Add chart values and category names
$data = json_decode($json);
foreach($data as $key=>$value){
	$FC->addChartData($value,"label=$key");
}
 echo "	<script src='".APP_URI."/assets/js/FusionCharts/FusionCharts.js'></script>
	<script>FusionCharts.setCurrentRenderer('javascript');</script>";
	ob_flush();flush();


$FC->renderChart();