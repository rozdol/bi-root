<?php
$body=$strXMLData;
if($showplain==''){
header('Content-type: text/xml');
echo pack("C3",0xef,0xbb,0xbf);
$body=trim($strXMLData);
$body=$strXMLData;
}else {
	header('Content-type: text/html');
	echo "<textarea cols=160 rows=40>$strXMLData</textarea>";
}