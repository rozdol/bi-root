<?php
$status=$this->comm->sendgrid('FastConsent:fastconsent@gmail.com', "alex Titov:rozdol@gmail.com", "Testing ".$GLOBALS[settings][rnd], "Test Text ".$GLOBALS[settings][rnd]);
if ($status==1) {
    echo $this->html->message('Massage sent');
} else {
    $this->html->error('Massage failed to send');
}
