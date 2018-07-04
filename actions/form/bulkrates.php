<?php
if ($what == 'bulkrates') {
    if ($act=='edit') {
        $id=$this->html->readRQ('id');
                $sql="select * from listitems WHERE id='$id'";
                $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
        $hidden="<input type='hidden' name='ins' value='ins'>";
    }
            $currency=$this->html->htlist('currency', "SELECT id, name from listitems WHERE list_id=6 ORDER by id", $res[currency]);
        $out.= "
    <form action='?csrf=$GLOBALS[csrf]&act=save&what=$what'  method='post' name='Form1' id='Form1'>";
    $out.="
  <table class='m'>
  <tr><td class='mr'>Currency</td><td class='m'>$currency</td></tr>
  <td class='m' valign='top'>Data:</td><td class='m'><textarea cols=100 rows=40 name='data' >$res[varvalue]</textarea><br></td></tr>
     <tr><td class='mr'> </td><td class='m'><input type='submit' value='save'> </td></tr>      
    
  </table>
  </form>";
}
  
$body.=$out;
