<?php
if ($what == 'comments'){
		$res=$this->db->GetRow("select * from comments where id=$id");
		$typename=$this->db->GetVal("select name from listitems where id=$res[type]");
		$out.="<h1>$res[name] ($typename)</h1>\n";
		$out.= "Dated: <b>$res[date]</b><br>";

		$out.= "Controller: <b>$res[controller]</b><br>";
		$out.= "Ref:$res[tablename]($res[refid]) - <a href='?act=details&what=$res[tablename]&id=$res[refid]'>".$this->data->get_name($res[tablename],$res[refid])."</a><br>";
		$out.= "<b>Comments:</b><br> <span class='comments'>$res[descr]</span><br>";


		$_POST[tablename]=$what;
		$_POST[refid]=$id;
		$_POST[reffinfo]="&tablename=$what&refid=$id";
		$out.=$this->show('comments');
		$out.=$this->show('uploads');
	}
	
$body.=$out;
