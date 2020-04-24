<?php
if ($access['main_admin']){
	
	//require_once '../PHPGangsta/GoogleAuthenticator.php';
	require_once FW_DIR.'vendor'.DS.'PHPGangsta'.DS.'GoogleAuthenticator.php'; 

	$ga = new PHPGangsta_GoogleAuthenticator();

	$secret = $ga->createSecret();
	$secret = "3KPMJWWHI3REUAF3";


	$qrCodeUrl = $ga->getQRCodeGoogleUrl($GLOBALS[db_name].':'.$GLOBALS['username'], $secret); // change db_name to db_name

	$oneCode = $ga->getCode($secret);
	

	
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
	
	$out='
	<div class="well">

	<strong>Scan the QR code using <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">Google Play</a> 
	or <a target="_blank" href="https://itunes.apple.com/en/app/google-authenticator/id388497605?mt=8">AppStore</a>
	<br>
	<img src=\''.$qrCodeUrl.'\'/>
	<br>
	Secret is: '.$secret.'<br>
	Code is: '.$oneCode.'<br><br>
	</div>
	<br><br>
	
	<div class="well">
	 <br>
	<strong>Enter OTP from your app: </strong>
	<input type="text" name="otp" id="otp" value="" /> <br>
	<button class="btn btn-info" id="verify">Verify OTP</button>

	<div id ="status">


	</div>
	<br><br>

	</div>
	';
}
$body.=$out;