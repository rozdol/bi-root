<?php
if ($what == 'help') {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
    }
    $out.=  "<h2>$act $what</h2>\n";
            $out.= "<form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
			<input type='hidden' name='id' value='$id'>
			<input type='hidden' name='dirty' value='1'>
			<table class='m'>      
	       	<dt><label>Topic</label><input type='text' name='name'  id='name_id' value='$res[name]'></dt>
	<dt><label>Description</label><textarea name='descr' id='descr_' cols=50 rows=20>$res[descr]</textarea></td>
	       <tr><td class='mr'> </td><td class='m'><input type='submit' value='save'> </td></tr>        
	    </table></form>";
    //$out.= "$sql";
}
    
$body.=$out;
