<?php

	$data1=explode(',',$this->html->readRQcsv('data1'));
	$data2=explode(',',$this->html->readRQcsv('data2'));

	$d1=count($data1);
	$d2=count($data2);

	$union=array_unique(array_merge($data1, $data2));
	$diff=array_unique(array_diff($data1, $data2));
	$intersec=array_unique(array_intersect($data1, $data2));

	$u=count($union);
	$d=count($diff);
	$i=count($intersec);
	$union=implode(',',$union);
	$diff=implode(',',$diff);
	$intersec=implode(',',$intersec);
	$response.="Data1 ($d1)<br>";
	$response.="Data2 ($d2)<br><hr>";

	$response.="union($u)<br><textarea name='union' id='union' class='span12'>$union</textarea><br>";
	$response.="diff($d)<br><textarea name='diff' id='diff' class='span12'>$diff</textarea><br>";
	$response.="intersec($i)<br><textarea name='intersec' id='intersec' class='span12'>$intersec</textarea><br>";
	$out.= "$response";




$body.=$out;