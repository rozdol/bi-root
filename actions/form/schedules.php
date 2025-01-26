<?php
if ($what == 'schedules') {
        $refid=$this->html->readRQn("refid");
        $tablename=$this->html->readRQ("reference");
    if ($tablename=='') {
        $tablename=$this->html->readRQ("tablename");
    }
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
            $userslist=$res[usersinvolved];
            $userslist2=$this->utils->normalize_list($userslist);
            $usersnamelist="";
        if ($userslist2!='') {
            $usersnamelist=$this->utils->F_tostring($this->db->GetResults("select username from users where id in ($userslist2);"));
        }
            $refid=$res[refid];
            $tablename=$res[tablename];
    } else {
        $res[type]=0;
        $res[qty]=1000;
        $userslist="$uid,";
        $usersnamelist=$this->utils->F_tostring($this->db->GetResults("select username from users where id=$uid;"));
    }
            $ref=$this->db->GetRow("select * from $tablename where id=$refid; -- chedules");
    if (($res[active]=='t')||($res[active]=='')) {
        $checked='checked';
    } else {
        $checked='';
    }
    if (($res[confidential]=='t')||($res[confidential]=='')) {
        $cchecked='checked';
    } else {
        $cchecked='';
    }
    if (($res[confirm]=='t')) {
        $fchecked='checked';
    } else {
        $fchecked='';
    }
    if ($res[makeintorders]=='t') {
        $iochecked='checked';
    } else {
        $iochecked='';
    }
    if ($res[makerequests]=='t') {
        $crchecked='checked';
    } else {
        $crchecked='';
    }
            
    if ($res[send_sms]=='t') {
        $sschecked='checked';
    } else {
        $sschecked='';
    }
    if ($res[send_mail]=='t') {
        $smchecked='checked';
    } else {
        $smchecked='';
    }
            
            $sql="SELECT id, surname||' '||firstname||' '||id FROM users where active='1' and id>0 order by surname, firstname";
            $userid=$this->html->htlist('userid', $sql, $id, 'Select User', "onchange='itemid=this.options[this.selectedIndex].value;
			itemname=this.options[this.selectedIndex].text;
			document.getElementById(\"userslist\").innerHTML+=itemid+\", \";
			document.getElementById(\"usersnamelist\").innerHTML+=itemname+\", \";'");

            
            $type=$this->html->htlist('type', "SELECT id, name from listitems WHERE list_id=30 ORDER by id", $res[type], 'Select type');

            $out.= "
			  
				<div id='stylized' class='well'>
				
				  <form id='form1' name='form1' method='post' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post'>
				    <h1>$action $what</h1>
				    <p>Ref $tablename $ref[name]</p>    
					<input type='hidden' name='debug' value='0'>
					<input type='hidden' name='id' value='$id'>
					<input type='hidden' name='refid' value='$refid'>
					<input type='hidden' name='tablename' value='$tablename'>       

					    
					   <label>Name</label><input type='text' name='name'  value='$res[name]'>
					<label>Type</label>$type
						<label>Interval (days)</label><input type='text' name='interval'  value='$res[interval]'>
						<label>Qty (times)</label><input type='text' name='qty'  value='$res[qty]'>
						<dt><label>Next date</label><input name='nextdate' value='$res[nextdate]' data-datepicker='datepicker' class='date' type='text' placeholder='DD.MM.YYYY'/></dt>
						<div id='hiddenfield' style='display: none;'>
						<label>Users:</label><textarea name='userslist' id='userslist'>$userslist</textarea>
						</div>
						<label>Users:<br><img src='".ASSETS_URI."/assets/img/custom/cancel.png' onclick='document.getElementById(\"userslist\").innerHTML=\"\";
						document.getElementById(\"usersnamelist\").innerHTML=\"\";'></label><textarea name='usersnamelist' id='usersnamelist' >$usersnamelist</textarea>
					    <label>Users</label>$userid
										
					 
						
					   <label>Description</label><textarea name='descr' >$res[descr]</textarea>
					  <label class='checkbox'>Make internal Orders<input type='checkbox' name='makeintorders' value='1' $iochecked /></label>
					<label class='checkbox'>Make Client Request<input type='checkbox' name='makerequests' value='1' $crchecked /></label>
					   <label class='checkbox'>Confidential<input type='checkbox' name='confidential' value='1' $cchecked /></label>
					   <label class='checkbox'>Force Confirmation<input type='checkbox' name='confirm' value='1' $fchecked /></label>
					
					<label class='checkbox'>Send SMS<input type='checkbox' name='send_sms' value='1' $sschecked /></label>
					<label class='checkbox'>Send e-Mail<input type='checkbox' name='send_mail' value='1' $smchecked /></label>
					
					   <label class='checkbox'>Active<input type='checkbox' name='active' value='1' $checked /></label>
					".$this->html->form_confirmations()."
				<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
				    <div class='spacer'></div>
				  </form>
				</div>
				";
}
    
    
    
$body.=$out;
