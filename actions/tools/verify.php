<?php 
$verification_code=$this->html->readRQ('verification_code');
$email=$this->html->readRQ('email');
if(($verification_code=='')||($email==''))$this->html->error("Wrong data");
$signup=$this->db->getrow("SELECT * from signups where email='$email' and verification_code='$verification_code'");
if($signup[id]>0){

	if($signup[verified]=='t')$this->html->error("Your email $email han been already vefied");
	$descr="Verified from IP address: ".$_SERVER['REMOTE_ADDR'];
	//echo $this->html->pre_display($signup,"signup");
	$user_vals=[
		'username'=>$signup[email],
		'email'=>$signup[email],
		'password_hash'=>$signup[password_hash],
		'firstname'=>$signup[name],
		'surname'=>$signup[surname],
		'regdate'=>$GLOBALS[today],
		'active'=>1,
	];
	$user_id=$this->db->insert_db('users',$user_vals);
	$this->db->getval("INSERT into user_group (groupid,userid) VALUES (3,$user_id)");
	$vals=array(
		'verified'=>1,
		'rev_date'=>$this->db->getval("SELECT now()"),
		'descr'=>$descr,
		'user_id'=>$user_id
	);
	$this->db->update_db('signups',$signup[id],$vals);
	$content="Dear $signup[name],<br>Your email is verified.<br>Please <a href='?act=welcome'>Sign In</a>";
	echo $this->html->tag($content,'div','well');
	


}else{
	$this->html->error("Verification failed");
}

