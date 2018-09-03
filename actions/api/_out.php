<?php
if($GLOBALS[offline_messages]){
	$JSONData['offline_messages']=$GLOBALS[offline_messages];
}
echo json_encode($JSONData);
exit;
