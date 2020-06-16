<?php

$list=array(
			'Default'=>'',
			'By Date ascending'=>'date asc, id asc',
			'By Date descending'=>'date desc, id desc',
			'By Object'=>'what asc, id asc',
			'By IP'=>'ip asc, id asc',
		);
$sorting=$this->html->dropdown_list_array("Sort by", "sortby", $list);



$sql="SELECT distinct what, what||' - '||count(*),count(*) as qty from clicks where what!='' group by what order by qty desc";
$object=$this->html->htlist('refference',$sql,'','All Objects');

$sql="SELECT distinct act, act||' - '||count(*),count(*) as qty from clicks where what!='' group by act order by qty desc";
$act=$this->html->htlist('action',$sql,'','All Actions');

$sql="SELECT distinct c.uid, u.firstname||' '||u.surname||' - '||count(*),count(*) as qty 
from clicks c 
LEFT JOIN users u ON c.uid=u.id
group by c.uid, u.firstname,u.surname order by qty desc";
$users=$this->html->htlist('uid',$sql,'','All users');

$form_opt['url']="?act=show&table=clicks";
$form_opt['title']="Search Clicks";
$form_opt['well_class']="span11 columns form-wrap";
$out.=$this->html->form_start($what,$id,'d',$form_opt);
$out.="<hr>";
//$out.=$this->html->form_hidden('nopager',1);
//$out.=$this->html->form_textarea('names','','List of names','name1, name2',0,0,'span12');
//$out.=$this->html->form_text('post','','In Post','',0,'span12');
//$out.=$this->html->form_text('get','','In Get','',0,'span12');
$out.=$this->html->form_text('ip','','IP address','',0,'span12');

$left.=$this->html->form_date('df','','Date from','',0,'span12');
$right.=$this->html->form_date('dt','','Date to','',0,'span12');



$left.= "<label>User</label>$users";
$left.= "<label>Object</label>$object";
$left.= "<label>Action</label>$act";



$left.=$sorting;

$out.=$this->html->cols2($left,$right);

$out.=$this->html->form_textarea('tags','','List of words','MAKEPAYMENT,23941',0,0,'span12');

$out.=$this->html->form_submit('Search');
$out.=$this->html->form_end();


$body.=$out;
