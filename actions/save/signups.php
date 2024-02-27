<?php
//Save signups

$id=$this->html->readRQn('id');
$name=$this->html->readRQ('name');
$surname=$this->html->readRQ('surname');
$email=$this->html->readRQ('email');
$password=$this->html->readRQ('password');
$password_confirm=$this->html->readRQ('password_confirm');
$ip=$this->html->readRQ('ip');
$exists=$this->db->getval("SELECT count(*) from signups where email='$email'")*1;
//if($exists>0)$this->html->error("$email alredy registered");
if ($name=='') {
    $this->html->error("Name is required");
}
if ($surname=='') {
    $this->html->error("Surname is required");
}
if ($email=='') {
    $this->html->error("Email is required");
}
if ($password=='') {
    $this->html->error("password is required");
}
if ($password!=$password_confirm) {
    $this->html->error("password verification is not the same");
}
//if (!$this->utils->validate_pass($password))$this->html->error("Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit");
$ip=$_SERVER['REMOTE_ADDR'];
//$verification_code=$this->utils->gen_Password(9,9,9);
$verification_code=md5(uniqid(time()));
$password_hash=$this->crypt->create_hash($password);
$vals=array(
    'name'=>$name,
    'surname'=>$surname,
    'email'=>$email,
    'verification_code'=>$verification_code,
    'password_hash'=>$password_hash,
    'exp_date'=>$this->db->getval("SELECT now() + INTERVAL '7 days'"),
    'ip'=>$ip,
    //'descr'=>$descr
);

//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;
if ($id==0) {
    $id=$this->db->insert_db($what, $vals);
} else {
    $id=$this->db->update_db($what, $id, $vals);
}


$verification_link="?act=tools&what=verify&email=$email&verification_code=$verification_code";

$content="Dear $name,<br>Check your email ($email) for verification link.<br>";
if ($GLOBALS['settings']['dev_mode']) {
    $content.=$verification_link;
}

$body='Dear '.$name.'.<br><br>Please visit <a href="'.$GLOBALS[URL].'?act=tools&what=verify&email='.$email.'&verification_code='.$verification_code.'">This Link</a> to verify your email address and activate your account.
'.$rus;
$from=$GLOBALS['settings']['brand_email'];
$subject=$GLOBALS['settings']['brand_name'].': e-mail verification';
$description='Your registration needs to be verified';

$emal_list="it@example.com;$email";
$emal_list_arr=explode(";", $emal_list);
foreach ($emal_list_arr as $email) {
    $i++;
    //echo "$i - $email<br>";
    //$this->utils->send_announcement_mail($email,$from,$subject,$description,$body);
    $this->utils->send_announcement($email, $from, $subject, $description, $body);
}

echo $this->html->tag($content, 'div', 'well');
exit;
$body.=$out;
