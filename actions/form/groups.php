<?php
if (($what == 'groups')&&($access['main_admin'])) {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
            //$ugid=$this->db->GetVar("SELECT groupid FROM user_group WHERE userid=$id");
            //if (($res[active])){$checked='checked';}else{$checked='';}
    } else {
        $id=0;
    }
                
    //$type=$this->html->htlist('type',"SELECT id, name from listitems WHERE list_id=9 ORDER by id",$res[type]);
    //$groupid=$this->html->htlist('groupid',"SELECT id, name FROM groups ORDER by name",$ugid);
    $out.= "    
    <form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
    <table class='m'>
    <input type='hidden' name='id' value='$id'>
    <tr><td class='mr'>Groupname</td><td class='m'><input type='text' name='name' size='20' maxlength='255'  value='$res[name]'> </td></tr>
    <tr><td class='mr'>Description</td><td class='m'><input type='text' name='descr' size='20' maxlength='255'  value='$res[descr]'> </td></tr>     
      <tr><td class='m'></td><td class='m'><input type='submit' class='btn btn-primary' name='act' value='save'></td></tr>
    </table>
    </form>";
}
    
$body.=$out;
