<?php

$list=array(
			'Default'=>'',
			'By Date ascending'=>'date asc, id asc',
			'By Date descending'=>'date desc, id desc',
			'By Object'=>'what asc, id asc',
			'By IP'=>'ip asc, id asc',
		);
$sorting=$this->html->dropdown_list_array("Sort by", "sortby", $list);

$this->livestatus("Preparing table list...");
$sql="SELECT distinct tablename, tablename||' - '||count(*),count(*) as qty from dbchanges where tablename!='' group by tablename order by qty desc";
$tablename=$this->html->htlist('tablename',$sql,'','All Tables');

$this->livestatus("Preparing action list...");
$sql="SELECT distinct action, action||' - '||count(*),count(*) as qty from dbchanges where tablename!='' group by action order by qty desc";
$action=$this->html->htlist('action',$sql,'','All Actions');

$this->livestatus("Preparing user list...");
$sql="SELECT distinct c.user_id, u.firstname||' '||u.surname||' - '||count(*),count(*) as qty
from dbchanges c
LEFT JOIN users u ON c.user_id=u.id
group by c.user_id, u.firstname,u.surname order by qty desc";
$users=$this->html->htlist('user_id',$sql,'','All users');

$this->livestatus("Forming ...");
$form_opt['url']="?act=show&table=dbchanges";
$form_opt['title']="Search DB Alterations";
$form_opt['well_class']="span11 columns form-wrap";
$out.=$this->html->form_start($what,$id,'d',$form_opt);
$out.="<hr>";
//$out.=$this->html->form_hidden('nopager',1);
//$out.=$this->html->form_textarea('names','','List of names','name1, name2',0,0,'span12');
//$out.=$this->html->form_text('post','','In Post','',0,'span12');
//$out.=$this->html->form_text('get','','In Get','',0,'span12');
//$out.=$this->html->form_text('ip','','IP address','',0,'span12');

$left.=$this->html->form_date('df','','Date from','',0,'span12');
$right.=$this->html->form_date('dt','','Date to','',0,'span12');



$left.= "<label>User</label>$users";
$left.= "<label>Table</label>$tablename";
$left.= "<label>Action</label>$action";



$left.=$sorting;

$out.=$this->html->cols2($left,$right);

$out.=$this->html->form_textarea('tags','','List of words','MAKEPAYMENT,23941',0,0,'span12');

$out.=$this->html->form_submit('Search');
$out.=$this->html->form_end();

$this->livestatus("");
$body.=$out;
