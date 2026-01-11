<?php
// if (($what == 'config')&&($access['main_admin'])){
//      $id=$this->html->readRQ('id');
//      if ($act=='edit'){
//              $sql="select * from $what WHERE name='$id'";
//              $res=$this->utils->escape($this->db->GetRow($sql));
//              $configname="<input type='hidden' name='id' value='$id'>";
//          }else{
//          $id='';
//          $configname="";
//          $res['name']="Value";
//          $newrecord="<label>Variable Name<span class='small'>New value</span></label><input type='text' name='name' id='textfield' value=''/>";
//          }
//  $out.= "
//      <div id='stylized' class='well'>
//        <form id='form1' name='form1' method='post' action='?csrf=$GLOBALS['csrf']&act=save&what=$what' method='post' name='directory'>
//       $configname
//          <h1>$action System Settings</h1>
//          <p>Manage System variables</p>
//      $newrecord
//      <label>$res['name']<span class='small'><br>Changing value</span></label><textarea></textarea><input type='text' name='value' id='textfield' value='$res['value']'/>

//          ".$this->html->form_confirmations()."
//              <button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
//          <div class='spacer'></div>
//        </form>
//      </div>
//      ";
//  }
    
// $body.=$out;

//Edit config
$id=$this->html->readRQ('id');
if ($act=='edit') {
        $sql="select * from $what WHERE name='$id'";
        $res=$this->utils->escape($this->db->GetRow($sql));
        $configname="<input type='hidden' name='id' value='$id'>";
} else {
    $id='';
    $configname="";
    $res['name']="Value";
    $newrecord="<label>Variable Name<span class='small'>New value</span></label><input type='text' name='name' id='textfield' value=''/>";
}
$form_opt['well_class']="span11 columns form-wrap";

$out.=$this->html->form_start($what, $id, '', $form_opt);
$out.="<hr>";

$out.=$this->html->form_hidden('reflink', $reflink);
$out.=$this->html->form_hidden('id', $id);
$out.=$this->html->form_hidden('reference', $reference);
$out.=$this->html->form_hidden('refid', $refid);

$out.=$this->html->form_text('name', $res['name'], 'Name', '', 0, 'span12');
$out.=$this->html->form_textarea('value', $res['value'], 'Value', '', 0, 0, 'span12');


$out.=$this->html->form_confirmations();
$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$body.=$out;
