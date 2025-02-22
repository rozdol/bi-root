<?php
if ($what == 'useralerts') {
    $res2 = $this->db->GetRow("select * from cashregisters where id=$refid");
    if ($act == 'edit') {
        $sql = "select * from $what WHERE id=$id";
        $res = $this->utils->escape($this->db->GetRow($sql));
    } else {
        $res['registerid'] = $refid;
        $res['date'] = $this->dates->F_date("", 1);
        $no = $this->db->GetVal("SELECT max(id) FROM cashtransactions");
        $no += 1;
        $no = sprintf("%05s", $no);
        $y = substr($thisyear, 3, 1);
        $res['name'] = "CASH-$no";
        //$res[name]=$this->db->GetVal("select name from $what order by id desc limit 1;");
    }
    $userid = $this->html->htlist('userid', "SELECT id, surname||' '||firstname FROM users where active='1' and id>0 order by surname, firstname", $res['userid'], 'Select User');
    $out .= "<div id='stylized' class='well'>
			
			
			  <form id='form1' name='form1' method='post' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post'>
			    <h1>$action $what</h1>
			    <p>Manage $what</p>   
				<input type='hidden' name='id' value='$id'>       
			    <dl>
			    <dt><label>Employee</label>$userid</dt>
			    </dl>

			    <dt><label>Description:</label><textarea name='descr' >$res[descr]</textarea></dt>
				<dt><label>Add info:</label><textarea name='addinfo' >$res[addinfo]</textarea></dt>			
				<div class='spacer'></div>
				" . $this->html->form_confirmations() . "
				<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button><br>
			    <div class='spacer'></div>
			  </form>
			</div>
			";
    //$out.= "$sql";
}


$body .= $out;
