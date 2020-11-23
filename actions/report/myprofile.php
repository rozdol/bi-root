<?php

$user=$this->db->GetRow("select * from users where id=$uid");
$out.= "<h1>".\util::l('Profile of')." $user[surname]  $user[firstname]</h1>";
$out.=$this->edit('home_page_reports');
$out.= "<a href='?act=edit&what=profile'><span class='btn btn-info btn-mini'>".\util::l('Edit My Profile')."</span></a><br>";

//if($access['main_admin'])$out.= manage_signs($id);

if (!$GLOBALS[settings][simple_profile]) {
    $out.= $this->html->show_hide('Alerts sent', "?act=show&table=useralerts&showall=1&wasread=&unread=&from=$uid&to=&tablename=&nowrap=1");//?act=show&table=useralerts&showall=1&wasread=&unread=1&from=&to=&tablename=
    $out.= $this->html->show_hide('Alerts recieved', "?act=show&table=useralerts&showall=1&wasread=&unread=&from=&to=$uid&tablename=&nowrap=1");
    //$out.= $this->html->show_hide('My Internal Orders', "?act=show&table=documents&type=1658&belong=me&nowrap=1");
    //$out.= $this->html->show_hide('My Favorivtes', "?act=show&table=favorites&reference=&nowrap=1");
}

if (getenv('MFA_AUTH')||($GLOBALS[settings][use_mfa])) {
    //require_once FW_DIR.'vendor'.DS.'PHPGangsta'.DS.'GoogleAuthenticator.php';
	include_once(CLASSES_DIR.'/PHPGangsta/GoogleAuthenticator.php');
    $ga = new PHPGangsta_GoogleAuthenticator();

    if ($user['ga']=='') {
        $secret = $ga->createSecret();
        $this->db->getVal("update users set ga='$secret' where id=$uid");
    } else {
        $secret=$user['ga'];
    }

    //var_dump($this->utils->reserved_ip($_SERVER['REMOTE_ADDR']));


    $qrCodeUrl = $ga->getQRCodeGoogleUrl($GLOBALS[app_name].':'.$user['username'], $secret);

    $oneCode = $ga->getCode($secret);

    $out.='
	<br><h2>Multi-factor authentication code</h2><div class="well">

	<strong>Scan the QR code using <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">Google Play</a> 
	or <a target="_blank" href="https://itunes.apple.com/en/app/google-authenticator/id388497605?mt=8">AppStore</a>
	<br>
	<img src=\''.$qrCodeUrl.'\'/>
	<br>
	</div>

		<h4>Time-Based One time password check</h4>
	<div class="well">
	 <br>
	<strong>Enter OTP from your app: </strong><br>
	<input type="text" name="otp" id="otp" value="" /> <br>
	<button class="btn btn-info" id="verify">Verify OTP</button>

	<div id ="status">


	</div>
	<br><br>

	</div>
	';

    $GLOBALS['js']['footer'][]='<script>
	$(document).ready(function()
	{
		$("#verify").click(function()
		{
			$("#status").html("<br><div class=\'alert\'>Verifying "+$("#otp").val()+"...</div>");

			$.post("?act=json&what=google_auth",
			{otp:$("#otp").val()},
			function(data, textStatus, jqXHR)
			{
				if(data.error == 0)
				$("#status").html("<br><div class=\'alert alert-success\'>Verification Success</div>");
				else
				$("#status").html("<br><div class=\'alert alert-danger\'>Verification Failed</div>");

			},"json");

		});

	});
	</script>';
}


$body.=$out;
