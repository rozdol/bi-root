<?php
if ($access['main_admin']){
		$sql="SELECT firstname||' '||surname FROM users where id=$id";
		$res=$this->db->GetVal($sql);
		$_POST[nomaxid]="$id";
		$_POST[refid]="$id";
		$_POST[reference]="$what";
		$_POST[span]="users";
		$_POST[title]="APIS of $res";
		$out.=$this->data->details_bar($what, $id, '');
		if($this->data->table_exists('apis')){
			//$out.=$this->html->tag("APIS",'h2','class');
			$out.=$this->show('apis');
		}
		$_POST[title]="LOG of $res";
		//$out.= "Permissions for username <b>$line[0]</b><br>";
		//require($root_path . 'index.'.$phpEx);
		
		//$out.=$this->show('usersdet');
		//$out.= $this->html->show_hide("LOG of $res",     "?act=show&what=usersdet&plain=1&nomaxid=1&refid=$id");
		$out.= $this->html->show_hide("Deletes of $res", "?act=show&what=usersdet&plain=1&nomaxid=1&refid=$id&action=DELETE from");
		$out.= $this->html->show_hide("Inserts of $res", "?act=show&what=usersdet&plain=1&nomaxid=1&refid=$id&action=INSERTED");
		$out.= $this->html->show_hide("Edits of $res", "?act=show&what=usersdet&plain=1&nomaxid=1&refid=$id&action=EDIT");
		//$out.= $this->html->show_hide("Partner access of $res", "?act=report&what=tableaccess&plain=1&nomaxid=1&id=$id");
		$out.= $this->html->show_hide("History trail of $res", "?act=show&what=tableaccess&plain=1&nomaxid=1&userid=$id");
		$out.= $this->html->show_hide("Clicks of $res", "?act=show&what=clicks&plain=1&nomaxid=1&uid=$id");
		//$out.= $this->html->show_hide("History trail $res", "?act=show&what=tableaccess&plain=1&nomaxid=1&userid=$id");
		//$out.= "<span class='btn btn-small btn-danger' onclick=\"confirmation('?act=tools&what=reset_allowed_pids&user_id=$id','Are you sure you what to RESET allowed partners?')\">Reset Allowed Partners</span> ";
	}
	/*
	$form.=$this->html->form_start('allowed_pids_2user',0,'no_title');
	$form.=$this->html->form_hidden('user_id',$id);
	$form.=$this->html->tag('Select administrator');						
	$partner_id=$this->data->partner_form('partner_id',$res[partner_id],'partner'); $form.="$partner_id[out]";
	$form.=$this->html->form_textarea('pids','','List of Partner IDs');
	$form.=$this->html->form_chekbox('add_related','1','Add related');
	$form.=$this->html->form_submit('Add partners');
	$form.=$this->html->form_end();
	
	$out.=$this->html->collapse($form,'Add allowed partners by administrator',0);
	
	$pids=$this->data->get_list_csv("select partner_id from allowed_pids where type_id=10800 and user_id=$id");
	
	$out.=$this->html->form_textarea('dummy',$pids,'List of direct Partner IDs');
	
	$_POST[title]="";
	$out.=$this->show('allowed_pids');
	*/
	
$body.=$out;
