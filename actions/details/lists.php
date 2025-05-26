<?php
if ($what == 'lists'){
		$res=$this->db->GetRow("select * from $what where id=$id");
		$out.="<h1>$res[name]</h1>\n";
		$res['descr']=str_ireplace("\n","<br>",$res['descr']);
		$out.= "$res[descr]";
		$_POST['list_id']=$id;
		$_POST['refid']=$id;
		$out.=$this->show('listitems');
		if($access['edit_lists'])$out.= "<a href='?act=add&what=import_lists&refid=$id' class='btn btn-mini2 btn-info'>Import list</a>";
	}
	
$body.=$out;
