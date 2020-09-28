<?php
if ($what == 'profile') {
    $email=$this->html->readRQ('email');
    $firstname=$this->html->readRQ('firstname');
    $surname=$this->html->readRQ('surname');
    $lang=$this->html->readRQ('lang');
    $css=$this->html->readRQ('css');
    $mainscreen=$this->html->readRQ('mainscreen');
    $pdffont=$this->html->readRQ('pdffont');
    $mobile=$this->html->readRQ('mobile');
    $pdffontsize=$this->html->readRQ('pdffontsize');
    $rows=$this->html->readRQ('rows');
    $maxdescr=$this->html->readRQ('maxdescr');
    $ulevel=$this->html->readRQ('ulevel');
    $vals=array(
            'firstname'=>$firstname,
            'surname'=>$surname,
            'email'=>$email,
            'rows'=>$rows,
            'maxdescr'=>$maxdescr,
            'mobile'=>$mobile,
            'lang'=>$lang,
    );
    $err=0;
    $id=$this->db->update_db('users', $uid, $vals);
    //$query = "UPDATE users set email='$email',firstname='$firstname',surname='$surname', lang='$lang',css='$css',mainscreen='$mainscreen',pdffont='$pdffont',pdffontsize='$pdffontsize',rows=$rows, maxdescr=$maxdescr, mobile='$mobile'  where id=$uid;";
    //$result = $this->db->GetVar($query);
    $has=$this->html->readRQ('password');
    $pass1=$this->html->readRQ('password');
    $pass2=$this->html->readRQ('password2');
    $username=$this->data->get_val('users', 'username', $uid);
    if ($has<>'') {
        if (!$this->utils->validate_pass($has)) {
            $out.= "<div class='alert alert-error span12'>Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit</div>";
            $err++;
        }
        if ($pass1!=$pass2) {
            $out.= "<div class='alert alert-error span12'>New password does not match!</div>";
            $err++;
        }
        $hash=$this->crypt->create_hash($pass1);
        
        $query = "UPDATE users set password_hash='$hash' where id=$uid;";
        if ($err<1) {
            $result = $this->db->GetVar($query);
            $smstext=APP_NAME.": $username had changed his password ($firstname $surname).";
            $this->comm->sms2admin($smstext);
        }
    }
    if ($err>0) {
        $GLOBALS[no_refresh]=1;
    }


    $logtext.=" name=$username fullname=$fullname";
}
$body.=$out;
