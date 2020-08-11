<?php
if (!$access['main_admin']) {
    $this->html->error('Honey pot');
}
    
    $data=array(
        'ref_table'=>'documents',
        'ref_id'=>444,
    );
    
    $data_json=json_encode($data);
    echo $this->html->dropzoneJS($data_json, 'test', 'Here');
    exit;
    
    echo $this->html->cameraJS();
    exit;
    $out='
		<form action="?act=save&csrf='.$GLOBALS[csrf].'&what=webcam" method="post">
		    <input type="text" name="myname" id="myname">
		    <input type="submit" name="send" id="send">
		</form>

		<script type="text/javascript" src="assets/js/webcam.js"></script>
		<script language="JavaScript">
				document.write( webcam.get_html(320, 240) );
		</script>
		<form>
				<input type=button value="Configure..." onClick="webcam.configure()">
				&nbsp;&nbsp;
				<input type=button value="Take Snapshot" onClick="take_snapshot()">
			</form>
	';
    $js="<script>
	        webcam.set_api_url( '?act=save&what=webcam&plain=1' ); // send captured picture to this file
	        webcam.set_quality( 90 ); // JPEG quality (1 - 100)
	        webcam.set_shutter_sound( false ); // play shutter click sound

	        webcam.set_hook( 'onComplete', 'my_completion_handler' );

	        function take_snapshot() {
	            // take snapshot and upload to server
	            document.getElementById('upload_results').innerHTML = 'Snapshot<br>'+
	            '<img src=\"assets/img/loadingsmall.gif\">';
	            webcam.snap();
	        }

	        function my_completion_handler(msg) {
	            // extract URL out of PHP output
	            if (msg.match(/(http\:\/\/\S+)/)) {
	                var image_url = RegExp.$1;
	                // show JPEG image in page
	                document.getElementById('upload_results').innerHTML = 
	                    'Snapshot<br>' + 
	                    '<a href=\"'+image_url+'\" target\"_blank\"><img src=\"' + image_url + '\"></a>';

	                // reset camera for another shot
	                webcam.reset();
	            }
	            else alert(\"PHP Error: \" + msg);
	        }
	    </script>";

    $out.=$js;

    $out.='<div id="upload_results" style="background-color:#eee;"></div>';
    //echo $out;
    
    //QR
    
    /*
    //require_once '../PHPGangsta/GoogleAuthenticator.php';
    require_once FW_DIR.'vendor'.DS.'PHPGangsta'.DS.'GoogleAuthenticator.php';

    $ga = new PHPGangsta_GoogleAuthenticator();

    $secret = $ga->createSecret();
    $secret = "3KPMJWWHI3REUAF3";


    $qrCodeUrl = $ga->getQRCodeGoogleUrl('IS-'.$GLOBALS['uid'], $secret);

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

    */
    //echo $out;
    
    
    //$service=$this->project->get_service_byname('disbursements',3500);
    //echo $this->html->pre_display($service,'service');
    //exit;
    //$test=$this->project->calc_inv_comission(1190,1000,0.19);
    ///echo $this->html->pre_display($test,'test');
    
    /*
    $from=601;
    $to=600;
    $amount=1000;
    $result=round($this->data->convert_currency($amount,$from,$to,'28.11.2011'),2);
    $from=$this->data->get_name('listitems',$from);
    $to=$this->data->get_name('listitems',$to);
    echo "$amount -> $result ($from - > $to)";
    */
    /*
    $id=5775;
    $subsids=array();
    $result=array();
    global $subsids_ids;
    $subsids_ids=array();
    $this->project->get_partner_subsid_ids($id,$subsids);
    echo $this->html->pre_display($subsids_ids,'$subsids_ids');
    */
        
    //echo $this->utils->findnumber("COMM PURCH 1.2M ADR GAZPR");
    /*
    $partnerid=992;
    $arr=array();
    $pids=$this->utils->F_toarray($this->db->getResults("SELECT distinct id from transactions where sender=$partnerid and lower(bankdescr) like '%securities purchase%' order by id asc")) ;
    foreach($pids as $pid){
        $bankdescr=$this->data->get_val('transactions','bankdescr',$pid);
        $split=explode('Securities Purchase',$bankdescr);
        $split2=explode(' - ',$split[0]);
        $name=$split2[1];
        if(!in_array($name,$arr))$arr[]=$name;
    }
    sort($arr);
    foreach($arr as $itm){
        echo "$itm<br>";
    }
    */
    /*
    $sql="select * from users where active=1 order by id";
    $body.="<div class='green'>";
    if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
    while ($row = pg_fetch_array($cur)) {
        $user=$this->data->username($row[id]);
        $pass=$this->utils->gen_Password();
        $txt.= "$user\t$row[username]\t$pass<br>";
    }
    $out.="<textarea>$txt</textarea>";
    */

    $body.=$out;
