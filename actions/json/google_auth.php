<?php
$err=1;
require_once FW_DIR.DS.'classes'.DS.'PHPGangsta'.DS.'GoogleAuthenticator.php';
$ga = new PHPGangsta_GoogleAuthenticator();

$secret = $this->data->get_val('users','ga', $GLOBALS['uid']);

$oneCode=$this->html->readRQ('otp');
$checkResult = $ga->verifyCode($secret, $oneCode, 0);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
    $err=0;
} else {
    $err=1;
}

$JSONData=array('error' => $err);
header('Content-type: application/json');
echo json_encode($JSONData);