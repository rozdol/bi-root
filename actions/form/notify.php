<?php
if ($what == 'notify') {
        $refid=$this->html->readRQn("refid");
        $tablename=$this->html->readRQ("tablename");
    if ($act=='edit') {
            $sql="select * from $tablename WHERE id=$refid";
            $res=$this->utils->escape($this->db->GetRow($sql));
            $partnerslist=$this->utils->F_tostring($this->db->GetResults("select partnerid from project2partner where projectid=$id"));
            $partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select p.name from partners p, project2partner d where d.projectid=$id and d.partnerid=p.id"));
    } else {
        $partnerslist="$refid,";
        $partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select p.name from partners p where p.id=$refid"));
        $res[type]=0;
    }
            $sendalert="<dt><label><input type='checkbox' name='sendalert' value='1' checked/> Send alert to selected</label></dt>";
            $smsalert="<dt><label><input type='checkbox' name='sendsms' value='1' /> Send SMS to Selected</label></dt>";
            $mailalert="<dt><label><input type='checkbox' name='sendmail' value='1' /> Send Mail to Selected</label></dt>";
            //$makeintorder="<dt><label>Generate internal order</label><input type='checkbox' name='makeintorder' value='1' /></dt>";
            
    if (($res[active]=='t')||($res[active]=='')) {
        $checked='checked';
    } else {
        $checked='';
    }
    if (($res[confirm]=='t')) {
        $fchecked='checked';
    } else {
        $fchecked='';
    }
            $sql="SELECT id, name FROM groups ORDER by name";
            $groupid=$this->html->htlist('groupid', $sql, $id, 'Select Group', "onchange='itemid=this.options[this.selectedIndex].value;
			itemname=this.options[this.selectedIndex].text;
			document.getElementById(\"groupslist\").innerHTML+=itemid+\", \";
			document.getElementById(\"groupsnamelist\").innerHTML+=itemname+\", \";'");
            
            $sql="SELECT id, surname||' '||firstname||' ('||username||')' as name FROM users ORDER by surname";
            $userid=$this->html->htlist('userid', $sql, $id, 'Select User', "onchange='itemid=this.options[this.selectedIndex].value;
			itemname=this.options[this.selectedIndex].text;
			document.getElementById(\"userslist\").innerHTML+=itemid+\", \";
			document.getElementById(\"usersnamelist\").innerHTML+=itemname+\", \";'");
            
            $type=$this->html->htlist('type', "SELECT id, name from listitems WHERE list_id=27 ORDER by id", $res[type], 'Select Type');

            $out.= "
				<div id='stylized' class='well'>
				
				  <form id='form1' name='form1' method='post' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post'>
				    <h1>Send notification</h1>
				    <p>$tablename  $res[name]</p>    
					<input type='hidden' name='tablename' value='$tablename'>
					<input type='hidden' name='refid' value='$refid'>       
					   <label>Name</label><input type='text' name='name'  value='From $tablename $res[name]'>
					
					   <label>Text</label><textarea name='descr' ></textarea>
					 <div class='spacer'></div>
					
					<div id='hiddenfield' style='display: none;'>
					<dt><label>Groups:</label><textarea name='groupslist' id='groupslist'>$groupslist</textarea></dt>
					</div>
					<dt><label>Groups:<br><img src='".ASSETS_URI."/assets/img/custom/cancel.png' onclick='document.getElementById(\"groupslist\").innerHTML=\"\";
					document.getElementById(\"groupsnamelist\").innerHTML=\"\";'></label><textarea name='groupsnamelist' id='groupsnamelist' >$groupsnamelist</textarea></dt>
				    <dt><label>Groups</label>$groupid</dt>
				 <div class='spacer'></div>
				
						<div id='hiddenfield' style='display: none;'>
						<dt><label>Users:</label><textarea name='userslist' id='userslist'>$userslist</textarea></dt>
						</div>
						<dt><label>Users:<br><img src='".ASSETS_URI."/assets/img/custom/cancel.png' onclick='document.getElementById(\"userslist\").innerHTML=\"\";
						document.getElementById(\"usersnamelist\").innerHTML=\"\";'></label><textarea name='usersnamelist' id='usersnamelist' >$groupsnamelist</textarea></dt>
					    <dt><label>Users</label>$userid</dt>
						$sendalert
					   	$smsalert
					$mailalert
						$makeintorder
						<dt><label><input type='checkbox' name='confirm' value='1' $fchecked/> Force Confirmation</label></dt>
					".$this->html->form_confirmations()."
				<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
				    <div class='spacer'></div>
				  </form>
				</div>
				";
}
    
    
$body.=$out;
