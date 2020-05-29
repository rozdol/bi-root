<?php

$user=$this->db->GetRow("select * from users where id=$uid");
$out.= "<h1>Profile of $user[surname]  $user[firstname]</h1>";
$out.= "<a href='?act=edit&what=profile'><span class='btn btn-info btn-mini'>Edit My Profile</span></a><br>";

if (!$GLOBALS[settings][simple_profile]) {
    $out.= $this->html->show_hide('Alerts sent', "?act=show&table=useralerts&showall=1&wasread=&unread=&from=$uid&to=&tablename=&nowrap=1");//?act=show&table=useralerts&showall=1&wasread=&unread=1&from=&to=&tablename=
    $out.= $this->html->show_hide('Alerts recieved', "?act=show&table=useralerts&showall=1&wasread=&unread=&from=&to=$uid&tablename=&nowrap=1");
    //$out.= $this->html->show_hide('My Internal Orders', "?act=show&table=documents&type=1658&belong=me&nowrap=1");
    //$out.= $this->html->show_hide('My Favorivtes', "?act=show&table=favorites&reference=&nowrap=1");
}

//if($access['main_admin'])$out.= manage_signs($id);

$sql="SELECT * FROM reports";
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);$start_time=$this->utils->get_microtime();
while ($row = pg_fetch_array($cur,NULL,PGSQL_ASSOC)){
	$i++;
	$homepage_id=$this->db->getval("SELECT id from homepages where user_id=$GLOBALS[uid] and report_id=$row[id]")*1;
	if($homepage_id==0){
		$vals=[
			'name'=>$row[name],
			'user_id'=>$GLOBALS[uid],
			'report_id'=>$row[id]
		];
		$homepage_id=$this->db->insert_db('homepages', $vals);
		$homepage=$this->data->detalize('homepages',$homepage_id);
		$out.="$i $row[name] -> $homepage<br>";
	}
	$homepage=$this->data->detalize('homepages',$homepage_id);
	$this->progress($start_time, $rows, $i, "$i / $rows");
	//$name=$this->data->detalize('table',$row[id]);
	//echo "$i $row[name] -> $homepage<br>";
}



$i=0;
$fields=array('#','id','name','s','a');
$out.=$this->html->tag($what,'foldered');
$out.=$this->html->tablehead('',$qry, $order, $addbutton, $fields,'autosort');

$sql="SELECT r.name, r.descr, r.link, hp.active, hp.sorting, r.id as report_id ,hp.id as homepage_id
FROM reports r
LEFT JOIN homepages hp ON r.id=hp.report_id
WHERE hp.user_id=$GLOBALS[uid]
ORDER BY hp.active DESC, hp.sorting ASC, r.name ASC";
$inline_edit=1;
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);$start_time=$this->utils->get_microtime();
while ($row = pg_fetch_array($cur,NULL,PGSQL_ASSOC)){
	$i++;
	$homepage=$this->data->detalize('homepages',$row[homepage_id]);
	$this->progress($start_time, $rows, $i, "$i / $rows");
	//$name=$this->data->detalize('table',$row[id]);
	if ($inline_edit>0) {
	    $submitdata=array(
	        'table'=>'homepages',
	        'field'=>'sorting',
	        'id'=>$row[homepage_id],
	    );

	    $class="#homepages_sorting_1_".$row[homepage_id];
	    $js1.=$this->utils->inline_js($class, $submitdata, 0);

	    $submitdata=array(
	        'table'=>'homepages',
	        'field'=>'active',
	        'id'=>$row[homepage_id],
	    );

	    $class="#homepages_active_1_".$row[homepage_id];
	    $js1.=$this->utils->inline_js($class, $submitdata, 0);
	}
	//$active_chk=$this->html->form_chekbox('active',$row[active],' ');
	$active_chk=($row[active]=='t')?'1':'0';
	$sorting= "<span id='homepages_sorting_1_".$row[homepage_id]."'>$row[sorting]</span>";
	$active= "<span id='homepages_active_1_".$row[homepage_id]."'>$active_chk</span>";
	$class='';
	if($row[active]=='t'){$class='bold';}
	$link="<a href='$row[link]'>$row[name]</a>";
	$out.= "<tr class='$class'>";
	$out.= "<td>$i</td>";
	$out.= "<td id='homepages:$row[homepage_id]' class='cart-selectable' reference='homepages'>$row[homepage_id]</td>";
	$out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$link</td>";
	$out.= "<td>$sorting</td>";
	$out.= "<td>$active</td>";
	//$out.=$this->html->HT_editicons($what, $row[id]);
	$out.= "</tr>";
}
$this->livestatus('');
$out.=$this->html->tablefoot($i, $totals, $totalrecs);

    $js="
	<script>
	$(document).ready(function() {
	     $js1
	 });
	</script>";
    $out.=$js;


if (getenv('MFA_AUTH')) {
    //require_once FW_DIR.'vendor'.DS.'PHPGangsta'.DS.'GoogleAuthenticator.php';

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
