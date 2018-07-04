<?php
if ($what == 'menus') {
        $groupid=$this->html->readRQn('groupid');
            
        $title='Menu Structure';
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
            $groupid=$res[groupid]*1;
    } else {
      //Default Fieds Values
        $sql="select * from $what WHERE id=$refid";
        $res2=$this->db->GetRow($sql);
        $groupid=$res2[groupid]*1;
        $res[parentid]=$refid;
    }
        $group=$this->data->get_name('groups', $groupid);
        $parentid=$this->html->htlist('parentid', "SELECT m.id, i.name||' ['||substr(i.link,0,50)||']' FROM menus m, menuitems i WHERE i.id=m.menuid  ORDER by name", $res[parentid]);
        $menuid=$this->html->htlist('menuid', "SELECT id, name||' ['||substr(link,0,50)||']' FROM menuitems WHERE id>0 ORDER by name", $res[menuid]);
        $out.="
		  <form class='well span4' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='form'>
		<input type='hidden' name='id' value='$id'>
		<input type='hidden' name='refid' value='$refid'> 
		<input type='hidden' name='groupid' value='$groupid'>
		<input type='hidden' name='debug' value='0'> 
		    <h1>$title</h1>
		    <p>$action $title for group $group<br>GID:$groupid, refID:$refid, ParentID:$res[parentid], MenueID:$res[menuid]</p>
		
		<hr>
		<fieldset>
		    <label>Parent</label>
		    $parentid
		
			<label>Menue</label>
		    $menuid
		
			<label>Sort</label>
		    <input type='text' name='sort' value='$res[sort]' class='span3'/>
		
			<p> </p>
		     ".$this->html->form_confirmations()."
		<button type='submit' class='btn btn-primary' name='act' value='save'>Submit</button> 
		    <div class='spacer'></div>
		</fieldset>
		  </form>";
}
    
$body.=$out;
