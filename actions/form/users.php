<?php
if (($what == 'users')&&($access['main_admin'])) {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
            $ugid=$this->db->GetVar("SELECT groupid FROM user_group WHERE userid=$id");
                
        if (($res[active])) {
            $checked='checked';
        } else {
            $checked='';
        }
    } else {
        $id=0;
    }
                
    //$type=$this->html->htlist('type',"SELECT id, name from listitems WHERE list_id=9 ORDER by id",$res[type]);
    $groupid=$this->html->htlist('groupid', "SELECT id, name FROM groups ORDER by name", $ugid);
    //if($this->table_exists('partners'))$partnerid=$this->html->htlist('partnerid',"SELECT id, name FROM partners WHERE active='1' ORDER by name",$res[partnerid]);
    $out.= "    
    <form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
    <table class='m'>
    <input type='hidden' name='id' value='$id'>
    <tr><td class='mr'>Username</td><td class='m'><input type='text' name='username' size='20' maxlength='255'  value='$res[username]'> </td></tr>
    <tr><td class='mr'>Firstname</td><td class='m'><input type='text' name='firstname' size='20' maxlength='255'  value='$res[firstname]'> </td></tr>
    <tr><td class='mr'>Surname</td><td class='m'><input type='text' name='surname' size='20' maxlength='255'  value='$res[surname]'> </td></tr>
    <tr><td class='mr'>email</td><td class='m'><input type='text' name='email' size='20' maxlength='255'  value='$res[email]'> </td></tr>
    <tr><td class='mr'>mobile</td><td class='m'><input type='text' name='mobile' size='20' maxlength='255'  value='$res[mobile]'> </td></tr>
	<tr><td class='mr'>New Password</td><td class='m'><input type='text' name='password' size='20' maxlength='255'  value=''> </td></tr>
    <tr><td class='mr'>Group</td><td class='m'>$groupid</td></tr>

    <tr><td class='mr'>Active</td><td class='m'><label><input type='checkbox' name='active' value='1' $checked /></label> </td></tr>     
      <tr><td class='m'></td><td class='m'><input type='submit' class='btn btn-primary' name='act' value='save'></td></tr>
    </table>
    </form>";
}
    
$body.=$out;
