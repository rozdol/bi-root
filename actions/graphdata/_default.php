<?php
//grpahdata

//----------------------------
if ($what=="columnchart"){
	$values=($this->html->readRQ("values"));
	include 'FC_Colors.php';
	$title=$this->html->readRQ("title");
	$total=$this->html->readRQn("total");
	$json=$this->html->readRQc("json");
	$json=str_replace("^",":",$json);
	//$json2 = urldecode($json2);
	$labels=$this->html->readRQ("labels");
	if($title=='')$title="Title";
	if($labels='')$labels="ten,five,thirty";
	if($values='')$values="10,5,30";
	if($json=='')$json = '{"one":1,"one":1,"ten":10,"two":2,"twenty":20}';
	//$json = '{"one":1,"one":1,"ten":10,"two":2,"twenty":20}';
	$json = urldecode($json);
	$json=str_replace("%26quot%3B%",'"',$json);
	$json=str_replace("&quot;",'"',$json);
	$data = json_decode($json);
	//$strXMLData = "<chart caption='$title' showPercentageInLabel='1' showValues='1' showLabels='0' showLegend='1' numberPrefix=''  bgcolor='FFFFFF' baseFontSize='9'>";
	$strXMLData = "<chart yAxisName='Sales Figure' caption='$title' numberPrefix='' useRoundEdges='1' bgColor='FFFFFF,FFFFFF' showBorder='0'  animation='0'>";


	foreach ($data as $name => $value) {
		//echo "$name,$value\n";
		$i++;if($i>20)$i=1;
		//$color=$strColor[$i];
		$color="003355";
		//if($total>0)$value=(($value/$total)*100);
		$strXMLData = $strXMLData ."<set value='$value' label='$name' color='$color' isSliced='0'/>";
	}	

	$strXMLData = $strXMLData ."</chart>
	";
	$strXMLData = $strXMLData . chr(13);

}
//----------------------------
if ($what=="piechart"){
	//$showplain=1;
	$values=($this->html->readRQ("values"));
	include 'FC_Colors.php';
	$title=$this->html->readRQ("title");
	$total=$this->html->readRQn("total");
	$json=$this->html->readRQc("json");
	$json=str_replace("^",":",$json);
	//$json2 = urldecode($json2);
	$labels=$this->html->readRQ("labels");
	if($title=='')$title="Title";
	if($labels='')$labels="ten,five,thirty";
	if($values='')$values="10,5,30";
	//if($json=='')
	//$json = '{"one":1,"one":1,"ten":10,"two":2,"twenty":20}';
	//$json2 = '{"Cyprus":40,"Russia":50,"GB":10}';
	$json = urldecode($json);
	$json=str_replace("%26quot%3B%",'"',$json);
	$json=str_replace("&quot;",'"',$json);
	$data = json_decode($json);

	$strXMLData = "<chart caption='$title' showPercentageInLabel='1' showValues='1' showLabels='0' showLegend='1' numberPrefix=''  bgcolor='FFFFFF' baseFontSize='9'  animation='0'>";

	foreach ($data as $name => $value) {
		//echo "$name,$value\n";
		$i++;if($i>20)$i=1;
		$color=$strColor[$i];
		//if($total>0)$value=(($value/$total)*100);
		$strXMLData = $strXMLData ."<set value='$value' label='$name' color='$color' isSliced='0'/>";
	}	

	$strXMLData = $strXMLData ."</chart>
	";
	$strXMLData = $strXMLData . chr(13);
	if($showplain!=''){
		$strXMLData = $strXMLData . "\n\n\njson='$json'" . "\njson='$json2'";
		echo "<br><textarea cols=20 rows=20>";
		print_r($data);
		echo "</textarea><br>";
	}
	//json='{"Cyprus":40,"Russia":50,"GB":10}'
	//json='{"Cyprus":40,"Russia":50,"GB":10}'
	//$strXMLData="<chart caption='ÃÅ¸ÃÂ¾ ÃÂ¿Ã‘â‚¬ÃÂ¾ÃÂµÃÂºÃ‘â€šÃÂ°ÃÂ¼' showPercentageInLabel='1' showValues='1' showLabels='0' showLegend='1' numberPrefix=''  bgcolor='FFFFFF' baseFontSize='9'  animation='0'><set value='1253120' label='Kalina' color='F6BD0F' isSliced='0'/><set value='25620000' label='Potapovo' color='8BBA00' isSliced='0'/><set value='1952814.62' label='Print-House' color='A66EDD' isSliced='0'/><set value='5260596.8147086' label='CyprusBusiness (land)' color='F984A1' isSliced='0'/><set value='6554491.8921052' label='MFT' color='CCCC00' isSliced='0'/></chart>";

}
//----------------------------
if ($what=="linechart"){
	$values=($this->html->readRQ("values"));
	include 'FC_Colors.php';
	$title=$this->html->readRQ("title");
	$subtitle=$this->html->readRQ("subtitle");
	$xAxisName=$this->html->readRQ("xAxisName");
	$yAxisName=$this->html->readRQ("yAxisName");
	$numberPrefix=$this->html->readRQ("numberPrefix");
	$total=$this->html->readRQn("total");
	$json=$this->html->readRQc("json");
	$json=str_replace("^",":",$json);
	//$json2 = urldecode($json2);
	$labels=$this->html->readRQ("labels");
	if($title=='')$title="Title";
	if($labels='')$labels="ten,five,thirty";
	if($values='')$values="10,5,30";
	if($json=='')$json = '{"one":1,"one":1,"ten":10,"two":2,"twenty":20}';
	//$json = '{"one":1,"one":1,"ten":10,"two":2,"twenty":20}';
	$json = urldecode($json);
	$json=str_replace("%26quot%3B%",'"',$json);
	$json=str_replace("&quot;",'"',$json);
	$data = json_decode($json);
	//$strXMLData = "<chart caption='$title' showPercentageInLabel='1' showValues='1' showLabels='0' showLegend='1' numberPrefix=''  bgcolor='FFFFFF' baseFontSize='9'>";
	$strXMLData = "<chart caption='$title' subcaption='$subtitle' xAxisName='$xAxisName' yAxisName='$yAxisName' numberPrefix='$numberPrefix' showLabels='1' showColumnShadow='1' animation='1' showAlternateHGridColor='1' AlternateHGridColor='999999' divLineColor='666666' divLineAlpha='20' alternateHGridAlpha='5' canvasBorderColor='666666' baseFontColor='666666' lineColor='FF5904' lineAlpha='85' showValues='1' rotateValues='1' valuePosition='auto' canvaspadding='8' setAdaptiveYMin='1' labelDisplay='ROTATE'>";


	foreach ($data as $name => $value) {
		//echo "$name,$value\n";
		$i++;if($i>20)$i=1;
		//$color=$strColor[$i];
		$color="003355";
		//if($total>0)$value=(($value/$total)*100);
		$strXMLData = $strXMLData ."<set value='$value' label='$name' color='$color' isSliced='0'/>";
	}	

	$strXMLData = $strXMLData ."</chart>
	";

	$strXMLData = $strXMLData . chr(13);

}
