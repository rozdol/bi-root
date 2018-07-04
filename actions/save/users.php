<?php
//echo $this->html->pre_display($_POST,'post'); exit;
if (($what == 'users')&&(($access['main_admin'])||($id==$uid))) {
    $username=$this->html->readRQ('username');
    $pass1=$this->html->readRQ('password');
    $pass2=$this->html->readRQ('password2');
    $active=$this->html->readRQn('active');
    $mobile=$this->html->readRQ('mobile');
    if ((!$this->utils->validate_pass($pass1))&&($pass1!='')) {
        echo "<div class='alert alert-error span12'>Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit</div>";
        exit;
    }
    if (($pass1!=$pass2)&&($pass2!='')) {
        echo "<div class='alert alert-error span12'>Password does not match. Go back and try again</div>";
        exit;
    }

    if ($pass1!='') {
        $hash=$this->crypt->create_hash($pass1);
    }
    $firstname=$this->html->readRQ('firstname');
    $surname=$this->html->readRQ('surname');
    $email=$this->html->readRQ('email');
    $res=$this->db->GetRow("select * from users where username='$username'", true);
    $count=$res[id]*1;
    if (($count>0)&&($id==0)) {
        echo "<div class='alert alert-error span12'>Username already taken. Go back and use another one</div>";
        exit;
    }
    $today=$this->dates->F_date('', 1);
    $ip=$_SERVER['REMOTE_ADDR'];
    //$vars=array('username','password','email','firstname', 'lastname');
    $vals=array(
        'username'=>$username,
        'email'=>$email,
        'firstname'=>$firstname,
        'surname'=>$surname,
        'active'=>$active,
        'mobile'=>$mobile,

        );
        
        $items = array("password_hash" => "$hash");
    if ($pass1!='') {
        $this->utils->array_push_associative($vals, $items);
    }
    if ($id==0) {
        $id=$this->db->insert_db($what, $vals);
        $smstext=APP_NAME.": New user $username is registered ($firstname $surname).";
    } else {
        $id=$this->db->update_db($what, $id, $vals);
        $smstext=APP_NAME.": $username had changed his profile ($firstname $surname).";
    }
    
    $has=$this->html->readRQ('password');
    $pass1=$this->html->readRQ('password');
    $pass2=$this->html->readRQ('password2');
    if ($has<>'') {
        if (!$this->utils->validate_pass($has)) {
            $out.= "<div class='alert alert-error span12'>Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit</div>";
            $err++;
        }
        if (($pass1!=$pass2)&&(!$access['main_admin'])) {
            $out.= "<div class='alert alert-error span12'>New password does not match!</div>";
            $err++;
        }
        $hash=$this->crypt->create_hash($pass1);
        
        $query = "UPDATE users set password_hash='$hash' where id=$uid;";
        if ($err<1) {
            $result = $this->db->GetVar($query);
        }
    }
    
    
    if ($id>0) {
        $groupid=$this->html->readRQn('groupid');
        $this->db->GetVal("delete from user_group where userid=$id");
        $vals2=array(
            'groupid'=>$groupid,
            'userid'=>$id,
            );
        $res2=$this->db->insert_db('user_group', $vals2, 1);
        echo "<div class='alert alert-info span12'>Dear $firstname $surname,<br>Your registration with id $res[id] was successful.<br>Your access will be activated by sending an invitation to your email <b>$email</b> as soon as possible.</div>";
        $this->comm->sms2admin($smstext);
    } else {
        echo "<div class='alert alert-error span12'>Error:Insert into $what<br>SQL:<br>ID:$res</div>";
        exit;
    };
    $GLOBALS[no_refresh]=1;
}


$body.=$out;
