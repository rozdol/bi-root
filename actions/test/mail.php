<?php
$status=$this->comm->sendgrid('test:'.$GLOBALS['settings']['system_email'], "Tester:".$GLOBALS['settings']['test_email'], "Testing ".$GLOBALS[settings][rnd], "Test Text ".$GLOBALS[settings][rnd]);
if ($status==1) {
    echo $this->html->message('Massage sent');
} else {
    $this->html->error('Massage failed to send');
}
