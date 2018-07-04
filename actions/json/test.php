<?php
  if ($what=="test"){
	$JSONData=array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
	header('Content-type: application/json');
	echo json_encode($JSONData);
}