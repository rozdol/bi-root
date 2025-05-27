<?php
if ($what == 'register') {
    $username=$this->html->readRQ('username');
    $pass1=$this->html->readRQ('password');
    $pass2=$this->html->readRQ('password2');
    if (!validate_pass($pass1)) {
        $out.= "<div class='alert alert-error span12'>Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit</div>";
        exit;
    }
    if ($pass1!=$pass2) {
        $out.= "<div class='alert alert-error span12'>Password does not match. Go back and try again</div>";
        exit;
    }

    $hash=create_hash($pass1);
    $firstname=$this->html->readRQ('firstname');
    $lastname=$this->html->readRQ('lastname');
    $email=$this->html->readRQ('email');
    $res=$this->db->GetRow("select * from users where username='$username'", true);
    $count=$res[id]*1;
    if ($count>0) {
        $out.= "<div class='alert alert-error span12'>Username already taken. Go back and use another one</div>";
        exit;
    }
    $today=$this->dates->F_date('', 1);
    $ip=$_SERVER['REMOTE_ADDR'];
    //$vars=array('username','password','email','firstname', 'lastname');
    $vals=array(
        'username'=>$username,
        'password_hash'=>$hash,
        'email'=>$email,
        'firstname'=>$firstname,
        'surname'=>$lastname,
        'active'=>0
        );
    $res=$this->db->insert_db('users', $vals);
    
    if ($res>0) {
        $vals2=array(
            'groupid'=>0,
            'userid'=>$res
            );
        $res2=$this->db->insert_db('user_group', $vals2, 1);
        
        $out.= "<div class='alert alert-info span12'>Dear $firstname $surname,<br>Your registration with id $res was successful.<br>Your access will be activated by sending an invitation to your email <b>$email</b> as soon as possible.</div>";
        $sys_id=$this->data->readconfig("system id");
        $this->comm->sms2admin("$sys_id:New user $username is registered");
        $this->comm->send_telegram_adm("$sys_id:New user $username is registered");
    } else {
        $out.= "<div class='alert alert-error span12'>Error:Insert into $what<br>SQL:<br>ID:$res</div>";
        exit;
    };
}

$body.=$out;
