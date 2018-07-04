<?php
if ($what == 'versions') {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
        $res[name]=$this->db->GetVal("select name from $what order by id desc limit 1;");
    }
    $out.=  "<div class='title2'>$act version</div>\n";
            $out.= "<form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
			<input type='hidden' name='id' value='$id'>
			
			<table class='m'>      
	       	<dt><label>Version No</label><input type='text' name='name'  id='name_id' value='$res[name]'></dt>
	<dt><label>Description</label><textarea name='descr' id='descr_' cols=50 rows=20>$res[descr]</textarea></td>
	       <tr><td class='mr'> </td><td class='m'><input type='submit' value='save'> </td></tr>        
	    </table></form>";
    //$out.= "$sql";
}
        
$body.=$out;
