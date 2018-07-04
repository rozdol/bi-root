<?php
//--------------------------
if (($what == 'groupaccess')&&($access['main_admin'])){
	$sql="SELECT name FROM groups where id=$id";
	$line=$this->db->GetVar($sql);
	echo "Permissions for group <b>$line</b><br>";
	//require($root_path . 'index.'.$phpEx);
	$_POST[refid]="$id";
	$_POST[active]=$this->html->readRQn('active');
	reset($_POST[what]);
	$out.=$this->show('accesslevel');
	//DB_show('accesslevel');

}
$body.="$out $nav $export";