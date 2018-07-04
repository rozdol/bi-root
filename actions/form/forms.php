<?php
if( ($what == 'forms')&&($access['main_admin'])){

				
    //$type=$this->html->htlist('type',"SELECT id, name from listitems WHERE list_id=9 ORDER by id",$res[type]);
    //$groupid=$this->html->htlist('groupid',"SELECT id, name FROM groups ORDER by name",$ugid);
    $out.= "    
    <form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
    <table class='m'>
    <td class='m' valign='top'>SQL Data:</td><td class='m'><textarea cols=120 rows=20 name='data' ></textarea><br></td></tr>     
	  
      <tr><td class='m'></td><td class='m'><input type='submit' class='btn btn-primary' name='act' value='save'></td></tr>
    </table>
    </form>";		
	}
	
	
$body.=$out;
