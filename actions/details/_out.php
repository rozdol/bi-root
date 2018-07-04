<?php 
if($GLOBALS[access][main_admin]){
	$body.= "<span media='print' class='noPrint'><hr>";
	$body.= $this->html->show_hide("Logs $what:$id", "?act=show&what=logs&plain=1&refference=$what&ref_id=$id");
	$body.= $this->html->show_hide("Changes $what:$id", "?act=report&what=db_changes&plain=1&refference=$what&ref_id=$id");
	$body.= $this->html->show_hide("Access $what:$id", "?act=show&what=tableaccess&plain=1&tablename=$what&refid=$id");
	$body.= $this->html->show_hide("Clicks $what:$id", "?act=show&what=clicks&plain=1&refference=$what&ref_id=$id");
	$body.= "<hr></span>";
}
