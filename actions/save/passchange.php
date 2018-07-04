<?php
if ($what == 'passchange'){
	$oldpass=md5($this->html->readRQ('oldpass'));	
	$pass1=md5($this->html->readRQ('pass1'));
	$pass2=md5($this->html->readRQ('pass2'));
	$pass=md5($this->html->readRQ('pass1'));
	
		
	$sql="select password from users where id=$uid";
	$res = $this->db->GetVar($sql);
	if(!$res==$oldpass){$out.= "<div class='alert alert-error span12'>Old password is wrong!</div>";exit;}
	if(!$pass1==$pass2){$out.= "<div class='alert alert-error span12'>New password does not match!</div>";exit;}
	if (!validate_pass($pass)) {$out.= "<div class='alert alert-error span12'>Password must be 8-16 characters and have at least one lowercase, one uppercase and one digit</div>"; exit;}
	$query = "UPDATE users set password='$pass1' where id=$uid;";
	$result = $this->db->GetVar($query);
	$logtext.=" name=$username fullname=$fullname";

}

$body.=$out;
