<?php
if ($what == 'fastmenu') {
    $id=$this->html->readRQn('id');
    $name=$this->html->readRQ('name');
    $date=$this->dates->F_date($this->html->readRQ('date'), 1);
    $gid=$this->html->readRQn('gid');
    $menu=$_POST['menu'];
    $menu = str_replace("\0", "", $menu);
    $menu = stripslashes($menu);
    $menu=htmlspecialchars($menu, ENT_QUOTES);
    //string htmlspecialchars_decode ( string $string [, int $flags = ENT_COMPAT | ENT_HTML401 ] )

    $vals=array(
        'name'=>$name,
        'date'=>$date,
        'gid'=>$gid,
        'menu'=>$menu
    );
    //$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;
    if ($id==0) {
        $id=$this->db->insert_db($what, $vals);
    } else {
        $id=$this->db->update_db($what, $id, $vals);
    }
}


$body.=$out;
