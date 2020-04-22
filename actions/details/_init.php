<?php
if($this->data->table_exists($what)){
	$count=$this->db->GetVal("select count(*) from $what where id=$id")*1;
	if($count==0){
		//if($GLOBALS[access][main_admin]){
			$out.= "<span media='print' class='noPrint'><hr>";
			$out.= $this->html->show_hide("Logs $what:$id", "?act=show&what=logs&plain=1&refference=$what&ref_id=$id");
			$out.= $this->html->show_hide("Changes $what:$id", "?act=report&what=db_changes&plain=1&refference=$what&ref_id=$id");
			$out.= $this->html->show_hide("Access $what:$id", "?act=show&what=tableaccess&plain=1&tablename=$what&refid=$id");
			$out.= $this->html->show_hide("Clicks $what:$id", "?act=show&what=clicks&plain=1&refference=$what&ref_id=$id");
			$out.= "<hr></span>";
			echo "$out<br>";
		//}

		$this->html->error($this->html->message("Record id=$id from [$what] not found.","404",'alert-error'));
	}
}
